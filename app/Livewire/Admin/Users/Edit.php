<?php

namespace App\Livewire\Admin\Users;

use App\Livewire\Forms\Admin\UserForms;
use App\Models\User;
use Livewire\Component;

class Edit extends Component
{
    public UserForms $form;
    public User $user;

    public function mount(User $user)
    {
        $this->user = $user;
        $this->form->setUser($user);
    }

    public function save()
    {
        try {
            $this->form->update();

            session()->flash('message', 'User updated successfully.');

            return $this->redirect(route('admin.users.index'), navigate: true);
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update user: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.users.edit');
    }
}
