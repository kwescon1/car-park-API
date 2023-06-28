<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\AuthService\AuthServiceInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function authService()
    {
        return app(AuthServiceInterface::class);
    }

    public function test_verify_user_password()
    {
        $user = User::factory()->create();

        //password check returns true
        $true = $this->authService()->verifyUserPassword('password', $user->password);

        $this->assertTrue($true);

        //password check returns false

        $false = $this->authService()->verifyUserPassword('false', $user->password);
        $this->assertFalse($false);
    }
}
