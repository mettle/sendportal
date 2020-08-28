<?php

declare(strict_types=1);

namespace Sendportal\Base\Http\Controllers\Auth;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Sendportal\Base\Http\Controllers\Controller;
use Sendportal\Base\Http\Requests\ProfileUpdateRequest;

class ProfileController extends Controller
{
    public function show(): View
    {
        return view('sendportal::profile.show');
    }

    public function edit(): View
    {
        return view('sendportal::profile.edit');
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->update($request->validated());

        return redirect()->back()->with('success', __('Your profile was updated successfully!'));
    }
}
