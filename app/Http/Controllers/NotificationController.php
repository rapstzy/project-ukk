<?php

namespace App\Http\Controllers;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Inertia\Inertia;
use Inertia\Response;

class NotificationController extends Controller
{
    public function index(Request $request): Response
    {
        if (!Schema::hasTable('notifications')) {
            $notifications = new LengthAwarePaginator([], 0, 15, 1, [
                'path' => url()->current(),
                'pageName' => 'page',
            ]);
        } else {
            $notifications = $request->user()
                ->notifications()
                ->latest()
                ->paginate(15);
        }

        return Inertia::render('Notifications/Index', [
            'notifications' => $notifications,
        ]);
    }

    public function markRead(Request $request, string $notification): RedirectResponse
    {
        if (!Schema::hasTable('notifications')) {
            return back();
        }

        $item = $request->user()
            ->notifications()
            ->where('id', $notification)
            ->firstOrFail();

        $item->markAsRead();

        return back();
    }

    public function markAllRead(Request $request): RedirectResponse
    {
        if (!Schema::hasTable('notifications')) {
            return back();
        }

        $request->user()
            ->unreadNotifications()
            ->update(['read_at' => now()]);

        return back();
    }
}
