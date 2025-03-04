<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * AuditLog model representing the audit_logs table.
 *
 * @property int $log_id Unique identifier for audit log
 * @property int $user_id User who acted (staff, admin, customer)
 * @property string $action Action type, e.g., payment_processed
 * @property string $entity_type Morphable entity type (e.g., App\Models\Transaction)
 * @property int $entity_id Morphable entity ID
 * @property string|null $details Additional info
 * @property CarbonImmutable $logged_at When action occurred
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property-read User $user User who performed the action
 * @property-read Model|Transaction|User|Product|StockAlert $entity Morphable entity (e.g., Transaction, User)
 */
final class AuditLog extends Model
{
    /** @use HasFactory<\Database\Factories\AuditLogFactory> */
    use HasFactory;

    public $timestamps = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'audit_logs';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'log_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $guarded = [];

    /**
     * Get the user who performed the action.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Get the morphable entity associated with this
     * audit log (e.g., Transaction, User).
     *
     *
     * @return MorphTo<Model, $this>
     */
    public function entity(): MorphTo
    {
        return $this->morphTo();
    }
}
