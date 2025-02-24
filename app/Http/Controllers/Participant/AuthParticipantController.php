<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthParticipantController extends Controller
{
    public function viewLogin()
    {
        return view('pages.user.auth.login');
    }

    public function login(Request $request)
    {
        $validateRequest = request()->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (auth('participant')->attempt($validateRequest)) {
            return redirect()->route('participant.dashboard');
        }

        return redirect()->back()->with('error', 'Invalid email or password');
    }

    public function logout()
    {
        auth('participant')->logout();
        return redirect()->route('participant.login');
    }
}
