<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function home()
    {
        return view('home');
    }

    public function checkPremium()
    {
        return view('check');
    }

    public function otpConfirmation(Request $request)
    {
        return view('otp', [
            'customer' => [
                'name' => $request->input('name'),
                'occupation' => $request->input('occupation'),
                'email' => $request->input('email'),
                'date_of_birth' => $request->input('date_of_birth'),
            ],
        ]);
    }

    public function gg(Request $request)
    {
        return view('otp', [
            'customer' => [
                'name' => $request->input('name'),
                'occupation' => $request->input('occupation'),
                'email' => $request->input('email'),
                'date_of_birth' => $request->input('date_of_birth'),
            ],
        ]);
    }
}
