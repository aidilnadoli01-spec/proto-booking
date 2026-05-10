# 📋 Cara Clone Repository Proto Booking

## 1. Buka Terminal/Command Prompt
Di folder mana saja (contoh: Desktop atau Documents)

## 2. Jalankan perintah clone:
```bash
git clone https://github.com/username/proto-booking.git
```
> Ganti `username` dengan username GitHub Anda

## 3. Masuk ke folder project:
```bash
cd proto-booking
```

## 4. Install dependencies:
```bash
composer install
npm install
```

## 5. Setup environment:
```bash
cp .env.example .env
php artisan key:generate
```

## 6. Setup database:
Edit file `.env` - sesuaikan config database Anda:
```env
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_antrean_dokter
DB_USERNAME=root
DB_PASSWORD=
```

Kemudian jalankan migrasi dan seeder:
```bash
php artisan migrate
php artisan db:seed
```

## 7. Jalankan project:
```bash
php artisan serve
npm run dev
```

Akses aplikasi di: `http://localhost:8000`

---

## ⚠️ Prerequisites
Pastikan sudah ter-install:
- **PHP** 8.1+
- **Composer** (PHP Dependency Manager)
- **Node.js** & **npm**
- **MySQL** server (running)
- **Git**

## 📝 Catatan
- `.env.example` akan otomatis di-clone dari repository
- Jangan commit file `.env` (sudah di .gitignore)
- Database akan di-buat otomatis saat `php artisan migrate`

## 🆘 Troubleshooting
Jika ada error saat install:

**Error: Composer dependencies**
```bash
composer install --no-interaction --prefer-dist
```

**Error: Database connection**
- Pastikan MySQL running
- Cek username/password di `.env`

**Error: Node modules**
```bash
npm install --legacy-peer-deps
```
