<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserService
{
    public function createUserWithCode(array $data): User
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'adress' => $data['adress'],
            'phone' => $data['phone'],
            'password' => Hash::make(Str::random(16)),
        ]);

        $user->verification_code = rand(10000, 99999);
        $user->save();

        return $user;
    }
}
