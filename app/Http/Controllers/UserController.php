<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;

use DashX;

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
        $user->fill($request->safe()->only(['first_name', 'last_name', 'email', 'avatar']));
        $user->save();

        // TODO: hit dx.identify $user->id

        return response()->json($user->fresh());
    }

    /**
     * Send email notification using DashX SDK.
     *
     * @param  App\Http\Requests\ContactRequest $request
     * @return \Illuminate\Http\Response
     */
    public function contact(ContactRequest $request)
    {
        DashX::deliver('email', [
            'content' => [
                'name' => 'Contact us',
                'from' => 'noreply@dashxdemo.com',
                'to' => [$request->email, 'sales@dashx.com'],
                'subject' => 'Contact Us Form',
                'html_body' => <<<HTML
                    <mjml>
                        <mj-body>
                            <mj-section>
                                <mj-column>
                                    <mj-divider border-color='#F45E43'></mj-divider>
                                    <mj-text>Thanks for reaching out! We will get back to you soon!</mj-text>
                                    <mj-text>Your feedback: </mj-text>
                                    <mj-text>Name: $request->name</mj-text>
                                    <mj-text>Email: $request->email</mj-text>
                                    <mj-text>Feedback: $request->feedback</mj-text>
                                    <mj-divider border-color='#F45E43'></mj-divider>
                                </mj-column>
                            </mj-section>
                        </mj-body>
                    </mjml>
                HTML
            ]
        ]);

        return response()->json([
            'message' => 'Thanks for reaching out! We will get back to you soon.'
        ]);
    }
}
