<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Transaction model representing the transactions table.
 *
 * @property int $transaction_id Unique identifier for each transaction
 * @property int $user_id Staff who processed the transaction
 * @property int|null $customer_user_id Customer who ordered, null for walk-ins
 * @property float $total_amount Total payment amount
 * @property string $payment_status Payment state (pending, completed, failed)
 * @property string $payment_method Method, e.g., cash, card
 * @property \Illuminate\Support\Carbon $transaction_date When the transaction occurred
 * @property bool $is_synced Sync status for offline mode
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read User $user Staff who processed the transaction
 * @property-read User|null $customer Customer who ordered (if registered)
 * @property-read Receipt|null $receipt Receipt associated with this transaction
 * @property-read \Illuminate\Database\Eloquent\Collection<int, TransactionItem> $items Items in this transaction
 */
final class Transaction extends Model
{
    /** @use HasFactory<\Database\Factories\TransactionFactory> */
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'transactions';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'transaction_id';

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
        'total_amount' => 'float',
        'payment_status' => 'string',
        'transaction_date' => 'datetime',
        'is_synced' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the staff who processed the transaction.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Get the customer who ordered (if registered).
     *
     * @return BelongsTo<User, $this>
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_user_id', 'user_id')->nullable();
    }

    /**
     * Get the receipt associated with this transaction.
     *
     * @return HasOne<Receipt, $this>
     */
    public function receipt(): HasOne
    {
        return $this->hasOne(Receipt::class, 'transaction_id', 'transaction_id');
    }

    /**
     * Get the items in this transaction.
     *
     * @return HasMany<TransactionItem, $this>
     */
    public function items(): HasMany
    {
        return $this->hasMany(TransactionItem::class, 'transaction_id', 'transaction_id');
    }
}
