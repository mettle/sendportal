<?php

declare(strict_types=1);

namespace Sendportal\Base\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Sendportal\Base\Http\Controllers\Controller;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('sendportal::auth.login');
    }

    protected function redirectTo(): string
    {
        return route('sendportal.dashboard');
    }
}
