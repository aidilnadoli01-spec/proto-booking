# 📊 Database Schema - Proto Booking

Dokumentasi struktur tabel database custom (tidak termasuk tabel bawaan Laravel)

---

## 1. **roles** - Daftar Role Pengguna

| Column | Type | Constraint | Description |
|--------|------|-----------|-------------|
| id | BIGINT | PRIMARY KEY | ID unik |
| name | VARCHAR | UNIQUE, NOT NULL | Nama role (admin, dokter, pasien) |
| label | VARCHAR | NOT NULL | Label tampilan role |
| created_at | TIMESTAMP | NULL | Waktu dibuat |
| updated_at | TIMESTAMP | NULL | Waktu diubah |

---

## 2. **dokter** - Daftar Dokter

| Column | Type | Constraint | Description |
|--------|------|-----------|-------------|
| id | BIGINT | PRIMARY KEY | ID unik |
| nama | VARCHAR | NOT NULL | Nama dokter |
| spesialisasi | VARCHAR | NOT NULL | Spesialisasi (Umum, Gigi, dll) |
| no_str | VARCHAR | UNIQUE, NOT NULL | Nomor STR dokter |
| telepon | VARCHAR | NULLABLE | Nomor telepon |
| alamat | TEXT | NULLABLE | Alamat kantor/praktik |
| created_at | TIMESTAMP | NULL | Waktu dibuat |
| updated_at | TIMESTAMP | NULL | Waktu diubah |

**Relasi:**
- One-to-Many dengan `jadwal_dokter`

---

## 3. **pasien** - Data Pasien

| Column | Type | Constraint | Description |
|--------|------|-----------|-------------|
| id | BIGINT | PRIMARY KEY | ID unik |
| user_id | BIGINT | FOREIGN KEY (users.id) | Link ke user account |
| nik | VARCHAR | UNIQUE, NOT NULL | Nomor Identitas Kependudukan |
| tanggal_lahir | DATE | NOT NULL | Tanggal lahir |
| jenis_kelamin | ENUM | NOT NULL | 'L' atau 'P' |
| telepon | VARCHAR | NOT NULL | Nomor telepon kontak |
| alamat | TEXT | NOT NULL | Alamat tempat tinggal |
| created_at | TIMESTAMP | NULL | Waktu dibuat |
| updated_at | TIMESTAMP | NULL | Waktu diubah |

**Relasi:**
- Many-to-One dengan `users`
- One-to-Many dengan `pendaftaran`

---

## 4. **jadwal_dokter** - Jadwal Praktik Dokter

| Column | Type | Constraint | Description |
|--------|------|-----------|-------------|
| id | BIGINT | PRIMARY KEY | ID unik |
| dokter_id | BIGINT | FOREIGN KEY (dokter.id) | Link ke dokter |
| hari | VARCHAR | NOT NULL | Hari praktik (Senin, Selasa, dll) |
| jam_mulai | TIME | NOT NULL | Jam mulai praktik |
| jam_selesai | TIME | NOT NULL | Jam selesai praktik |
| kuota | UNSIGNED INT | DEFAULT 20 | Kuota pasien per hari |
| aktif | BOOLEAN | DEFAULT true | Status jadwal aktif |
| created_at | TIMESTAMP | NULL | Waktu dibuat |
| updated_at | TIMESTAMP | NULL | Waktu diubah |

**Relasi:**
- Many-to-One dengan `dokter`
- One-to-Many dengan `antrean`

---

## 5. **antrean** - Antrian Periksa

| Column | Type | Constraint | Description |
|--------|------|-----------|-------------|
| id | BIGINT | PRIMARY KEY | ID unik |
| jadwal_dokter_id | BIGINT | FOREIGN KEY | Link ke jadwal dokter |
| tanggal_periksa | DATE | NOT NULL | Tanggal pemeriksaan |
| nomor_antrean | UNSIGNED INT | NOT NULL | Nomor antrian |
| status | ENUM | DEFAULT 'menunggu' | Status: menunggu, dipanggil, selesai, batal |
| dipanggil_pada | TIMESTAMP | NULLABLE | Waktu pasien dipanggil |
| selesai_pada | TIMESTAMP | NULLABLE | Waktu pemeriksaan selesai |
| dibatalkan_pada | TIMESTAMP | NULLABLE | Waktu antrian dibatalkan |
| updated_by_user_id | BIGINT | FOREIGN KEY (users.id) | User yang update status |
| created_at | TIMESTAMP | NULL | Waktu dibuat |
| updated_at | TIMESTAMP | NULL | Waktu diubah |

**Unique Constraint:**
- `(jadwal_dokter_id, tanggal_periksa, nomor_antrean)` - tidak boleh ada nomor antrian ganda

**Relasi:**
- Many-to-One dengan `jadwal_dokter`
- One-to-Many dengan `pendaftaran`
- Many-to-One dengan `users` (updated_by)

---

## 6. **pendaftaran** - Pendaftaran Kunjungan

| Column | Type | Constraint | Description |
|--------|------|-----------|-------------|
| id | BIGINT | PRIMARY KEY | ID unik |
| pasien_id | BIGINT | FOREIGN KEY (pasien.id) | Link ke pasien |
| antrean_id | BIGINT | FOREIGN KEY (antrean.id) | Link ke antrian |
| keluhan | TEXT | NULLABLE | Keluhan pasien |
| status_kunjungan | ENUM | DEFAULT 'terdaftar' | Status: terdaftar, selesai, dibatalkan |
| created_at | TIMESTAMP | NULL | Waktu pendaftaran |
| updated_at | TIMESTAMP | NULL | Waktu diupdate |

**Relasi:**
- Many-to-One dengan `pasien`
- Many-to-One dengan `antrean`

---

## 7. **audit_logs** - Log Aktivitas & Audit Trail

| Column | Type | Constraint | Description |
|--------|------|-----------|-------------|
| id | BIGINT | PRIMARY KEY | ID unik |
| user_id | BIGINT | FOREIGN KEY (users.id) | User yang melakukan aksi |
| action | VARCHAR(80) | NOT NULL | Jenis aksi (create, update, delete) |
| auditable_type | VARCHAR(120) | NOT NULL | Tipe model yang di-audit |
| auditable_id | BIGINT | NOT NULL | ID record yang di-audit |
| old_values | JSON | NULLABLE | Nilai sebelum perubahan |
| new_values | JSON | NULLABLE | Nilai setelah perubahan |
| ip | VARCHAR(45) | NULLABLE | IP address user |
| user_agent | VARCHAR(512) | NULLABLE | Browser/device info |
| created_at | TIMESTAMP | NULL | Waktu aktivitas |
| updated_at | TIMESTAMP | NULL | Waktu update log |

**Index:**
- `(auditable_type, auditable_id)`
- `(action, created_at)`

**Relasi:**
- Many-to-One dengan `users`

---

## 8. **notifications** - Notifikasi User

| Column | Type | Constraint | Description |
|--------|------|-----------|-------------|
| id | BIGINT | PRIMARY KEY | ID unik |
| user_id | BIGINT | FOREIGN KEY (users.id) | User penerima notifikasi |
| message | TEXT | NOT NULL | Isi pesan notifikasi |
| is_read | BOOLEAN | DEFAULT 0 | Status: 0=belum baca, 1=sudah |
| created_at | TIMESTAMP | NULL | Waktu dikirim |
| updated_at | TIMESTAMP | NULL | Waktu diupdate |

**Relasi:**
- Many-to-One dengan `users`

---

## 9. **queues** - Antrian Display

| Column | Type | Constraint | Description |
|--------|------|-----------|-------------|
| id | BIGINT | PRIMARY KEY | ID unik |
| nomor_antrian | VARCHAR | NOT NULL | Nomor antrian yang ditampilkan |
| nama_pasien | VARCHAR | NULLABLE | Nama pasien (untuk display) |
| status | ENUM | DEFAULT 'waiting' | Status: waiting, called, done |
| created_at | TIMESTAMP | NULL | Waktu entry dibuat |
| updated_at | TIMESTAMP | NULL | Waktu diupdate |

---

## 10. **users** - User Account (Modified)

**Additional Columns Added:**
- `role_id` (BIGINT, FOREIGN KEY) - Link ke roles
- `is_active` (BOOLEAN, DEFAULT true) - Status akun aktif/nonaktif

---

## 📐 Entity Relationship Diagram (ERD)

```
users (Bawaan Laravel)
├─→ has many → roles
├─→ has many → pasien
├─→ has many → notifications
└─→ has many → audit_logs

roles
└─→ has many → users

dokter
└─→ has many → jadwal_dokter

jadwal_dokter
├─→ belongs to → dokter
└─→ has many → antrean

antrean
├─→ belongs to → jadwal_dokter
├─→ has many → pendaftaran
└─→ belongs to → users (updated_by)

pasien
├─→ belongs to → users
└─→ has many → pendaftaran

pendaftaran
├─→ belongs to → pasien
└─→ belongs to → antrean

notifications
└─→ belongs to → users

audit_logs
└─→ belongs to → users

queues (Standalone table)
```

---

## 🔍 Key Points

1. **Foreign Key Cascade:** Semua FK menggunakan `cascadeOnDelete()` - data terkait akan otomatis dihapus
2. **Soft Delete:** Tidak menggunakan soft delete, menggunakan status column
3. **Timestamps:** Semua tabel memiliki `created_at` dan `updated_at` untuk tracking
4. **Audit Trail:** Semua perubahan dicatat di `audit_logs` tabel
5. **Unique Constraints:** NIK pasien dan STR dokter harus unik
6. **Status Tracking:** Antrean memiliki timestamp untuk setiap status change
