# Deployment Guide

Panduan ini dibuat khusus untuk project Laravel `PeminjamanBuku-Rafly XII PPLG` agar bisa dipindahkan dari local Laragon ke hosting atau server online.

## Ringkasan Kebutuhan

- PHP 8.2 atau lebih baru
- MySQL/MariaDB
- Composer
- Node.js hanya dibutuhkan jika build asset dilakukan di server
- Akses ke folder `public` sebagai document root

## Checklist Sebelum Upload

1. Pastikan aplikasi berjalan normal di local.
2. Jalankan build asset produksi:
   ```bash
   npm run build
   ```
3. Pastikan file `public/hot` tidak ikut ter-upload ke hosting.
4. Siapkan database kosong di hosting.
5. Siapkan `.env` production dengan nilai domain dan database yang benar.

Kalau kamu mau import database langsung, pakai file [IMPORT_DATABASE.sql](IMPORT_DATABASE.sql) di root project.

## Nilai `.env` Untuk Hosting

Gunakan minimal konfigurasi seperti ini:

```env
APP_NAME="Apay's Books"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://domainkamu.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database
DB_USERNAME=username_database
DB_PASSWORD=password_database

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=sync
FILESYSTEM_DISK=local
```

Catatan:
- `APP_URL` harus sesuai domain final.
- Jika hosting memakai HTTPS, pastikan URL dimulai dengan `https://`.
- Aplikasi ini menyimpan cover buku ke storage publik, jadi `storage:link` harus dijalankan.

## Langkah Deploy Di cPanel / Shared Hosting

1. Upload seluruh project ke folder di luar `public_html` jika provider mengizinkan.
2. Arahkan document root domain ke folder `public`.
3. Buat database MySQL baru dan catat kredensialnya.
4. Upload atau salin file project ke server.
5. Buat file `.env` dari `.env.example` lalu sesuaikan isinya.
6. Import file `IMPORT_DATABASE.sql` ke database lewat phpMyAdmin.
7. Jalankan perintah berikut di terminal SSH atau Terminal cPanel:
   ```bash
   composer install --no-dev --optimize-autoloader
   php artisan key:generate
   php artisan storage:link
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```
8. Upload folder `public/build` hasil `npm run build` kalau asset dibuild di local.
9. Buka website dan cek login, halaman dashboard, dan upload cover buku.

Catatan:
- Karena database sudah diimpor, kamu tidak perlu menjalankan `php artisan migrate --force` lagi.
- Jika file SQL ini dipakai, tabel dan data demo sudah tersedia.
- Kalau folder `vendor` sudah ikut di-upload dari lokal, `composer install` bisa dilewati.
- Kalau `APP_KEY` sudah ada di `.env`, `php artisan key:generate` juga bisa dilewati.

## Jika Shared Hosting Tidak Bisa Mengubah Document Root

Pakai pola fallback ini:

1. Pindahkan isi folder `public/` ke `public_html/`.
2. Sisakan file `index.php` dan `.htaccess` dari folder `public`.
3. Edit `index.php` agar path `vendor/autoload.php` dan `bootstrap/app.php` mengarah ke lokasi project yang asli.
4. Tetap jalankan `storage:link` jika hosting mendukung symbolic link.

Kalau symbolic link tidak didukung, folder `storage` untuk file publik harus disesuaikan dengan solusi dari provider hosting.

## Deploy Ke VPS

1. Install PHP 8.2+, MySQL, Nginx atau Apache, Composer, dan Node.js.
2. Clone atau upload project ke folder server.
3. Set `.env` production.
4. Jalankan:
   ```bash
   composer install --no-dev --optimize-autoloader
   npm install
   npm run build
   php artisan key:generate
   php artisan migrate --force
   php artisan storage:link
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```
5. Arahkan web server ke folder `public`.

## Data Demo

Seeder project ini membuat akun demo berikut:

- Admin: `admin@apaysbooks.com`
- Password: `admin123`
- User: `user@apaysbooks.com`
- Password: `password123`

Jika ingin langsung pakai data demo di hosting, jalankan:

```bash
php artisan db:seed
```

Atau jika ingin bersih total:

```bash
php artisan migrate:fresh --seed
```

## Troubleshooting

- Halaman blank atau 500: cek `storage/logs/laravel.log`
- Asset CSS/JS tidak tampil: pastikan `public/build` sudah ter-upload
- Upload cover gagal: jalankan `php artisan storage:link` dan cek permission folder `storage`
- Login bermasalah: cek `APP_KEY`, session table, dan nilai `APP_URL`
- Redirect aneh ke dev server: pastikan file `public/hot` tidak ada di production
