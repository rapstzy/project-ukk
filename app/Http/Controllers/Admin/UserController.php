<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\PasswordChangedByAdminNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    public function index(Request $request): Response
    {
        $query = User::query()->orderByRaw("CASE WHEN role = 'admin' THEN 0 ELSE 1 END")->orderBy('name');

        if ($search = trim((string) $request->query('search', ''))) {
            $query->where(function ($builder) use ($search) {
                $builder->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        return Inertia::render('Admin/Users', [
            'users' => $query->paginate(12)->withQueryString(),
        ]);
    }

    public function updatePassword(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $admin = $request->user();

        $user->forceFill([
            'password' => $validated['password'],
            'remember_token' => Str::random(60),
        ])->save();

        if ($admin && Schema::hasTable('notifications')) {
            $user->notify(new PasswordChangedByAdminNotification($admin, $user));
        }

        return back()->with('success', 'Password untuk ' . $user->name . ' berhasil diperbarui.');
    }
}
