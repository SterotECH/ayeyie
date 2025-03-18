<?php

namespace App\Livewire\Actions\Admin\User;

use App\Models\User;

class UpdateUser
{
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
        $user->name = $data['name'];
        $user->phone = $data['phone'];
        $user->email = $data['email'];
        $user->role = $data['role'];
        $user->language = $data['language'];

        $user->save();

        return $user;
    }
}
