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
 * @property string $alert_message Notification text
 * @property CarbonImmutable $triggered_at When alert was generated
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property-read Product $product The product associated with this stock alert
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
}
