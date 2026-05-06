# 📖 Guest Book — Sistem Buku Tamu BPS

> Aplikasi manajemen buku tamu berbasis web untuk Badan Pusat Statistik (BPS), dibangun dengan Laravel 12, Tailwind CSS, dan Alpine.js. Data tamu disinkronkan secara otomatis dari Google Sheets (Google Form) ke database.

---

## ✨ Fitur Utama

| Fitur | Deskripsi |
|---|---|
| 🔄 **Sync Google Sheets** | Tarik data tamu dari Google Form/Sheets ke database secara on-demand |
| 📋 **Manajemen Tamu** | Lihat daftar tamu hari ini & riwayat kunjungan dengan filter tanggal |
| 👤 **Assign Pegawai** | Tugaskan pegawai untuk melayani tamu secara langsung |
| 🔢 **Sistem Antrian** | Nomor antrian otomatis dan unik per tanggal kunjungan |
| 📊 **Laporan Bulanan** | Rekap kunjungan per pegawai + breakdown layanan, bisa ekspor PDF |
| 🏢 **Manajemen Pegawai** | CRUD pegawai dengan fitur aktivasi/nonaktivasi |
| 📈 **Dashboard KPI** | Statistik real-time: total tamu, tamu terassign, PPID vs PST |
| 🔒 **Sync Lock** | Mekanisme cooldown 30 menit untuk mencegah sync berlebihan |

---

## 🛠 Tech Stack

- **Backend**: Laravel 12 (PHP ^8.2)
- **Frontend**: Blade + Tailwind CSS v3 + Alpine.js
- **Build Tool**: Vite
- **Database**: MySQL
- **Queue**: Laravel Queue (database driver)
- **External**: Google Sheets API v4

---

## 🚀 Quick Start (Lokal)

```bash
# 1. Clone repository
git clone <repo-url> guest-book
cd guest-book

# 2. Install dependensi
composer install
npm install

# 3. Setup environment
cp .env.example .env
php artisan key:generate

# 4. Setup database (pastikan MySQL sudah berjalan dan database 'guest_book' dibuat)
php artisan migrate

# 5. Build frontend & jalankan
npm run build
composer run dev
```

> Untuk setup lengkap (Google Sheets API, konfigurasi lanjutan), lihat [docs/SETUP.md](docs/SETUP.md).  
> Untuk panduan deploy ke shared/cloud hosting, lihat [docs/DEPLOYMENT.md](docs/DEPLOYMENT.md).

---

## 📁 Struktur Direktori

```
guest-book/
├── app/
│   ├── Console/Commands/    # SyncGuests - Artisan command
│   ├── Http/Controllers/    # DashboardController, GuestController, dll
│   ├── Models/              # Guest, Employee, GuestAssignment, Queue, SyncLock
│   └── Services/            # AssignmentService, QueueService
├── database/
│   ├── migrations/          # 9 file migration
│   └── seeders/
├── resources/views/         # Blade templates (dashboard, guests, reports, employees)
├── routes/
│   ├── web.php              # Route utama aplikasi
│   └── auth.php             # Route autentikasi (Breeze)
├── docs/                    # Dokumentasi lengkap
└── guest_book.sql           # SQL dump untuk MySQL
```

---

## 📚 Dokumentasi

| Dokumen | Isi |
|---|---|
| [SETUP.md](docs/SETUP.md) | Panduan instalasi lengkap (lokal & dengan MySQL) |
| [DEPLOYMENT.md](docs/DEPLOYMENT.md) | Panduan deploy ke server produksi |
| [ARCHITECTURE.md](docs/ARCHITECTURE.md) | Arsitektur aplikasi dan alur data |
| [DATABASE.md](docs/DATABASE.md) | Skema database, relasi, dan penjelasan kolom |
| [FEATURE.md](docs/FEATURE.md) | Deskripsi fitur lengkap beserta alur kerja |
| [KNOWN_ISSUES.md](docs/KNOWN_ISSUES.md) | Isu yang diketahui dan workaround |
| [CHANGELOG.md](docs/CHANGELOG.md) | Riwayat perubahan versi |

---

## 🔑 Akun Pengguna

Buat akun admin pertama setelah setup selesai:

```bash
php artisan tinker
```

```php
\App\Models\User::create([
    'name'     => 'Nama Admin',
    'email'    => 'email@instansi.anda',
    'password' => bcrypt('password_kuat_anda'),
]);
```

> ⚠️ Gunakan email dan password yang kuat. Jangan gunakan password default di lingkungan produksi.

---

## 📄 Lisensi

MIT License — lihat file `LICENSE` untuk detail.
