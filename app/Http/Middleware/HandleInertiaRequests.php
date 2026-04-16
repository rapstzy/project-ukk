<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $user = $request->user();
        $hasNotificationsTable = Schema::hasTable('notifications');

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $user,
            ],
            'flash' => [
                'success' => session('success'),
                'error' => session('error'),
                'status' => session('status'),
            ],
            'notificationSummary' => [
                'unreadCount' => $user && $hasNotificationsTable ? $user->unreadNotifications()->count() : 0,
                'recent' => $user && $hasNotificationsTable
                    ? $user->notifications()
                        ->latest()
                        ->take(5)
                        ->get()
                        ->map(fn ($notification) => [
                            'id' => $notification->id,
                            'type' => $notification->type,
                            'data' => $notification->data,
                            'read_at' => $notification->read_at?->toIso8601String(),
                            'created_at' => $notification->created_at?->toIso8601String(),
                        ])
                        ->values()
                    : [],
            ],
        ];
    }
}
