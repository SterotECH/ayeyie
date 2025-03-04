<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\TransactionItemFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $transaction_item_id
 * @property int $transaction_id
 * @property-read Transaction $transaction
 */
final class TransactionItem extends Model
{
    /** @use HasFactory<TransactionItemFactory> **/
    use HasFactory;

    protected $primaryKey = 'transaction_item_id';

    public $timestamps = false;

    /**
     * Get the transaction this item belongs to.
     *
     * @return BelongsTo<Transaction, $this>
     */
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'transaction_id', 'transaction_id');
    }
}
