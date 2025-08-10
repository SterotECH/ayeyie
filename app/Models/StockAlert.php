<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * StockAlert model representing the stock_alerts table.
 *
 * @property int $alert_id Unique identifier for stock alert
 * @property int $product_id Product nearing low stock
 * @property int $current_quantity Stock level when triggered
 * @property int $threshold Minimum stock level
 * @property string $alert_message Notification text (contains JSON data)
 * @property CarbonImmutable $triggered_at When alert was generated
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property-read Product $product The product associated with this stock alert
 * @property-read string $alert_level Alert severity level (computed)
 * @property-read bool $is_resolved Whether the alert has been addressed (computed)
 * @property-read CarbonImmutable|null $resolved_at When alert was resolved (computed)
 * @property-read int|null $resolved_by User who resolved the alert (computed)
 */
final class StockAlert extends Model
{
    /** @use HasFactory<\Database\Factories\StockAlertFactory> */
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'stock_alerts';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'alert_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'current_quantity' => 'integer',
        'threshold' => 'integer',
        'triggered_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the product associated with this stock alert.
     *
     * @return BelongsTo<Product, $this>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    /**
     * Get parsed alert data from JSON message
     */
    private function getAlertData(): array
    {
        $decoded = json_decode($this->alert_message, true);
        return is_array($decoded) ? $decoded : ['message' => $this->alert_message];
    }

    /**
     * Get the alert level (computed from current stock vs threshold)
     */
    public function getAlertLevelAttribute(): string
    {
        $data = $this->getAlertData();
        if (isset($data['alert_level'])) {
            return $data['alert_level'];
        }
        
        // Calculate based on current stock
        if ($this->current_quantity <= 0) {
            return 'out_of_stock';
        }
        
        $percentage = ($this->current_quantity / $this->threshold) * 100;
        if ($percentage <= 25) return 'critical';
        if ($percentage <= 50) return 'medium';
        return 'low';
    }

    /**
     * Check if alert is resolved
     */
    public function getIsResolvedAttribute(): bool
    {
        $data = $this->getAlertData();
        return isset($data['is_resolved']) ? (bool) $data['is_resolved'] : false;
    }

    /**
     * Get resolved timestamp
     */
    public function getResolvedAtAttribute(): ?CarbonImmutable
    {
        $data = $this->getAlertData();
        return isset($data['resolved_at']) ? CarbonImmutable::parse($data['resolved_at']) : null;
    }

    /**
     * Get user who resolved the alert
     */
    public function getResolvedByAttribute(): ?int
    {
        $data = $this->getAlertData();
        return isset($data['resolved_by']) ? (int) $data['resolved_by'] : null;
    }

    /**
     * Get the user who resolved this alert.
     */
    public function resolvedBy(): ?User
    {
        return $this->resolved_by ? User::find($this->resolved_by) : null;
    }

    /**
     * Get the alert level as enum
     */
    public function getAlertLevelEnum(): \App\Enums\StockAlertLevel
    {
        return \App\Enums\StockAlertLevel::from($this->alert_level);
    }

    /**
     * Check if this is a critical alert (critical or out of stock)
     */
    public function isCritical(): bool
    {
        return in_array($this->alert_level, ['critical', 'out_of_stock']);
    }

    /**
     * Get shortage amount (how many units below threshold)
     */
    public function getShortageAmount(): int
    {
        return max(0, $this->threshold - $this->current_quantity);
    }

    /**
     * Get display message (without JSON data)
     */
    public function getDisplayMessageAttribute(): string
    {
        $data = $this->getAlertData();
        return $data['message'] ?? $this->alert_message;
    }

    /**
     * Mark alert as resolved
     */
    public function markAsResolved(?int $userId = null): bool
    {
        $data = $this->getAlertData();
        $data['is_resolved'] = true;
        $data['resolved_at'] = CarbonImmutable::now()->toISOString();
        if ($userId) {
            $data['resolved_by'] = $userId;
        }
        
        return $this->update(['alert_message' => json_encode($data)]);
    }
}
