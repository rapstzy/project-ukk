# ⚡ QUICK REFERENCE GUIDE - Apay's Books

## 🚀 QUICK START COMMANDS

### Setup Database (First Time)
```bash
# Fresh database with seeding
php artisan migrate:fresh --seed

# If error "Table already exists"
php artisan db:wipe
php artisan migrate:fresh --seed
```

### Development Servers
```bash
# Terminal 1: PHP Development Server
php artisan serve
# URL: http://localhost:8000

# Terminal 2: Frontend Watcher
npm run dev

# Production Build
npm run build
```

### Testing & Debugging
```bash
# Run tests
php artisan test

# Tinker shell (interactive PHP)
php artisan tinker

# Check migrations
php artisan migrate:status

# Clear caches
php artisan cache:clear
php artisan config:clear
npm run build  # rebuild JS/CSS
```

---

## 👤 TEST ACCOUNTS

### Admin Account
- Email: `admin@apaysbooks.com`
- Password: `admin123`
- Role: `admin`
- Can: Create/Edit/Delete books, View all loans

### User Account
- Email: `user@apaysbooks.com`
- Password: `password123`
- Role: `user`
- Can: Browse books, Borrow books, Return books

---

## 🗂️ IMPORTANT FILES

### Database Setup
- Migrations: `database/migrations/`
- Seeders: `database/seeders/`
  - `DatabaseSeeder.php` - Main seeder
  - `BookSeeder.php` - 20 sample books

### Backend
- Routes: `routes/web.php`
- Controllers: `app/Http/Controllers/`
- Models: `app/Models/`
- Services: `app/Services/LoanService.php`
- Policies: `app/Policies/`

### Frontend
- Pages: `resources/js/Pages/`
- Layouts: `resources/js/Layouts/`
- Components: `resources/js/Components/`
- Styles: `resources/css/app.css`

### Configuration
- Django settings: `.env`
- Database: `config/database.php`
- Auth: `config/auth.php`

---

## 📝 COMMON TASKS

### Create New Page
```bash
# 1. Create Vue component
touch resources/js/Pages/MyPage.vue

# 2. Add route in routes/web.php
Route::get('/mypage', MyPageController@show)->name('mypage');

# 3. Create controller
php artisan make:controller MyPageController

# 4. Return Inertia response
return Inertia::render('MyPage', ['data' => $data]);
```

### Create New API Endpoint
```bash
# 1. Create controller
php artisan make:controller ApiController

# 2. Add route in routes/api.php (or web.php)
Route::post('/api/endpoint', ApiController@store);

# 3. Add logic to controller
public function store(Request $request) {
    // Validate
    $validated = $request->validate([...]);
    
    // Process
    // Return response
    return response()->json([...]);
}
```

### Add New Model Field
```bash
# 1. Create migration
php artisan make:migration add_field_to_table

# 2. Edit migration file
Schema::table('table_name', function (Blueprint $table) {
    $table->string('field_name')->nullable();
});

# 3. Run migration
php artisan migrate

# 4. Update Model fillable
protected $fillable = ['field_name', ...];
```

### Debug Eloquent Queries
```bash
# In controller or service
\Illuminate\Support\Facades\DB::listen(function ($query) {
    \Log::info($query->sql);
});

# Or use tinker
php artisan tinker
> User::with('loans')->first()
```

### Update Styles
```bash
# Edit Tailwind CSS
# File: resources/css/app.css

# Rebuild assets
npm run dev      # Development (watch mode)
npm run build    # Production (minified)
```

---

## 🐛 TROUBLESHOOTING

### Database Issues

**Error: "Table already exists"**
```bash
php artisan db:wipe
php artisan migrate:fresh --seed
```

**Error: "Column doesn't exist"**
```bash
php artisan migrate:reset
php artisan migrate:fresh --seed
```

**Error: "Foreign key constraint fails"**
- Make sure parent table exists
- Check column types match (both BIGINT)
- Ensure ON DELETE CASCADE is set if needed

---

### Frontend Issues

**CSS not updating**
```bash
# Clear Vite cache and rebuild
rm -rf node_modules/.vite
npm run build
```

**JS errors in browser**
```bash
# Check browser console (F12)
# Check server logs: php artisan serve (shows errors)
# Clear browser cache (Ctrl+Shift+Del)
```

**Component not rendering**
```bash
# Check if component imported correctly
import MyComponent from '@/Components/MyComponent.vue'

# Check route name matches
route('page.name')  // must exist in routes/web.php

# Check props passed from controller
return Inertia::render('Page', ['data' => $data]);
// In Vue: {{ data }}
```

---

### Performance Issues

**Slow database queries**
```bash
# Use eager loading
$loans = Loan::with('items.book', 'user')->get();

# Add indexes
Schema::table('loans', function (Blueprint $table) {
    $table->index('user_id');
    $table->index('status');
});

# Check query count
php artisan tinker
> Illuminate\Support\Facades\DB::enableQueryLog()
> User::with('loans')->get()
> Illuminate\Support\Facades\DB::getQueryLog()
```

**Slow frontend**
```bash
# Profile in DevTools (F12 → Performance tab)
# Check for N+1 queries
# Minimize re-renders (use computed/watch wisely)
# Chunk large lists with pagination
```

---

## 📊 DATABASE QUERIES

### Check All Users
```bash
php artisan tinker

> User::all()
> User::where('role', 'admin')->first()
> User::with('loans')->get()
```

### Check All Books
```bash
> Book::all()
> Book::where('stock', '>', 0)->get()
> Book::with('loans')->get()
```

### Check All Loans
```bash
> Loan::all()
> Loan::with('user', 'items.book')->get()
> Loan::where('status', 'borrowed')->get()
> Loan::whereDate('due_date', '<', now())->get()  // Overdue
```

### Update Data
```bash
# Update user role
> $user = User::find(1)
> $user->role = 'admin'
> $user->save()

# Delete book
> Book::find(1)->delete()

# Create new loan
> $user = User::find(1)
> $loan = $user->loans()->create([
    'loan_date' => now(),
    'due_date' => now()->addDays(7),
    'status' => 'borrowed'
  ])
```

---

## 🔄 COMMON WORKFLOWS

### Borrow Books Workflow
```
1. User logs in (user@apaysbooks.com / password123)
2. Go to Books page (/books)
3. Select up to 3 books (checkmark appears)
4. Click "Pinjam Buku" button
5. Books added to active loans
6. Return to dashboard to see loans
7. Click "Kembalikan" to return books
```

### Admin Book Management Workflow
```
1. Login as admin (admin@apaysbooks.com / admin123)
2. Go to Books page
3. Click "Tambah Buku" (if UI implemented)
4. Fill form: title, author, ISBN, publisher, year, category, stock
5. Upload cover image
6. Submit form
7. Book appears in grid
8. Edit/delete available on admin view
```

### Loan Extension Workflow
```
1. User has active loan (borrowed status)
2. Click "Perpanjang" on loan card
3. Check: is_extended == false (can't extend twice)
4. Due date extended by 7 days
5. Status still "borrowed"
6. is_extended set to true (prevents further extension)
```

### Fine Calculation Workflow
```
1. Book due date passed
2. User returns book
3. System calculates: days_late = now - due_date
4. Fine calculated: days_late × Rp 2000
5. Status set to "late"
6. Fine amount displayed on dashboard
7. User needs to pay fine (feature ready)
```

---

## 🎯 PROJECT STRUCTURE QUICK MAP

```
PeminjamanBuku/
├── app/                    (Backend logic)
│   ├── Models/            (Database models)
│   ├── Controllers/        (Request handlers)
│   ├── Services/           (Business logic)
│   ├── Policies/           (Authorization)
│   └── Http/
│       ├── Middleware/     (Request filters)
│       └── Requests/       (Validation)
│
├── database/               (Database)
│   ├── migrations/         (Table structure)
│   └── seeders/            (Sample data)
│
├── resources/              (Frontend)
│   ├── js/
│   │   ├── Pages/         (Full page components)
│   │   ├── Layouts/       (Layout templates)
│   │   ├── Components/    (Reusable components)
│   │   └── app.js         (Entrypoint)
│   ├── views/
│   │   └── app.blade.php  (HTML template)
│   └── css/
│       └── app.css        (Tailwind + custom)
│
├── routes/                 (URL mapping)
│   ├── web.php            (Web routes)
│   ├── api.php            (API routes)
│   └── auth.php           (Auth routes)
│
├── config/                 (Configuration)
│   ├── app.php
│   ├── auth.php
│   ├── database.php
│   └── others...
│
├── storage/                (File storage)
├── bootstrap/              (App startup)
├── vendor/                 (Composer packages)
├── node_modules/           (NPM packages)
├── public/                 (Publicly accessible)
├── tests/                  (Test suite)
│
├── .env                    (Environment variables)
├── package.json            (NPM dependencies)
├── composer.json           (PHP dependencies)
├── vite.config.js          (Vite config)
├── tailwind.config.js      (Tailwind config)
├── phpunit.xml             (Test config)
│
└── SETUP_GUIDE.md          (This project setup)
    FEATURE_CHECKLIST.md    (What's implemented)
    ARCHITECTURE.md         (Technical details)
    QUICK_REFERENCE.md      (You are here!)
```

---

## 🔧 USEFUL ARTISAN COMMANDS

```bash
# Migrations
php artisan migrate           # Run pending migrations
php artisan migrate:fresh     # Drop & recreate all tables
php artisan migrate:reset     # Rollback all migrations
php artisan migrate:rollback  # Rollback last batch
php artisan migrate:status    # Check migration status

# Seeders
php artisan db:seed                      # Run all seeders
php artisan db:seed --class=BookSeeder   # Run specific seeder

# Models & Factories
php artisan make:model Model              # Create model
php artisan make:model Model -m           # With migration
php artisan make:factory ModelFactory     # Create factory

# Controllers
php artisan make:controller ControllerName
php artisan make:controller ControllerName -r   # With methods

# Other
php artisan tinker              # PHP shell
php artisan route:list          # Show all routes
php artisan config:cache        # Cache config (production)
php artisan config:clear        # Clear config cache
php artisan cache:clear         # Clear app cache
php artisan queue:work          # Process queue jobs
php artisan test                # Run tests
```

---

## 🌐 ENVIRONMENT VARIABLES (.env)

```env
# Application
APP_NAME="Apay's Books"
APP_ENV=local
APP_DEBUG=true
APP_KEY=base64:xxxxx

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=buku
DB_USERNAME=root
DB_PASSWORD=

# Mail (optional)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=xxxxx
MAIL_PASSWORD=xxxxx

# Session
SESSION_DRIVER=cookie
SESSION_LIFETIME=120
```

---

## 📚 USEFUL RESOURCES

### Laravel
- Docs: https://laravel.com/docs
- API: https://laravel.com/api/12.x
- Laracasts: https://laracasts.com

### Vue 3
- Docs: https://vuejs.org
- API: https://vuejs.org/api/

### Inertia.js
- Docs: https://inertiajs.com
- Examples: https://github.com/inertiajs

### Tailwind CSS
- Docs: https://tailwindcss.com
- Components: https://tailwindui.com

---

## ✅ PRE-DEPLOYMENT CHECKLIST

- [ ] Database migrated (`php artisan migrate:fresh --seed`)
- [ ] .env configured (database, app key, debug=false)
- [ ] Assets built (`npm run build`)
- [ ] Routes tested (all pages accessible)
- [ ] Auth tested (login works, roles enforced)
- [ ] Loans tested (borrow, return, extend works)
- [ ] No console errors (open DevTools with F12)
- [ ] No server errors (check storage/logs/laravel.log)
- [ ] Email configured (if notifications enabled)
- [ ] File uploads working (if cover images)
- [ ] Mobile responsive tested
- [ ] Dark mode verified
- [ ] Performance acceptable (under 3s load time)

---

Last Updated: April 2, 2026
Quick Reference v1.0
