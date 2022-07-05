<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\UserUpdateRequest;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return response()->json(auth()->user());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\UserUpdateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function update(UserUpdateRequest $request)
    {
        $user = User::findOrFail(auth()->user()->id);
        $user->fill($request->safe()->only(['password', 'last_name', 'email']));
        $user->save();

        // TODO: hit dx.identify $user->id

        return response()->json($user->fresh());
    }
}
