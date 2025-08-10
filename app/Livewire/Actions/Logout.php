<?php

declare(strict_types=1);

namespace App\Livewire\Actions;

use App\Enums\AuditAction;
use App\Services\AuditLogService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

final class Logout
{
    public function __construct(
        private readonly AuditLogService $auditLogService
    ) {}
    /**
     * Log the current user out of the application.
     */
    public function __invoke()
    {
        $user = Auth::user();
        
        Auth::guard('web')->logout();

        Session::invalidate();
        Session::regenerateToken();

        // Log the logout event
        if ($user) {
            $this->auditLogService->logAuth(AuditAction::USER_LOGOUT, $user);
        }

        return redirect('/');
    }
}
