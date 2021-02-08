<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\ConfirmsPasswords;
use App\Http\Controllers\Controller;

class ConfirmPasswordController extends Controller
{
    use ConfirmsPasswords;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function showConfirmForm()
    {
        return view('sendportal::auth.passwords.confirm');
    }

    protected function redirectTo(): string
    {
        return route('sendportal.dashboard');
    }
}
