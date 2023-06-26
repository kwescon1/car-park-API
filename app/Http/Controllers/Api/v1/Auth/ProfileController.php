<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    //
    public function show(Request $request)
    {
        return response()->success($request->user()->only('name', 'email'));
    }

    public function update(UpdateProfileRequest $request)
    {
        $validatedData = $request->validated();

        auth()->user()->update($validatedData);

        return response()->success($validatedData);
    }
}
