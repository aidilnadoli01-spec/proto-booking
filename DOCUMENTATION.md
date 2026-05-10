# Dokumentasi Project: Sistem Booking & Antrean Klinik Sehat

Dokumentasi ini dibuat untuk mempermudah developer baru dalam memahami alur kerja, struktur kode, dan fitur-fitur yang ada di dalam aplikasi **Klinik Sehat**.

---

## 1. Overview Project

* **Nama Project:** Klinik Sehat (Sistem Booking & Antrean Berbasis Web)
* **Tujuan Aplikasi:** Mendigitalisasi proses pendaftaran pasien, pengaturan jadwal dokter, dan pemantauan antrean secara *real-time* baik untuk pasien dari rumah maupun layar display (TV) di ruang tunggu klinik.
* **Fitur Utama:**
  * Pendaftaran/Booking Online oleh Pasien.
  * Manajemen Jadwal & Dokter oleh Admin.
  * Live Display Antrean (Mode Web & Mode TV dengan Suara).
  * Sistem Notifikasi Real-time (Lonceng Notifikasi).
  * Manajemen User & Role (Super Admin, Admin, Pasien).
* **Teknologi yang Digunakan:**
  * **Backend:** Laravel (PHP)
  * **Frontend:** Blade Templating, Tailwind CSS, Alpine.js
  * **Database:** MySQL / SQLite (Eloquent ORM)

---

## 2. Struktur Folder & File Utama

Laravel menggunakan pola arsitektur MVC (Model-View-Controller). Berikut adalah lokasi file-file penting dalam project ini:

* **`app/`**
  Folder utama backend. Logika bisnis aplikasi berada di sini.
  * `Http/Controllers/`: Menyimpan semua logika pengatur *request* dari user sebelum dikembalikan ke *view* atau *response*.
  * `Models/`: Struktur data dan relasi antar tabel database (ORM Eloquent).
* **`routes/`**
  Tempat mendaftarkan URL aplikasi.
  * `web.php`: Berisi semua route web reguler dan API internal aplikasi yang memerlukan *session state* (middleware `auth`).
* **`resources/views/`**
  Tempat menyimpan file tampilan frontend (UI) yang bereksistensi `.blade.php`. Terbagi dalam sub-folder seperti `auth/`, `layouts/`, `display/`, dan `super-admin/`.
* **`public/`**
  Folder yang dapat diakses langsung oleh browser. File gambar, favicon, atau file hasil build dari Vite (`build/assets/`) disimpan di sini.
* **`database/`**
  Berisi struktur tabel database.
  * `migrations/`: Script untuk membuat dan mengubah tabel database (seperti tabel `users`, `notifications`, dsb).
  * `seeders/`: Script untuk memasukkan data awal dummy ke dalam database.

---

## 3. Routing (`routes/web.php`)

Routing digunakan untuk memetakan URL yang diketik di browser ke fungsi Controller tertentu.

* **Landing Page & Display:**
  * `GET /` &rarr; Menampilkan halaman utama (Landing Page).
  * `GET /display/antrean` &rarr; `DisplayAntreanController@index` (Halaman Layar TV Ruang Tunggu).
  * `GET /api/display/antrean/data` &rarr; `DisplayAntreanController@data` (Endpoint untuk fetch data antrean Live).
* **Autentikasi (Bawaan Laravel Breeze):**
  * `GET /login`, `POST /login` &rarr; Proses masuk akun.
  * `GET /register`, `POST /register` &rarr; Pendaftaran akun pasien.
  * `POST /logout` &rarr; Proses keluar akun.
* **API Notifikasi (Di bawah middleware `auth`):**
  * `GET /api/notifications` &rarr; `NotificationController@index` (Ambil daftar notifikasi terbaru).
  * `GET /api/notifications/unread-count` &rarr; `NotificationController@unreadCount` (Ambil angka badge merah).
  * `POST /api/notifications/mark-all-read` &rarr; `NotificationController@markAllRead` (Tandai sudah dibaca).
* **Super Admin Area (Middleware `role:super_admin`):**
  * `GET /super-admin/users` &rarr; `UserManagementController@index` (Halaman list user).
  * `POST /super-admin/users` &rarr; `UserManagementController@store` (Tambah user baru).
  * `PATCH /super-admin/users/{user}/toggle-status` &rarr; `UserManagementController@toggleStatus` (Ubah status Aktif/Tidak Aktif).

---

## 4. Controller

Berikut adalah beberapa *Controller* utama penyokong fitur aplikasi:

1. **`UserManagementController`**
   * **Fungsi:** Mengatur seluruh data pengguna (CRUD).
   * **Alur Logic:** Saat masuk ke halaman Kelola User, method `index` akan mengambil data `User::with('role')`, menerima input *search* (jika ada), mem-paginate hasilnya, dan mengirimkannya ke view `super-admin.users`.
2. **`NotificationController`**
   * **Fungsi:** Mengatur respon data notifikasi untuk *dropdown* lonceng.
   * **Alur Logic:** Hanya mengembalikan *response* dalam bentuk JSON. Data dicari secara spesifik berdasarkan user yang login saat ini (`Auth::id()`).
3. **`DisplayAntreanController`**
   * **Fungsi:** Mengatur halaman display TV di ruang tunggu.
   * **Alur Logic:** Menentukan pasien mana yang "Sedang Dipanggil" dan siapa saja yang masuk list "Selanjutnya (Menunggu)".

---

## 5. Model & Database

Selain tabel bawaan Laravel (seperti `users`, `sessions`, `jobs`, `cache`, dll), sistem ini memiliki **9 Tabel Custom** utama yang membentuk keseluruhan arsitektur Klinik Sehat. Berikut adalah daftarnya:

1. **`roles` (Model: Role)**
   * **Fungsi:** Menentukan hak akses pengguna dalam aplikasi (contoh: `super_admin`, `admin`, `user`).
   * **Relasi:** Memiliki banyak `User`.
2. **`dokters` (Model: Dokter)**
   * **Fungsi:** Menyimpan data inti dokter seperti nama, spesialisasi/poli, dan kontak.
3. **`pasiens` (Model: Pasien)**
   * **Fungsi:** Menyimpan rekam medis dan data profil riwayat pasien.
   * **Relasi:** Terhubung langsung (1-to-1) dengan tabel `users`.
4. **`jadwal_dokters` (Model: JadwalDokter)**
   * **Fungsi:** Mengatur jam praktek harian dokter.
   * **Relasi:** Dimiliki oleh `Dokter`.
5. **`pendaftarans` (Model: Pendaftaran / Booking)**
   * **Fungsi:** Menyimpan riwayat reservasi atau *booking* yang dilakukan oleh pasien.
6. **`antreans` (Model: Antrean)**
   * **Fungsi:** Sistem antrean yang sinkron dengan jadwal dokter harian.
7. **`audit_logs` (Model: AuditLog)**
   * **Fungsi:** Sistem rekam jejak (riwayat aktivitas) untuk keperluan laporan (*reporting*) harian/bulanan.
8. **`notifications` (Model: Notification)**
   * **Fungsi:** Sistem lonceng pemberitahuan dinamis (menyimpan pesan dan status *is_read*).
   * **Relasi:** Dimiliki oleh `User`.
9. **`queues` (Model: Queue)**
   * **Fungsi:** Tabel antrean *standalone* yang difokuskan untuk layar Display TV (memiliki field `nomor_antrian` dan `status`).

**Tabel `users` (Bawaan yang Dimodifikasi):**
Tabel default `users` telah ditambahkan beberapa kolom penting, yaitu `role_id` (sebagai Foreign Key ke tabel `roles`) dan `is_active` (boolean untuk mematikan/menghidupkan akun).

---

## 6. View (Blade)

File-file view UI dibangun dengan framework Tailwind CSS untuk penataan yang responsif dan rapi.

* **`resources/views/landing.blade.php`**
  Halaman depan untuk promosi klinik (berisi Hero, Statistik, Fitur Unggulan, Testimoni, dan Blog).
* **`resources/views/layouts/`**
  Menyimpan kerangka utama UI.
  * `app.blade.php`: *Wrapper* utama yang melingkupi Navbar dan Sidebar (untuk area Dashboard).
  * `topbar.blade.php`: Navbar atas yang memuat tombol Profil dan Ikon Lonceng Notifikasi.
  * `sidebar.blade.php`: Menu navigasi sebelah kiri yang link-nya menyesuaikan dengan `Role` dari user yang login.
* **`resources/views/display/antrean.blade.php`**
  Tampilan UI besar berlatar gelap yang ditujukan khusus untuk ditembakkan melalui proyektor / layar TV di rumah sakit.
* **`resources/views/super-admin/users.blade.php`**
  Halaman CRUD untuk mengatur seluruh user yang memakai modal *popup* modern.

---

## 7. Fitur Utama

### A. Fitur Notifikasi (Lonceng)
1. User login masuk ke Dashboard.
2. Di navbar (`topbar.blade.php`), ada *script* latar belakang (Alpine.js) yang memanggil API setiap 5 detik.
3. Jika terdapat notifikasi baru, ikon lonceng akan muncul *badge* merah berkedip (`animate-bounce`) dan muncul *Toast* pesan kecil di pojok layar.
4. Saat user mengklik lonceng, *dropdown* terbuka memunculkan pesan, dan otomatis sistem mengubah status `is_read` menjadi true (dibaca) di database.

### B. Fitur CRUD User (Kelola Pengguna)
1. Super Admin menekan menu "Kelola User".
2. Menekan tombol "Tambah User" memunculkan jendela modal di tengah layar.
3. Super Admin mengisi Nama, Email, Password, Role, dan menggeser Switch Status.
4. Data dikirim (POST), divalidasi oleh `UserManagementController`, dan tabel `users` diperbarui secara aman.
5. Jika ingin menonaktifkan pengguna, Super admin cukup menekan badge status berwarna Hijau di dalam tabel (Berubah secara instan).

### C. Live Display Antrean
1. Halaman display dibuka di TV. Admin memilih "Aktifkan Suara".
2. Script JavaScript terus menembak API (polling) setiap beberapa detik.
3. Jika admin di komputernya memencet "Panggil Nomor Selanjutnya", API mengirim respons nomor antrean baru ke TV.
4. TV akan merespon dengan bunyi *Beep* / *Ting!* dan mengubah angka besar di layar tanpa melakukan *refresh* halaman secara kasar.

---

## 8. API Endpoint (JSON)

Aplikasi memanfaatkan endpoint internal yang mengembalikan tipe data JSON untuk keperluan *dynamic frontend*:

* **Ambil Daftar Notifikasi**
  * `GET /api/notifications`
  * Response: `[ { "id": 1, "message": "Booking berhasil", "is_read": 0, "created_at": "..." } ]`
* **Ambil Angka Badge**
  * `GET /api/notifications/unread-count`
  * Response: `{ "count": 2 }`
* **Tandai Dibaca**
  * `POST /api/notifications/mark-all-read` (Butuh Header `X-CSRF-TOKEN`)
  * Response: `{ "status": "success" }`

---

## 9. JavaScript Logic (Frontend Interactivity)

Projek ini tidak menggunakan React/Vue yang rumit, melainkan memanfaatkan kombinasi **Alpine.js** dan **Vanilla JavaScript (Fetch API)** untuk kesederhanaan.

* **Alpine.js (`x-data`, `x-show`)**
  Digunakan dalam `users.blade.php` dan `topbar.blade.php` untuk mengurus buka-tutup dropdown dan modal *popup* secara responsif tanpa perlu jQuery.
* **Fetch API & Polling**
  Digunakan pada sistem Notifikasi dan Layar TV. Kode menggunakan perintah `setInterval(..., 5000)` untuk menjalankan perintah `fetch()` ke URL internal secara berkala di latar belakang, kemudian mengupdate elemen HTML (DOM) berdasarkan *response* data.
* **Custom Event EventListener**
  Pada fitur notifikasi, digunakan metode pemanggilan *Toast Popup* berskala global menggunakan `window.dispatchEvent(new CustomEvent('show-toast', { detail: 'Pesan' }))`.

---

## 10. Instalasi & Setup (Local Development)

Jika ada developer baru yang ingin menjalankan project ini di komputernya (Laptop), berikut adalah tahapannya:

1. **Clone repository & Install dependencies PHP:**
   ```bash
   composer install
   ```
2. **Install dependencies Frontend (Node.js):**
   ```bash
   npm install
   ```
3. **Setup Environment:**
   Duplikat file `.env.example` lalu ubah namanya menjadi `.env`. Sesuaikan koneksi database Anda (biasanya `DB_CONNECTION=mysql` atau `sqlite`).
4. **Generate App Key & Database:**
   ```bash
   php artisan key:generate
   php artisan migrate --seed
   ```
   *(Perintah di atas akan merestrukturisasi database kosong dan mengisi data default seperti akun Super Admin).*
5. **Jalankan Aplikasi:**
   Buka dua jendela terminal.
   * Terminal 1 (Menjalankan Web Server): `php artisan serve`
   * Terminal 2 (Mengkompilasi CSS/JS Tailwind): `npm run dev`
6. Akses aplikasi melalui browser di `http://127.0.0.1:8000`.

---

## 11. Kesimpulan

**Klinik Sehat** adalah aplikasi yang dibangun dengan paradigma Monolith Modern menggunakan ekosistem Laravel (Blade + Alpine + Tailwind). Fokus utama aplikasi ini ditekankan pada kecepatan performa untuk pengguna, antarmuka (*UI/UX*) yang bersih dan animatif, serta kemudahan proses bisnis pendaftaran klinik.

**Saran Pengembangan Lanjutan:**
* Untuk performa notifikasi dan *live display* yang lebih *scalable* (jika klinik memiliki ribuan pasien/hari), teknik HTTP Polling (Fetch setiap 5 detik) dapat digantikan perlahan menggunakan teknologi WebSocket seperti **Laravel Reverb** atau **Pusher** agar meminimalisir beban *request* server.
* Fitur keamanan dapat diperluas dengan menambahkan batasan *rate-limiting* pada endpoint API *public*.
