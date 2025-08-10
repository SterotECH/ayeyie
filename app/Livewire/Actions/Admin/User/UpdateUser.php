<?php

namespace App\Livewire\Actions\Admin\User;

use App\Enums\AuditAction;
use App\Models\User;
use App\Services\AuditLogService;
use App\Services\SuspiciousActivityService;
use Illuminate\Support\Facades\Auth;

class UpdateUser
{
    public function __construct(
        private readonly AuditLogService $auditLogService,
        private readonly SuspiciousActivityService $suspiciousActivityService
    ) {}
    /**
     * Update an existing user
     *
     * @param User $user
     * @param array{
     *  name: string,
     *  phone: string,
     *  email: string,
     *  role: string,
     *  language: string
     * } $data
     *
     * @return User
     */
    public function handle(User $user, array $data): User
    {
        $actor = Auth::user();
        $originalData = [
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'phone' => $user->phone,
            'language' => $user->language,
        ];
        
        $user->name = $data['name'];
        $user->phone = $data['phone'];
        $user->email = $data['email'];
        $user->role = $data['role'];
        $user->language = $data['language'];

        $user->save();

        // Log the user update event
        $this->auditLogService->logUserManagement(
            AuditAction::USER_UPDATED,
            $user,
            $actor,
            [
                'updated_fields' => array_diff_assoc($data, $originalData),
                'original_values' => $originalData,
                'new_values' => $data,
            ]
        );

        // Check for role changes and potential escalation attempts
        if ($originalData['role'] !== $data['role'] && $actor) {
            $this->auditLogService->logUserManagement(
                AuditAction::USER_ROLE_CHANGED,
                $user,
                $actor,
                [
                    'old_role' => $originalData['role'],
                    'new_role' => $data['role'],
                    'target_user_id' => $user->user_id,
                ]
            );

            $this->suspiciousActivityService->detectRoleEscalation(
                $user,
                $actor,
                $data['role'],
                $originalData['role']
            );
        }

        return $user;
    }
}
