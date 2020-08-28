<?php

declare(strict_types=1);

namespace Sendportal\Base\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;
use Sendportal\Base\Http\Controllers\Controller;

class VerificationController extends Controller
{
    use VerifiesEmails;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    public function show(Request $request)
    {
        return $request->user()->hasVerifiedEmail()
            ? redirect($this->redirectPath())
            : view('sendportal::auth.verify');
    }

    protected function redirectTo(): string
    {
        return route('sendportal.dashboard');
    }
}
