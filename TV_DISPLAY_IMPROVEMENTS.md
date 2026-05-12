# 🎯 Perbaikan Mode TV Display Sistem Antrean Klinik

## Overview

Dokumentasi ini berisi perbaikan logic dan desain untuk fitur TV Display pada sistem antrean klinik berbasis Laravel Blade.

Tujuan utama:

* Membuat tampilan TV lebih modern
* Membuat sistem realtime
* Memperbaiki logic antrean
* Menambahkan voice notification
* Memisahkan admin panel dan TV display
* Membuat UI fullscreen khusus TV

---

# 📌 Masalah Pada TV Display Saat Ini

## Masalah UI

* Tampilan terlalu kosong
* Nomor antrean tidak jelas
* Layout tidak fokus
* Sidebar admin masih muncul
* Warna terlalu gelap
* Tidak responsive untuk TV
* Tidak ada animasi
* Informasi terlalu kecil

## Masalah Logic

* Data antrean tidak realtime
* Tidak ada auto refresh
* Logic panggil antrean belum sinkron
* Tidak ada status antrean yang jelas
* Voice notification belum optimal

---

# ✅ Struktur Sistem yang Disarankan

## Admin Panel

Digunakan untuk:

* Kelola antrean
* Panggil antrean
* Kelola dokter
* Kelola user
* Pengaturan sistem

---

## TV Display

Khusus layar TV fullscreen.

Hanya menampilkan:

* Nomor antrean aktif
* Loket tujuan
* Antrean selanjutnya
* Jam realtime
* Running text
* Video/promosi
* Voice notification

---

# 📂 Struktur Route Laravel

```php
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
});

Route::get('/tv-display', [TvDisplayController::class, 'index']);

Route::get('/api/tv-queue', [TvDisplayController::class, 'queue']);

Route::post('/queue/call-next', [QueueController::class, 'callNext']);
```

---

# 📊 Struktur Database Antrean

## Table queues

| Column       | Type      |
| ------------ | --------- |
| id           | BIGINT    |
| queue_number | VARCHAR   |
| patient_name | VARCHAR   |
| counter      | VARCHAR   |
| status       | ENUM      |
| called_at    | TIMESTAMP |
| created_at   | TIMESTAMP |
| updated_at   | TIMESTAMP |

---

## Status Antrean

| Status  | Fungsi           |
| ------- | ---------------- |
| waiting | Menunggu         |
| called  | Sedang dipanggil |
| done    | Selesai          |

---

# ⚙️ Logic Realtime TV Display

## Controller Laravel

```php
public function queue()
{
    $current = Queue::where('status', 'called')
        ->latest('called_at')
        ->first();

    $next = Queue::where('status', 'waiting')
        ->orderBy('id')
        ->limit(5)
        ->get();

    return response()->json([
        'current' => $current,
        'next' => $next
    ]);
}
```

---

# 🔄 Auto Refresh Realtime

Gunakan polling setiap 3 detik.

```javascript
setInterval(() => {
    fetch('/api/tv-queue')
        .then(res => res.json())
        .then(data => {
            updateCurrentQueue(data.current)
            updateNextQueue(data.next)
        })
}, 3000)
```

---

# 📢 Logic Panggil Antrean Berikutnya

## QueueController

```php
public function callNext()
{
    Queue::where('status', 'called')
        ->update([
            'status' => 'done'
        ]);

    $next = Queue::where('status', 'waiting')
        ->orderBy('id')
        ->first();

    if ($next) {
        $next->update([
            'status' => 'called',
            'called_at' => now()
        ]);
    }

    return response()->json([
        'success' => true
    ]);
}
```

---

# 🔊 Voice Notification

Gunakan Web Speech API.

```javascript
const speech = new SpeechSynthesisUtterance(
    `Nomor antrean ${queueNumber}, silakan menuju loket ${counter}`
)

speech.lang = 'id-ID'
window.speechSynthesis.speak(speech)
```

---

# 🎨 Desain UI TV Modern

## Struktur Layout

```text
------------------------------------------------
LOGO | NAMA KLINIK              JAM DIGITAL
------------------------------------------------

        NOMOR ANTREAN SAAT INI

                A-023

         SILAKAN MENUJU LOKET 2

------------------------------------------------

ANTREAN SELANJUTNYA

A-024
A-025
A-026
A-027

------------------------------------------------

RUNNING TEXT INFORMASI
------------------------------------------------
```

---

# 🎨 Warna UI Modern

| Element    | Warna      |
| ---------- | ---------- |
| Background | Navy Gelap |
| Primary    | Biru Neon  |
| Accent     | Cyan       |
| Text       | Putih      |
| Success    | Hijau      |

---

# 💅 CSS Modern

## Background

```css
background: linear-gradient(
    135deg,
    #0f172a,
    #1e293b
);
```

---

## Nomor Antrean Besar

```css
font-size: 140px;
font-weight: bold;
letter-spacing: 8px;
```

---

## Glassmorphism Card

```css
background: rgba(255,255,255,0.05);
backdrop-filter: blur(12px);
border-radius: 30px;
border: 1px solid rgba(255,255,255,0.1);
```

---

# ✨ Animasi Nomor Antrean

```css
@keyframes zoomPulse {
    0% {
        transform: scale(0.8);
        opacity: 0;
    }

    100% {
        transform: scale(1);
        opacity: 1;
    }
}
```

---

# 🕒 Jam Realtime

```javascript
setInterval(() => {
    const now = new Date()

    document.getElementById('clock').innerHTML =
        now.toLocaleTimeString('id-ID')
}, 1000)
```

---

# 📱 Responsive TV Mode

Gunakan:

```css
height: 100vh;
overflow: hidden;
```

Dan:

```css
font-size: clamp(40px, 8vw, 140px);
```

---

# 🚫 Yang Tidak Boleh Ditampilkan di TV

* Sidebar admin
* Tombol logout
* Tombol setting
* Form admin
* Menu dashboard
* Data internal

TV harus fokus hanya pada antrean.

---

# 📁 Struktur Blade Laravel

```text
resources/views/
├── admin/
│   └── dashboard.blade.php
│
├── tv/
│   └── index.blade.php
```

---

# 🚀 Fitur Tambahan yang Direkomendasikan

## Tambahan Modern

✅ Running text informasi
✅ Fullscreen otomatis
✅ Multi loket
✅ Statistik antrean
✅ Video promosi klinik
✅ Auto reconnect realtime
✅ Dark mode modern
✅ QR check-in pasien
✅ Notifikasi suara otomatis
✅ Informasi dokter aktif

---

# 🧠 Prompt AI / Cursor untuk Memperbaiki TV Display

```text
Perbaiki fitur TV Display Sistem Antrean Klinik menggunakan Laravel Blade dan Tailwind CSS.

Buat tampilan modern fullscreen khusus TV tanpa sidebar admin.

Fokus utama:
- Nomor antrean besar di tengah
- Informasi loket
- Antrean selanjutnya
- Jam realtime
- Running text informasi
- Voice notification otomatis

Tambahkan realtime auto refresh menggunakan AJAX polling setiap 3 detik.

Buat logic:
- Panggil antrean berikutnya
- Status waiting, called, done
- Sinkron realtime antara admin dan TV display

Gunakan desain modern:
- Background gradient navy
- Card glassmorphism
- Font besar untuk nomor antrean
- Animasi saat nomor berubah
- Responsive untuk TV 43-55 inch

Gunakan:
- Laravel Blade
- Tailwind CSS
- JavaScript Vanilla
- Web Speech API

Pisahkan halaman admin dan halaman TV display.

Tambahkan fallback jika tidak ada antrean:
"Belum ada antrean dipanggil"

Tambahkan tombol fullscreen otomatis saat halaman TV dibuka.
```

---

# ✅ Hasil Akhir yang Diharapkan

TV Display menjadi:

* Modern
* Bersih
* Realtime
* Responsive
* Nyaman dilihat di TV besar
* Fokus pada antrean
* Mudah digunakan
* Sinkron dengan admin panel
