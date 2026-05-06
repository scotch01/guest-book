# ✨ FEATURE — Dokumentasi Fitur Lengkap

Dokumen ini menjelaskan setiap fitur aplikasi **Guest Book BPS** secara detail beserta alur kerjanya.

---

## Daftar Fitur

1. [Autentikasi](#1-autentikasi)
2. [Dashboard & KPI](#2-dashboard--kpi)
3. [Sync Data dari Google Sheets](#3-sync-data-dari-google-sheets)
4. [Manajemen Tamu](#4-manajemen-tamu)
5. [Assign Pegawai ke Tamu](#5-assign-pegawai-ke-tamu)
6. [Sistem Antrian Otomatis](#6-sistem-antrian-otomatis)
7. [Manajemen Pegawai](#7-manajemen-pegawai)
8. [Laporan Bulanan](#8-laporan-bulanan)
9. [Ekspor PDF](#9-ekspor-pdf)
10. [Mekanisme Sync Lock (Cooldown)](#10-mekanisme-sync-lock-cooldown)

---

## 1. Autentikasi

**Diimplementasikan oleh**: Laravel Breeze  
**Route file**: `routes/auth.php`  
**Controller**: `app/Http/Controllers/Auth/`

### Fitur
- Login dengan email & password
- Logout
- Reset password via email
- Verifikasi email (opsional)
- "Remember Me" (persistent session)

### Alur Login
```
GET  /login  → Tampil form login
POST /login  → Validasi credentials → Redirect ke /dashboard
GET  /logout → Hapus session → Redirect ke /login
```

### Middleware
Semua route kecuali `/` dan auth routes dilindungi middleware `auth`.  
Dashboard memerlukan tambahan middleware `verified`.

---

## 2. Dashboard & KPI

**Controller**: `DashboardController@index`  
**View**: `resources/views/dashboard.blade.php`  
**Route**: `GET /dashboard`

### Fitur
Dashboard menampilkan statistik real-time untuk hari ini:

#### KPI Cards
| Metrik | Keterangan |
|---|---|
| Total Tamu Hari Ini | Jumlah tamu dengan `tanggal_kunjungan = today` |
| Tamu Terassign | Tamu yang sudah ditugaskan ke pegawai |
| Tamu Belum Dilayani | Total tamu - tamu terassign |
| Antrian Terakhir | Nomor antrian tertinggi hari ini |

#### Breakdown Layanan
- **PPID**: Jumlah tamu dengan keperluan PPID hari ini
- **PST**: Jumlah tamu dengan keperluan Pelayanan PST hari ini

#### Tabel Antrian Hari Ini
Menampilkan semua tamu hari ini beserta:
- Nomor antrian
- Nama tamu
- Keperluan
- Pegawai yang melayani (jika sudah di-assign)

#### Distribusi Pegawai
Grafik/tabel yang menampilkan berapa tamu yang dilayani setiap pegawai hari ini.

#### Tombol Sync
Tombol trigger sync manual dengan indikator cooldown (lihat [Fitur #10](#10-mekanisme-sync-lock-cooldown)).

### Query yang Digunakan
```php
// Total tamu hari ini
Guest::whereDate('tanggal_kunjungan', today())->count()

// Antrian + pegawai dalam satu eager load
Queue::with(['guest.assignment.employee'])
    ->where('queue_date', today())
    ->orderBy('queue_number')
    ->get()

// Distribusi per pegawai (DB::table, join 3 tabel)
DB::table('guest_assignments')
    ->join('guests', ...)
    ->join('employees', ...)
    ->whereDate('guests.tanggal_kunjungan', today())
    ->select('employees.nama', DB::raw('COUNT(*) as total'))
    ->groupBy('employees.nama')
    ->get()
```

---

## 3. Sync Data dari Google Sheets

**Controller**: `SyncController@run`  
**Command**: `SyncGuests` (`php artisan sync:guests`)  
**Route**: `POST /sync/guests`

### Fitur
- Menarik data tamu dari Google Sheets (respons Google Form) ke database lokal
- Deduplication berbasis `source_key` (MD5 hash)
- Otomatis generate nomor antrian untuk tamu baru

### Prasyarat
- Service account Google dengan akses ke spreadsheet
- Konfigurasi `GOOGLE_SHEETS_ID` dan `GOOGLE_SHEETS_RANGE` di `.env`
- File credentials JSON di `storage/app/google-credentials.json`

### Alur Kerja
```
1. Petugas klik tombol "Sync" di dashboard
2. SyncController@run dipanggil
3. Cek SyncLock (cooldown 30 menit)
   ├── Masih cooldown → Error, tampilkan sisa waktu
   └── Sudah bisa → Update last_run_at, jalankan sync
4. Artisan::call('sync:guests')
5. SyncGuests::handle()
   ├── Koneksi ke Google Sheets API
   ├── Ambil semua baris dari range yang dikonfigurasi
   └── Untuk setiap baris:
       ├── mapRow() → normalisasi & validasi data
       ├── Hitung source_key = MD5(nama+tanggal+email+form_email)
       ├── Guest::firstOrCreate(source_key, data)
       └── Jika guest baru && tanggal_kunjungan ada
           └── QueueService::generate(guest_id, tanggal)
```

### Mapping Kolom Google Sheets

| Index | Kolom Spreadsheet | Kolom Database |
|---|---|---|
| 0 | Timestamp | `form_timestamp` |
| 1 | Nama | `nama` |
| 2 | Nomor Identitas | `no_identitas` |
| 3 | Jenis Kelamin | `jenis_kelamin` |
| 4 | Umur | `umur` |
| 5 | Pendidikan | `pendidikan` |
| 6 | Pekerjaan | `pekerjaan` |
| 7 | Kategori Instansi | `kategori_instansi` |
| 8 | Nama Instansi | `nama_instansi` |
| 9 | Alamat | `alamat` |
| 10 | Telepon | `telepon` |
| 11 | Email | `email` |
| 12 | Tanggal Kunjungan | `tanggal_kunjungan` |
| 13 | Keperluan | `keperluan` |
| 14 | Jenis Layanan | `jenis_layanan` |
| 15 | Jenis Data | `jenis_data` |
| 16 | Level Data | `level_data` |
| 17 | Email Form (akun Google) | `form_email` |

---

## 4. Manajemen Tamu

**Controller**: `GuestController`  
**Views**: `resources/views/guests/`  
**Routes**:
- `GET /guests` → daftar tamu
- `GET /guests/{guest}` → detail tamu

### Fitur Daftar Tamu (`index`)

Halaman daftar tamu memiliki dua **tab**:

#### Tab: Hari Ini
- Menampilkan tamu dengan `tanggal_kunjungan = today()`
- Tampil dalam tabel dengan pagination (default 20 per halaman)
- Setiap baris menampilkan: nama, keperluan, status, pegawai yang melayani, nomor antrian

#### Tab: Riwayat
- Menampilkan tamu dengan `tanggal_kunjungan < today()` atau null
- Filter berdasarkan rentang tanggal (`start_date`, `end_date`)
- Validasi: format tanggal, end_date tidak boleh sebelum start_date

#### Fitur Umum
- **Assign langsung** dari tabel: dropdown pegawai aktif + tombol assign
- **Pagination** dengan `per_page` query param
- **Filter tab** via query param `?tab=today` atau `?tab=history`

### Fitur Detail Tamu (`show`)

Menampilkan semua informasi tamu:
- Data personal (nama, identitas, umur, dll)
- Data kontak (telepon, email, alamat)
- Data kunjungan (tanggal, keperluan, layanan)
- Status & informasi assignment
- Nomor antrian

---

## 5. Assign Pegawai ke Tamu

**Controller**: `AssignmentController@store`  
**Service**: `AssignmentService@assign`  
**Route**: `POST /assign`

### Fitur
- Menugaskan pegawai aktif untuk melayani tamu tertentu
- Idempotent: assign ulang ke tamu yang sudah di-assign akan diabaikan
- Otomatis update status tamu dari `menunggu` → `dilayani`

### Validasi
```php
$request->validate([
    'guest_id'    => 'required|exists:guests,id',
    'employee_id' => 'required|exists:employees,id',
]);
```

### Alur Kerja
```
POST /assign (guest_id, employee_id)
    ↓
Validasi input
    ↓
AssignmentService::assign(guestId, employeeId)
    ↓
DB::transaction {
    Cek existing assignment
    ├── Ada → Return existing (idempotent)
    └── Tidak ada →
        ├── Buat GuestAssignment
        └── Update Guest.status = 'dilayani'
}
    ↓
Redirect back + flash 'Tamu berhasil di-assign'
```

---

## 6. Sistem Antrian Otomatis

**Service**: `QueueService@generate`  
**Model**: `Queue`

### Fitur
- Nomor antrian di-generate otomatis saat tamu baru disinkronisasi
- Nomor antrian bersifat sekuensial dan unik per tanggal (1, 2, 3, ...)
- Race-condition safe menggunakan `lockForUpdate()`

### Alur Generate Antrian
```
QueueService::generate(guestId, date)
    ↓
Jika date kosong → return null (antrian tidak dibuat)
    ↓
DB::transaction {
    $last = Queue::where('queue_date', $date)->lockForUpdate()->max('queue_number')
    $next = ($last ?? 0) + 1
    Queue::create([guest_id, queue_number: $next, queue_date: $date])
}
```

### Tampilan di Dashboard
Dashboard menampilkan semua antrian hari ini dalam urutan nomor antrian, lengkap dengan informasi pegawai yang melayani.

---

## 7. Manajemen Pegawai

**Controller**: `EmployeeController`  
**Views**: `resources/views/employees/`  
**Routes**: `Route::resource('employees', ...)` + `PATCH /employees/{id}/activate`

### Fitur CRUD

| Aksi | Route | Keterangan |
|---|---|---|
| Lihat daftar | `GET /employees` | Dengan pagination |
| Tambah form | `GET /employees/create` | |
| Simpan | `POST /employees` | Validasi nama (required) |
| Edit form | `GET /employees/{id}/edit` | |
| Update | `PUT /employees/{id}` | Validasi nama (required) |
| Nonaktifkan | `DELETE /employees/{id}` | Soft deactivate (is_active = 0) |
| Aktifkan | `PATCH /employees/{id}/activate` | Re-activate (is_active = 1) |

### Validasi
```php
$request->validate([
    'nama' => 'required|string|max:255',
    'nip'  => 'nullable|string|max:255',
]);
```

### Catatan
- Pegawai tidak benar-benar dihapus dari database (soft-deactivate)
- Pegawai nonaktif tidak muncul di dropdown assignment tamu
- Pagination default 10 per halaman

---

## 8. Laporan Bulanan

**Controller**: `ReportController@monthly`  
**View**: `resources/views/reports/monthly.blade.php`  
**Route**: `GET /report/monthly?month=2026-05`

### Fitur
Laporan bulanan yang dapat difilter per bulan, menampilkan:

1. **Total tamu** yang sudah di-assign (dilayani) pada bulan tersebut
2. **Rekap per pegawai**: berapa tamu yang dilayani setiap pegawai
3. **Breakdown PPID**: jumlah tamu dengan keperluan PPID
4. **Breakdown PST**: jumlah tamu PST per jenis layanan (Konsultasi Statistik, Perpustakaan, Data Mikro, dll)

> **Catatan**: Laporan hanya menghitung tamu yang **sudah di-assign** (ada di tabel `guest_assignments`). Tamu yang masih berstatus `menunggu` tidak masuk laporan.

### Query Utama
```php
// Base query: join 3 tabel, filter bulan & tahun
$base = DB::table('guest_assignments')
    ->join('guests', 'guests.id', '=', 'guest_assignments.guest_id')
    ->join('employees', 'employees.id', '=', 'guest_assignments.employee_id')
    ->whereMonth('guests.tanggal_kunjungan', $monthNum)
    ->whereYear('guests.tanggal_kunjungan', $yearNum);
```

### Normalisasi Data Laporan
Nilai `keperluan` dan `jenis_layanan` dari database dinormalisasi sebelum ditampilkan (case-insensitive matching) untuk memastikan konsistensi laporan meskipun data input bervariasi.

---

## 9. Ekspor PDF

**Controller**: `ReportController@monthlyPdf`  
**View**: `resources/views/reports/monthly-pdf.blade.php`  
**Route**: `GET /report/monthly/pdf?month=2026-05`  
**Library**: `barryvdh/laravel-dompdf`

### Fitur
- Menghasilkan PDF laporan bulanan dengan format yang sama seperti laporan web
- Langsung di-stream ke browser (download otomatis)
- Nama file: `report-{month}.pdf`

### Alur Kerja
```
GET /report/monthly/pdf?month=2026-05
    ↓
ReportController@monthlyPdf
    ↓
getReportData($month) → data laporan sama dengan web
    ↓
Pdf::loadView('reports.monthly-pdf', $data)
    ↓
$pdf->stream('report-{$month}.pdf')
```

---

## 10. Mekanisme Sync Lock (Cooldown)

**Model**: `SyncLock`  
**Tabel**: `sync_locks`  
**Digunakan di**: `SyncController`, `DashboardController`

### Fitur
Mencegah petugas menekan tombol Sync berulang kali dalam waktu singkat:
- Cooldown period: **30 menit** setelah sync terakhir berhasil
- Dashboard menampilkan countdown sisa waktu cooldown
- Tombol Sync di-disable secara visual selama cooldown aktif

### Logika Cooldown
```
SyncLock::where('key', 'guests')->first()
    ↓
Jika last_run_at ada:
    next_available = last_run_at + 30 menit
    Jika now() < next_available:
        canSync = false
        remainingSeconds = next_available - now()
    Jika now() >= next_available:
        canSync = true
Jika last_run_at null:
    canSync = true
```

### Variable yang Dikirim ke View
| Variable | Tipe | Keterangan |
|---|---|---|
| `canSync` | bool | Apakah sync diizinkan |
| `lastSync` | Carbon\|null | Waktu sync terakhir |
| `nextAvailableAt` | Carbon\|null | Kapan bisa sync lagi |
| `remainingSeconds` | int | Sisa detik cooldown |

---

## Ringkasan Halaman / View

| View | Route | Keterangan |
|---|---|---|
| `welcome.blade.php` | `/` (redirect) | Landing page (redirect ke login) |
| `dashboard.blade.php` | `/dashboard` | Dashboard KPI + antrian hari ini |
| `guests/index.blade.php` | `/guests` | Daftar tamu (tab: hari ini / riwayat) |
| `guests/show.blade.php` | `/guests/{id}` | Detail tamu |
| `employees/index.blade.php` | `/employees` | Daftar pegawai |
| `employees/create.blade.php` | `/employees/create` | Form tambah pegawai |
| `employees/edit.blade.php` | `/employees/{id}/edit` | Form edit pegawai |
| `reports/monthly.blade.php` | `/report/monthly` | Laporan bulanan (web) |
| `reports/monthly-pdf.blade.php` | `/report/monthly/pdf` | Template PDF laporan |
| `profile/edit.blade.php` | `/profile` | Edit profil pengguna |
