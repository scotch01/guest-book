# 🐛 KNOWN ISSUES — Isu yang Diketahui

Dokumen ini mencatat isu-isu yang sudah diidentifikasi pada aplikasi **Guest Book BPS** beserta workaround atau status penanganannya.

---

## Isu Aktif

---

### [KI-001] Sync Berjalan Synchronous dalam HTTP Request

**Severity**: Medium  
**Status**: Open  
**Ditemukan**: 2026-05-04

**Deskripsi**:  
`SyncController` memanggil `Artisan::call('sync:guests')` secara synchronous dalam HTTP request. Jika spreadsheet memiliki ratusan/ribuan baris, request bisa timeout (error 504 di server produksi).

**Dampak**:  
- Petugas mendapatkan halaman putih / timeout jika data spreadsheet besar
- Tidak ada feedback progress sync

**Workaround**:  
Jalankan sync langsung dari terminal server:
```bash
php artisan sync:guests
```

**Solusi Jangka Panjang**:  
Dispatch ke Job Queue dengan `SyncGuestsJob::dispatch()` dan tampilkan status di UI.

---

### [KI-002] PDF Ekspor — Nama File Tidak Interpolasi dengan Benar

**Severity**: Low  
**Status**: Open  
**Ditemukan**: 2026-05-05  
**File**: `app/Http/Controllers/ReportController.php` baris 29

**Deskripsi**:  
Nama file PDF menggunakan single quotes sehingga variabel `$month` tidak diinterpolasi:
```php
// Kode saat ini (BUG)
return $pdf->stream('report-{$month}.pdf');

// Seharusnya
return $pdf->stream("report-{$month}.pdf");
```

**Dampak**:  
File PDF diunduh dengan nama literal `report-{$month}.pdf` alih-alih nama bulan yang sesungguhnya (contoh: `report-2026-05.pdf`).

**Workaround**:  
Tidak ada — nama file salah, tapi konten PDF tetap benar.

**Solusi**:  
Ganti single quote menjadi double quote pada baris 29 `ReportController.php`.

---

### [KI-003] `source_key` Tidak Update jika Data Google Form Diubah

**Severity**: Low  
**Status**: Open / By Design  
**Ditemukan**: 2026-04-29

**Deskripsi**:  
Deduplication berbasis `MD5(nama + tanggal + email + form_email)`. Jika seseorang mengedit respons Google Form setelah sync dilakukan, perubahan tersebut **tidak akan tercermin** di database karena `source_key` yang sama sudah ada.

**Dampak**:  
Data tamu di database bisa tidak sinkron dengan data terbaru di Google Sheets jika ada editing respons.

**Workaround**:  
- Edit data tamu langsung di database via Tinker/phpMyAdmin
- Atau hapus record dan sync ulang

**Solusi Jangka Panjang**:  
Tambahkan mekanisme "force update" yang mengabaikan deduplication, atau gunakan `updateOrCreate` dengan data terbaru.

---

### [KI-004] Tidak Ada Penanganan Error Saat Google Sheets API Gagal

**Severity**: Medium  
**Status**: Open  
**File**: `app/Console/Commands/SyncGuests.php`

**Deskripsi**:  
Jika Google Sheets API tidak dapat dijangkau (timeout, rate limit, credentials kedaluwarsa), command `sync:guests` akan throw exception tanpa error handling yang proper. SyncLock sudah di-update sebelum sync berjalan, sehingga petugas harus menunggu 30 menit lagi.

**Dampak**:  
- SyncLock ter-update meski sync gagal
- Petugas tidak mendapat pesan error yang jelas

**Workaround**:  
Jalankan sync langsung dari CLI untuk melihat error detail:
```bash
php artisan sync:guests
```

**Solusi Jangka Panjang**:  
Wrap koneksi Google API dalam try-catch, reset `SyncLock.last_run_at` jika sync gagal, dan tampilkan pesan error yang informatif.

---

### [KI-005] Laporan Hanya Menghitung Tamu yang Sudah Di-assign

**Severity**: Low  
**Status**: Open / By Design  
**File**: `app/Http/Controllers/ReportController.php`

**Deskripsi**:  
`ReportController` hanya menghitung tamu yang ada di tabel `guest_assignments` (sudah di-assign ke pegawai). Tamu yang belum di-assign tidak masuk dalam total laporan.

**Dampak**:  
Total tamu di laporan bisa lebih kecil dari total tamu sesungguhnya pada bulan tersebut.

**Workaround**:  
Lihat total tamu sesungguhnya di halaman `/guests?tab=history` dengan filter bulan yang sama.

**Solusi**:  
Pertimbangkan untuk menambahkan kolom "Total Pengunjung" (semua tamu, termasuk yang belum di-assign) vs "Total Dilayani" (sudah di-assign) dalam laporan.

---

### [KI-006] Tidak Ada Role / Permission System

**Severity**: Medium  
**Status**: Open / Planned  

**Deskripsi**:  
Semua pengguna yang login memiliki akses yang sama ke seluruh fitur aplikasi. Tidak ada pembedaan antara admin, petugas front-desk, dan supervisor.

**Dampak**:  
Semua user bisa: menghapus/nonaktifkan pegawai, menjalankan sync, melihat laporan.

**Solusi Jangka Panjang**:  
Implementasi role-based access control (RBAC) dengan package seperti `spatie/laravel-permission`.

---

## Isu Terselesaikan

---

### [FIXED-001] Race Condition pada Generate Nomor Antrian

**Severity**: High  
**Status**: Fixed (2026-04-29)

**Deskripsi**:  
Jika dua request sync berjalan bersamaan, bisa menghasilkan nomor antrian duplikat.

**Solusi yang Diterapkan**:  
Menggunakan `lockForUpdate()` dalam `QueueService::generate()` dan constraint `UNIQUE(queue_number, queue_date)` di database.

---

### [FIXED-002] Double-click Sync Menjalankan Sync Dua Kali

**Severity**: Medium  
**Status**: Fixed (2026-05-04)

**Deskripsi**:  
Klik cepat tombol Sync dua kali bisa memicu dua sync sekaligus.

**Solusi yang Diterapkan**:  
`SyncLock` di-update **sebelum** `Artisan::call()` dieksekusi di dalam `DB::transaction`. Ini memastikan request kedua langsung ditolak.

---

## Cara Melaporkan Isu Baru

Jika menemukan isu baru, dokumentasikan dengan format berikut:

```
**ID**: KI-XXX
**Severity**: Critical / High / Medium / Low
**Status**: Open
**Ditemukan**: YYYY-MM-DD
**File**: (jika relevan)

**Deskripsi**: Jelaskan isu secara detail.
**Dampak**: Apa efek dari isu ini.
**Langkah Reproduksi**: Cara mereproduksi isu.
**Workaround**: Solusi sementara (jika ada).
**Solusi**: Rencana perbaikan jangka panjang.
```
