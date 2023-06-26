<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Services\AuthService\AuthService;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    //
    private $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function __invoke(LoginRequest $request)
    {
        $userCredentials = $request->validated();

        $user = $this->authService->getUserByEmail($userCredentials['email']);

        if (! $user || ! $this->authService->verifyUserPassword($userCredentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $device = substr($request->userAgent() ?? '', 0, 255);
        $expiresAt = $request->remember ? null : now()->addMinutes(config('session.lifetime'));

        return response()->success(['access_token' => $user->createToken($device, expiresAt: $expiresAt)->plainTextToken], 'User successfully logged in.');

    }
}
