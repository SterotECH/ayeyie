<?php

namespace App\Livewire\Actions\Admin\User;

use App\Enums\AuditAction;
use App\Models\User;
use App\Services\AuditLogService;
use App\Services\SuspiciousActivityService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CreateUser
{
    public function __construct(
        private readonly AuditLogService $auditLogService,
        private readonly SuspiciousActivityService $suspiciousActivityService
    ) {}
    /**
     * Handle the creation of a new user.
     *
     * @param array{
     *  name: string,
     *  phone: string,
     *  email: string,
     *  password: string,
     *  role: string,
     *  language: string,
     * } $data The data for the new user.
     */
    public function handle(array $data): User
    {
        $actor = Auth::user();
        
        $user = new User();
        $user->name = $data['name'];
        $user->phone = $data['phone'];
        $user->email = $data['email'];
        $user->role = $data['role'];
        $user->language = $data['language'];

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        // Log the user creation event
        $this->auditLogService->logUserManagement(
            AuditAction::USER_CREATED,
            $user,
            $actor,
            [
                'created_user_name' => $data['name'],
                'created_user_email' => $data['email'],
                'created_user_role' => $data['role'],
                'created_user_language' => $data['language'],
            ]
        );

        // Check for suspicious role escalation attempts
        if ($actor) {
            $this->suspiciousActivityService->detectRoleEscalation(
                $user,
                $actor,
                $data['role'],
                'none' // New user, so no previous role
            );
        }

        return $user;
    }
}
