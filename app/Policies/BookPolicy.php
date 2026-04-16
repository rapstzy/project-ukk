<?php

namespace App\Policies;

use App\Models\Book;
use App\Models\User;

class BookPolicy
{
    /**
     * Determine if user can view books
     */
    public function viewAny(User $user): bool
    {
        return true; // Anyone can view books
    }

    /**
     * Determine if user can view a book
     */
    public function view(User $user, Book $book): bool
    {
        return true; // Anyone can view a book
    }

    /**
     * Determine if user can create books (Admin only)
     */
    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine if user can update books (Admin only)
     */
    public function update(User $user, Book $book): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine if user can delete books (Admin only)
     */
    public function delete(User $user, Book $book): bool
    {
        return $user->role === 'admin';
    }
}
