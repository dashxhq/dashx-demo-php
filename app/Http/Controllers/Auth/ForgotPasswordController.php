<?php

namespace App\Http\Controllers\Auth;

use Tymon\JWTAuth\Providers\JWT\Namshi;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;

use DashX;

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

        DashX::deliver('email/forgot-password', [
            'to' => $request->safe()->email,
            'data' => [
                'token' => $token
            ]
        ]);

        return response()->json([
            'message' => 'Check your inbox for a link to reset your password.',
        ]);
    }
}
