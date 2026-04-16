<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\PasswordResetRequestedNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Schema;
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
        $admins = User::where('role', 'admin')->get();

        if ($admins->isNotEmpty() && Schema::hasTable('notifications')) {
            Notification::send($admins, new PasswordResetRequestedNotification($user, (string) $request->ip()));
        }

        return back()->with('status', 'Permintaan reset password sudah dikirim ke admin. Silakan tunggu konfirmasi.');
    }
}
