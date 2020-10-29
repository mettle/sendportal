<?php

namespace App\Http\Controllers;

use App\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SetupController extends Controller
{
    /**
     * @return View|RedirectResponse
     */
    public function index()
    {
        try {
            if (User::exists()) {
                return redirect()->route('login');
            }
        } catch (Exception $e) {
            //
        }

        return view('setup.index');
    }
}
