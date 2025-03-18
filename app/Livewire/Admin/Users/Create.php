<?php

namespace App\Livewire\Admin\Users;

use App\Livewire\Forms\Admin\UserForms;
use Livewire\Component;

class Create extends Component
{
    public UserForms $form;
    public function save()
    {
        $this->validate();
        try {
            $this->form->store();
            $this->reset('form');
            session()->flash('message', 'User created successfully.');
            return $this->redirect(route('admin.users.index'), navigate: true);
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to create user: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.users.create');
    }
}
