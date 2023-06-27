<?php

namespace App\Services\AuthService;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService implements AuthServiceInterface
{
    public function getUserByEmail(string $email): ?User
    {

        return User::whereEmail($email)->first();
    }

    public function verifyUserPassword(string $password, string $userPassword): bool
    {
        return Hash::check($password, $userPassword);
    }
}
