<?php

namespace App\Services\AuthService;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function getUserByEmail($email): ?User
    {

        return User::whereEmail($email)->first();
    }

    public function verifyUserPassword($password, $userPassword): bool
    {
        return Hash::check($password, $userPassword);
    }
}
