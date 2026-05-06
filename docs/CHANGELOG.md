# 📋 CHANGELOG — Riwayat Perubahan

Semua perubahan signifikan pada proyek **Guest Book BPS** didokumentasikan di sini.

Format mengikuti [Keep a Changelog](https://keepachangelog.com/en/1.0.0/).

---

## [Unreleased]

### Planned
- Implementasi role-based access control (RBAC)
- Pindahkan sync ke background Job Queue
- Tambah fitur filter & search di halaman tamu
- Tambah notifikasi real-time antrian baru

---

## [1.2.0] — 2026-05-05

### Added
- Kolom `status` pada tabel `guests` (`menunggu` / `dilayani`)
- Status tamu otomatis berubah ke `dilayani` saat di-assign ke pegawai
- Migration: `2026_05_05_111435_add_status_to_guests_table.php`

### Changed
- `AssignmentService::assign()` sekarang mengupdate `Guest.status` ke `dilayani`

---

## [1.1.0] — 2026-05-04

### Added
- **Mekanisme Sync Lock**: Cooldown 30 menit untuk mencegah sync berlebihan
- Model `SyncLock` dan migration `create_sync_locks_table`
- Dashboard menampilkan countdown sisa waktu cooldown sync
- Tombol Sync di-disable saat dalam periode cooldown
- SQL dump database MySQL: `guest_book.sql`

### Fixed
- **[FIXED-002]** Race condition double-click sync: SyncLock di-update sebelum sync dijalankan dalam DB transaction

---

## [1.0.0] — 2026-04-29

### Added

#### Core Architecture
- Inisialisasi project Laravel 12 dengan Breeze authentication
- Konfigurasi Vite + Tailwind CSS v3 + Alpine.js
- Setup database: MySQL (dev & prod)
- Laravel Queue dengan database driver

#### Models
- `Guest` — model utama data tamu dengan 20+ atribut dari Google Form
- `Employee` — model pegawai dengan soft-deactivate pattern
- `GuestAssignment` — model pivot relasi tamu-pegawai
- `Queue` — model nomor antrian harian
- `User` — model pengguna sistem (dari Breeze)

#### Migrations
- `create_guests_table` — tabel utama dengan `source_key` unique constraint
- `create_employees_table` — tabel pegawai dengan `is_active` index
- `create_guest_assignments_table` — tabel assignment dengan FK cascade
- `create_queues_table` — tabel antrian dengan `UNIQUE(queue_number, queue_date)`

#### Services
- `AssignmentService::assign()` — logic assign tamu ke pegawai dengan transaction + idempotency
- `QueueService::generate()` — generate nomor antrian sequential dengan `lockForUpdate()`

#### Controllers
- `DashboardController` — KPI real-time: total tamu, distribusi pegawai, antrian hari ini
- `GuestController` — daftar tamu (tab: hari ini / riwayat) + detail tamu
- `AssignmentController` — trigger assignment pegawai ke tamu
- `EmployeeController` — CRUD pegawai + activate/deactivate
- `ReportController` — laporan bulanan web + ekspor PDF
- `SyncController` — trigger sync manual dengan sync lock check

#### Artisan Commands
- `sync:guests` — sync data dari Google Sheets ke database dengan deduplication MD5

#### Views
- `dashboard.blade.php` — dashboard dengan KPI cards, tabel antrian, distribusi pegawai
- `guests/index.blade.php` — daftar tamu dengan tab hari ini / riwayat + inline assign
- `guests/show.blade.php` — detail lengkap tamu
- `employees/index.blade.php` — daftar pegawai dengan aksi activate/deactivate
- `employees/create.blade.php` — form tambah pegawai
- `employees/edit.blade.php` — form edit pegawai
- `reports/monthly.blade.php` — laporan bulanan per pegawai + breakdown layanan
- `reports/monthly-pdf.blade.php` — template PDF laporan

#### Routes
- Route group `auth` middleware untuk semua fitur utama
- `Route::resource('employees', ...)` + custom `activate` route
- `POST /sync/guests` dengan cooldown protection
- `GET /report/monthly` dan `GET /report/monthly/pdf`

#### External Integration
- Google Sheets API v4 via `google/apiclient`
- DomPDF via `barryvdh/laravel-dompdf` untuk ekspor PDF

#### Fixed
- **[FIXED-001]** Race condition generate nomor antrian: menggunakan `lockForUpdate()` + `UNIQUE` constraint

---

## Format Versi

Versi mengikuti [Semantic Versioning](https://semver.org/):
- **MAJOR**: Perubahan breaking (incompatible API / schema)
- **MINOR**: Fitur baru yang backward-compatible
- **PATCH**: Bug fix yang backward-compatible

## Kategori Perubahan

| Kategori | Keterangan |
|---|---|
| `Added` | Fitur baru |
| `Changed` | Perubahan pada fitur yang ada |
| `Deprecated` | Fitur yang akan segera dihapus |
| `Removed` | Fitur yang sudah dihapus |
| `Fixed` | Bug fix |
| `Security` | Perbaikan terkait keamanan |
