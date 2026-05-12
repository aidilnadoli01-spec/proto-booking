# 📺 TV Display System - Dokumentasi Lengkap

Sistem TV Display untuk Manajemen Antrean Klinik berbasis Laravel. Fitur realtime dengan auto-refresh, voice notification, dan kontrol admin yang mudah.

---

## 🎯 Overview

**TV Display System** adalah solusi modern untuk menampilkan antrian pasien di layar TV/monitor dengan fitur:

✅ **Realtime Updates** - Update setiap 3 detik  
✅ **Voice Notification** - Pengumuman otomatis saat antrean dipanggil  
✅ **Modern UI** - Desain glassmorphism dengan dark mode  
✅ **Fullscreen Support** - Otomatis fullscreen di TV  
✅ **Admin Control Panel** - Mudah control antrian  
✅ **Responsive Design** - Cocok untuk TV 43-65 inch  

---

## 📍 URL Akses

| URL | Fungsi | Auth | Akses |
|-----|--------|------|-------|
| `/tv-display` | Fullscreen TV Display | ❌ Tidak | Public |
| `/api/tv-queue` | API Data Antrian | ❌ Tidak | Public |
| `/tv-display/admin` | Admin Control Panel | ✅ Ya | Admin, Super Admin |
| `/api/tv-display/call-next` | API Panggil Antrian | ✅ Ya | Admin, Super Admin |
| `/api/tv-display/reset` | API Reset Antrian | ✅ Ya | Admin, Super Admin |

---

## 🎬 Cara Menggunakan

### 1️⃣ Setup TV Display (Untuk Layar TV)

**Langkah:**
1. Siapkan layar TV/monitor di ruang tunggu klinik
2. Buka browser ke URL: `http://[IP-SERVER]:8000/tv-display`
3. Sistem akan otomatis fullscreen (tekan ESC untuk exit)
4. Display akan auto-refresh setiap 3 detik

**Contoh IP Server:**
- Lokal: `http://127.0.0.1:8000/tv-display`
- Network: `http://192.168.1.100:8000/tv-display`

---

### 2️⃣ Setup Admin Control Panel

**Langkah:**
1. Login sebagai **Admin** atau **Super Admin**
2. Di dashboard, klik tombol **"📺 TV Display Panel"** atau **"🎛️ Manajemen TV Display"**
3. Atau akses langsung: `http://localhost:8000/tv-display/admin`

**Fitur Admin Panel:**
- ✅ Lihat antrean saat ini
- ✅ Lihat preview 5 antrean berikutnya
- ✅ Tombol **"Panggil Antrean Berikutnya"**
- ✅ Tombol **"Reset Antrean Hari Ini"**
- ✅ Statistik real-time (menunggu, selesai, dokter aktif)

---

## 🎨 Fitur TV Display

### 1. Header Section
```
┌─────────────────────────────────────────────────────────┐
│ 🏥 KLINIK ANTREAN          |          17:25:52           │
│ Sistem Antrean Real-Time   |    Selasa, 12 Mei 2026     │
└─────────────────────────────────────────────────────────┘
```

- Logo klinik + nama klinik
- Jam digital realtime (update setiap detik)
- Tanggal

### 2. Main Display
```
┌─────────────────────────────────────────────────────────┐
│                 NOMOR ANTREAN SAAT INI                  │
│                                                          │
│                         A-023                           │
│                                                          │
│                   SILAKAN MENUJU LOKET 2                │
│                        Dr. Budi                         │
└─────────────────────────────────────────────────────────┘
```

- **Nomor Antrean Besar** (font 140-180px)
- **Informasi Loket** - Dimana pasien harus menuju
- **Nama Dokter** - Dokter yang menangani
- **Animasi** - Zoom pulse saat nomor berubah

### 3. Next Queue Section
```
ANTREAN BERIKUTNYA
┌──────────────────────┐
│ 1. A-024             │
│ 2. A-025             │
│ 3. A-026             │
│ 4. A-027             │
│ 5. A-028             │
└──────────────────────┘
```

- Preview 5 antrian berikutnya
- Status setiap antrian
- Scrollable jika lebih dari 5

### 4. Running Text (Footer)
```
ℹ️ Antrean Menunggu: 12 | Selesai Hari Ini: 45 | 
Dokter Aktif: 5 | 📞 Layanan: 0812-3456-7890
```

- Informasi statistik
- Running text scroll otomatis
- Dapat dikustomisasi

---

## 🔊 Voice Notification

**Fitur Suara Otomatis:**

Saat ada antrean baru dipanggil, sistem akan mengucapkan:

```
"Nomor antrean A-023, silakan menuju loket 2. Dokter Budi Santoso"
```

### Pengaturan Voice:
- **Bahasa:** Indonesia (id-ID)
- **Kecepatan:** Normal
- **Pitch:** Normal
- **Trigger:** Saat nomor antrean berubah

### Cara Mute/Volume:
- Volume di kontrol browser atau speaker
- Ubah di settings browser (WebSpeech API)

---

## 🎨 Desain & Styling

### Warna Tema
| Element | Warna | Hex |
|---------|-------|-----|
| Background | Navy Gelap | #0f172a |
| Primary | Cyan Neon | #00d9ff |
| Accent | Kuning | #fbbf24 |
| Text | Putih | #ffffff |
| Card Border | Cyan Transparan | rgba(0,217,255,0.3) |

### Responsive Sizing
```css
/* Font sizes menggunakan clamp untuk responsive */
font-size: clamp(MIN, VIEWPORT, MAX);

Contoh:
- Clock: clamp(28px, 4vw, 48px)
- Queue Number: clamp(80px, 15vw, 180px)
- Label: clamp(20px, 2.5vw, 30px)
```

### Animasi
- **Zoom Pulse** - Saat nomor antrean berubah
- **Scroll Text** - Running text di footer
- **Hover Effect** - Hover di item antrian berikutnya

---

## 🔄 Auto Refresh Logic

### Client-side (TV Display):
```javascript
// Fetch data setiap 3 detik
setInterval(() => {
    fetch('/api/tv-queue')
        .then(response => response.json())
        .then(data => {
            updateCurrentQueue(data.current);
            updateNextQueue(data.nextQueue);
        })
}, 3000);

// Update clock setiap 1 detik
setInterval(() => updateClock(), 1000);
```

### Server-side (Controller):
```php
public function getQueueData()
{
    // Get current queue (status = dipanggil)
    $current = Antrean::where('status', 'dipanggil')->first();
    
    // Get next 5 queues (status = menunggu)
    $nextQueue = Antrean::where('status', 'menunggu')
        ->limit(5)->get();
    
    // Get statistics
    $statistics = [
        'waiting' => Antrean::where('status', 'menunggu')->count(),
        'completed' => Antrean::where('status', 'selesai')->count(),
        'activeDoctors' => JadwalDokter::where('aktif', true)->count(),
    ];
    
    return response()->json([...]);
}
```

---

## 👥 Admin Panel Features

### 1. Current Queue Info
Menampilkan:
- Nomor antrean saat ini
- Loket tujuan
- Nama dokter
- Waktu dipanggil

### 2. Action Buttons

#### 📞 Panggil Antrean Berikutnya
```
Fungsi:
1. Set current antrean status = "selesai"
2. Ambil antrean pertama dengan status = "menunggu"
3. Set statusnya = "dipanggil"
4. TV Display otomatis update + voice notification

Endpoint: POST /api/tv-display/call-next
```

#### 🔄 Reset Antrean Hari Ini
```
Fungsi:
1. Update SEMUA antrean hari ini
2. Status "menunggu" dan "dipanggil" → "batal"
3. Tidak bisa dibatalkan (perlu konfirmasi)

Endpoint: POST /api/tv-display/reset
```

### 3. Next Queue Preview
- Tampilkan 5 antrian berikutnya yang menunggu
- Update real-time setiap 3 detik

### 4. Statistics
- Jumlah yang menunggu
- Jumlah selesai hari ini
- Jumlah dokter aktif

---

## 🛠️ Technical Stack

| Layer | Technology |
|-------|------------|
| Backend | Laravel 11 |
| Frontend (TV) | HTML5, CSS3, Vanilla JavaScript |
| Frontend (Admin) | Laravel Blade, Tailwind CSS |
| API | REST (JSON) |
| Database | MySQL |
| Voice | Web Speech API |
| Real-time | Polling (3s interval) |

---

## 📁 File Structure

```
project/
├── app/Http/Controllers/
│   └── TvDisplayController.php
├── routes/
│   └── web.php (routes untuk TV display)
├── resources/views/tv/
│   ├── display.blade.php (TV fullscreen)
│   └── admin-panel.blade.php (Admin control)
└── resources/views/dashboard/
    ├── admin.blade.php (Admin dashboard)
    └── super-admin.blade.php (Super admin dashboard)
```

---

## 🚀 Deployment

### Production Setup

1. **Server Requirement:**
   - PHP 8.1+
   - Laravel 11
   - MySQL 5.7+
   - Node.js (untuk build assets)

2. **Konfigurasi Environment:**
```bash
cp .env.example .env
php artisan key:generate
php artisan migrate
npm run build
```

3. **TV Setup di Klinik:**
   - Siapkan device (Raspberry Pi, PC, atau Smart TV dengan browser)
   - Arahkan ke: `http://[SERVER-IP]:8000/tv-display`
   - Set browser fullscreen (F11)
   - Atur timer auto-refresh jika diperlukan

4. **Admin Setup:**
   - Login ke dashboard admin
   - Akses TV Display Panel
   - Configure sesuai kebutuhan

---

## ⚙️ Customization

### Mengubah Running Text
Edit di `resources/views/tv/display.blade.php`:
```javascript
// Baris 211
const message = `ℹ️ Antrean Menunggu: ${statistics.waiting} | ...`;
```

### Mengubah Warna
Edit di `<style>` section dalam `display.blade.php`:
```css
.current-label {
    color: rgba(255, 255, 255, 0.7);
}
```

### Mengubah Voice Language
Edit di controller:
```javascript
utterance.lang = 'en-US'; // Ganti menjadi bahasa lain
```

### Mengubah Refresh Interval
Edit di `display.blade.php`:
```javascript
setInterval(fetchQueueData, 3000); // 3000ms = 3 detik
```

---

## 🐛 Troubleshooting

### TV Display Tidak Update
**Solusi:**
1. Cek koneksi internet TV
2. Cek `GET /api/tv-queue` di browser
3. Pastikan Laravel server running: `php artisan serve`
4. Clear browser cache (Ctrl+Shift+Delete)

### Voice Notification Tidak Jalan
**Solusi:**
1. Cek volume speaker/headset
2. Pastikan browser support Web Speech API
3. Cek console browser (F12 → Console)
4. Ubah settings browser untuk allow microphone/audio

### Admin Panel Tidak Bisa Akses
**Solusi:**
1. Login sebagai admin/super_admin
2. Cek middleware di routes (harus role:admin,super_admin)
3. Clear session: `php artisan cache:clear`

### Data Tidak Realtime
**Solusi:**
1. Cek Network tab (F12 → Network)
2. Pastikan `/api/tv-queue` return data correctly
3. Check server logs
4. Reduce interval dari 3s menjadi 1s (test saja)

---

## 📊 API Endpoints

### GET /api/tv-queue
**Response:**
```json
{
    "success": true,
    "current": {
        "id": 1,
        "nomor_antrean": "A-023",
        "dokter": "Budi Santoso",
        "loket": 2,
        "status": "dipanggil",
        "dipanggil_pada": "17:25:30"
    },
    "nextQueue": [
        {"nomor_antrean": "A-024", "status": "menunggu"},
        {"nomor_antrean": "A-025", "status": "menunggu"}
    ],
    "statistics": {
        "waiting": 12,
        "completed": 45,
        "activeDoctors": 5
    },
    "timestamp": "2026-05-12 17:25:52"
}
```

### POST /api/tv-display/call-next
**Response:**
```json
{
    "success": true,
    "message": "Antrean berhasil dipanggil",
    "queue": {
        "nomor_antrean": "A-024",
        "status": "dipanggil"
    }
}
```

### POST /api/tv-display/reset
**Response:**
```json
{
    "success": true,
    "message": "Antrean berhasil direset"
}
```

---

## 📞 Support

Jika ada pertanyaan atau issue:
1. Check dokumentasi ini
2. Check file TV_DISPLAY_IMPROVEMENTS.md
3. Check console browser (F12)
4. Check server logs: `tail -f storage/logs/laravel.log`

---

## 📝 Version

- **Version:** 1.0.0
- **Last Updated:** 12 Mei 2026
- **Status:** ✅ Production Ready

---

**Dibuat dengan ❤️ untuk Sistem Antrean Klinik Modern**
