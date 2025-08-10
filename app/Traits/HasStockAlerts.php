<?php

declare(strict_types=1);

namespace App\Traits;

use App\Enums\StockAlertLevel;
use App\Models\Product;
use App\Models\StockAlert;
use App\Services\StockAlertService;
use Illuminate\Database\Eloquent\Collection;

/**
 * Trait for easy stock alert integration in controllers and components
 */
trait HasStockAlerts
{
    /**
     * Get the stock alert service
     */
    protected function stockAlertService(): StockAlertService
    {
        return app(StockAlertService::class);
    }

    /**
     * Check a product's stock level and create alerts if needed
     */
    protected function checkProductStock(Product $product): ?StockAlert
    {
        return $this->stockAlertService()->checkProductStock($product);
    }

    /**
     * Create a stock alert manually
     */
    protected function createStockAlert(
        Product $product,
        StockAlertLevel $alertLevel,
        ?string $customMessage = null
    ): StockAlert {
        return $this->stockAlertService()->createAlert($product, $alertLevel, $customMessage);
    }

    /**
     * Resolve alerts for a product
     */
    protected function resolveProductAlerts(Product $product): int
    {
        return $this->stockAlertService()->resolveAlertsForProduct($product);
    }

    /**
     * Get critical alerts for dashboard display
     */
    protected function getCriticalStockAlerts(): Collection
    {
        return $this->stockAlertService()->getCriticalAlerts();
    }

    /**
     * Get all unresolved alerts ordered by priority
     */
    protected function getUnresolvedStockAlerts(): Collection
    {
        return $this->stockAlertService()->getUnresolvedAlerts();
    }

    /**
     * Get stock alert statistics
     */
    protected function getStockAlertStatistics(): array
    {
        return $this->stockAlertService()->getAlertStatistics();
    }

    /**
     * Update a product's threshold and check for alerts
     */
    protected function updateProductThreshold(Product $product, int $newThreshold): ?StockAlert
    {
        return $this->stockAlertService()->updateProductThreshold($product, $newThreshold);
    }

    /**
     * Check all products for stock alerts
     */
    protected function checkAllProductsStock(): Collection
    {
        return $this->stockAlertService()->checkAllProductsStock();
    }

    /**
     * Process bulk stock updates and handle alerts
     */
    protected function processBulkStockUpdate(array $stockUpdates): array
    {
        return $this->stockAlertService()->processBulkStockUpdate($stockUpdates);
    }

    /**
     * Get stock alert level for a product
     */
    protected function getProductAlertLevel(Product $product): ?StockAlertLevel
    {
        if ($product->stock_quantity > $product->threshold_quantity) {
            return null;
        }

        return StockAlertLevel::fromStockPercentage(
            $product->stock_quantity,
            $product->threshold_quantity
        );
    }

    /**
     * Check if a product has critical stock level
     */
    protected function hasProductCriticalStock(Product $product): bool
    {
        $level = $this->getProductAlertLevel($product);
        return $level && in_array($level, [StockAlertLevel::CRITICAL, StockAlertLevel::OUT_OF_STOCK]);
    }

    /**
     * Get products with low stock
     */
    protected function getProductsWithLowStock(): Collection
    {
        return Product::whereRaw('stock_quantity <= threshold_quantity')
            ->orderBy('stock_quantity', 'asc')
            ->get();
    }

    /**
     * Get products that are out of stock
     */
    protected function getOutOfStockProducts(): Collection
    {
        return Product::where('stock_quantity', 0)
            ->orderBy('name')
            ->get();
    }
}