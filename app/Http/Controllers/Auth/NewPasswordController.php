<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordResetRequest;
use App\Models\User;
use App\Notifications\PasswordChangedAdminNotification;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): Response|RedirectResponse
    {
        $token = $request->route('token');
        $email = $request->email;

        $resetRequest = PasswordResetRequest::where('token', $token)
            ->where('status', 'approved')
            ->where('expires_at', '>', now())
            ->whereHas('user', function ($query) use ($email) {
                $query->where('email', $email);
            })
            ->first();

        if (!$resetRequest) {
            return redirect()->route('login')->withErrors(['message' => 'Tautan reset password tidak valid atau sudah kedaluwarsa. Silakan ajukan permintaan baru.']);
        }

        return Inertia::render('Auth/ResetPassword', [
            'email' => $email,
            'token' => $token,
        ]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $resetRequest = PasswordResetRequest::where('token', $request->token)
            ->where('status', 'approved')
            ->where('expires_at', '>', now())
            ->whereHas('user', function ($query) use ($request) {
                $query->where('email', $request->email);
            })
            ->first();

        if (!$resetRequest) {
            throw ValidationException::withMessages([
                'email' => ['Email tidak sesuai dengan data terdaftar atau tautan sudah kedaluwarsa.'],
            ]);
        }

        $user = $resetRequest->user;
        $user->forceFill([
            'password' => Hash::make($request->password),
            'remember_token' => Str::random(60),
        ])->save();

        $resetRequest->update(['status' => 'completed']);

        // Send notification to admins
        $admins = User::where('role', 'admin')->get();
        if ($admins->isNotEmpty()) {
            Notification::send($admins, new PasswordChangedAdminNotification($user));
        }

        event(new PasswordReset($user));

        if (auth()->check()) {
            return redirect()->route('dashboard')->with('success', 'Password berhasil diperbarui.');
        }

        return redirect()->route('login')->with('status', 'Password berhasil direset. Silakan login dengan password baru.');
    }
}
