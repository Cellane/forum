<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;

class RegisterConfirmationController extends Controller
{
    public function index()
    {
        $user = User::whereConfirmationToken(request('token'))->first();

        if (!$user) {
            return redirect(route('threads'))
                ->with('flash', 'Unknown token.')
                ->with('flash_level', 'danger');
        }

        $user->confirm();

        return redirect(route('threads'))
            ->with('flash', 'Your account is now confirmed! You may post to the forum.');
    }
}
