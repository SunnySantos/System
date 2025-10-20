<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\SendResetLinkEmailRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm(): View
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(SendResetLinkEmailRequest $request): RedirectResponse
    {
        $status = Password::sendResetLink(
            $request->only('email')
        );

        switch ($status) {
            case Password::RESET_LINK_SENT:
                return back()->with('success', __($status));
            case Password::RESET_THROTTLED:
                return back()->with('throttled', __($status));
            default:
                return back()->withErrors(['email', __($status)]);
        }
    }
}
