# Langkah Setelah Clone Repo Laravel "Reuse dan Share"

Ikuti langkah berikut agar project Laravel ini berjalan lancar di lokal:

## 1. Copy file environment
Salin file `.env.example` menjadi `.env`:
```sh
cp .env.example .env
```

## 2. Edit konfigurasi database
Pastikan di file `.env`:
```
DB_DATABASE=adpl2
DB_USERNAME=root
DB_PASSWORD=
```
Buat database `adpl2` di MySQL jika belum ada.

## 3. Install dependency composer
```sh
composer install
```
Tunggu hingga proses selesai tanpa error.

## 4. Generate application key
```sh
php artisan key:generate
```

## 5. Jalankan migrasi database
```sh
php artisan migrate
```
Jika ada error terkait foreign key, pastikan kolom yang di-SET NULL sudah nullable.

## 6. Buat storage link
Agar file upload bisa diakses dari web:
```sh
php artisan storage:link
```

## 7. Upload gambar
Pastikan file gambar di-upload ke `storage/app/public` dan field `url_foto` di database hanya berisi nama file (tanpa path `public/`).

## 8. Tampilkan gambar di web
Gunakan kode berikut di Blade:
```blade
<img src="{{ asset('storage/' . $foto->url_foto) }}">
```

## 9. Jalankan server Laravel
```sh
php artisan serve
```

---

### Troubleshooting
- Jika gambar tidak muncul, cek folder `storage/app/public` dan pastikan sudah menjalankan `php artisan storage:link`.
- Jika migrasi gagal, cek error dan pastikan kolom foreign key yang pakai `onDelete('set null')` sudah nullable.
- Jika composer error, pastikan tidak ada proses yang dihentikan di tengah jalan dan ulangi `composer install`.

---

Untuk pertanyaan lebih lanjut, cek README atau hubungi maintainer project.
