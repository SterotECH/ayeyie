<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Product model representing the products table.
 *
 * @property int $product_id Unique identifier for each product
 * @property string $name Product name, e.g., Premium Feed
 * @property string|null $description Optional product details
 * @property float $price Current price per unit
 * @property int $stock_quantity Current stock level
 * @property int $threshold_quantity Minimum stock level for alerts
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, StockAlert> $stockAlerts Stock alerts for this product
 * @property-read \Illuminate\Database\Eloquent\Collection<int, TransactionItem> $transactionItems Transaction items involving this product
 */
final class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'products';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'product_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'description',
        'price',
        'stock_quantity',
        'threshold_quantity',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'float',
        'stock_quantity' => 'integer',
        'threshold_quantity' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the stock alerts for this product.
     *
     * @return HasMany<StockAlert, $this>
     */
    public function stockAlerts(): HasMany
    {
        return $this->hasMany(StockAlert::class, 'product_id', 'product_id');
    }

    /**
     * Get the transaction items involving this product.
     *
     * @return HasMany<TransactionItem, $this>
     */
    public function transactionItems(): HasMany
    {
        return $this->hasMany(TransactionItem::class, 'product_id', 'product_id');
    }
}
