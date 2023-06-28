<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * @group Authentication
 */
class LogoutController extends Controller
{
    /**
     * Logout
     *
     * This endpoint allows users to log out from the application.After a successful logout, the user's session will be terminated,and they will no longer have access to protected resources
     *
     * @authenticated
     *
     * @response 204 {}
     */
    public function __invoke(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->success(null, null, Response::HTTP_NO_CONTENT);
    }
}
