<?php

namespace App\Console\Commands;

use App\Models\Receipt;
use App\Models\Pickup;
use App\Enums\AuditAction;
use Illuminate\Console\Command;

class CreateMissingPickups extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pickups:create-missing';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create pickup records for receipts that don\'t have them';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Find receipts that don't have pickup records
        $receiptsWithoutPickups = Receipt::whereDoesntHave('pickup')
            ->with('transaction')
            ->get();

        if ($receiptsWithoutPickups->isEmpty()) {
            $this->info('All receipts already have pickup records.');
            return Command::SUCCESS;
        }

        $this->info("Creating pickup records for {$receiptsWithoutPickups->count()} receipts...");

        $bar = $this->output->createProgressBar($receiptsWithoutPickups->count());
        $created = 0;

        foreach ($receiptsWithoutPickups as $receipt) {
            try {
                // Create pickup record
                $pickup = Pickup::create([
                    'receipt_id' => $receipt->receipt_id,
                    'user_id' => null, // Will be set when staff processes pickup
                    'pickup_status' => 'pending',
                    'pickup_date' => null, // Will be set when pickup is completed
                    'is_synced' => false,
                ]);

                // Log pickup creation using system log method
                app(\App\Services\AuditLogService::class)->logSystem(
                    action: AuditAction::PICKUP_CREATED,
                    entity: $pickup,
                    details: [
                        'transaction_id' => $receipt->transaction_id,
                        'receipt_code' => $receipt->receipt_code,
                        'pickup_status' => 'pending',
                        'created_by' => 'system_migration'
                    ]
                );

                $created++;
            } catch (\Exception $e) {
                $this->error("Failed to create pickup for receipt {$receipt->receipt_id}: {$e->getMessage()}");
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Successfully created {$created} pickup records.");

        return Command::SUCCESS;
    }
}