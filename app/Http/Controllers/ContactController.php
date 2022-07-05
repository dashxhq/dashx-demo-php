<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;

class ContactController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  App\Http\Requests\ContactRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(ContactRequest $request)
    {
        // TODO: hit dx.deliver email

        return response()->json([
            'message' => 'Thanks for reaching out! We will get back to you soon.'
        ]);
    }
}
