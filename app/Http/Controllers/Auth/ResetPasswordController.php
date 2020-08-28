<?php

declare(strict_types=1);

namespace Sendportal\Base\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Sendportal\Base\Http\Controllers\Controller;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    public function showResetForm(Request $request, $token = null)
    {
        return view('sendportal::auth.passwords.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    protected function redirectTo(): string
    {
        return route('sendportal.dashboard');
    }
}
