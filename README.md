<div align="center">
  <img src="https://ui-avatars.com/api/?name=Klinik+Sehat&background=E0F2FE&color=0369A1&size=100&rounded=true" alt="Logo Klinik Sehat" />
  <h1>🏥 Sistem Manajemen Antrean & Penjadwalan Klinik Sehat</h1>
  
  <p>
    Aplikasi web modern berbasis Laravel 12 + Tailwind CSS untuk mengelola jadwal dokter, sistem antrean langsung (real-time display), dan reservasi pasien secara online.
  </p>

  <p>
    <img src="https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel" />
    <img src="https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP" />
    <img src="https://img.shields.io/badge/MySQL-00000F?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL" />
    <img src="https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS" />
  </p>
</div>

---

## ✨ Fitur Utama

Aplikasi ini telah diperbarui dengan **UI/UX Premium** yang adaptif dan interaktif:

- 🔐 **Multi-Role Authentication (Breeze)**: Mendukung 3 hak akses: `Super Admin`, `Admin`, dan `Pasien`.
- 🎨 **Dashboard Premium**: Desain antarmuka baru dengan layout Sidebar & Topbar responsif, kartu statistik bergradien, dan grafik tren data interaktif menggunakan Chart.js.
- 📺 **Display Antrean Layar Penuh**: Halaman khusus untuk layar TV di ruang tunggu klinik dengan fitur *auto-refresh*.
- 📅 **Manajemen Jadwal**: Fitur pencarian, filter, dan pagination untuk jadwal dokter.
- 🎫 **Booking Pintar**: Pengambilan nomor antrean otomatis secara online beserta fitur *reschedule*.
- 👨‍👩‍👧‍👦 **Kelola Akun**: Super Admin dapat mengelola Roles & Users, sedangkan Admin dapat mengelola Data Dokter & Antrean Harian.
- 🚀 **Landing Page Modern**: Tampilan beranda yang profesional dengan animasi AOS.

---

## 💻 Stack Teknologi

* **Backend**: Laravel 12 (PHP 8.2+)
* **Frontend**: Blade Templating, Tailwind CSS v3, Alpine.js
* **Asset Manager**: Vite
* **Database**: MySQL

---

## 🔑 Akun Demo (Seeder)

Anda dapat login menggunakan salah satu akun berikut untuk menguji sistem.  
**Password untuk semua akun:** `password`

| Role | Email |
| :--- | :--- |
| **Super Admin** | `superadmin@hospital.test` |
| **Admin** | `admin@hospital.test` |
| **Pasien** | `pasien@hospital.test` |

---

## 🚀 Setup & Menjalankan Project

Ikuti langkah-langkah di bawah ini untuk menjalankan aplikasi di komputer lokal (Localhost):

1. **Clone repository dan install dependency**
   ```bash
   composer install
   npm install
   ```

2. **Konfigurasi Environment**
   ```bash
   copy .env.example .env
   # atau `cp .env.example .env` di Mac/Linux
   ```
   Jangan lupa sesuaikan konfigurasi koneksi database MySQL di dalam file `.env` (misalnya `DB_DATABASE=proto_booking`).

3. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

4. **Migrasi Database & Seeding Dummy Data**
   ```bash
   php artisan migrate --seed
   ```

5. **Kompilasi Asset (Tailwind & Vite)**
   ```bash
   npm run build
   # atau `npm run dev` untuk hot-reload saat proses development
   ```

6. **Jalankan Server Laravel**
   ```bash
   php artisan serve
   ```
   Akses aplikasi di: `http://localhost:8000`

---

## 🗺️ Peta Halaman (Sitemap)

**Halaman Publik:**
* `/` - Landing Page Utama
* `/display/antrean` - Layar Display Antrean (TV Ruang Tunggu)
* `/login`, `/register` - Halaman Autentikasi

**Halaman Dashboard & User (Pasien):**
* `/dashboard` - Beranda Utama (Berbeda tiap role)
* `/jadwal-dokter` - Jadwal Periksa Dokter
* `/booking-antrean` - Formulir Pendaftaran Nomor Antrean Baru
* `/booking-saya` - Riwayat & Reschedule Antrean
* `/pasien/profil` - Pengaturan Data Pasien

**Halaman Admin:**
* `/admin/dokter` - CRUD Data Dokter & Jadwal
* `/admin/antrean-harian` - Panggilan Nomor Antrean (Layar Loket)
* `/admin/audit-log` - Log Aktivitas Sistem

**Halaman Super Admin:**
* `/super-admin/users` - Manajemen Semua Akun
* `/super-admin/roles` - Manajemen Role (Hak Akses)

---

## 🔄 Alur Sistem Utama

### 1. Alur Booking Antrean
1. Pasien melakukan Login ke dalam sistem.
2. Membuka menu **Buat Booking Antrean**.
3. Memilih Poliklinik, Dokter, dan Tanggal Periksa.
4. Sistem akan men-generate Nomor Antrean secara berurutan dan terstruktur.
5. Pembuatan data Pendaftaran selesai, pasien bisa melihat nomor di "Riwayat Booking".

### 2. Alur Pendaftaran Pasien Baru
1. Pengunjung membuka halaman registrasi melalui Landing Page atau halaman Login.
2. Mengisi formulir akun dasar dan detail data pasien.
3. Sistem secara otomatis memberikan role `user` dan membuat profil rekam medis `pasien`.
4. Jika akun terdaftar tanpa profil pasien lengkap, sistem memiliki fungsi otomatis pendataan dasar (fallback) saat mereka pertama kali mencoba melakukan booking, guna mencegah error.

---

## 🧪 Testing

Untuk menjalankan rangkaian pengujian (*automated tests*):
```bash
php artisan test
```

## 📚 Dokumentasi Ekstra

- Rencana Langkah Lanjutan (Roadmap): `docs/LANGKAH-LANJUTAN.md`
- Q&A dan Draft Presentasi Proyek: `docs/PRESENTASI.md`

<div align="center">
  <p>Dibuat dengan ❤️ untuk sistem pelayanan kesehatan yang lebih baik.</p>
</div>
