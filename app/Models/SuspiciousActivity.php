<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * SuspiciousActivity model representing the suspicious_activities table.
 *
 * @property int $activity_id Unique identifier for suspicious event
 * @property int $user_id Staff or customer involved
 * @property string $entity_type Morphable entity type (e.g., App\Models\User)
 * @property int $entity_id Morphable entity ID
 * @property string $description Details of suspicious action
 * @property string $severity Risk level (low, medium, high)
 * @property CarbonImmutable $detected_at When flagged
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property-read User $user User involved in the suspicious activity
 * @property-read Model|User|Transaction|Pickup $entity Morphable entity (e.g., User, Transaction)
 */
final class SuspiciousActivity extends Model
{
    /** @use HasFactory<\Database\Factories\SuspiciousActivityFactory> */
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'suspicious_activities';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'activity_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'severity' => 'string',
        'detected_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user involved in the suspicious activity.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Get the morphable entity associated with this suspicious activity (e.g., User, Transaction).
     *
     * @return MorphTo<Model, $this>
     */
    public function entity(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Scope a query to only include activities of a specific severity.
     *
     * @param  Builder<$this>  $query
     * @return Builder<$this>
     */
    public function scopeSeverity(Builder $query, string $severity): Builder
    {
        return $query->where('severity', $severity);
    }
}
