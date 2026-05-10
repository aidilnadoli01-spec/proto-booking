# Dokumentasi Presentasi Proyek

## 1. Ringkasan Proyek

**Judul:** Sistem Manajemen Antrean dan Penjadwalan Dokter Online  
**Tujuan:** Memudahkan pasien melihat jadwal dokter dan melakukan booking antrean secara digital.  
**Manfaat utama:** Mengurangi antre manual, meningkatkan keteraturan jadwal, dan mempercepat layanan.

## 2. Ruang Lingkup Fitur

### Role dan Hak Akses

- **Super Admin**
  - Kelola seluruh user
  - Kelola role
  - Akses seluruh data sistem
- **Admin**
  - Kelola data dokter
  - Kelola jadwal dokter
  - Edit/hapus data dokter dan jadwal
  - Pantau antrean pasien
- **User (Pasien)**
  - Registrasi dan login
  - Lihat jadwal dokter
  - Booking antrean
  - Lihat nomor antrean

### Halaman Utama Sistem

- Landing page
- Login/register
- Dashboard Super Admin
- Dashboard Admin
- Dashboard User
- Jadwal dokter
- Booking antrean
- Kelola user (super admin)
- Kelola role (super admin)
- Kelola dokter dan jadwal (admin/super admin)

## 3. Arsitektur dan Teknologi

- **Framework:** Laravel 12
- **Database:** MySQL
- **Frontend:** Blade + Tailwind CSS + Vite
- **Auth:** Laravel Breeze
- **Pola:** MVC
- **ORM:** Eloquent

## 4. Struktur Database dan Relasi

### Tabel Inti

1. `roles`
2. `users`
3. `dokter`
4. `pasien`
5. `jadwal_dokter`
6. `antrean`
7. `pendaftaran`

### Relasi

- `users.role_id -> roles.id` (many-to-one)
- `pasien.user_id -> users.id` (one-to-one)
- `jadwal_dokter.dokter_id -> dokter.id` (many-to-one)
- `antrean.jadwal_dokter_id -> jadwal_dokter.id` (many-to-one)
- `pendaftaran.pasien_id -> pasien.id` (many-to-one)
- `pendaftaran.antrean_id -> antrean.id` (many-to-one)

## 5. Alur Bisnis Utama

1. Pasien login.
2. Pasien memilih jadwal dokter.
3. Pasien mengisi tanggal periksa dan keluhan.
4. Sistem membuat nomor antrean otomatis berdasarkan jadwal + tanggal.
5. Sistem menyimpan pendaftaran dan menampilkan notifikasi sukses.

## 6. Penjelasan Implementasi Kunci

### A. Role-Based Access Control (RBAC)

- Middleware `role` dipasang sebagai alias pada bootstrap.
- Route dikelompokkan berdasarkan role.
- User hanya dapat mengakses menu sesuai role.

### B. Nomor Antrean Otomatis

- Saat booking, sistem mencari nomor antrean terakhir untuk kombinasi:
  - `jadwal_dokter_id`
  - `tanggal_periksa`
- Proses dilakukan dalam database transaction (`lockForUpdate`) agar aman dari race condition.
- Nomor baru = nomor terakhir + 1.

### C. Validasi dan Notifikasi

- Form menggunakan validasi request Laravel.
- Saat booking berhasil, sistem mengirim flash message sukses.

### F. Registrasi Pasien Baru

- Tombol `Daftar Pasien Baru` tersedia di landing page dan login page.
- Saat registrasi, sistem membuat:
  - data `users` (role `user`)
  - data `pasien` secara otomatis
- Sistem punya fallback `firstOrCreate` untuk memastikan user tetap bisa booking meskipun profil pasien belum ada.

### D. CRUD Dokter & Jadwal

- Route update/delete ditambahkan untuk resource dokter dan jadwal.
- Admin dapat mengubah data langsung dari halaman kelola.
- Admin dapat menghapus data dokter/jadwal menggunakan tombol hapus dengan konfirmasi.

### E. Dashboard Premium dan Chart

- Dashboard admin menggunakan chart doughnut untuk distribusi status antrean.
- Dashboard super admin menggunakan chart line untuk tren booking 7 hari terakhir.
- Card statistik menggunakan tampilan gradien agar visual lebih modern.

## 7. Demo Skenario Presentasi (disarankan)

1. Tampilkan landing page.
2. Login sebagai super admin, tampilkan dashboard statistik.
3. Login sebagai admin, tambah dokter dan jadwal.
4. Login sebagai pasien, lihat jadwal dokter.
5. Lakukan booking antrean.
6. Tunjukkan nomor antrean di dashboard pasien.

## 8. Potensi Pertanyaan Penguji + Jawaban

### Q1. Kenapa pakai Laravel?

**Jawab:** Karena Laravel menyediakan ekosistem lengkap untuk autentikasi, routing, ORM, migration, dan security sehingga pengembangan lebih cepat dan terstruktur.

### Q2. Kenapa perlu tabel `roles` terpisah?

**Jawab:** Agar sistem role fleksibel, mudah ditambah, dan tidak hardcode di tabel user. Ini juga memudahkan manajemen hak akses di masa depan.

### Q3. Bagaimana mencegah dua user mendapat nomor antrean sama?

**Jawab:** Proses generate nomor antrean dilakukan di transaction dengan `lockForUpdate` pada query antrean per jadwal/tanggal sehingga request paralel tetap konsisten.

### Q4. Kenapa ada tabel `antrean` dan `pendaftaran` terpisah?

**Jawab:** `antrean` fokus pada nomor antrean dan status antre, sedangkan `pendaftaran` menyimpan hubungan pasien dengan antrean serta konteks kunjungan (keluhan/status kunjungan). Ini membuat data lebih normal dan mudah dikembangkan.

### Q5. Apa pembeda super admin dan admin?

**Jawab:** Super admin mengelola user dan role sistem, sedangkan admin fokus operasional medis (dokter, jadwal, monitoring antrean).

### Q6. Bagaimana validasi input dilakukan?

**Jawab:** Setiap form penting menggunakan validasi server-side Laravel (`$request->validate`) untuk memastikan format dan relasi data valid.

### Q7. Bagaimana memastikan aplikasi tidak error?

**Jawab:** Dilakukan pengujian bawaan Laravel (`php artisan test`), pengecekan route (`php artisan route:list`), build frontend (`npm run build`), dan verifikasi migrasi-seeder.

### Q8. Apa kekurangan versi sekarang?

**Jawab:** Edit/hapus dokter dan jadwal sudah tersedia. Kekurangan saat ini ada pada audit log detail, notifikasi real-time, dan validasi kuota booking yang lebih ketat.

### Q9. Bagaimana alur pasien baru mendaftar?

**Jawab:** Pasien baru klik `Daftar Pasien Baru`, isi form registrasi, lalu sistem otomatis membuat akun user beserta profil pasien. Setelah itu pasien bisa langsung login dan booking antrean.

## 9. Bukti Kesiapan Teknis

- Migrasi dan seeder berjalan.
- Asset frontend berhasil dibuild.
- Unit/feature test berjalan sukses.
- Struktur MVC dan relasi Eloquent sudah sesuai requirement.

## 10. Penutup Presentasi

Sistem ini sudah memenuhi kebutuhan inti manajemen jadwal dokter dan antrean online berbasis role. Dengan roadmap lanjutan, sistem dapat ditingkatkan menuju level produksi yang lebih siap untuk operasional klinik/rumah sakit nyata.
