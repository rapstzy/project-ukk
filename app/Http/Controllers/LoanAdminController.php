<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Notifications\LoanVerifiedNotification;
use Illuminate\Support\Facades\Schema;
use Inertia\Inertia;

class LoanAdminController extends Controller
{
    /**
     * Show all loans for admin management
     */
    public function index()
    {
        $loans = Loan::with('user', 'items.book')
            ->latest()
            ->paginate(20);

        $stats = [
            'total' => Loan::count(),
            'borrowed' => Loan::where('status', 'borrowed')->count(),
            'returned' => Loan::where('status', 'returned')->count(),
            'late' => Loan::where('status', 'late')->count(),
        ];

        return Inertia::render('Loans/Admin', [
            'loans' => $loans,
            'stats' => $stats,
        ]);
    }

    /**
     * Cancel a loan
     */
    public function cancel(Loan $loan)
    {
        // Return books to stock
        foreach ($loan->items as $item) {
            $item->book->increment('stock');
        }

        $loan->update([
            'status' => 'cancelled',
            'returned_at' => now(),
        ]);

        return back()->with('success', 'Peminjaman ditolak.');
    }

    /**
     * Verify/approve a loan
     */
    public function verify(Loan $loan)
    {
        $loan->update(['status' => 'verified']);
        if (Schema::hasTable('notifications')) {
            $loan->user?->notify(new LoanVerifiedNotification($loan));
        }

        return back()->with('success', 'Peminjaman diverifikasi.');
    }

    /**
     * Complete a return
     */
    public function completeReturn(Loan $loan)
    {
        if ($loan->status === 'returned' || $loan->status === 'late') {
            $loan->update([
                'status' => 'completed',
                'returned_at' => now()
            ]);
            return back()->with('success', 'Pengembalian dikonfirmasi.');
        }

        return back()->withErrors(['message' => 'Peminjaman belum dikembalikan.']);
    }
}
