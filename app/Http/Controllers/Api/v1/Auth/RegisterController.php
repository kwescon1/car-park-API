<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;

/**
 * @group Authentication
 */
class RegisterController extends Controller
{
    /**
     * Register User
     *
     * Register a new user with the provided information.
     *
     * @bodyParams  string  $name The name of the user.
     * @bodyParams  string  $email The email address of the user.
     * @bodyParams  string  $password The password for the user's account.
     * @bodyParams  string  $password_confirmation The confirmation of the user's password.
     *
     * @respone 201 {"data": {"access_token": "7|7Hzz14i9T48bcnj65qCSYnEZALcmbBcJVX7c1WF7"},"message": null}
     *
     * @response 422 {"message": "The name field is required. (and 2 more errors)","errors": {"name": ["The name field is required."],"email": ["The email has already been taken."],"password": ["The password field confirmation does not match."]}}
     */
    public function __invoke(RegisterUserRequest $request)
    {
        //
        $user = User::create($request->validated());

        // fire general laravel auth event
        event(new Registered($user));

        $device = substr($request->userAgent() ?? '', 0, 255);

        return response()->created(['access_token' => $user->createToken($device)->plainTextToken]);

    }
}
