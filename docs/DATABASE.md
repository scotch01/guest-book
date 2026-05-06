# 🗄️ DATABASE — Skema dan Dokumentasi Database

Dokumen ini menjelaskan skema database, relasi antar tabel, dan penjelasan kolom dari aplikasi **Guest Book BPS**.

---

## Konfigurasi Database

| Environment | Driver | Database |
|---|---|---|
| Local/Dev | MySQL 8.x | `guest_book` |
| Production | MySQL 8.x | `guest_book` |
| Charset | - | `utf8mb4` |
| Collation | - | `utf8mb4_unicode_ci` |

---

## Daftar Tabel

| Tabel | Keterangan |
|---|---|
| `guests` | Data tamu pengunjung (sumber: Google Form) |
| `employees` | Data pegawai pelayan |
| `guest_assignments` | Relasi tamu ↔ pegawai |
| `queues` | Nomor antrian harian tamu |
| `sync_locks` | State cooldown sinkronisasi Google Sheets |
| `users` | Pengguna sistem (akun login petugas) |
| `sessions` | Sesi login (driver: database) |
| `cache` / `cache_locks` | Cache data |
| `jobs` / `job_batches` / `failed_jobs` | Laravel Queue |
| `migrations` | Riwayat migrasi |
| `password_reset_tokens` | Token reset password |

---

## Diagram Relasi (ERD)

```
guests (1) ──── (N) guest_assignments (N) ──── (1) employees
guests (1) ──── (1) queues
users (standalone)
sync_locks (standalone)
```

---

## Detail Tabel

### `guests`

Tabel ini menyimpan data tamu yang ditarik dari Google Form milikmu. **Struktur kolom tabel ini harus disesuaikan dengan pertanyaan/kolom yang ada di Google Form yang kamu gunakan.**

Kolom yang ada saat ini hanyalah contoh implementasi untuk satu kasus penggunaan tertentu. Jika Google Form kamu berbeda, kolom-kolom di tabel ini perlu diubah.

#### Kolom yang Wajib Ada (Digunakan oleh Sistem)

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | BIGINT UNSIGNED | Primary key, auto increment |
| `source_key` | VARCHAR(255) UNIQUE | Hash MD5 untuk deduplication — **jangan dihapus** |
| `tanggal_kunjungan` | DATE | Digunakan oleh `QueueService` untuk generate antrian — **wajib ada** |
| `status` | VARCHAR(255) | Nilai: `menunggu` / `dilayani` — dikelola oleh `AssignmentService` — **jangan dihapus** |
| `created_at` / `updated_at` | TIMESTAMP | Laravel timestamps |

#### Kolom Data Tamu (Sesuaikan dengan Google Form Kamu)

Semua kolom lainnya di tabel `guests` adalah **kolom data** yang merepresentasikan jawaban dari Google Form. Kolom-kolom ini **harus disesuaikan** dengan pertanyaan yang ada di form milikmu.

Contoh: jika Google Form kamu memiliki pertanyaan "Nama Lengkap", "Instansi", dan "Keperluan", maka buat kolom `nama`, `instansi`, dan `keperluan` di tabel ini.

> **Lihat juga**: `database/migrations/*_create_guests_table.php` untuk menambah/mengubah kolom, dan `app/Console/Commands/SyncGuests.php` untuk menyesuaikan mapping kolom spreadsheet ke database.

#### Indexes yang Direkomendasikan

```php
$table->index('tanggal_kunjungan'); // untuk filter tamu hari ini
$table->unique('source_key');       // wajib, untuk deduplication
```



### `employees`

Data pegawai pelayan.

| Kolom | Tipe | Nullable | Keterangan |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | No | Primary key |
| `nama` | VARCHAR(255) | No | Nama lengkap |
| `nip` | VARCHAR(255) | Yes | Nomor Induk Pegawai |
| `is_active` | TINYINT(1) | No | Status aktif (default: 1) |
| `created_at` | TIMESTAMP | Yes | Timestamp |
| `updated_at` | TIMESTAMP | Yes | Timestamp |

**Indexes**: `PRIMARY KEY(id)`, `INDEX(is_active)`

> Penghapusan pegawai bersifat **soft-deactivate** (`is_active = 0`), bukan hard-delete.

---

### `guest_assignments`

Relasi tamu dengan pegawai yang melayani.

| Kolom | Tipe | Nullable | Keterangan |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | No | Primary key |
| `guest_id` | BIGINT UNSIGNED | No | FK → `guests.id` ON DELETE CASCADE |
| `employee_id` | BIGINT UNSIGNED | No | FK → `employees.id` ON DELETE CASCADE |
| `assigned_at` | TIMESTAMP | No | Waktu assignment |
| `created_at` | TIMESTAMP | Yes | Timestamp |
| `updated_at` | TIMESTAMP | Yes | Timestamp |

**Indexes**: `INDEX(assigned_at)` untuk performa query laporan

> Setiap tamu hanya bisa di-assign ke **satu** pegawai (one-to-one).

---

### `queues`

Nomor antrian harian tamu.

| Kolom | Tipe | Nullable | Keterangan |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | No | Primary key |
| `guest_id` | BIGINT UNSIGNED | No | FK → `guests.id` ON DELETE CASCADE |
| `queue_number` | INT UNSIGNED | No | Nomor antrian (1, 2, 3, ...) |
| `queue_date` | DATE | No | Tanggal antrian |
| `created_at` | TIMESTAMP | Yes | Timestamp |
| `updated_at` | TIMESTAMP | Yes | Timestamp |

**Constraints**: `UNIQUE(queue_number, queue_date)` — anti duplikat per hari

> Nomor antrian di-generate dengan `SELECT MAX() FOR UPDATE` untuk mencegah race condition.

---

### `sync_locks`

Kontrol mekanisme cooldown sinkronisasi.

| Kolom | Tipe | Nullable | Keterangan |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | No | Primary key |
| `key` | VARCHAR(255) | No | **UNIQUE** identifier lock (contoh: `guests`) |
| `last_run_at` | TIMESTAMP | Yes | Waktu terakhir sync berhasil |
| `created_at` | TIMESTAMP | Yes | Timestamp |
| `updated_at` | TIMESTAMP | Yes | Timestamp |

**Logika cooldown**: `last_run_at + 30 menit > now()` → tolak sync.

---

### `users`

Akun login petugas sistem.

| Kolom | Tipe | Nullable | Keterangan |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | No | Primary key |
| `name` | VARCHAR(255) | No | Nama pengguna |
| `email` | VARCHAR(255) | No | **UNIQUE** email login |
| `email_verified_at` | TIMESTAMP | Yes | Waktu verifikasi email |
| `password` | VARCHAR(255) | No | Password ter-hash (bcrypt, 12 rounds) |
| `remember_token` | VARCHAR(100) | Yes | Token "remember me" |
| `created_at` | TIMESTAMP | Yes | Timestamp |
| `updated_at` | TIMESTAMP | Yes | Timestamp |

---

## Daftar Migrasi

| File Migrasi | Tabel |
|---|---|
| `0001_01_01_000000_create_users_table.php` | `users`, `password_reset_tokens`, `sessions` |
| `0001_01_01_000001_create_cache_table.php` | `cache`, `cache_locks` |
| `0001_01_01_000002_create_jobs_table.php` | `jobs`, `job_batches`, `failed_jobs` |
| `2026_04_29_075952_create_guests_table.php` | `guests` |
| `2026_04_29_080000_create_employees_table.php` | `employees` |
| `2026_04_29_080005_create_guest_assignments_table.php` | `guest_assignments` |
| `2026_04_29_080009_create_queues_table.php` | `queues` |
| `2026_05_04_031212_create_sync_locks_table.php` | `sync_locks` |
| `2026_05_05_111435_add_status_to_guests_table.php` | Tambah kolom `guests.status` |

```bash
# Jalankan semua migrasi
php artisan migrate

# Lihat status
php artisan migrate:status

# Rollback terakhir
php artisan migrate:rollback

# Reset & jalankan ulang
php artisan migrate:fresh
```

---

## Nilai Status & Normalisasi

### `guests.status`

| Nilai | Keterangan | Diset Oleh |
|---|---|---|
| `menunggu` | Tamu baru, belum dilayani | Default setelah sync |
| `dilayani` | Tamu sudah di-assign | `AssignmentService::assign()` |

### Normalisasi `keperluan` (di laporan)

| Nilai Raw | Dinormalisasi |
|---|---|
| Mengandung `ppid` (case-insensitive) | `PPID` |
| Lainnya | `Pelayanan PST` |

### Normalisasi `jenis_layanan` (di laporan)

| Nilai Raw | Dinormalisasi |
|---|---|
| Mengandung `konsultasi` | `Konsultasi Statistik` |
| Mengandung `pustaka` | `Perpustakaan` |
| Mengandung `mikro` | `Data Mikro` |
| Kosong | `Lainnya` |
| Lainnya | `ucfirst(value)` |

---

## Import Database (MySQL)

Untuk setup MySQL dari awal, gunakan migrasi Laravel (rekomendasi):

```bash
php artisan migrate
```

Jika tersedia file SQL dump untuk restore data tertentu, konsultasikan dengan tim terkait sebelum melakukan import.

