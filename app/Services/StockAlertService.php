<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\AuditAction;
use App\Enums\StockAlertLevel;
use App\Models\Product;
use App\Models\StockAlert;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

/**
 * Service for managing stock alerts and inventory monitoring
 */
final class StockAlertService
{
    public function __construct(
        private readonly AuditLogService $auditLogService
    ) {}

    /**
     * Create a stock alert for a product
     */
    public function createAlert(
        Product $product,
        StockAlertLevel $alertLevel,
        ?string $customMessage = null,
        ?User $user = null
    ): StockAlert {
        $user = $user ?? Auth::user();
        
        // Check if alert already exists for this product (unresolved)
        $existingAlert = StockAlert::where('product_id', $product->product_id)
            ->get()
            ->first(fn($alert) => !$alert->is_resolved && $alert->alert_level === $alertLevel->value);

        if ($existingAlert) {
            return $existingAlert;
        }

        $message = $customMessage ?? $this->generateAlertMessage($product, $alertLevel);

        // Create alert data with JSON structure
        $alertData = [
            'message' => $message,
            'alert_level' => $alertLevel->value,
            'is_resolved' => false,
            'created_at' => CarbonImmutable::now()->toISOString(),
        ];

        $alert = StockAlert::create([
            'product_id' => $product->product_id,
            'current_quantity' => $product->stock_quantity,
            'threshold' => $product->threshold_quantity,
            'alert_message' => json_encode($alertData),
            'triggered_at' => CarbonImmutable::now(),
        ]);

        // Log the stock alert creation
        $this->auditLogService->logInventory(
            AuditAction::STOCK_ALERT_CREATED,
            $alert,
            $user,
            [
                'product_name' => $product->name,
                'alert_level' => $alertLevel->value,
                'current_stock' => $product->stock_quantity,
                'threshold' => $product->threshold_quantity,
                'shortage' => max(0, $product->threshold_quantity - $product->stock_quantity),
            ]
        );

        return $alert;
    }

    /**
     * Check a product's stock level and create alerts if necessary
     */
    public function checkProductStock(Product $product, ?User $user = null): ?StockAlert
    {
        // No alert needed if stock is above threshold
        if ($product->stock_quantity > $product->threshold_quantity) {
            // Resolve any existing alerts for this product
            $this->resolveAlertsForProduct($product, $user);
            return null;
        }

        $alertLevel = StockAlertLevel::fromStockPercentage(
            $product->stock_quantity,
            $product->threshold_quantity
        );

        return $this->createAlert($product, $alertLevel, null, $user);
    }

    /**
     * Check all products for stock alerts
     */
    public function checkAllProductsStock(?User $user = null): Collection
    {
        $products = Product::where('stock_quantity', '<=', \DB::raw('threshold_quantity'))
            ->get();

        $alerts = new Collection();

        foreach ($products as $product) {
            $alert = $this->checkProductStock($product, $user);
            if ($alert) {
                $alerts->push($alert);
            }
        }

        return $alerts;
    }

    /**
     * Resolve alerts for a product (when stock is replenished)
     */
    public function resolveAlertsForProduct(Product $product, ?User $user = null): int
    {
        $user = $user ?? Auth::user();
        
        $unresolvedAlerts = StockAlert::where('product_id', $product->product_id)
            ->get()
            ->filter(fn($alert) => !$alert->is_resolved);

        foreach ($unresolvedAlerts as $alert) {
            $alert->markAsResolved($user?->user_id);

            // Log alert resolution
            $this->auditLogService->logInventory(
                AuditAction::INVENTORY_ADJUSTMENT,
                $alert,
                $user,
                [
                    'action' => 'stock_alert_resolved',
                    'product_name' => $product->name,
                    'alert_level' => $alert->alert_level,
                    'new_stock_level' => $product->stock_quantity,
                    'threshold' => $product->threshold_quantity,
                ]
            );
        }

        return $unresolvedAlerts->count();
    }

    /**
     * Get critical alerts (out of stock or critical level)
     */
    public function getCriticalAlerts(): Collection
    {
        return StockAlert::with('product')
            ->get()
            ->filter(function ($alert) {
                return !$alert->is_resolved && in_array($alert->alert_level, [
                    StockAlertLevel::OUT_OF_STOCK->value,
                    StockAlertLevel::CRITICAL->value
                ]);
            })
            ->sortByDesc('triggered_at')
            ->values();
    }

    /**
     * Get all unresolved alerts ordered by priority
     */
    public function getUnresolvedAlerts(): Collection
    {
        return StockAlert::with('product')
            ->get()
            ->filter(fn($alert) => !$alert->is_resolved)
            ->sortBy(function ($alert) {
                // Sort by priority (higher priority = lower sort value)
                $priority = match ($alert->alert_level) {
                    'out_of_stock' => 1,
                    'critical' => 2,
                    'medium' => 3,
                    'low' => 4,
                    default => 5,
                };
                // Secondary sort by triggered_at (newer first)
                return [$priority, -$alert->triggered_at->timestamp];
            })
            ->values();
    }

    /**
     * Get alert statistics
     */
    public function getAlertStatistics(): array
    {
        $allAlerts = StockAlert::all();
        
        $unresolved = $allAlerts->filter(fn($alert) => !$alert->is_resolved);
        $critical = $unresolved->filter(fn($alert) => $alert->alert_level === 'critical')->count();
        $outOfStock = $unresolved->filter(fn($alert) => $alert->alert_level === 'out_of_stock')->count();
        
        $resolvedToday = $allAlerts->filter(function ($alert) {
            return $alert->is_resolved && 
                   $alert->resolved_at && 
                   $alert->resolved_at->isToday();
        })->count();
        
        $affectedProducts = $unresolved->pluck('product_id')->unique()->count();

        return [
            'total_unresolved' => $unresolved->count(),
            'critical' => $critical,
            'out_of_stock' => $outOfStock,
            'resolved_today' => $resolvedToday,
            'products_affected' => $affectedProducts,
        ];
    }

    /**
     * Update threshold for a product and check for new alerts
     */
    public function updateProductThreshold(
        Product $product,
        int $newThreshold,
        ?User $user = null
    ): ?StockAlert {
        $user = $user ?? Auth::user();
        $oldThreshold = $product->threshold_quantity;
        
        $product->update(['threshold_quantity' => $newThreshold]);

        // Log threshold update
        $this->auditLogService->logInventory(
            AuditAction::STOCK_THRESHOLD_UPDATED,
            $product,
            $user,
            [
                'product_name' => $product->name,
                'old_threshold' => $oldThreshold,
                'new_threshold' => $newThreshold,
                'current_stock' => $product->stock_quantity,
            ]
        );

        // Check if new threshold creates or resolves alerts
        return $this->checkProductStock($product, $user);
    }

    /**
     * Generate alert message based on product and alert level
     */
    private function generateAlertMessage(Product $product, StockAlertLevel $alertLevel): string
    {
        $shortage = max(0, $product->threshold_quantity - $product->stock_quantity);
        
        return match ($alertLevel) {
            StockAlertLevel::OUT_OF_STOCK => 
                "Product '{$product->name}' is completely out of stock. Immediate restocking required.",
            StockAlertLevel::CRITICAL => 
                "CRITICAL: Product '{$product->name}' has only {$product->stock_quantity} units left (threshold: {$product->threshold_quantity}). Need {$shortage} more units.",
            StockAlertLevel::MEDIUM => 
                "MEDIUM ALERT: Product '{$product->name}' is running low with {$product->stock_quantity} units (threshold: {$product->threshold_quantity}). Consider restocking soon.",
            StockAlertLevel::LOW => 
                "LOW ALERT: Product '{$product->name}' is approaching low stock with {$product->stock_quantity} units (threshold: {$product->threshold_quantity})."
        };
    }

    /**
     * Process bulk stock updates and generate alerts
     */
    public function processBulkStockUpdate(array $stockUpdates, ?User $user = null): array
    {
        $results = [
            'alerts_created' => 0,
            'alerts_resolved' => 0,
            'products_processed' => 0,
        ];

        foreach ($stockUpdates as $productId => $newQuantity) {
            $product = Product::find($productId);
            if (!$product) {
                continue;
            }

            $oldQuantity = $product->stock_quantity;
            $product->update(['stock_quantity' => $newQuantity]);

            // Log inventory change
            $this->auditLogService->logInventory(
                AuditAction::INVENTORY_ADJUSTMENT,
                $product,
                $user,
                [
                    'adjustment_type' => 'bulk_update',
                    'old_quantity' => $oldQuantity,
                    'new_quantity' => $newQuantity,
                    'quantity_change' => $newQuantity - $oldQuantity,
                ]
            );

            // Check for alerts
            $alert = $this->checkProductStock($product, $user);
            
            if ($alert && !$alert->wasRecentlyCreated) {
                // Existing alert updated
                continue;
            } elseif ($alert) {
                $results['alerts_created']++;
            } elseif ($newQuantity > $product->threshold_quantity) {
                // Stock replenished, resolve alerts
                $resolved = $this->resolveAlertsForProduct($product, $user);
                $results['alerts_resolved'] += $resolved;
            }

            $results['products_processed']++;
        }

        return $results;
    }
}