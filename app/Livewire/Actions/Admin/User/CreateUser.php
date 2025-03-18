<?php

namespace App\Livewire\Actions\Admin\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateUser
{
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

        return $user;
    }
}
