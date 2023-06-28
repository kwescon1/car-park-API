<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\Request;

/**
 * @group Authentication
 */
class ProfileController extends Controller
{
    /**
     * Show User Details
     *
     * This endpoint retrieves the details of the authenticated user.
     *
     * It returns the user's name and email as a response
     *
     * @authenticated
     *
     * @response 200 {"data": {"name": "kwesi","email": "kwescon@gmail.coms"},"message": null}
     */
    public function show(Request $request)
    {
        return response()->success($request->user()->only('name', 'email'));
    }

    /**
     * Update User Profile
     *
     * This endpoint allows the authenticated user to update their profile information.
     * The request should include the updated values for the name and email fields.
     * If the update is successful, the updated profile data will be returned in the response.
     * If validation fails, a 422 Unprocessable Entity status code will be returned.
     *
     * @authenticated
     *
     * @bodyParam name string required The updated name of the user.
     * @bodyParam email string required The updated email of the user.
     *
     * @response 200 {"data": {"name": "kwesis","email": "kwescon@gmail.coms"},"message": null}
     * @response 422 {"message": "The email has already been taken.","errors": {"email": ["The email has already been taken."]}}
     */
    public function update(UpdateProfileRequest $request)
    {
        $validatedData = $request->validated();

        auth()->user()->update($validatedData);

        return response()->success($validatedData);
    }
}
