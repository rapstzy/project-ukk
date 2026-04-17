<?php

namespace App\Notifications;

use App\Models\PasswordResetRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PasswordResetApprovedNotification extends Notification
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
            'kind' => 'password_reset_approved',
            'title' => 'Permintaan Reset Password Disetujui',
            'message' => 'Permintaan reset password Anda telah disetujui oleh admin. Silakan klik untuk mengubah password.',
            'action_url' => route('password.reset', ['token' => $this->passwordResetRequest->token, 'email' => $this->passwordResetRequest->user->email], false),
            'token' => $this->passwordResetRequest->token,
        ];
    }
}
