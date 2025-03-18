<?php

namespace App\Livewire\Forms\Admin;

use App\Livewire\Actions\Admin\User\CreateUser;
use App\Livewire\Actions\Admin\User\UpdateUser;
use App\Models\User;
use Livewire\Attributes\Rule;
use Livewire\Form;

class UserForms extends Form
{
    public ?int $userId;

    public $name = '';

    public $phone = '';

    public $email = '';

    public $password = '';

    public $password_confirmation = '';

    public $role = 'customer';

    public $language = 'en';

    public ?User $user;

    /**
     * The store method to save the user to db
     */
    public function store(): User
    {
        $this->validate();


        return app(CreateUser::class)->handle([
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'password' => $this->password,
            'role' => $this->role,
            'language' => $this->language
        ]);
    }

    public function setUser(User $user)
    {
        $this->userId = $user->user_id;
        $this->name = $user->name;
        $this->phone = $user->phone;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->language = $user->language;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:100',
            'phone' => 'required|string|max:20|unique:users,phone,' . $this->userId . ',user_id',
            'email' => 'nullable|email|max:100|unique:users,email,' . $this->userId . ',user_id',
            'role' => 'required|in:staff,admin,customer',
            'language' => 'required|string|size:2',
            'password' => 'nullable|string|min:8|max:255',
            'password_confirmation' => 'nullable|same:password'
        ];
    }

    public function update()
    {
        $this->validate();

        app(UpdateUser::class)->handle($this->user, [
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'password' => $this->password,
            'role' => $this->role,
            'language' => $this->language
        ]);
    }
}
