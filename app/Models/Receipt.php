<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Receipt model representing the receipts table.
 *
 * @property int $receipt_id Unique identifier for each receipt
 * @property int $transaction_id Linked transaction
 * @property string $receipt_code Human-readable receipt code
 * @property string $qr_code QR code for pickup verification
 * @property CarbonImmutable $issued_at When receipt was issued
 * @property bool $is_synced Sync status for offline mode
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property-read Transaction $transaction The transaction this receipt belongs to
 * @property-read Pickup|null $pickup The pickup associated with this receipt
 */
final class Receipt extends Model
{
    /** @use HasFactory<\Database\Factories\ReceiptFactory> */
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'receipts';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'receipt_id';

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
        'issued_at' => 'datetime',
        'is_synced' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the transaction this receipt belongs to.
     *
     * @return BelongsTo<Transaction, $this>
     */
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'transaction_id', 'transaction_id');
    }

    /**
     * Get the pickup associated with this receipt.
     *
     * @return HasOne<Pickup, $this>
     */
    public function pickup(): HasOne
    {
        return $this->hasOne(Pickup::class, 'receipt_id', 'receipt_id');
    }
}
