<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

/**
 * User model representing the users table for staff, admins, and customers.
 *
 * @property int $user_id Unique identifier for all users (staff, admin, customers)
 * @property string $name Full name of the user
 * @property string $phone Phone number for contact or login
 * @property string|null $email Email for login or notifications, optional for walk-ins
 * @property string|null $password Hashed password, null for unregistered walk-ins
 * @property string|null $username Username for staff/admin, optional for customers
 * @property string $role User role: staff process transactions, admins manage, customers order (staff, admin, customer)
 * @property string $language Preferred language, e.g., en for English, tw for Twi
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property-read DatabaseNotificationCollection<int, DatabaseNotification> $notifications
 * @property-read Collection<int, Transaction> $transactionsAsStaff Transactions processed by this staff
 * @property-read Collection<int, Transaction> $transactionsAsCustomer Transactions ordered by this customer
 * @property-read Collection<int, Pickup> $pickups Pickups processed by this staff
 * @property-read Collection<int, SuspiciousActivity> $suspiciousActivitiesAsUser Suspicious activities where this user was involved
 * @property-read Collection<int, SuspiciousActivity> $suspiciousActivities Suspicious activities where this user is the entity
 * @property-read Collection<int, AuditLog> $auditLogs Audit logs where this user is the entity
 */
final class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'user_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'role' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user's initials.
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->map(fn (string $name) => Str::of($name)->substr(0, 1))
            ->implode('');
    }

    /**
     * Get the transactions processed by this staff member.
     *
     * @return HasMany<Transaction, $this>
     */
    public function transactionsAsStaff(): HasMany
    {
        return $this->hasMany(Transaction::class, 'user_id', 'user_id');
    }

    /**
     * Get the transactions ordered by this customer.
     *
     * @return HasMany<Transaction, $this>
     */
    public function transactionsAsCustomer(): HasMany
    {
        return $this->hasMany(Transaction::class, 'customer_user_id', 'user_id');
    }

    /**
     * Get the pickups processed by this staff member.
     *
     * @return HasMany<Pickup, $this>
     */
    public function pickups(): HasMany
    {
        return $this->hasMany(Pickup::class, 'user_id', 'user_id');
    }

    /**
     * Get the suspicious activities where this user was involved.
     *
     * @return HasMany<SuspiciousActivity, $this>
     */
    public function suspiciousActivitiesAsUser(): HasMany
    {
        return $this->hasMany(SuspiciousActivity::class, 'user_id', 'user_id');
    }

    /**
     * Get the suspicious activities where this user is the entity.
     *
     * @return MorphMany<SuspiciousActivity, $this>
     */
    public function suspiciousActivities(): MorphMany
    {
        return $this->morphMany(SuspiciousActivity::class, 'entity');
    }

    /**
     * Get the audit logs where this user is the entity.
     *
     * @return MorphMany<AuditLog, $this>
     */
    public function auditLogs(): MorphMany
    {
        return $this->morphMany(AuditLog::class, 'entity');
    }
}
