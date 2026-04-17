<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PasswordResetRequest;
use App\Notifications\PasswordResetRequestedNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/ForgotPassword', [
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        $user = User::where('email', $request->input('email'))->firstOrFail();

        // Create a new request that is already approved
        $resetRequest = PasswordResetRequest::create([
            'user_id' => $user->id,
            'token' => Str::random(60),
            'status' => 'approved',
            'expires_at' => now()->addHours(1),
        ]);

        // Redirect directly to the reset password page
        return redirect()->route('password.reset', [
            'token' => $resetRequest->token,
            'email' => $user->email
        ]);
    }
}
