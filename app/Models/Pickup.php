<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Pickup model representing the pickups table.
 *
 * @property int $pickup_id Unique identifier for each pickup
 * @property int $receipt_id Linked receipt
 * @property int $user_id Staff who processed pickup
 * @property string $pickup_status Pickup state (pending, completed)
 * @property CarbonImmutable|null $pickup_date When pickup occurred
 * @property bool $is_synced Sync status for offline mode
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property-read Receipt $receipt The receipt associated with this pickup
 * @property-read User $user The staff member who processed the pickup
 */
final class Pickup extends Model
{
    /** @use HasFactory<\Database\Factories\PickupFactory> */
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pickups';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'pickup_id';

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'pickup_status' => 'string',
        'pickup_date' => 'datetime',
        'is_synced' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the receipt associated with this pickup.
     *
     * @return BelongsTo<Receipt, $this>
     */
    public function receipt(): BelongsTo
    {
        return $this->belongsTo(Receipt::class, 'receipt_id', 'receipt_id');
    }

    /**
     * Get the staff member who processed the pickup.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
