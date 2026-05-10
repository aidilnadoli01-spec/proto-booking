# Langkah Lanjutan Implementasi

Dokumen ini berisi roadmap lanjutan agar sistem siap dipakai untuk skala nyata.

## Fase 1 - Penyempurnaan Fitur Inti (1-2 minggu)

- [SELESAI] Tambahkan fitur edit dan hapus data dokter.
- [SELESAI] Tambahkan fitur edit dan hapus jadwal dokter.
- [SELESAI] Tampilkan detail antrean harian untuk admin (filter berdasarkan tanggal/dokter/status) + operasi panggil/selesai/batal.
- [SELESAI] Tambahkan validasi kuota saat booking (booking ditolak jika kuota penuh).
- [SELESAI] Tambahkan pembatalan booking oleh pasien sebelum waktu tertentu.
- [SELESAI] Tambahkan reschedule booking oleh pasien.
- [SELESAI] Tambahkan display antrean (mode web + mode TV).

## Fase 2 - UX, Keamanan, dan Audit (1 minggu)

- Buat halaman profil pasien terpisah (update data diri).
- [SELESAI] Tambahkan audit trail sederhana (siapa mengubah data apa dan kapan) + Audit Log Viewer.
- Tambahkan kebijakan keamanan:
  - limit login attempts
  - stronger password policy
  - optional email verification enforcement
- Tambahkan halaman error custom (`403`, `404`, `500`) agar UX lebih baik.

## Fase 3 - Operasional dan Monitoring (1 minggu)

- Setup logging terstruktur (daily log rotation).
- Setup backup database terjadwal.
- Tambahkan health check endpoint internal.
- Tambahkan metrik sederhana (booking per hari, antrean aktif, load per dokter).

## Fase 4 - Siap Produksi (1 minggu)

- Pisahkan konfigurasi environment untuk dev/staging/production.
- Hardening server:
  - HTTPS
  - secure headers
  - `APP_DEBUG=false` di production
- Siapkan CI pipeline sederhana:
  - install dependency
  - run test
  - build assets
- Buat SOP deployment dan rollback.

## Prioritas Teknis Cepat (disarankan dikerjakan dulu)

1. Validasi kuota antrean saat booking.
2. [SELESAI] CRUD lengkap dokter dan jadwal.
3. Audit log perubahan data penting.
4. Dashboard admin detail antrean harian.
5. CI otomatis untuk testing.
