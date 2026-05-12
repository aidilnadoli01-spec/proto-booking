# 📊 Database Schema Documentation — Proto Booking System

Dokumentasi struktur database untuk sistem **Proto Booking** berbasis Laravel.
Dokumen ini mencakup tabel custom utama, relasi antar entitas, serta aturan database yang digunakan dalam sistem antrean dan booking klinik/rumah sakit.

---

# 🧩 Struktur Database

## 1. roles — Role & Hak Akses Pengguna

Menyimpan daftar role pengguna dalam sistem seperti Admin, Dokter, dan Pasien.

| Column     | Type      | Constraint       | Description                             |
| ---------- | --------- | ---------------- | --------------------------------------- |
| id         | BIGINT    | PRIMARY KEY      | ID unik role                            |
| name       | VARCHAR   | UNIQUE, NOT NULL | Nama role (`admin`, `dokter`, `pasien`) |
| label      | VARCHAR   | NOT NULL         | Nama tampilan role                      |
| created_at | TIMESTAMP | NULL             | Waktu data dibuat                       |
| updated_at | TIMESTAMP | NULL             | Waktu data diperbarui                   |

### 🔗 Relasi

* One-to-Many → users

---

## 2. dokter — Data Dokter

Menyimpan informasi seluruh dokter yang tersedia dalam sistem booking.

| Column       | Type      | Constraint       | Description           |
| ------------ | --------- | ---------------- | --------------------- |
| id           | BIGINT    | PRIMARY KEY      | ID unik dokter        |
| nama         | VARCHAR   | NOT NULL         | Nama lengkap dokter   |
| spesialisasi | VARCHAR   | NOT NULL         | Bidang spesialisasi   |
| no_str       | VARCHAR   | UNIQUE, NOT NULL | Nomor STR dokter      |
| telepon      | VARCHAR   | NULLABLE         | Nomor telepon         |
| alamat       | TEXT      | NULLABLE         | Alamat praktik        |
| created_at   | TIMESTAMP | NULL             | Waktu data dibuat     |
| updated_at   | TIMESTAMP | NULL             | Waktu data diperbarui |

### 🔗 Relasi

* One-to-Many → jadwal_dokter

---

## 3. pasien — Data Pasien

Menyimpan identitas pasien yang terhubung dengan akun pengguna.

| Column        | Type      | Constraint             | Description              |
| ------------- | --------- | ---------------------- | ------------------------ |
| id            | BIGINT    | PRIMARY KEY            | ID unik pasien           |
| user_id       | BIGINT    | FOREIGN KEY → users.id | Relasi ke akun user      |
| nik           | VARCHAR   | UNIQUE, NOT NULL       | Nomor Induk Kependudukan |
| tanggal_lahir | DATE      | NOT NULL               | Tanggal lahir pasien     |
| jenis_kelamin | ENUM      | NOT NULL               | `L` / `P`                |
| telepon       | VARCHAR   | NOT NULL               | Nomor kontak pasien      |
| alamat        | TEXT      | NOT NULL               | Alamat lengkap pasien    |
| created_at    | TIMESTAMP | NULL                   | Waktu data dibuat        |
| updated_at    | TIMESTAMP | NULL                   | Waktu data diperbarui    |

### 🔗 Relasi

* Many-to-One → users
* One-to-Many → pendaftaran

---

## 4. jadwal_dokter — Jadwal Praktik Dokter

Menyimpan jadwal praktik aktif setiap dokter.

| Column      | Type         | Constraint              | Description                |
| ----------- | ------------ | ----------------------- | -------------------------- |
| id          | BIGINT       | PRIMARY KEY             | ID unik jadwal             |
| dokter_id   | BIGINT       | FOREIGN KEY → dokter.id | Relasi ke dokter           |
| hari        | VARCHAR      | NOT NULL                | Hari praktik               |
| jam_mulai   | TIME         | NOT NULL                | Jam mulai praktik          |
| jam_selesai | TIME         | NOT NULL                | Jam selesai praktik        |
| kuota       | UNSIGNED INT | DEFAULT 20              | Maksimal pasien per jadwal |
| aktif       | BOOLEAN      | DEFAULT true            | Status jadwal aktif        |
| created_at  | TIMESTAMP    | NULL                    | Waktu dibuat               |
| updated_at  | TIMESTAMP    | NULL                    | Waktu diperbarui           |

### 🔗 Relasi

* Many-to-One → dokter
* One-to-Many → antrean

---

## 5. antrean — Data Antrean Pemeriksaan

Menyimpan nomor antrean pasien berdasarkan jadwal dokter dan tanggal pemeriksaan.

| Column             | Type         | Constraint             | Description                                 |
| ------------------ | ------------ | ---------------------- | ------------------------------------------- |
| id                 | BIGINT       | PRIMARY KEY            | ID unik antrean                             |
| jadwal_dokter_id   | BIGINT       | FOREIGN KEY            | Relasi ke jadwal dokter                     |
| tanggal_periksa    | DATE         | NOT NULL               | Tanggal pemeriksaan                         |
| nomor_antrean      | UNSIGNED INT | NOT NULL               | Nomor antrean                               |
| status             | ENUM         | DEFAULT `menunggu`     | `menunggu`, `dipanggil`, `selesai`, `batal` |
| dipanggil_pada     | TIMESTAMP    | NULLABLE               | Waktu antrean dipanggil                     |
| selesai_pada       | TIMESTAMP    | NULLABLE               | Waktu pemeriksaan selesai                   |
| dibatalkan_pada    | TIMESTAMP    | NULLABLE               | Waktu antrean dibatalkan                    |
| updated_by_user_id | BIGINT       | FOREIGN KEY → users.id | User yang mengubah status                   |
| created_at         | TIMESTAMP    | NULL                   | Waktu dibuat                                |
| updated_at         | TIMESTAMP    | NULL                   | Waktu diperbarui                            |

### 🔒 Unique Constraint

```sql
(jadwal_dokter_id, tanggal_periksa, nomor_antrean)
```

Constraint ini memastikan tidak ada nomor antrean ganda pada jadwal dan tanggal yang sama.

### 🔗 Relasi

* Many-to-One → jadwal_dokter
* One-to-Many → pendaftaran
* Many-to-One → users (updated_by)

---

## 6. pendaftaran — Pendaftaran Kunjungan

Menyimpan data registrasi pasien terhadap antrean pemeriksaan.

| Column           | Type      | Constraint               | Description                          |
| ---------------- | --------- | ------------------------ | ------------------------------------ |
| id               | BIGINT    | PRIMARY KEY              | ID unik pendaftaran                  |
| pasien_id        | BIGINT    | FOREIGN KEY → pasien.id  | Relasi ke pasien                     |
| antrean_id       | BIGINT    | FOREIGN KEY → antrean.id | Relasi ke antrean                    |
| keluhan          | TEXT      | NULLABLE                 | Keluhan pasien                       |
| status_kunjungan | ENUM      | DEFAULT `terdaftar`      | `terdaftar`, `selesai`, `dibatalkan` |
| created_at       | TIMESTAMP | NULL                     | Waktu registrasi                     |
| updated_at       | TIMESTAMP | NULL                     | Waktu diperbarui                     |

### 🔗 Relasi

* Many-to-One → pasien
* Many-to-One → antrean

---

## 7. audit_logs — Audit Trail & Aktivitas Sistem

Digunakan untuk mencatat seluruh aktivitas penting dalam sistem.

| Column         | Type         | Constraint             | Description            |
| -------------- | ------------ | ---------------------- | ---------------------- |
| id             | BIGINT       | PRIMARY KEY            | ID unik log            |
| user_id        | BIGINT       | FOREIGN KEY → users.id | User pelaku aktivitas  |
| action         | VARCHAR(80)  | NOT NULL               | Jenis aksi             |
| auditable_type | VARCHAR(120) | NOT NULL               | Nama model yang diubah |
| auditable_id   | BIGINT       | NOT NULL               | ID record terkait      |
| old_values     | JSON         | NULLABLE               | Data sebelum perubahan |
| new_values     | JSON         | NULLABLE               | Data setelah perubahan |
| ip             | VARCHAR(45)  | NULLABLE               | IP address pengguna    |
| user_agent     | VARCHAR(512) | NULLABLE               | Browser/device info    |
| created_at     | TIMESTAMP    | NULL                   | Waktu aktivitas        |
| updated_at     | TIMESTAMP    | NULL                   | Waktu update           |

### 📌 Index

```sql
(auditable_type, auditable_id)
(action, created_at)
```

### 🔗 Relasi

* Many-to-One → users

---

## 8. notifications — Notifikasi Sistem

Menyimpan notifikasi untuk setiap pengguna.

| Column     | Type      | Constraint             | Description         |
| ---------- | --------- | ---------------------- | ------------------- |
| id         | BIGINT    | PRIMARY KEY            | ID unik notifikasi  |
| user_id    | BIGINT    | FOREIGN KEY → users.id | Penerima notifikasi |
| message    | TEXT      | NOT NULL               | Isi pesan           |
| is_read    | BOOLEAN   | DEFAULT 0              | Status baca         |
| created_at | TIMESTAMP | NULL                   | Waktu dikirim       |
| updated_at | TIMESTAMP | NULL                   | Waktu diperbarui    |

### 🔗 Relasi

* Many-to-One → users

---

## 9. queues — Display Antrean

Digunakan untuk kebutuhan tampilan layar antrean realtime.

| Column        | Type      | Constraint        | Description                 |
| ------------- | --------- | ----------------- | --------------------------- |
| id            | BIGINT    | PRIMARY KEY       | ID unik                     |
| nomor_antrian | VARCHAR   | NOT NULL          | Nomor antrean tampil        |
| nama_pasien   | VARCHAR   | NULLABLE          | Nama pasien                 |
| status        | ENUM      | DEFAULT `waiting` | `waiting`, `called`, `done` |
| created_at    | TIMESTAMP | NULL              | Waktu dibuat                |
| updated_at    | TIMESTAMP | NULL              | Waktu diperbarui            |

> 📌 Tabel ini bersifat standalone dan digunakan khusus untuk display monitor antrean.

---

## 10. users — User Account (Laravel Modified)

Tabel bawaan Laravel dengan penambahan beberapa kolom berikut:

| Column    | Type    | Description                |
| --------- | ------- | -------------------------- |
| role_id   | BIGINT  | Relasi ke tabel `roles`    |
| is_active | BOOLEAN | Status akun aktif/nonaktif |

---

# 📐 Entity Relationship Diagram (ERD)

```text
roles
└── has many → users

users
├── belongs to → roles
├── has one → pasien
├── has many → notifications
├── has many → audit_logs
└── has many → antrean (updated_by)

dokter
└── has many → jadwal_dokter

jadwal_dokter
├── belongs to → dokter
└── has many → antrean

antrean
├── belongs to → jadwal_dokter
├── belongs to → users (updated_by)
└── has many → pendaftaran

pasien
├── belongs to → users
└── has many → pendaftaran

pendaftaran
├── belongs to → pasien
└── belongs to → antrean

notifications
└── belongs to → users

audit_logs
└── belongs to → users

queues
└── standalone table
```

---

# ⚙️ Database Rules & System Notes

## ✅ Foreign Key Rules

Semua foreign key menggunakan:

```php
->cascadeOnDelete()
```

Sehingga data terkait akan otomatis terhapus saat parent data dihapus.

---

## ✅ Status-Based System

Sistem tidak menggunakan soft delete.
Sebagai gantinya menggunakan kolom status seperti:

* menunggu
* dipanggil
* selesai
* batal

---

## ✅ Timestamp Tracking

Semua tabel memiliki:

```text
created_at
updated_at
```

Untuk kebutuhan logging dan tracking aktivitas.

---

## ✅ Audit Trail

Setiap perubahan data penting dicatat ke tabel:

```text
audit_logs
```

Termasuk:

* User pelaku perubahan
* Data sebelum & sesudah update
* IP Address
* Device/Browser info

---

## ✅ Unique Validation

Data berikut wajib unik:

| Field  | Table  |
| ------ | ------ |
| nik    | pasien |
| no_str | dokter |

---

## ✅ Queue Tracking

Tabel `antrean` memiliki tracking waktu otomatis:

| Column          | Function                  |
| --------------- | ------------------------- |
| dipanggil_pada  | Waktu antrean dipanggil   |
| selesai_pada    | Waktu pemeriksaan selesai |
| dibatalkan_pada | Waktu antrean dibatalkan  |

---

# 🚀 Kesimpulan

Struktur database **Proto Booking** dirancang untuk mendukung:

* Sistem booking & antrean pasien
* Multi-role authentication
* Monitoring jadwal dokter
* Audit trail aktivitas sistem
* Notifikasi realtime
* Display antrean digital
* Tracking status pemeriksaan secara lengkap

Database ini sudah cukup scalable untuk dikembangkan menjadi:

* Sistem klinik
* Rumah sakit
* Telemedicine
* Dashboard admin realtime
* Mobile app booking pasien
