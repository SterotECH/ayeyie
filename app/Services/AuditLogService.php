<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\AuditAction;
use App\Enums\AuditLogLevel;
use App\Models\AuditLog;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * Service for logging audit events throughout the application
 */
final class AuditLogService
{
    /**
     * Log an audit event
     */
    public function log(
        AuditAction $action,
        ?Model $entity = null,
        ?User $user = null,
        array|string|null $details = null,
        AuditLogLevel $logLevel = AuditLogLevel::INFO,
    ): AuditLog {
        $user = $user ?? Auth::user();

        // Prepare details with log level
        $detailsArray = is_array($details) ? $details : ($details ? ['message' => $details] : []);
        $detailsArray['log_level'] = $logLevel->value;
        $detailsArray['timestamp'] = CarbonImmutable::now()->toISOString();

        return AuditLog::create([
            'user_id' => $user?->user_id,
            'action' => $action->value,
            'entity_type' => $entity ? get_class($entity) : null,
            'entity_id' => $entity?->getKey(),
            'details' => json_encode($detailsArray),
            'logged_at' => CarbonImmutable::now(),
        ]);
    }

    /**
     * Log user authentication events
     */
    public function logAuth(AuditAction $action, ?User $user = null, array $details = []): AuditLog
    {
        $details = array_merge($details, [
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return $this->log($action, $user, $user, $details, AuditLogLevel::INFO);
    }

    /**
     * Log user management events
     */
    public function logUserManagement(AuditAction $action, User $targetUser, ?User $actor = null, array $details = []): AuditLog
    {
        return $this->log($action, $targetUser, $actor, $details);
    }

    /**
     * Log product management events
     */
    public function logProductManagement(AuditAction $action, Model $product, ?User $user = null, array $details = []): AuditLog
    {
        return $this->log($action, $product, $user, $details);
    }

    /**
     * Log transaction events
     */
    public function logTransaction(AuditAction $action, Model $transaction, ?User $user = null, array $details = []): AuditLog
    {
        return $this->log($action, $transaction, $user, $details);
    }

    /**
     * Log inventory events
     */
    public function logInventory(AuditAction $action, Model $entity, ?User $user = null, array $details = []): AuditLog
    {
        return $this->log($action, $entity, $user, $details);
    }

    /**
     * Log system events (no specific user)
     */
    public function logSystem(AuditAction $action, ?Model $entity = null, array $details = []): AuditLog
    {
        return $this->log($action, $entity, null, $details);
    }

    /**
     * Log security events
     */
    public function logSecurity(AuditAction $action, ?Model $entity = null, ?User $user = null, array $details = []): AuditLog
    {
        $details = array_merge($details, [
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return $this->log($action, $entity, $user, $details, AuditLogLevel::WARNING);
    }

    /**
     * Log critical events
     */
    public function logCritical(AuditAction $action, ?Model $entity = null, ?User $user = null, array $details = []): AuditLog
    {
        return $this->log($action, $entity, $user, $details, AuditLogLevel::CRITICAL);
    }

    /**
     * Log error events
     */
    public function logError(AuditAction $action, ?Model $entity = null, ?User $user = null, array $details = []): AuditLog
    {
        return $this->log($action, $entity, $user, $details, AuditLogLevel::ERROR);
    }
}
