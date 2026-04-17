<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PasswordChangedAdminNotification extends Notification
{
    use Queueable;

    public function __construct(private readonly User $user)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'kind' => 'password_changed',
            'title' => 'Password User Berubah',
            'message' => "User [{$this->user->name}] ({$this->user->email}) baru saja memperbarui password mereka melalui fitur lupa password.",
            'user_id' => $this->user->id,
        ];
    }
}
