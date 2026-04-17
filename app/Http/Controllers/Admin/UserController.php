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

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors(['message' => 'Anda tidak bisa menghapus akun Anda sendiri.']);
        }

        if ($user->role === 'admin') {
            return back()->withErrors(['message' => 'Anda tidak bisa menghapus sesama admin.']);
        }

        $user->delete();

        return back()->with('success', "Pengguna {$user->name} berhasil dihapus.");
    }
}
