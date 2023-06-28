<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePasswordRequest;
use Illuminate\Http\Response;

/**
 * @group Authentication
 */
class PasswordUpdateController extends Controller
{
    /**
     * Update Password
     *
     * This endpoint allows the authenticated user to update their password.
     * The request should include the current password, new password, and password confirmation.
     * If the update is successful, a 204 No Content response will be returned.
     * If validation fails or the current password is incorrect, a 422 Unprocessable Entity status code will be returned.
     *
     * @authenticated
     *
     * @bodyParam current_password string required The current password of the user.
     * @bodyParam password string required The new password of the user.
     * @bodyParam password_confirmation string required The confirmation of the new password.
     *
     * @response 204
     * @response 422 {"message": "The current password is incorrect","errors": {"current_password": ["The current password is incorrect"]}}
     */
    public function __invoke(UpdatePasswordRequest $request)
    {
        $data = $request->validated();

        auth()->user()->update([
            'password' => $data['password']]);

        return response()->success(null, 'Your password has been updated.', Response::HTTP_NO_CONTENT);
    }
}
