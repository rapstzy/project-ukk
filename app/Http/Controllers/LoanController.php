<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\User;
use App\Models\Ticket;
use App\Notifications\LoanBorrowedNotification;
use App\Notifications\BookReturnedAdminNotification;
use App\Notifications\TicketDeletedUserNotification;
use App\Services\LoanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Schema;
use Inertia\Inertia;

class LoanController extends Controller
{
    public function __construct(private LoanService $loanService) {}

    /**
     * Show user's loans (My Loans page)
     */
    public function myLoans()
    {
        $loans = auth()->user()->loans()
            ->with('items.book')
            ->latest()
            ->get();

        // Group by status
        $grouped = [
            'pending' => $loans->where('status', 'borrowed')->values(),
            'verified' => $loans->where('status', 'verified')->values(),
            'returned' => $loans->where('status', 'returned')->values(),
            'late' => $loans->where('status', 'late')->values(),
            'completed' => $loans->where('status', 'completed')->values(),
            'cancelled' => $loans->where('status', 'cancelled')->values(),
        ];

        return Inertia::render('Loans/MyLoans', [
            'loans' => $grouped,
        ]);
    }

    /**
     * Show printable loan ticket
     */
    public function ticket(Request $request, Loan $loan)
    {
        $user = $request->user();

        $loan->load('user', 'items.book');

        if ($user->role !== 'admin' && $loan->user_id !== $user->id) {
            abort(403);
        }

        if ($user->role !== 'admin' && !in_array($loan->status, ['verified', 'returned', 'late', 'completed'], true)) {
            abort(403);
        }

        return Inertia::render('Loans/Ticket', [
            'loan' => $loan,
            'ticketNumber' => $this->generateTicketNumber($loan),
            'issuedAt' => optional($loan->updated_at)->toIso8601String() ?? now()->toIso8601String(),
            'canPrint' => true,
        ]);
    }

    /**
     * Return a loan and delete ticket
     */
    public function returnBook(Loan $loan)
    {
        $this->authorize('returnLoan', $loan);

        if ($loan->status !== 'verified' && $loan->status !== 'late') {
            return back()->withErrors(['message' => 'Hanya peminjaman yang sudah diverifikasi yang dapat dikembalikan.']);
        }

        try {
            DB::transaction(function () use ($loan) {
                // (a) Menghapus data di tabel tickets secara permanen
                if ($loan->ticket) {
                    $loan->ticket->delete();
                }

                // (b) Mengupdate status di tabel loans menjadi 'returned'
                $loan->update([
                    'status' => 'returned',
                    'returned_at' => now(),
                ]);

                // (c) Menambah stok buku kembali (+1) di tabel books
                foreach ($loan->items as $item) {
                    $item->book()->increment('stock');
                }

                // Admin menerima pesan: "Buku [Judul] telah dikembalikan oleh [Nama User]"
                $admins = User::where('role', 'admin')->get();
                if ($admins->isNotEmpty()) {
                    Notification::send($admins, new BookReturnedAdminNotification($loan));
                }

                // User menerima pesan: "Tiket peminjaman Anda telah dihapus/dibatalkan"
                $loan->user->notify(new TicketDeletedUserNotification($loan));
            });

            return back()->with('success', 'Buku berhasil dikembalikan dan tiket telah dihapus.');
        } catch (\Exception $e) {
            return back()->withErrors(['message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Store a new loan (borrow books)
     */
    public function store(Request $request)
    {
        // Admin tidak bisa meminjam
        if ($request->user()->role === 'admin') {
            return back()->withErrors(['message' => 'Admin tidak dapat meminjam buku.']);
        }

        $validated = $request->validate([
            'book_ids' => 'required|array|min:1|max:3',
            'book_ids.*' => 'required|integer|exists:books,id',
        ]);

        try {
            $loan = $this->loanService->borrowBooks($request->user(), $validated['book_ids']);

            $admins = User::where('role', 'admin')->get();
            if ($admins->isNotEmpty() && Schema::hasTable('notifications')) {
                Notification::send($admins, new LoanBorrowedNotification($loan, $request->user()));
            }

            return redirect()->route('dashboard')
                ->with('success', 'Berhasil meminjam ' . count($validated['book_ids']) . ' buku. Tenggat pengembalian: ' . $loan->due_date->format('d M Y'));
        } catch (\Exception $e) {
            return back()->withErrors(['message' => $e->getMessage()]);
        }
    }

    /**
     * Return a loan
     */
    public function return(Loan $loan)
    {
        $this->authorize('returnLoan', $loan);

        try {
            $this->loanService->returnLoan($loan);
            $message = 'Buku berhasil dikembalikan.';

            if ($loan->fine_amount > 0) {
                $message .= ' Denda: Rp ' . number_format($loan->fine_amount, 0, ',', '.');
            }

            return back()->with('success', $message);
        } catch (\Exception $e) {
            return back()->withErrors(['message' => $e->getMessage()]);
        }
    }

    /**
     * Extend loan (tambah 7 hari)
     */
    public function extend(Loan $loan)
    {
        $this->authorize('extendLoan', $loan);

        try {
            $this->loanService->extendLoan($loan);
            return back()->with('success', 'Peminjaman diperpanjang 7 hari hingga ' . $loan->due_date->format('d M Y'));
        } catch (\Exception $e) {
            return back()->withErrors(['message' => $e->getMessage()]);
        }
    }

    private function generateTicketNumber(Loan $loan): string
    {
        $date = $loan->loan_date?->format('Ymd')
            ?? $loan->created_at?->format('Ymd')
            ?? now()->format('Ymd');

        return sprintf('TKT-%s-%04d', $date, $loan->id);
    }
}
