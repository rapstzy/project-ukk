<?php

namespace App\Notifications;

use App\Models\PasswordResetRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PasswordResetRejectedNotification extends Notification
{
    use Queueable;

    public function __construct(private readonly PasswordResetRequest $passwordResetRequest)
    {
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
            'kind' => 'password_reset_rejected',
            'title' => 'Permintaan Reset Password Ditolak',
            'message' => 'Permintaan reset password Anda telah ditolak oleh admin. Silakan hubungi admin jika Anda merasa ini adalah kesalahan.',
        ];
    }
}
