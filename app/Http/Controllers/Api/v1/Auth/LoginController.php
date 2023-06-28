<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Services\AuthService\AuthService;
use Illuminate\Validation\ValidationException;

/**
 *@group Authentication
 */
class LoginController extends Controller
{
    //
    private $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Login User
     *
     * Authenticate the user with the provided email and password.
     *
     * This method allows users to log in to the system by verifying their credentials.It accepts the user's email and password as parameters and performs authenticationusing the provided credentials. If the credentials are valid, the user is grantedaccess to the system and an authentication token is generated for subsequent requests.
     *
     * @bodyParams  string  $email The email address of the user.
     * @bodyParams  string  $password The password of the user.
     *
     * @return \Illuminate\Http\Response Returns the response containing the authentication token
     *
     * @response 200 {"data": {"access_token": "5|LbZnhjamKqR1igkHTnsq74nNtbm7By7n5GGHnK4e"},"message": "User successfully logged in."}
     * @response 422 {"message": "The provided credentials are incorrect.","errors": {"email": ["The provided credentials are incorrect."]}}
     */
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
