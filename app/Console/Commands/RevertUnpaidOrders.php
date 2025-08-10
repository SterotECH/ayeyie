<?php

namespace App\Console\Commands;

use App\Models\Transaction;
use App\Models\SuspiciousActivity;
use App\Models\Product;
use App\Enums\AuditAction;
use Carbon\CarbonImmutable;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RevertUnpaidOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:revert-unpaid {--dry-run : Show what would be reverted without actually doing it}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Revert unpaid orders older than one week with overdue pickup dates and flag suspicious customers';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $isDryRun = $this->option('dry-run');
        $oneWeekAgo = CarbonImmutable::now()->subWeek();
        
        // Find unpaid orders older than one week with pickup dates that have passed
        $unpaidOrders = Transaction::with(['items.product', 'customer', 'receipt.pickup'])
            ->where('payment_status', 'pending')
            ->where('transaction_date', '<=', $oneWeekAgo)
            ->whereHas('receipt.pickup', function ($query) {
                $query->where('pickup_status', 'pending')
                      ->where('pickup_date', '<=', CarbonImmutable::now());
            })
            ->get();

        if ($unpaidOrders->isEmpty()) {
            $this->info('No unpaid orders found older than one week with overdue pickup dates.');
            return Command::SUCCESS;
        }

        $this->info("Found {$unpaidOrders->count()} unpaid orders older than one week with overdue pickup dates.");
        
        if ($isDryRun) {
            $this->warn('DRY RUN MODE - No changes will be made');
            $this->table(
                ['Order ID', 'Customer', 'Amount', 'Order Date', 'Pickup Date', 'Items'],
                $unpaidOrders->map(function ($order) {
                    return [
                        $order->transaction_id,
                        $order->customer->name ?? 'Walk-in',
                        '₵' . number_format($order->total_amount, 2),
                        $order->transaction_date->format('Y-m-d H:i'),
                        $order->receipt->pickup->pickup_date->format('Y-m-d'),
                        $order->items->count()
                    ];
                })
            );
            return Command::SUCCESS;
        }

        $bar = $this->output->createProgressBar($unpaidOrders->count());
        $revertedCount = 0;
        $suspiciousCustomers = [];

        foreach ($unpaidOrders as $order) {
            try {
                DB::beginTransaction();
                
                // Check if customer has multiple unpaid orders (suspicious behavior)
                if ($order->customer_user_id) {
                    $customerUnpaidCount = Transaction::where('customer_user_id', $order->customer_user_id)
                        ->where('payment_status', 'pending')
                        ->where('transaction_date', '<=', $oneWeekAgo)
                        ->count();
                    
                    if ($customerUnpaidCount >= 3) { // Flag if 3 or more unpaid orders
                        $suspiciousCustomers[$order->customer_user_id] = [
                            'customer' => $order->customer,
                            'unpaid_count' => $customerUnpaidCount
                        ];
                    }
                }

                // Restore product stock
                foreach ($order->items as $item) {
                    $product = Product::find($item->product_id);
                    if ($product) {
                        $product->increment('stock_quantity', $item->quantity);
                        
                        // Log stock restoration
                        logger()->info('Stock restored for reverted order', [
                            'product_id' => $product->product_id,
                            'product_name' => $product->name,
                            'quantity_restored' => $item->quantity,
                            'old_stock' => $product->stock_quantity - $item->quantity,
                            'new_stock' => $product->stock_quantity,
                            'transaction_id' => $order->transaction_id
                        ]);
                    }
                }

                // Mark order as cancelled
                $order->update([
                    'payment_status' => 'cancelled'
                ]);

                // Log the order cancellation using system log method
                app(\App\Services\AuditLogService::class)->logSystem(
                    action: AuditAction::TRANSACTION_CANCELLED,
                    entity: $order,
                    details: [
                        'reason' => 'auto_revert_unpaid',
                        'days_overdue' => $order->transaction_date->diffInDays(CarbonImmutable::now()),
                        'original_amount' => $order->total_amount,
                        'items_count' => $order->items->count(),
                        'customer_id' => $order->customer_user_id,
                        'preferred_pickup_date' => $order->receipt->pickup->pickup_date->format('Y-m-d'),
                        'pickup_overdue_days' => $order->receipt->pickup->pickup_date->diffInDays(CarbonImmutable::now())
                    ]
                );

                DB::commit();
                $revertedCount++;
                
            } catch (\Exception $e) {
                DB::rollBack();
                $this->error("Failed to revert order {$order->transaction_id}: {$e->getMessage()}");
                logger()->error('Order revert failed', [
                    'transaction_id' => $order->transaction_id,
                    'error' => $e->getMessage()
                ]);
            }
            
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();

        // Handle suspicious customers
        if (!empty($suspiciousCustomers)) {
            $this->warn("Found " . count($suspiciousCustomers) . " customers with suspicious ordering behavior:");
            
            foreach ($suspiciousCustomers as $customerId => $data) {
                $this->createSuspiciousActivity($customerId, $data['customer'], $data['unpaid_count']);
                $this->line("- {$data['customer']->name} ({$data['customer']->email}): {$data['unpaid_count']} unpaid orders");
            }
        }

        $this->info("Successfully reverted {$revertedCount} unpaid orders.");
        $this->info("Flagged " . count($suspiciousCustomers) . " suspicious customers.");
        
        return Command::SUCCESS;
    }

    private function createSuspiciousActivity(int $customerId, $customer, int $unpaidCount): void
    {
        try {
            // Check if this customer is already flagged for this issue
            $existingFlag = SuspiciousActivity::where('user_id', $customerId)
                ->where('activity_type', 'multiple_unpaid_orders')
                ->where('created_at', '>=', CarbonImmutable::now()->subDays(30))
                ->first();

            if (!$existingFlag) {
                SuspiciousActivity::create([
                    'user_id' => $customerId,
                    'activity_type' => 'multiple_unpaid_orders',
                    'description' => "Customer has {$unpaidCount} unpaid orders that were automatically reverted after one week",
                    'ip_address' => null,
                    'user_agent' => 'System Command',
                    'severity' => 'medium',
                    'is_resolved' => false,
                    'metadata' => json_encode([
                        'unpaid_orders_count' => $unpaidCount,
                        'customer_email' => $customer->email,
                        'customer_name' => $customer->name,
                        'flagged_by' => 'auto_revert_command',
                        'flagged_at' => CarbonImmutable::now()->toISOString()
                    ])
                ]);

                // Log the suspicious activity creation using system log method
                app(\App\Services\AuditLogService::class)->logSystem(
                    action: AuditAction::SUSPICIOUS_ACTIVITY_DETECTED,
                    entity: null,
                    details: [
                        'customer_id' => $customerId,
                        'customer_name' => $customer->name,
                        'activity_type' => 'multiple_unpaid_orders',
                        'unpaid_count' => $unpaidCount,
                        'severity' => 'medium'
                    ]
                );
            }
        } catch (\Exception $e) {
            $this->error("Failed to create suspicious activity for customer {$customerId}: {$e->getMessage()}");
        }
    }
}