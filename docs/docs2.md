# Dokumentasi Struktur Folder Project (docs2)

Dokumen ini menjelaskan fungsi semua folder utama pada project `proto booking`, termasuk subfolder yang paling penting untuk pengembangan.

## 1) Folder Utama di Root Project

### `app/`
Berisi source code utama backend Laravel (controller, model, middleware, service/helper, dll).

Subfolder penting:
- `app/Console/` -> command Artisan custom (jika ada).
- `app/Http/` -> layer request HTTP.
  - `app/Http/Controllers/` -> logika endpoint web (booking, dashboard, antrean, user management, dll).
    - `app/Http/Controllers/Auth/` -> controller autentikasi (login, register, reset password, logout).
  - `app/Http/Middleware/` -> middleware seperti cek role (`RoleMiddleware`).
  - `app/Http/Requests/` -> validasi request berbasis class (mis. login).
- `app/Models/` -> model Eloquent untuk tabel database (User, Role, Dokter, Antrean, Pendaftaran, dll).
- `app/Notifications/` -> notifikasi aplikasi (email booking berhasil/batal, antrean dipanggil).
- `app/Providers/` -> service provider Laravel.
- `app/Support/` -> helper/utilitas internal (mis. helper audit log).
- `app/View/` -> komponen/logic tambahan terkait view (jika digunakan).

### `bootstrap/`
Inisialisasi awal Laravel.

Subfolder penting:
- `bootstrap/cache/` -> cache hasil kompilasi config/services agar aplikasi lebih cepat (generated file).

### `config/`
Semua konfigurasi aplikasi Laravel.

Contoh file:
- `config/app.php` -> konfigurasi inti app.
- `config/auth.php` -> guard/provider autentikasi.
- `config/database.php` -> koneksi database.
- `config/mail.php` -> konfigurasi email.
- `config/session.php` -> konfigurasi session.

### `database/`
Semua aset terkait skema dan data awal database.

Subfolder penting:
- `database/migrations/` -> definisi perubahan struktur tabel (schema versioning).
- `database/seeders/` -> data awal/seed.
- `database/factories/` -> factory data untuk testing/seed.
- `database/database.sqlite` -> database SQLite lokal (dipakai di environment ini).

### `docs/`
Dokumentasi internal project.

File saat ini:
- `docs/PERUBAHAN-CODING.md` -> ringkasan perubahan fitur.
- `docs/LANGKAH-LANJUTAN.md` -> daftar langkah lanjutan pengembangan.
- `docs/PRESENTASI.md` -> materi presentasi.
- `docs/docs2.md` -> dokumen struktur folder ini.

### `node_modules/`
Dependency frontend dari NPM. Folder ini auto-generated, bukan tempat coding utama.

### `public/`
Document root untuk web server (entry point dan aset public).

Isi penting:
- `public/index.php` -> front controller Laravel.
- `public/build/` -> hasil build aset frontend (Vite).

### `resources/`
Source aset frontend dan template tampilan.

Subfolder penting:
- `resources/css/` -> stylesheet.
- `resources/js/` -> JavaScript frontend.
- `resources/views/` -> file Blade template (UI).
  - `resources/views/auth/` -> halaman auth (login/register, dll).
  - `resources/views/dashboard/` -> dashboard per role.
  - `resources/views/booking/` -> UI booking antrean pasien.
  - `resources/views/display/` -> UI display antrean monitor/TV.
  - `resources/views/dokter/` -> UI manajemen dokter.
  - `resources/views/jadwal/` -> UI manajemen jadwal dokter.
  - `resources/views/super-admin/` -> UI khusus super admin.
  - `resources/views/audit/` -> UI audit log.
  - `resources/views/layouts/` -> layout utama.
  - `resources/views/components/` -> komponen Blade reusable.
  - `resources/views/pasien/` -> halaman profil pasien.
  - `resources/views/profile/` -> halaman profil user.

### `routes/`
Definisi semua route aplikasi.

File penting:
- `routes/web.php` -> route web utama (booking, dashboard, admin, super admin).
- `routes/auth.php` -> route autentikasi (login, register, logout, reset password).
- `routes/console.php` -> route/closure Artisan console.

### `storage/`
Penyimpanan runtime Laravel.

Subfolder penting:
- `storage/app/` -> file yang disimpan aplikasi.
- `storage/framework/` -> cache/session/view compiled.
- `storage/logs/` -> log aplikasi (`laravel.log`).

### `tests/`
Kode pengujian otomatis.

Subfolder:
- `tests/Feature/` -> test alur fitur/end-to-end level HTTP.
- `tests/Unit/` -> test unit untuk komponen kecil.

### `vendor/`
Dependency backend PHP dari Composer (framework + package). Auto-generated, bukan tempat coding utama.

## 2) Ringkas Alur Besar Berdasarkan Folder

- **Request masuk** dari `public/index.php`.
- **Route diproses** di `routes/`.
- **Controller dijalankan** dari `app/Http/Controllers/`.
- **Akses data** lewat `app/Models/` ke tabel dari `database/migrations/`.
- **Render tampilan** dari `resources/views/`.
- **Log dan cache runtime** disimpan di `storage/`.

## 3) Folder yang Umumnya Diedit Saat Menambah Fitur

- Backend logic: `app/Http/Controllers/`, `app/Models/`, `app/Http/Middleware/`
- Route: `routes/web.php`, `routes/auth.php`
- UI: `resources/views/`
- Schema/data awal: `database/migrations/`, `database/seeders/`
- Dokumentasi: `docs/`
