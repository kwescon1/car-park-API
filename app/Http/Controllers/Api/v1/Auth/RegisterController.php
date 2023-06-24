<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{
    /**
     * Handle the incoming request.
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
