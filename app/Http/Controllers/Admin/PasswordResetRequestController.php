<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PasswordResetRequest;
use App\Notifications\PasswordResetApprovedNotification;
use App\Notifications\PasswordResetRejectedNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PasswordResetRequestController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/PasswordResetRequests', [
            'requests' => PasswordResetRequest::with('user')
                ->orderByRaw("CASE WHEN status = 'pending' THEN 0 ELSE 1 END")
                ->orderBy('created_at', 'desc')
                ->paginate(10),
        ]);
    }

    public function approve(PasswordResetRequest $request): RedirectResponse
    {
        $request->update([
            'status' => 'approved',
            'admin_id' => auth()->id(),
            'expires_at' => now()->addHours(2),
        ]);

        $request->user->notify(new PasswordResetApprovedNotification($request));

        return back()->with('success', 'Permintaan reset password untuk ' . $request->user->name . ' telah disetujui.');
    }

    public function reject(PasswordResetRequest $request): RedirectResponse
    {
        $request->update([
            'status' => 'rejected',
            'admin_id' => auth()->id(),
        ]);

        $request->user->notify(new PasswordResetRejectedNotification($request));

        return back()->with('success', 'Permintaan reset password untuk ' . $request->user->name . ' telah ditolak.');
    }
}
