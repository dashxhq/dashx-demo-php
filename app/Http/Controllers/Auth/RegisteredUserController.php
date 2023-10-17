<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Hash;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisteredUserRequest;
use App\Models\User;

use DashX;

class RegisteredUserController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return App\Http\Requests\Auth\RegisteredUserRequest $request
     */
    public function __invoke(RegisteredUserRequest $request)
    {
        $user = User::create([
            'first_name' => $request->safe()->first_name,
            'last_name' => $request->safe()->last_name,
            'email' => $request->safe()->email,
            'encrypted_password' => Hash::make($request->safe()->password),
        ]);

        $user_data = [
            'firstName' => $user->first_name,
            'lastName' => $user->last_name,
            'email' => $user->email,
        ];

        DashX::identify($user->id, $user_data);
        DashX::track('User Registered', $user->id, $user_data);

        return response($user, 201);
    }
}
