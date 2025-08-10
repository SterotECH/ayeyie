<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Product;
use App\Services\StockAlertService;
use Illuminate\Support\Facades\Log;

/**
 * Observer for Product model to automatically manage stock alerts
 */
final class ProductObserver
{
    public function __construct(
        private readonly StockAlertService $stockAlertService
    ) {}

    /**
     * Handle the Product "created" event.
     */
    public function created(Product $product): void
    {
        // Check if newly created product needs an alert
        $this->checkStockLevel($product, 'created');
    }

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        // Only process if stock_quantity or threshold_quantity changed
        if ($product->isDirty(['stock_quantity', 'threshold_quantity'])) {
            $this->checkStockLevel($product, 'updated');
        }
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {
        // When a product is deleted, resolve any outstanding stock alerts
        try {
            $this->stockAlertService->resolveAlertsForProduct($product);
        } catch (\Exception $e) {
            Log::warning('Failed to resolve stock alerts for deleted product', [
                'product_id' => $product->product_id,
                'product_name' => $product->name,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Check stock level and create/resolve alerts as needed
     */
    private function checkStockLevel(Product $product, string $context): void
    {
        try {
            // Get the original values before the change
            $originalStock = $product->getOriginal('stock_quantity');
            $originalThreshold = $product->getOriginal('threshold_quantity');
            
            // Log the stock change for debugging
            if ($context === 'updated') {
                Log::info('Product stock updated', [
                    'product_id' => $product->product_id,
                    'product_name' => $product->name,
                    'old_stock' => $originalStock,
                    'new_stock' => $product->stock_quantity,
                    'old_threshold' => $originalThreshold,
                    'new_threshold' => $product->threshold_quantity,
                ]);
            }

            // Check if we need to create or resolve alerts
            $this->stockAlertService->checkProductStock($product);

        } catch (\Exception $e) {
            Log::error('Failed to process stock alert for product', [
                'product_id' => $product->product_id,
                'product_name' => $product->name,
                'context' => $context,
                'current_stock' => $product->stock_quantity,
                'threshold' => $product->threshold_quantity,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    /**
     * Handle the Product "restoring" event.
     */
    public function restoring(Product $product): void
    {
        // When a soft-deleted product is restored, check its stock level
        $this->checkStockLevel($product, 'restored');
    }
}