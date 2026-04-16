# 📚 SETUP GUIDE - Apay's Books Library Application

## ✅ Aplikasi Sudah Dibangun Dengan Lengkap!

Semua komponen, fitur, dan UI sudah diimplementasikan sesuai requirement. Berikut panduan final setup.

---

## 🚀 QUICK START - Setup Database

### Step 1: Reset Database
```bash
# Delete semua data lama (jika ada)
php artisan migrate:reset

# Jalankan fresh migration dengan seeder
php artisan migrate:fresh --seed
```

**Note:** Jika masih ada error "Table already exists", jalankan:
```bash
# Drop semua tables dan buat baru
php artisan db:wipe
php artisan migrate:fresh --seed
```

### Step 2: Verify Seeders
Setelah seeding, Anda akan memiliki:
- **Admin User**
  - Email: `admin@apaysbooks.com`
  - Password: `admin123`
  - Role: `admin`

- **Regular User**
  - Email: `user@apaysbooks.com`
  - Password: `password123`
  - Role: `user`

- **20 Buku** dengan stock yang bervariasi

### Step 3: Build Frontend Assets
```bash
npm run build
# atau untuk development:
npm run dev
```

### Step 4: Run Application
```bash
php artisan serve
php artisan queue:work  # jika menggunakan queue
```

Visit: **http://localhost:8000**

---

## 🎨 FEATURES IMPLEMENTED

### ✨ UI/UX (Modern X-Style)
- [x] Dark mode default (#000, #111, #16181C)
- [x] Sidebar left navigation (like Twitter/X)
- [x] Responsive design (mobile, tablet, desktop)
- [x] Custom cursor (dot + ring, smooth follow)
- [x] Smooth animations & transitions
- [x] Button hover: scale 1.05
- [x] Card hover: lift + shadow
- [x] Input focus glow effect

### 🔐 Authentication & Authorization
- [x] Login page (centered, clean, rounded)
- [x] Register page (centered, clean, rounded)
- [x] Role-based middleware (admin/user)
- [x] Password hashing
- [x] Auth checks

### 📚 Book Management
- [x] Browse buku grid (modern card design)
- [x] Book cover upload support
- [x] Stock status display
- [x] Category & filter support
- [x] Multi-select borrowing (up to 3 books)
- [x] Floating action bar saat item dipilih

### 🏠 Dashboard
- [x] Stats cards (borrowed, returned, late, total)
- [x] Active loans display
- [x] Loan status badges
- [x] Due date countdown
- [x] Tips & panduan
- [x] Responsive grid layout

### 📖 Peminjaman (Loans)
- [x] Auto-approve peminjaman
- [x] Maksimal 3 buku sekaligus
- [x] 7 hari period otomatis
- [x] Status: borrowed, returned, late
- [x] Perpanjangan 1x (+7 hari)
- [x] Denda Rp 2.000/hari keterlambatan

### 🔔 Loading & Feedback
- [x] Top loader (progress bar)
- [x] Spinner component
- [x] Skeleton loaders
- [x] Custom cursor
- [x] Smooth page transitions

### 📱 Components Created
- `AppLayout.vue` - Main layout with sidebar
- `GuestLayout.vue` - Auth layout
- `Dashboard.vue` - Dashboard page
- `Books/Index.vue` - Books browsing grid
- `Login.vue` - Login page
- `Register.vue` - Register page
- `Spinner.vue` - Loading spinner
- `SkeletonLoader.vue` - Skeleton loading
- `TopLoader.vue` - Progress bar
- `CustomCursor.vue` - Custom cursor

### 🗂️ Backend Struktur
```
app/
├── Http/
│   ├── Controllers/
│   │   ├── BookController.php (CRUD)
│   │   ├── LoanController.php (Loans management)
│   │   ├── DashboardController.php
│   │   └── Auth/*
│   ├── Middleware/
│   │   └── CheckRole.php (Role authorization)
│   └── Requests/
├── Models/
│   ├── User.php (dengan role)
│   ├── Book.php
│   ├── Loan.php
│   └── LoanItem.php
├── Policies/
│   ├── BookPolicy.php
│   └── LoanPolicy.php
└── Services/
    └── LoanService.php (Business logic)

database/
├── migrations/
│   ├── 0001_01_01_000000_create_users_table.php (dengan role)
│   ├── 2026_04_02_014436_create_books_table.php
│   ├── 2026_04_02_014436_create_loans_table.php
│   └── 2026_04_02_015624_create_loan_items_table.php
└── seeders/
    ├── BookSeeder.php (20 buku)
    └── DatabaseSeeder.php
```

---

## 🎯 TESTING SCENARIOS

### Test Admin Functions
```
Login: admin@apaysbooks.com / admin123

1. Lihat semua buku di Books page
2. Buku sudah terlihat dengan cover, title, author
3. Select buku (up to 3) → Floating action bar muncul
4. Klik "Pinjam Buku" → Auto-approve & redirect dashboard
5. Dashboard menunjukkan active loans
6. Cek upcoming due dates, late status, dll
```

### Test User Functions
```
Login: user@apaysbooks.com / password123

1. Browser books pada

 page
2. Multi-select buku untuk dipinjam
3. Floating action bar muncul dengan count
4. Pinjam 1-3 buku → Loan created
5. Lihat loans di dashboard
6. Lihat stats & tips
7. Return buku (jika ada)
8. Perpanjang peminjaman (jika eligible)
```

---

## 🛠️ TECH STACK

- **Backend:** Laravel 12
- **Frontend:** Vue 3 (Composition API)
- **Inertia:** Inertia.js v2
- **Styling:** Tailwind CSS v3
- **Database:** MySQL
- **Icons:** Emoji (ringan & lucu)
- **Animations:** Vue Transitions + CSS

---

## 📝 DATABASE STRUCTURE

### Users Table
```sql
- id (PK)
- name
- email (unique)
- password (hashed)
- role (enum: admin, user) 🆕
- email_verified_at
- remember_token
- timestamps
```

### Books Table
```sql
- id (PK)
- title
- author
- isbn (unique)
- publisher
- year
- category
- stock (integer)
- cover (nullable, path)
- description (nullable)
- timestamps
```

### Loans Table
```sql
- id (PK)
- user_id (FK → users)
- loan_date (date)
- due_date (date)
- returned_at (nullable, date)
- status (enum: borrowed, returned, late)
- fine_amount (integer)
- is_extended (boolean)
- timestamps
```

### Loan Items Table
```sql
- id (PK
- loan_id (FK → loans)
- book_id (FK → books)
- timestamps
```

---

## 🔐 AUTHORIZATION RULES

### Admin Can:
- ✅ View all books
- ✅ Create book (CRUD)
- ✅ Update book (CRUD)
- ✅ Delete book (CRUD)
- ✅ View all loans
- ✅ Monitor fines

### User Can:
- ✅ View all books
- ✅ Borrow books (max 3)
- ✅ Return books
- ✅ Extend loans (1x per loan)
- ✅ View own loans
- ❌ Cannot manage books

---

## 🎨 COLOR SCHEME

```css
/* Dark Mode (Default) */
--bg-black: #000000
--bg-dark: #0A0E27 / #111111
--bg-card: #16181C / #1E1E1E
--accent-blue: #1DA1F2 / #3B82F6
--text-white: #FFFFFF
--text-gray: #6B7280 / #9CA3AF
--border-gray: #374151 / #4B5563

/* Gradients */
--gradient-primary: from-blue-500 via-purple-500 to-blue-500
--gradient-card: from-gray-800 to-gray-900
```

---

## 📚 ENDPOINT ROUTES

```php
// Auth Routes
POST   /register
POST   /login
POST   /logout

// Protected Routes (Authenticated)
GET    /dashboard
GET    /books
POST   /books (Admin)
PUT    /books/{id} (Admin)
DELETE /books/{id} (Admin)

// Loan Routes
POST   /loans (Borrow)
POST   /loans/{loan}/return (Return)
POST   /loans/{loan}/extend (Extend)

// Profile
GET    /profile
PATCH  /profile
DELETE /profile
```

---

## 🚨 TROUBLESHOOTING

### Issue: "Table already exists"
```bash
# Solution: Wipe database completely
php artisan db:wipe
php artisan migrate:fresh --seed
```

### Issue: Migrations don't run
```bash
# Check migration status
php artisan migrate:status

# Run migrations
php artisan migrate
```

### Issue: Css/JS not loading
```bash
# Rebuild assets
npm run build
# or dev mode
npm run dev
```

### Issue: Permission denied upload
```bash
# Fix storage permissions
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
```

---

## 📊 DEMO DATA

After seeding, Anda akan mendapatkan:

### Admin Account
- Email: `admin@apaysbooks.com`
- Password: `admin123`
- 20 buku tersedia

### User Account  
- Email: `user@apaysbooks.com`
- Password: `password123`
- Siap meminjam buku!

### Books (20 Total)
1. Sapiens - 5 stock
2. The Midnight Library - 4 stock
3. 1984 - 3 stock
... dst

---

## 🎉 KESIMPULAN

Aplikasi **Apay's Books** sudah 100% siap! 
- ✅ Modern UI/UX (X-style)
- ✅ Semua fitur terimplementasi
- ✅ Dark mode activated
- ✅ Custom cursor & animations  
- ✅ Role-based auth
- ✅ Database ready
- ✅ Responsive design

**Next Step:** Jalankan `php artisan migrate:fresh --seed` dan nikmati! 🚀

---

Generated: April 2, 2026
App: Apay's Books Library System
Version: 1.0.0 (Beta)
