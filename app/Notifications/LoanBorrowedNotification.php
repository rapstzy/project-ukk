<?php

namespace App\Notifications;

use App\Models\Loan;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LoanBorrowedNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly Loan $loan,
        private readonly User $borrower,
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
            'kind' => 'loan_borrowed',
            'title' => 'Peminjaman baru masuk',
            'message' => $this->borrower->name . ' meminjam ' . $this->loan->items()->count() . ' buku.',
            'action_url' => route('loans.admin', absolute: false),
            'loan_id' => $this->loan->id,
            'user_id' => $this->borrower->id,
            'user_name' => $this->borrower->name,
            'user_email' => $this->borrower->email,
        ];
    }
}
