<?php

namespace App\Policies;

use App\Models\Loan;
use App\Models\User;

class LoanPolicy
{
    /**
     * Determine if user can return this loan
     */
    public function returnLoan(User $user, Loan $loan): bool
    {
        return $user->id === $loan->user_id || $user->role === 'admin';
    }

    /**
     * Determine if user can extend this loan
     */
    public function extendLoan(User $user, Loan $loan): bool
    {
        return $user->id === $loan->user_id && $loan->status === 'borrowed' && !$loan->is_extended;
    }

    /**
     * Admin can manage all loans
     */
    public function admin(User $user): bool
    {
        return $user->role === 'admin';
    }
}
