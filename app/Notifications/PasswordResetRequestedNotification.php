<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PasswordResetRequestedNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly User $requestingUser,
        private readonly string $ipAddress = ''
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return $this->payload();
    }

    public function toArray(object $notifiable): array
    {
        return $this->payload();
    }

    private function payload(): array
    {
        return [
            'kind' => 'password_reset_requested',
            'title' => 'Permintaan reset password',
            'message' => $this->requestingUser->name . ' meminta reset password untuk akun ' . $this->requestingUser->email . '.',
            'action_url' => route('admin.password-resets.index', absolute: false),
            'user_id' => $this->requestingUser->id,
            'user_name' => $this->requestingUser->name,
            'user_email' => $this->requestingUser->email,
            'ip_address' => $this->ipAddress,
        ];
    }
}
