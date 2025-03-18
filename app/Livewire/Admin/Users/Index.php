<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $name;
    public $email;
    public $password;
    public $role = 'user';
    public $searchTerm = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $confirmingUserDeletion = false;
    public $userIdBeingDeleted;
    public $userBeingEdited = null;
    public $isEditMode = false;
    public $showFilters = false;
    public $perPage = 10;
    public $roles = ['admin', 'manager', 'user'];
    public $selectedRoleFilter = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:users',
        'password' => 'required|min:8',
        'role' => 'required|string',
    ];

    public function updatingSearchTerm()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function confirmUserDeletion($userId)
    {
        $this->confirmingUserDeletion = true;
        $this->userIdBeingDeleted = $userId;
    }

    public function deleteUser()
    {
        User::find($this->userIdBeingDeleted)->delete();
        $this->confirmingUserDeletion = false;
        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'User deleted successfully!'
        ]);
    }

    public function editUser($userId)
    {
        $this->userBeingEdited = User::find($userId);
        $this->name = $this->userBeingEdited->name;
        $this->email = $this->userBeingEdited->email;
        $this->role = $this->userBeingEdited->role;
        $this->password = '';
        $this->isEditMode = true;
    }

    public function cancelEdit()
    {
        $this->resetInputFields();
        $this->isEditMode = false;
    }

    public function updateUser()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $this->userBeingEdited->id,
            'role' => 'required|string',
        ]);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
        ];

        if (!empty($this->password)) {
            $this->validate(['password' => 'min:8']);
            $data['password'] = Hash::make($this->password);
        }

        $this->userBeingEdited->update($data);
        $this->isEditMode = false;
        $this->resetInputFields();
        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'User updated successfully!'
        ]);
    }

    public function createUser()
    {
        $this->validate();

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => $this->role,
        ]);

        $this->resetInputFields();
        $this->dispatchBrowserEvent('notify', [
            'type' => 'success',
            'message' => 'User created successfully!'
        ]);
    }

    public function resetInputFields()
    {
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->role = 'user';
        $this->userBeingEdited = null;
    }

    public function toggleFilters()
    {
        $this->showFilters = !$this->showFilters;
    }

    public function resetFilters()
    {
        $this->searchTerm = '';
        $this->selectedRoleFilter = '';
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function render()
    {
        $users = User::query()
            ->when($this->searchTerm, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->searchTerm . '%')
                        ->orWhere('email', 'like', '%' . $this->searchTerm . '%');
                });
            })
            ->when($this->selectedRoleFilter, function ($query) {
                $query->where('role', $this->selectedRoleFilter);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.users.index', [
            'users' => $users,
        ]);
    }
}
