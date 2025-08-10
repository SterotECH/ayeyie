<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\StockAlertService;
use Illuminate\Console\Command;

final class CheckStockLevels extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stock:check 
                            {--force : Force check even if products were recently checked}
                            {--product= : Check specific product by ID}
                            {--notify : Send notifications for critical alerts}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check all product stock levels and generate alerts for low stock items';

    public function __construct(
        private readonly StockAlertService $stockAlertService
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting stock level check...');

        try {
            if ($productId = $this->option('product')) {
                return $this->checkSingleProduct((int) $productId);
            }

            return $this->checkAllProducts();

        } catch (\Exception $e) {
            $this->error('Stock check failed: ' . $e->getMessage());
            
            if ($this->output->isVerbose()) {
                $this->error($e->getTraceAsString());
            }

            return Command::FAILURE;
        }
    }

    /**
     * Check a single product's stock level
     */
    private function checkSingleProduct(int $productId): int
    {
        $product = \App\Models\Product::find($productId);

        if (!$product) {
            $this->error("Product with ID {$productId} not found.");
            return Command::FAILURE;
        }

        $this->info("Checking stock for product: {$product->name}");

        $alert = $this->stockAlertService->checkProductStock($product);

        if ($alert) {
            $alertLevel = $alert->getAlertLevelEnum();
            $this->warn("⚠️  Alert created: {$alertLevel->getLabel()} for {$product->name}");
            $this->line("   Stock: {$product->stock_quantity}, Threshold: {$product->threshold_quantity}");
        } else {
            $this->info("✅ No alert needed for {$product->name} (Stock: {$product->stock_quantity})");
        }

        return Command::SUCCESS;
    }

    /**
     * Check all products' stock levels
     */
    private function checkAllProducts(): int
    {
        $alerts = $this->stockAlertService->checkAllProductsStock();

        $stats = $this->stockAlertService->getAlertStatistics();

        $this->info("Stock check completed!");
        $this->table([
            'Metric', 'Count'
        ], [
            ['New Alerts Created', $alerts->count()],
            ['Total Unresolved Alerts', $stats['total_unresolved']],
            ['Critical Alerts', $stats['critical']],
            ['Out of Stock Alerts', $stats['out_of_stock']],
            ['Products Affected', $stats['products_affected']],
            ['Resolved Today', $stats['resolved_today']],
        ]);

        // Display critical alerts
        if ($stats['critical'] > 0 || $stats['out_of_stock'] > 0) {
            $this->warn("\n🚨 CRITICAL ALERTS:");
            $criticalAlerts = $this->stockAlertService->getCriticalAlerts();

            foreach ($criticalAlerts->take(10) as $alert) {
                $level = $alert->getAlertLevelEnum();
                $this->line("   {$level->getIcon()} {$alert->product->name}: {$alert->current_quantity} units (needs {$alert->threshold})");
            }

            if ($criticalAlerts->count() > 10) {
                $this->line("   ... and " . ($criticalAlerts->count() - 10) . " more critical alerts");
            }
        }

        // Send notifications if requested
        if ($this->option('notify') && ($stats['critical'] > 0 || $stats['out_of_stock'] > 0)) {
            $this->info("\n📧 Sending notifications for critical alerts...");
            // Here you could integrate with notification services
            // $this->sendCriticalAlertNotifications($criticalAlerts);
        }

        if ($alerts->count() > 0) {
            $this->warn("\n⚠️  {$alerts->count()} new stock alerts generated. Please review in the admin panel.");
        }

        return Command::SUCCESS;
    }
}