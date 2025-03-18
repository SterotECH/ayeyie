<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Show extends Component
{
    use WithPagination;

    public User $user;
    public string $activeTab = 'transactions';
    public bool $showDeleteModal = false;

    public function mount(User $user)
    {
        $this->user = $user;
    }

    public function confirmDelete()
    {
        $this->showDeleteModal = true;
    }

    public function deleteUser()
    {
        try {
            $this->user->delete();
            session()->flash('message', 'User successfully deleted.');
            return $this->redirect(route('admin.users.index'), navigate: true);
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete user: ' . $e->getMessage());
            $this->showDeleteModal = false;
        }
    }

    protected function loadTransactions()
    {
        if ($this->user->role === 'customer') {
            return $this->user->customerTransactions()->latest()->paginate(10);
        } else {
            return $this->user->staffTransactions()->latest()->paginate(10);
        }
    }

    protected function loadPickups()
    {
        return $this->user->pickups()->latest()->paginate(10);
    }

    protected function loadSuspiciousActivities()
    {
        return $this->user->suspiciousActivities()->latest()->paginate(10);
    }

    protected function loadAuditLogs()
    {
        return $this->user->auditLogs()->latest()->paginate(10);
    }

    public function render()
    {
        return view('livewire.admin.users.show');
    }
}
