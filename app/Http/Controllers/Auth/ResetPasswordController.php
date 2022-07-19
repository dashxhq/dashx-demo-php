<?php

namespace App\Http\Controllers\Auth;

use Tymon\JWTAuth\Providers\JWT\Namshi;
use Illuminate\Support\Facades\Hash;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Models\User;
use Exception;

class ResetPasswordController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  App\Http\Requests\Auth\ResetPasswordRequest  $request
     * @param  Tymon\JWTAuth\Providers\JWT\Namshi  $jwt
     * @return \Illuminate\Http\Response
     */
    public function __invoke(ResetPasswordRequest $request, Namshi $jwt)
    {
        try {
            $payload = $jwt->decode($request->safe()->token);
        }catch(Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 403);
        }

        if(empty($payload['email'])) {
            return response()->json([
                'message' => 'Unauthorized!'
            ], 403);
        }

        $user = User::where('email', $payload['email'])->firstOrFail();
        $user->fill([
            'encrypted_password' => Hash::make($request->safe()->password),
        ]);
        $user->save();

        return response()->json([
            'message' => 'Updated.'
        ]);
    }
}
