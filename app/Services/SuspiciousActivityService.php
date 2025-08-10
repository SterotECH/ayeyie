<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\AuditAction;
use App\Enums\SuspiciousActivitySeverity;
use App\Models\SuspiciousActivity;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * Service for detecting and logging suspicious activities and fraud alerts
 */
final class SuspiciousActivityService
{
    public function __construct(
        private readonly AuditLogService $auditLogService
    ) {}

    /**
     * Log a suspicious activity
     */
    public function logSuspiciousActivity(
        string $description,
        SuspiciousActivitySeverity $severity = SuspiciousActivitySeverity::MEDIUM,
        ?Model $entity = null,
        ?User $user = null,
        array $additionalData = []
    ): SuspiciousActivity {
        $user = $user ?? Auth::user();
        
        $activity = SuspiciousActivity::create([
            'user_id' => $user?->user_id,
            'entity_type' => $entity ? get_class($entity) : null,
            'entity_id' => $entity?->getKey(),
            'description' => $description,
            'severity' => $severity->value,
            'detected_at' => CarbonImmutable::now(),
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        // Also log this as an audit event
        $this->auditLogService->logSecurity(
            AuditAction::SUSPICIOUS_ACTIVITY_DETECTED,
            $activity,
            $user,
            array_merge($additionalData, [
                'severity' => $severity->value,
                'description' => $description,
                'activity_id' => $activity->activity_id,
            ])
        );

        return $activity;
    }

    /**
     * Detect multiple failed login attempts
     */
    public function detectFailedLogins(?User $user = null, string $email = null): ?SuspiciousActivity
    {
        $timeframe = CarbonImmutable::now()->subMinutes(15);
        
        // Count failed login attempts in the last 15 minutes
        $failedAttempts = \App\Models\AuditLog::where('action', AuditAction::USER_LOGIN_FAILED->value)
            ->where('logged_at', '>=', $timeframe)
            ->when($user, fn($q) => $q->where('user_id', $user->user_id))
            ->when($email, fn($q) => $q->where('details', 'like', "%$email%"))
            ->count();

        if ($failedAttempts >= 5) {
            return $this->logSuspiciousActivity(
                "Multiple failed login attempts detected: {$failedAttempts} attempts in 15 minutes",
                SuspiciousActivitySeverity::HIGH,
                $user,
                $user,
                [
                    'failed_attempts' => $failedAttempts,
                    'timeframe_minutes' => 15,
                    'email' => $email,
                    'ip_address' => request()->ip(),
                ]
            );
        }

        return null;
    }

    /**
     * Detect unusual transaction patterns
     */
    public function detectUnusualTransactionPattern(Model $transaction, User $user): ?SuspiciousActivity
    {
        // Get user's transaction history from last 30 days
        $recentTransactions = $user->transactions()
            ->where('created_at', '>=', CarbonImmutable::now()->subDays(30))
            ->get();

        if ($recentTransactions->isEmpty()) {
            return null;
        }

        $averageAmount = $recentTransactions->avg('amount');
        $currentAmount = $transaction->amount ?? 0;

        // Flag if transaction is 5x larger than average
        if ($currentAmount > ($averageAmount * 5)) {
            return $this->logSuspiciousActivity(
                "Unusually large transaction detected: ₵{$currentAmount} vs average ₵" . number_format($averageAmount, 2),
                SuspiciousActivitySeverity::MEDIUM,
                $transaction,
                $user,
                [
                    'transaction_amount' => $currentAmount,
                    'average_amount' => $averageAmount,
                    'multiplier' => round($currentAmount / $averageAmount, 2),
                    'recent_transaction_count' => $recentTransactions->count(),
                ]
            );
        }

        // Check for rapid successive transactions (more than 3 in 5 minutes)
        $recentRapidTransactions = $user->transactions()
            ->where('created_at', '>=', CarbonImmutable::now()->subMinutes(5))
            ->count();

        if ($recentRapidTransactions >= 3) {
            return $this->logSuspiciousActivity(
                "Rapid successive transactions detected: {$recentRapidTransactions} transactions in 5 minutes",
                SuspiciousActivitySeverity::HIGH,
                $transaction,
                $user,
                [
                    'rapid_transaction_count' => $recentRapidTransactions,
                    'timeframe_minutes' => 5,
                ]
            );
        }

        return null;
    }

    /**
     * Detect off-hours activity
     */
    public function detectOffHoursActivity(User $user, string $action = 'activity'): ?SuspiciousActivity
    {
        $currentHour = CarbonImmutable::now()->hour;
        
        // Consider 10 PM to 6 AM as off-hours
        if ($currentHour >= 22 || $currentHour <= 6) {
            return $this->logSuspiciousActivity(
                "Off-hours activity detected: {$action} at " . CarbonImmutable::now()->format('H:i'),
                SuspiciousActivitySeverity::LOW,
                null,
                $user,
                [
                    'activity_time' => CarbonImmutable::now()->toISOString(),
                    'hour' => $currentHour,
                    'action' => $action,
                ]
            );
        }

        return null;
    }

    /**
     * Detect role escalation attempts
     */
    public function detectRoleEscalation(User $targetUser, User $actor, string $newRole, string $oldRole): ?SuspiciousActivity
    {
        // Flag if non-admin tries to create admin users
        if ($newRole === 'admin' && $actor->role !== 'admin') {
            return $this->logSuspiciousActivity(
                "Unauthorized role escalation attempt: {$actor->role} trying to create/modify admin user",
                SuspiciousActivitySeverity::HIGH,
                $targetUser,
                $actor,
                [
                    'target_user_id' => $targetUser->user_id,
                    'actor_role' => $actor->role,
                    'attempted_role' => $newRole,
                    'original_role' => $oldRole,
                ]
            );
        }

        // Flag manager trying to escalate to admin
        if ($newRole === 'admin' && $oldRole !== 'admin' && $actor->role === 'manager') {
            return $this->logSuspiciousActivity(
                "Manager attempting to escalate user to admin role",
                SuspiciousActivitySeverity::HIGH,
                $targetUser,
                $actor,
                [
                    'target_user_id' => $targetUser->user_id,
                    'actor_role' => $actor->role,
                    'role_change' => "{$oldRole} -> {$newRole}",
                ]
            );
        }

        return null;
    }

    /**
     * Detect bulk operations that might indicate data scraping
     */
    public function detectBulkOperations(User $user, string $operation, int $count, int $timeframeMinutes = 5): ?SuspiciousActivity
    {
        if ($count >= 50) { // More than 50 operations in timeframe
            return $this->logSuspiciousActivity(
                "Bulk operation detected: {$count} {$operation} operations in {$timeframeMinutes} minutes",
                $count >= 100 ? SuspiciousActivitySeverity::HIGH : SuspiciousActivitySeverity::MEDIUM,
                null,
                $user,
                [
                    'operation_type' => $operation,
                    'operation_count' => $count,
                    'timeframe_minutes' => $timeframeMinutes,
                    'rate_per_minute' => round($count / $timeframeMinutes, 2),
                ]
            );
        }

        return null;
    }

    /**
     * Detect unusual IP address usage
     */
    public function detectUnusualIpAddress(User $user): ?SuspiciousActivity
    {
        $currentIp = request()->ip();
        
        // Get user's recent IP addresses from audit logs
        $recentIps = \App\Models\AuditLog::where('user_id', $user->user_id)
            ->where('logged_at', '>=', CarbonImmutable::now()->subDays(30))
            ->whereNotNull('details')
            ->get()
            ->pluck('details')
            ->map(function ($details) {
                $decoded = json_decode($details, true);
                return is_array($decoded) ? ($decoded['ip_address'] ?? null) : null;
            })
            ->filter()
            ->unique();

        // If user has previous activity and this is a completely new IP
        if ($recentIps->isNotEmpty() && !$recentIps->contains($currentIp)) {
            return $this->logSuspiciousActivity(
                "New IP address detected for user: {$currentIp}",
                SuspiciousActivitySeverity::LOW,
                null,
                $user,
                [
                    'new_ip_address' => $currentIp,
                    'known_ip_addresses' => $recentIps->take(5)->values()->toArray(),
                    'total_known_ips' => $recentIps->count(),
                ]
            );
        }

        return null;
    }

    /**
     * Detect inventory manipulation that might indicate theft
     */
    public function detectInventoryManipulation(Model $product, User $user, int $oldQuantity, int $newQuantity): ?SuspiciousActivity
    {
        $difference = abs($newQuantity - $oldQuantity);
        
        // Large quantity changes (more than 100 units) should be flagged
        if ($difference >= 100) {
            return $this->logSuspiciousActivity(
                "Large inventory adjustment detected: {$difference} units changed for product {$product->name}",
                $difference >= 500 ? SuspiciousActivitySeverity::HIGH : SuspiciousActivitySeverity::MEDIUM,
                $product,
                $user,
                [
                    'product_name' => $product->name,
                    'old_quantity' => $oldQuantity,
                    'new_quantity' => $newQuantity,
                    'difference' => $difference,
                    'adjustment_type' => $newQuantity > $oldQuantity ? 'increase' : 'decrease',
                ]
            );
        }

        return null;
    }

}