<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PasswordChangedByAdminNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly User $admin,
        private readonly User $targetUser,
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
            'kind' => 'password_changed_by_admin',
            'title' => 'Password diperbarui admin',
            'message' => 'Password akun kamu telah diubah oleh admin ' . $this->admin->name . '.',
            'action_url' => route('notifications.index', absolute: false),
            'admin_id' => $this->admin->id,
            'admin_name' => $this->admin->name,
            'target_user_id' => $this->targetUser->id,
            'target_user_email' => $this->targetUser->email,
        ];
    }
}
