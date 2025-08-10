<?php

declare(strict_types=1);

namespace App\Traits;

use App\Enums\AuditAction;
use App\Enums\AuditLogLevel;
use App\Services\AuditLogService;
use App\Services\SuspiciousActivityService;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

/**
 * Trait for easy audit logging integration in controllers and components
 */
trait HasAuditLogging
{
    protected function logAuditEvent(
        AuditAction $action,
        ?Model $entity = null,
        ?User $user = null,
        array $details = [],
        AuditLogLevel $logLevel = AuditLogLevel::INFO
    ): void {
        $auditLogService = app(AuditLogService::class);
        $auditLogService->log($action, $entity, $user, $details, $logLevel);
    }

    protected function logAuthEvent(AuditAction $action, ?User $user = null, array $details = []): void
    {
        $auditLogService = app(AuditLogService::class);
        $auditLogService->logAuth($action, $user, $details);
    }

    protected function logUserManagementEvent(
        AuditAction $action,
        User $targetUser,
        ?User $actor = null,
        array $details = []
    ): void {
        $auditLogService = app(AuditLogService::class);
        $auditLogService->logUserManagement($action, $targetUser, $actor, $details);
    }

    protected function logProductEvent(
        AuditAction $action,
        Model $product,
        ?User $user = null,
        array $details = []
    ): void {
        $auditLogService = app(AuditLogService::class);
        $auditLogService->logProductManagement($action, $product, $user, $details);
    }

    protected function logTransactionEvent(
        AuditAction $action,
        Model $transaction,
        ?User $user = null,
        array $details = []
    ): void {
        $auditLogService = app(AuditLogService::class);
        $auditLogService->logTransaction($action, $transaction, $user, $details);
    }

    protected function logInventoryEvent(
        AuditAction $action,
        Model $entity,
        ?User $user = null,
        array $details = []
    ): void {
        $auditLogService = app(AuditLogService::class);
        $auditLogService->logInventory($action, $entity, $user, $details);
    }

    protected function logSecurityEvent(
        AuditAction $action,
        ?Model $entity = null,
        ?User $user = null,
        array $details = []
    ): void {
        $auditLogService = app(AuditLogService::class);
        $auditLogService->logSecurity($action, $entity, $user, $details);
    }

    protected function logCriticalEvent(
        AuditAction $action,
        ?Model $entity = null,
        ?User $user = null,
        array $details = []
    ): void {
        $auditLogService = app(AuditLogService::class);
        $auditLogService->logCritical($action, $entity, $user, $details);
    }

    protected function detectSuspiciousActivity(): SuspiciousActivityService
    {
        return app(SuspiciousActivityService::class);
    }
}