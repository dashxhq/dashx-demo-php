<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  App\Http\Requests\Auth\ResetPasswordRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(ResetPasswordRequest $request)
    {
        // validate the jwt token $request->safe()->token, sent from forgot password
        // this jwt payload should have email

        $user = User::findOrFail(auth()->user()->id);
        $user->fill([
            'password' => Hash::make($request->safe()->password),
        ]);
        $user->save();

        return response()->json([
            'message' => 'Updated.'
        ]);
    }
}
