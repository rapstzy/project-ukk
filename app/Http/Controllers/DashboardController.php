<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Book;
use App\Models\User;
use Inertia\Inertia;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $isAdmin = $user->role === 'admin';

        if ($isAdmin) {
            $overdueCount = Loan::where('status', 'borrowed')
                ->whereDate('due_date', '<', Carbon::now())
                ->count();

            $loans = Loan::with('user', 'items.book')
                ->latest()
                ->limit(8)
                ->get();

            $upcomingDue = Loan::with('user', 'items.book')
                ->where('status', 'borrowed')
                ->whereBetween('due_date', [Carbon::now(), Carbon::now()->addDays(3)])
                ->orderBy('due_date')
                ->limit(4)
                ->get();

            $overdueLoans = Loan::with('user', 'items.book')
                ->where('status', 'borrowed')
                ->whereDate('due_date', '<', Carbon::now())
                ->orderBy('due_date', 'desc')
                ->limit(4)
                ->get();

            $stats = [
                'borrowed' => Loan::where('status', 'borrowed')->count(),
                'returned' => Loan::where('status', 'returned')->count(),
                'late' => Loan::where('status', 'late')->count(),
                'overdue' => $overdueCount,
                'total' => Loan::count(),
                'books' => Book::count(),
                'users' => User::count(),
            ];
        } else {
            $loans = $user->loans()
                ->with('items.book')
                ->latest()
                ->get();

            $upcomingDue = $loans->where('status', 'borrowed')
                ->filter(function ($loan) {
                    return $loan->due_date->diffInDays(Carbon::now()) <= 3 && $loan->due_date->diffInDays(Carbon::now()) >= 0;
                });

            $overdueLoans = $loans->where('status', 'borrowed')
                ->filter(function ($loan) {
                    return $loan->due_date->isPast();
                });

            $stats = [
                'borrowed' => $loans->where('status', 'borrowed')->count(),
                'returned' => $loans->where('status', 'returned')->count(),
                'late' => $loans->where('status', 'late')->count(),
                'overdue' => $overdueLoans->count(),
                'total' => $loans->count(),
            ];
        }

        return Inertia::render('Dashboard', [
            'loans' => $loans,
            'stats' => $stats,
            'upcomingDue' => $upcomingDue,
            'isAdmin' => $isAdmin,
            'overdueLoans' => $overdueLoans,
        ]);
    }
}
