<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use App\Models\Loan;

class BookReturnedAdminNotification extends Notification
{
    use Queueable;

    protected $loan;

    /**
     * Create a new notification instance.
     */
    public function __construct(Loan $loan)
    {
        $this->loan = $loan->load('user', 'items.book');
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $bookTitles = $this->loan->items->map(fn($item) => $item->book->title)->join(', ');
        $userName = $this->loan->user->name;

        return [
            'title' => 'Buku Dikembalikan',
            'message' => "Buku [{$bookTitles}] telah dikembalikan oleh [{$userName}]",
            'loan_id' => $this->loan->id,
            'type' => 'book_returned',
        ];
    }
}
