# 🏛️ TECHNICAL ARCHITECTURE - Apay's Books

## System Overview

```
┌─────────────────────────────────────────────────────────────┐
│                     APAY'S BOOKS                            │
│              Library Management System v1.0                 │
└─────────────────────────────────────────────────────────────┘
        ↓              ↓              ↓              ↓
    ┌──────────────────────────────────────────────────┐
    │           Frontend Layer (Vue 3 + Inertia)       │
    │  - Components, Pages, Plugins, Composables      │
    │  - Dark Mode, Animations, Custom Cursor         │
    └──────────────────────────────────────────────────┘
                           ↓
    ┌──────────────────────────────────────────────────┐
    │        Inertia.js (Server-Side Rendering)       │
    │  - Zero-state data passing                       │
    │  - Automatic prop validation                     │
    │  - Laravel ↔ Vue bridge                          │
    └──────────────────────────────────────────────────┘
                           ↓
    ┌──────────────────────────────────────────────────┐
    │         Backend Layer (Laravel 12)               │
    │  - Routes, Controllers, Services, Models         │
    │  - Database, Authorization, Validation           │
    └──────────────────────────────────────────────────┘
                           ↓
    ┌──────────────────────────────────────────────────┐
    │      Database Layer (MySQL)                      │
    │  - Users, Books, Loans, LoanItems tables         │
    │  - Relationships, Constraints, Migrations        │
    └──────────────────────────────────────────────────┘
```

---

## Code Architecture

### 1. Frontend Structure

```
resources/
├── js/
│   ├── app.js (Vue 3 app initialization + plugins)
│   ├── bootstrap.js (axios config)
│   │
│   ├── Layouts/
│   │   ├── AppLayout.vue (sidebar + main layout)
│   │   ├── GuestLayout.vue (auth pages layout)
│   │   └── AuthenticatedLayout.vue (fallback)
│   │
│   ├── Pages/
│   │   ├── Dashboard.vue (stats + loans)
│   │   ├── Books/
│   │   │   └── Index.vue (browse & borrow)
│   │   ├── Auth/
│   │   │   ├── Login.vue
│   │   │   └── Register.vue
│   │   └── Profile/
│   │       ├── Edit.vue
│   │       └── Partials/
│   │
│   └── Components/
│       ├── Spinner.vue (loading indicator)
│       ├── SkeletonLoader.vue (placeholder)
│       ├── TopLoader.vue (progress bar)
│       ├── CustomCursor.vue (cursor effect)
│       ├── PrimaryButton.vue
│       ├── SecondaryButton.vue
│       ├── TextInput.vue
│       ├── Modal.vue
│       └── [others...]
│
└── css/
    └── app.css (Tailwind + custom)

```

### 2. Backend Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── BookController.php
│   │   │   ├── index() - GET /books
│   │   │   ├── show() - GET /books/{id}
│   │   │   ├── create() - GET /books/create (show form)
│   │   │   ├── store() - POST /books
│   │   │   ├── edit() - GET /books/{id}/edit (show form)
│   │   │   ├── update() - PUT /books/{id}
│   │   │   └── destroy() - DELETE /books/{id}
│   │   │
│   │   ├── LoanController.php
│   │   │   ├── store() - POST /loans (borrow)
│   │   │   ├── return() - POST /loans/{id}/return
│   │   │   └── extend() - POST /loans/{id}/extend
│   │   │
│   │   ├── DashboardController.php
│   │   │   └── __invoke() - GET /dashboard
│   │   │
│   │   └── Auth/
│   │       ├── RegisteredUserController.php
│   │       ├── AuthenticatedSessionController.php
│   │       └── [others...]
│   │
│   ├── Middleware/
│   │   └── CheckRole.php (verify user role)
│   │
│   └── Requests/ (Form Validation)
│       ├── LoginRequest.php
│       └── RegisterRequest.php
│
├── Models/
│   ├── User.php
│   │   ├── Properties: id, name, email, password, role
│   │   ├── Methods: isAdmin(), isUser()
│   │   └── Relations: loans(), borrowedBooks()
│   │
│   ├── Book.php
│   │   ├── Properties: id, title, author, isbn, stock, cover, etc...
│   │   └── Relations: loans()
│   │
│   ├── Loan.php
│   │   ├── Properties: id, user_id, loan_date, due_date, status, fine_amount
│   │   ├── Enums: status(borrowed|returned|late)
│   │   └── Relations: user(), items(), books()
│   │
│   └── LoanItem.php (pivot model)
│       ├── Properties: id, loan_id, book_id
│       └── Relations: loan(), book()
│
├── Policies/
│   ├── BookPolicy.php
│   │   ├── viewAny() - return true (anyone can list)
│   │   ├── view() - return true
│   │   ├── create() - return user()->isAdmin()
│   │   ├── update() - return user()->isAdmin() && user->can(...)
│   │   └── delete() - return user()->isAdmin()
│   │
│   └── LoanPolicy.php
│       ├── returnLoan() - user is owner || admin
│       ├── extendLoan() - user is owner && !is_extended && admin
│       └── view() - user is owner || admin
│
├── Services/
│   └── LoanService.php
│       ├── borrowBooks(user, bookIds[])
│       │   ├── Validate max 3 books
│       │   ├── Check stock > 0
│       │   ├── Create Loan record
│       │   ├── Create LoanItem for each book
│       │   ├── Decrement stock
│       │   └── Return Loan instance
│       │
│       ├── returnLoan(loan)
│       │   ├── Calculate days_late = max(0, now - due_date)
│       │   ├── Calculate fine = days_late * 2000
│       │   ├── Set status = late if days_late > 0
│       │   ├── Increment book stocks
│       │   └── Save loan record
│       │
│       └── extendLoan(loan)
│           ├── Check !is_extended
│           ├── Add 7 days to due_date
│           ├── Set is_extended = true
│           └── Return updated Loan
│
└── Providers/
    └── AppServiceProvider.php
        └── Register policies
```

### 3. Database Schema

```sql
-- USERS TABLE
CREATE TABLE users (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255),
    email VARCHAR(255) UNIQUE,
    password VARCHAR(255),
    role ENUM('admin', 'user') DEFAULT 'user',
    email_verified_at TIMESTAMP NULL,
    remember_token VARCHAR(100),
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

-- BOOKS TABLE
CREATE TABLE books (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255),
    author VARCHAR(255),
    isbn VARCHAR(20) UNIQUE,
    publisher VARCHAR(255),
    year INT,
    category VARCHAR(100),
    stock INT DEFAULT 0,
    cover VARCHAR(255) NULL,
    description LONGTEXT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
INDEX idx_category (category);
INDEX idx_stock (stock);

-- LOANS TABLE
CREATE TABLE loans (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT,
    loan_date DATE,
    due_date DATE,
    returned_at DATE NULL,
    status ENUM('borrowed', 'returned', 'late') DEFAULT 'borrowed',
    fine_amount INT DEFAULT 0,
    is_extended BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
INDEX idx_user (user_id);
INDEX idx_status (status);
INDEX idx_due_date (due_date);

-- LOAN_ITEMS TABLE (Pivot)
CREATE TABLE loan_items (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    loan_id BIGINT,
    book_id BIGINT,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (loan_id) REFERENCES loans(id) ON DELETE CASCADE,
    FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE,
    UNIQUE KEY (loan_id, book_id)
);
```

---

## Data Flow

### Borrowing Book Flow
```
User selects books (Books/Index.vue)
        ↓
Click "Pinjam Buku" button
        ↓
Form POST to route('loans.store')
        ↓
LoanController@store()
        └─ authorize: user can borrow
        ├─ validate: bookIds required, count <= 3
        ├─ call: LoanService->borrowBooks($user, $bookIds)
        │   └─ LoanService:
        │       ├─ Validate stock for each book
        │       ├─ Create Loan record
        │       ├─ Create LoanItem for each book
        │       ├─ Decrement book stock
        │       └─ Return created Loan
        │
        ├─ Return inertia redirect to dashboard
        └─ Frontend: Redirect + clear selection
```

### Returning Book Flow
```
User clicks "Return" button on active loan
        ↓
Form POST to "routes.loans.return"
        ↓
LoanController->return()
        ├─ authorize: user is owner || admin
        ├─ validate: loan exists, status == 'borrowed'
        ├─ call: LoanService->returnLoan($loan)
        │   └─ LoanService:
        │       ├─ Calculate days_late
        │       ├─ Calculate fine amount
        │       ├─ Set status to 'returned' or 'late'
        │       ├─ Increment book stocks
        │       └─ Save loan
        │
        └─ Return JSON response with fine_amount
```

### Dashboard Stats Flow
```
User visits /dashboard
        ↓
Route: GET /dashboard (DashboardController)
        ↓
DashboardController->__invoke()
        ├─ Get user's loans (with items.book)
        ├─ Calculate stats:
        │   ├─ Borrowed count (status='borrowed')
        │   ├─ Returned count (status='returned')
        │   ├─ Late count (status='late')
        │   └─ Total borrowed (count)
        │
        ├─ Get upcoming due (next 7 days)
        ├─ Get overdue loans (past due_date)
        │
        └─ Return inertia view with props:
            {
              loans: Loan[],
              stats: {
                borrowed: int,
                returned: int,
                late: int,
                total: int
              },
              upcomingDue: Loan[],
              overdueLoans: Loan[]
            }
```

---

## Component Hierarchy

```
App.vue (root)
├── TopLoader (global)
├── CustomCursor (global)
│
└── AppLayout (if authenticated)
    ├── HeaderNav
    ├── Sidebar (desktop) / Hamburger (mobile)
    │   ├── Logo
    │   ├── NavLinks
    │   │   ├── Dashboard
    │   │   ├── Books
    │   │   └── My Loans
    │   └── UserMenu
    │       ├── Profile
    │       └── Logout
    │
    ├── MainContent
    │   └── <slot /> (Page component)
    │
    └── GuestLayout (if not authenticated)
        ├── CenteredForm
        └── <slot /> (Login/Register)

Pages Structure:
├── Dashboard.vue
│   ├── StatsCard (×4)
│   ├── LoansGrid
│   │   └── LoanCard
│   │       ├── BookCover
│   │       ├── LoanInfo
│   │       └── ActionButtons
│   └── TipsCard
│
├── Books/Index.vue
│   ├── SearchBar
│   ├── BooksGrid
│   │   └── BookCard (×many)
│   │       ├── BookCover
│   │       ├── CheckboxOverlay
│   │       └── StockBadge
│   └── FloatingActionBar
│       ├── SelectCount
│       └── BorrowButton
│
└── Auth/
    ├── Login.vue
    │   └── LoginForm
    │       ├── EmailInput
    │       ├── PasswordInput
    │       └── SubmitButton
    │
    └── Register.vue
        └── RegisterForm
            ├── NameInput
            ├── EmailInput
            ├── PasswordInput
            ├── ConfirmPasswordInput
            └── SubmitButton
```

---

## Authorization Rules

```php
// BookPolicy
public function viewAny() => true  // Anyone can list books

public function view() => true     // Anyone can view book detail

public function create(User $user) => $user->role === 'admin'

public function update(User $user, Book $book) => $user->role === 'admin'

public function delete(User $user, Book $book) => $user->role === 'admin'

// LoanPolicy
public function returnLoan(User $user, Loan $loan) => 
    $user->id === $loan->user_id || $user->role === 'admin'

public function extendLoan(User $user, Loan $loan) => 
    $user->id === $loan->user_id && 
    !$loan->is_extended && 
    ($user->role === 'admin' || true)
```

---

## Routes

```php
// routes/web.php

// Auth routes (built-in Laravel)
Route::middleware('guest')->group(function () {
    Route::get('register', RegisteredUserController@create)->name('register');
    Route::post('register', RegisteredUserController@store);
    Route::get('login', AuthenticatedSessionController@create)->name('login');
    Route::post('login', AuthenticatedSessionController@store);
});

Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    
    // Books
    Route::get('/books', BookController@index)->name('books.index');
    Route::get('/books/{book}', BookController@show)->name('books.show');
    
    // Admin only
    Route::middleware('check.role:admin')->group(function () {
        Route::get('/books/create', BookController@create)->name('books.create');
        Route::post('/books', BookController@store)->name('books.store');
        Route::get('/books/{book}/edit', BookController@edit)->name('books.edit');
        Route::put('/books/{book}', BookController@update)->name('books.update');
        Route::delete('/books/{book}', BookController@destroy)->name('books.destroy');
    });
    
    // Loans
    Route::post('/loans', LoanController@store)->name('loans.store');
    Route::post('/loans/{loan}/return', LoanController@return)->name('loans.return');
    Route::post('/loans/{loan}/extend', LoanController@extend)->name('loans.extend');
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post('/logout', AuthenticatedSessionController@destroy)->name('logout');
```

---

## Seeders

### DatabaseSeeder
```php
$admin = User::factory()->create([
    'name' => 'Admin',
    'email' => 'admin@apaysbooks.com',
    'password' => bcrypt('admin123'),
    'role' => 'admin'
]);

$user = User::factory()->create([
    'name' => 'User',
    'email' => 'user@apaysbooks.com',
    'password' => bcrypt('password123'),
    'role' => 'user'
]);

$this->call(BookSeeder::class);
```

### BookSeeder (20 Books)
```php
Book::create([
    'title' => 'Sapiens',
    'author' => 'Yuval Noah Harari',
    'isbn' => '978-0062316097',
    'publisher' => 'Harper',
    'year' => 2011,
    'category' => 'History',
    'stock' => 5,
    'description' => '...'
]);
// ... 19 more books (see BookSeeder.php)
```

---

## Technology Decisions

| Aspect | Choice | Reason |
|--------|--------|--------|
| Language | PHP 8.2 | Type safety, modern features |
| Framework | Laravel 12 | Rich ecosystem, built-in auth |
| Database | MySQL | Reliable, widely supported |
| Frontend | Vue 3 | Reactive, component-based |
| Bridge | Inertia.js | Type-safe, zero-state data |
| Styling | Tailwind | Utility-first, dark mode ready |
| Icons | Emoji | Lightweight, no extra dependencies |
| Animations | CSS/Vue | Smooth, performant |
| State | Props | Simple, predictable (Inertia) |
| Validation | Built-in | Consistent frontend + backend |

---

## Performance Considerations

### Database
- ✅ Eager loading (with relationships)
- ✅ Proper indexes on foreign keys
- ✅ Pagination for large lists
- ✅ Appropriate select() calls

### Frontend
- ✅ Component lazy loading ready
- ✅ Image optimization (cover uploads)
- ✅ CSS: Tailwind purged build
- ✅ JavaScript: Minified by Vite

### Caching
- ✅ HTTP cache headers on Inertia responses
- ✅ Browser caching for assets
- ✅ Service layer for query reuse

---

## Security Features

| Feature | Implementation |
|---------|-----------------|
| CSRF | Laravel middleware (enabled) |
| XSS | Vue auto-escaping + Inertia |
| SQL Injection | Eloquent ORM (parameterized queries) |
| Password | bcrypt (Laravel built-in) |
| Auth | Session-based with remember token |
| Authorization | Policies + middleware |
| Validation | Form requests + model rules |
| HTTPS | Recommended in production |

---

## Deployment Checklist

- [ ] Update .env with production database
- [ ] Run `php artisan config:cache`
- [ ] Run `php artisan route:cache`
- [ ] Run `npm run build` (minified assets)
- [ ] Set DEBUG=false in .env
- [ ] Configure HTTPS (SSL certificate)
- [ ] Set up proper file permissions
- [ ] Configure email for notifications
- [ ] Set up backups
- [ ] Monitor logs and errors
- [ ] Set up CDN for assets (optional)
- [ ] Database indexes verified
- [ ] Load testing completed

---

Last Updated: April 2, 2026
Status: Architecture Complete & Production Ready
