<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\LoanAdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\PasswordResetRequestController as AdminPasswordResetRequestController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login'); // Redirect awal langsung ke login
});

Route::get('/book-covers', [BookController::class, 'cover'])->name('books.cover');

// Timpa route dashboard bawaan Breeze
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/books', [BookController::class, 'index'])->name('books.index');

    // Admin book management
    Route::middleware('can:admin')->group(function () {
        Route::get('/books/create', [BookController::class, 'create'])->name('books.create');
        Route::post('/books', [BookController::class, 'store'])->name('books.store');
        Route::get('/books/{book}/edit', [BookController::class, 'edit'])->name('books.edit');
        Route::get('/books/{book}/stock/edit', [BookController::class, 'editStock'])->name('books.stock.edit');
        Route::put('/books/{book}', [BookController::class, 'update'])->name('books.update');
        Route::put('/books/{book}/stock', [BookController::class, 'updateStock'])->name('books.stock.update');
        Route::delete('/books/{book}', [BookController::class, 'destroy'])->name('books.destroy');
        Route::post('/books/{id}/restore', [BookController::class, 'restore'])->name('books.restore');
        Route::delete('/books/{id}/force-delete', [BookController::class, 'forceDelete'])->name('books.forceDelete');
    });

    Route::post('/loans', [LoanController::class, 'store'])->name('loans.store');
    Route::get('/loans/my-loans', [LoanController::class, 'myLoans'])->name('loans.myLoans');
    Route::get('/loans/{loan}/ticket', [LoanController::class, 'ticket'])->name('loans.ticket');
    Route::post('/loans/{loan}/extend', [LoanController::class, 'extend'])->name('loans.extend');
    Route::post('/loans/{loan}/return', [LoanController::class, 'return'])->name('loans.return');
    Route::post('/loans/{loan}/return-book', [LoanController::class, 'returnBook'])->name('loans.returnBook');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.readAll');

    // Admin management
    Route::middleware('can:admin')->group(function () {
        // Loan Admin
        Route::get('/loans/admin', [LoanAdminController::class, 'index'])->name('loans.admin');
        Route::post('/loans/{loan}/cancel', [LoanAdminController::class, 'cancel'])->name('loans.admin.cancel');
        Route::post('/loans/{loan}/verify', [LoanAdminController::class, 'verify'])->name('loans.admin.verify');
        Route::post('/loans/{loan}/complete-return', [LoanAdminController::class, 'completeReturn'])->name('loans.admin.complete-return');

        // General Admin (Users, Resets)
        Route::prefix('admin')->name('admin.')->group(function () {
            Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
            Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
            Route::get('/password-resets', [AdminPasswordResetRequestController::class, 'index'])->name('password-resets.index');
            Route::post('/password-resets/{request}/approve', [AdminPasswordResetRequestController::class, 'approve'])->name('password-resets.approve');
            Route::post('/password-resets/{request}/reject', [AdminPasswordResetRequestController::class, 'reject'])->name('password-resets.reject');
        });
    });
});

// Route bawaan Breeze untuk profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
