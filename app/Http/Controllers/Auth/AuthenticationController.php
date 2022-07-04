<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;

class AuthenticationController extends Controller
{
    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function login(LoginRequest $request) {
        $credentials = $request->safe()->only(['email', 'password']);

        if(!$token = auth()->attempt($credentials)) {
            return response([
                'message' => 'Unauthorized!'
            ], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Handle an incoming refresh token request.
     *
     * @return \Illuminate\Http\Response
     */
    public function refresh() {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Handle an incoming logout request.
     *
     * @return \Illuminate\Http\Response
     */
    public function logout() {
        auth()->logout(true);

        return response([
            'message' => 'Logged out.'
        ]);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
