# Dokumentasi Perubahan Coding (Update Terbaru)

Dokumen ini merangkum fitur yang ditambahkan/diubah beserta file yang terlibat.

## 1) Booking & Antrean (Pasien)

### A. Booking antrean + validasi kuota per tanggal
- **Fungsi**: Cegah double booking pada jadwal+tanggal yang sama dan cegah kuota penuh.
- **File**: `app/Http/Controllers/BookingController.php`
- **Catatan**: Nomor antrean dibuat aman dengan transaction + `lockForUpdate`.

### B. Pembatalan booking (policy 2 jam sebelum jadwal mulai)
- **Fungsi**: User bisa batal sebelum batas waktu.
- **File**: `app/Http/Controllers/BookingController.php`
- **Tambahan**: Update status antrean ke `batal` dan set `dibatalkan_pada`.

### C. Reschedule booking
- **Fungsi**: User bisa ganti jadwal/tanggal (policy sama: 2 jam sebelum jam mulai).
- **Route**:
  - `GET /booking-saya/{pendaftaran}/reschedule` (`booking.reschedule.form`)
  - `PATCH /booking-saya/{pendaftaran}/reschedule` (`booking.reschedule`)
- **File**:
  - `app/Http/Controllers/BookingController.php`
  - `resources/views/booking/reschedule.blade.php`
  - `resources/views/booking/index.blade.php` (tombol Reschedule)

## 2) Operasional Admin (Antrean)

### A. Antrean harian admin (filter + KPI ringkas)
- **Fungsi**: Filter tanggal/dokter/status dan ringkasan total per status.
- **File**:
  - `app/Http/Controllers/DashboardController.php` (summary)
  - `resources/views/dashboard/admin-queue.blade.php`

### B. Operasi antrean admin
- **Fitur**:
  - Panggil berikutnya (`menunggu` nomor terkecil)
  - Set status `dipanggil` / `selesai` / `batal`
- **File**:
  - `app/Http/Controllers/AdminAntreanController.php`
  - `routes/web.php`
  - `resources/views/dashboard/admin-queue.blade.php`

## 3) Display Antrean (TV / Monitor)

### A. Halaman display
- **Route**: `GET /display/antrean` (`display.antrean`)
- **Fitur**: Menampilkan “Sedang dipanggil” + “Berikutnya (Top 10)”
- **File**:
  - `app/Http/Controllers/DisplayAntreanController.php`
  - `resources/views/display/antrean.blade.php`

### B. Mode TV realtime + animasi + opsi suara
- **Query**: `mode=tv`
- **Behavior**:
  - Tanpa form filter (lebih clean)
  - Polling data tiap 5 detik (tanpa refresh halaman)
  - Animasi pulse pada nomor yang dipanggil
  - Tombol “Aktifkan Suara” untuk beep saat nomor berubah
- **Route data**: `GET /display/antrean/data` (`display.antrean.data`)

## 4) Notifikasi Email

- **Booking berhasil**: `BookingCreatedNotification`
- **Booking dibatalkan**: `BookingCancelledNotification`
- **Antrean dipanggil**: `QueueCalledNotification`

File:
- `app/Notifications/BookingCreatedNotification.php`
- `app/Notifications/BookingCancelledNotification.php`
- `app/Notifications/QueueCalledNotification.php`

## 5) Audit Log

### A. Tabel audit log
- **Migration**: `database/migrations/2026_04_27_000002_create_audit_logs_table.php`
- **Model**: `app/Models/AuditLog.php`
- **Helper**: `app/Support/Audit.php`

### B. Pencatatan audit yang sudah aktif
- Booking created/cancel/reschedule
- Operasi antrean admin (call/status update)
- CRUD dokter & jadwal (create/update/delete)

### C. Audit Log Viewer (UI)
- **Route**: `GET /admin/audit-log` (`audit.index`)
- **File**:
  - `app/Http/Controllers/AuditLogController.php`
  - `resources/views/audit/index.blade.php`
  - `resources/views/layouts/navigation.blade.php` (menu)

## 6) Keamanan

- **Rate limit** pada endpoint booking & aksi admin:
  - Diatur pada `routes/web.php` menggunakan `throttle:*`.

## 7) Database tracking antrean

- Kolom tambahan pada `antrean`:
  - `dipanggil_pada`, `selesai_pada`, `dibatalkan_pada`, `updated_by_user_id`
- Migration: `database/migrations/2026_04_27_000001_add_tracking_columns_to_antrean_table.php`

## 8) Verifikasi

- Jalankan test:
  - `php artisan test`

## 9) Dokumentasi Login Multi-Role (1 Halaman untuk 3 Role)

### A. Kenapa cukup 1 halaman login?
- Sistem memakai **1 tabel user** (`users`) sebagai sumber autentikasi untuk semua akun.
- Perbedaan hak akses tidak ditentukan dari form login, tetapi dari **role** user yang tersimpan di database (`role_id` -> relasi ke tabel role).
- Karena kredensial yang dicek tetap sama (`email` + `password`), maka halaman login yang dibutuhkan cukup satu.

### B. Role yang didukung dari halaman login yang sama
- `user` (pasien)
- `admin`
- `super_admin`

### C. Alur login sampai diarahkan ke halaman yang benar
1. User membuka `GET /login` -> menampilkan `resources/views/auth/login.blade.php`.
2. Form submit ke `POST /login` -> `AuthenticatedSessionController@store`.
3. Validasi + autentikasi dijalankan di `LoginRequest::authenticate()` menggunakan `Auth::attempt(email, password)`.
4. Jika sukses, session di-regenerate lalu redirect ke route `dashboard`.
5. Di `DashboardController@index`, sistem membaca `auth()->user()->role->name` lalu:
   - `super_admin` -> view `dashboard.super-admin`
   - `admin` -> view `dashboard.admin`
   - selain itu (default pasien) -> view `dashboard.user`

### D. Kenapa role tetap aman walaupun login page cuma satu?
- Seluruh route penting dilindungi middleware `auth` + `role`.
- Middleware `RoleMiddleware` mengecek role user yang sedang login pada setiap request.
- Jika role tidak sesuai, akses ditolak (`403`), jadi akun tidak bisa masuk ke area role lain.

### E. Lokasi file penting (biar mudah tracing)
- Route login/logout: `routes/auth.php`
- Controller login: `app/Http/Controllers/Auth/AuthenticatedSessionController.php`
- Request validasi login + rate limit: `app/Http/Requests/Auth/LoginRequest.php`
- Middleware role: `app/Http/Middleware/RoleMiddleware.php`
- Mapping dashboard per role: `app/Http/Controllers/DashboardController.php`
- Proteksi route berdasarkan role: `routes/web.php`

