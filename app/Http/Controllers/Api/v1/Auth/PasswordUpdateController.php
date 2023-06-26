<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePasswordRequest;
use Illuminate\Http\Response;

class PasswordUpdateController extends Controller
{
    //
    public function __invoke(UpdatePasswordRequest $request)
    {
        $data = $request->validated();

        auth()->user()->update([
            'password' => $data['password']]);

        return response()->success(null, 'Your password has been updated.', Response::HTTP_NO_CONTENT);
    }
}
