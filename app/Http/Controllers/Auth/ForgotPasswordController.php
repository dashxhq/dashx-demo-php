<?php

namespace App\Http\Controllers\Auth;

use Tymon\JWTAuth\Providers\JWT\Namshi;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;

class ForgotPasswordController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  App\Http\Requests\Auth\ForgotPasswordRequest  $request
     * @param  Tymon\JWTAuth\Providers\JWT\Namshi  $jwt
     * @return \Illuminate\Http\Response
     */
    public function __invoke(ForgotPasswordRequest $request, Namshi $jwt)
    {
        $token = $jwt->encode([
            'email' => $request->safe()->email
        ]);

        // TODO: hit dx.deliver email/forgot-password

        return response()->json([
            'message' => 'Password reset email sent.',
            // TODO: remove, using this for testing
            'token' => $token
        ]);
    }
}
