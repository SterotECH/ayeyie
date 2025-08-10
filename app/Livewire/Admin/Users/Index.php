<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Users;

use App\Enums\AuditAction;
use App\Models\User;
use App\Services\AuditLogService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

final class Index extends Component
{
    use WithPagination;
    
    public function __construct(
        private readonly AuditLogService $auditLogService
    ) {}

    #[Url(history: true)]
    public string $search = '';

    #[Url(history: true)]
    public array $filters = [
        'role' => '',
    ];

    #[Url(history: true)]
    public string $sortBy = 'created_at';

    #[Url(history: true)]
    public string $sortDirection = 'desc';

    public int $perPage = 15;

    public bool $confirmingUserDeletion = false;
    public ?int $userIdBeingDeleted = null;

    /**
     * Reset pagination to first page when search changes
     */
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    /**
     * Reset pagination when filters are updated
     */
    public function updatingFilters(): void
    {
        $this->resetPage();
    }

    /**
     * Set sorting column and direction
     */
    public function sortBy(string $column): void
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }

    /**
     * Reset all filters to default state
     */
    public function resetFilters(): void
    {
        $this->reset(['search', 'filters', 'sortBy', 'sortDirection']);
        $this->sortBy = 'created_at';
        $this->sortDirection = 'desc';
    }

    /**
     * Confirm user deletion
     */
    public function confirmUserDeletion(int $userId): void
    {
        $this->confirmingUserDeletion = true;
        $this->userIdBeingDeleted = $userId;
    }

    /**
     * Delete the confirmed user
     */
    public function deleteUser(): void
    {
        if ($this->userIdBeingDeleted) {
            $user = User::find($this->userIdBeingDeleted);
            
            if ($user) {
                $actor = Auth::user();
                
                // Log the user deletion event before deletion
                $this->auditLogService->logUserManagement(
                    AuditAction::USER_DELETED,
                    $user,
                    $actor,
                    [
                        'deleted_user_name' => $user->name,
                        'deleted_user_email' => $user->email,
                        'deleted_user_role' => $user->role,
                        'deleted_user_id' => $user->user_id,
                    ]
                );
                
                $user->delete();
            }
        }
        
        $this->confirmingUserDeletion = false;
        $this->userIdBeingDeleted = null;
        
        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'User deleted successfully!'
        ]);
    }

    public function render(): View
    {
        $users = $this->queryUsers();

        // Get statistics for dashboard cards
        $totalUsers = User::count();
        $adminUsers = User::where('role', 'admin')->count();
        $managerUsers = User::where('role', 'manager')->count();
        $regularUsers = User::where('role', 'user')->count();

        return view('livewire.admin.users.index', [
            'users' => $users,
            'stats' => [
                'total' => $totalUsers,
                'admin' => $adminUsers,
                'manager' => $managerUsers,
                'user' => $regularUsers,
            ],
        ]);
    }

    /**
     * Build the user query with filtering and searching
     */
    protected function queryUsers()
    {
        $query = User::query();

        // Search functionality
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%");
            });
        }

        // Role filter
        if (!empty($this->filters['role'])) {
            $query->where('role', $this->filters['role']);
        }

        $query->orderBy($this->sortBy, $this->sortDirection);

        return $query->paginate($this->perPage);
    }
}
