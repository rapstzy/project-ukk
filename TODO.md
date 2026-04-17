# DB Connection Fix - SQLite Fallback (MySQL service not found)

- [x] 1. MySQL service not found, switching to SQLite
- [ ] 1. Edit .env: DB_CONNECTION=sqlite   DB_DATABASE=database/database.sqlite   SESSION_DRIVER=file   CACHE_STORE=file
- [ ] 2. Run `php artisan migrate`
- [ ] 3. Clear caches (`php artisan config:clear && php artisan cache:clear && php artisan route:clear`)
- [ ] 4. Start server (`php artisan serve`)
- [ ] 5. Test connection (`php artisan tinker` then `DB::connection()->getPdo();`)

