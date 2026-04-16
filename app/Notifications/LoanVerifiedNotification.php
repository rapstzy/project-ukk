<?php

namespace App\Notifications;

use App\Models\Loan;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LoanVerifiedNotification extends Notification
{
    use Queueable;

    public function __construct(private readonly Loan $loan)
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
            'kind' => 'loan_ticket_ready',
            'title' => 'Tiket peminjaman siap',
            'message' => 'Peminjaman #' . $this->loan->id . ' sudah diverifikasi. Tiket peminjaman sudah otomatis tersedia.',
            'action_url' => route('loans.ticket', $this->loan->id, false),
            'loan_id' => $this->loan->id,
            'status' => $this->loan->status,
            'ticket_number' => sprintf('TKT-%s-%04d', optional($this->loan->loan_date)->format('Ymd') ?? now()->format('Ymd'), $this->loan->id),
        ];
    }
}
