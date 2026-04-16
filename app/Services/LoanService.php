<?php
// app/Services/LoanService.php
namespace App\Services;

use App\Models\{Loan, Book, User};
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Exception;

class LoanService
{
    const MAX_BOOKS = 3;
    const FINE_PER_DAY = 2000; // Denda Rp 2.000 per hari

    public function borrowBooks(User $user, array $bookIds): Loan
    {
        if (count($bookIds) > self::MAX_BOOKS) {
            throw new Exception("Maksimal peminjaman adalah " . self::MAX_BOOKS . " buku sekaligus.");
        }

        // Cek total buku yang sedang dipinjam user
        $currentBorrowed = $user->loans()->where('status', 'borrowed')->withCount('items')->get()->sum('items_count');
        if (($currentBorrowed + count($bookIds)) > self::MAX_BOOKS) {
            throw new Exception("Limit peminjaman tercapai. Anda masih meminjam $currentBorrowed buku.");
        }

        return DB::transaction(function () use ($user, $bookIds) {
            $loan = Loan::create([
                'user_id' => $user->id,
                'loan_date' => Carbon::now(),
                'due_date' => Carbon::now()->addDays(7),
                'status' => 'borrowed'
            ]);

            foreach ($bookIds as $bookId) {
                $book = Book::lockForUpdate()->findOrFail($bookId);
                if ($book->stock < 1) {
                    throw new Exception("Buku '{$book->title}' kehabisan stok.");
                }

                $book->decrement('stock');
                $loan->items()->create(['book_id' => $book->id]);
            }

            return $loan;
        });
    }

    public function returnLoan(Loan $loan): void
    {
        DB::transaction(function () use ($loan) {
            $now = Carbon::now();
            $fine = 0;
            $status = 'returned';

            if ($now->isAfter($loan->due_date)) {
                $daysLate = $now->startOfDay()->diffInDays($loan->due_date->startOfDay());
                $fine = $daysLate * self::FINE_PER_DAY;
                $status = 'late';
            }

            $loan->update([
                'returned_at' => $now,
                'status' => $status,
                'fine_amount' => $fine
            ]);

            // Kembalikan stok
            foreach ($loan->items as $item) {
                $item->book()->increment('stock');
            }
        });
    }

    public function extendLoan(Loan $loan): void
    {
        if ($loan->is_extended) {
            throw new Exception("Peminjaman ini sudah diperpanjang sebelumnya.");
        }

        $loan->update([
            'due_date' => Carbon::parse($loan->due_date)->addDays(7),
            'is_extended' => true
        ]);
    }
}
