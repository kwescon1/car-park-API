<?php

namespace App\Services\AuthService;

use App\Models\User;

interface AuthServiceInterface
{
    public function getUserByEmail(string $email): ?User;

    public function verifyUserPassword(string $password, string $userPassword): bool;
}
