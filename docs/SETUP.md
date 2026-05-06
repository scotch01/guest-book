# ⚙️ SETUP — Panduan Instalasi Lokal

Dokumen ini menjelaskan cara menyiapkan lingkungan pengembangan lokal untuk aplikasi **Guest Book BPS**.

---

## Prasyarat

Pastikan perangkat yang digunakan sudah terinstal:

| Software | Versi Minimal | Keterangan |
|---|---|---|
| **PHP** | 8.2 | Ekstensi wajib: `pdo_mysql`, `openssl`, `mbstring`, `curl`, `zip`, `xml` |
| **Composer** | 2.x | Package manager PHP |
| **Node.js** | 18.x | Untuk build frontend (Vite) |
| **npm** | 9.x | Bundled dengan Node.js |
| **Git** | - | Version control |

> Untuk pengembangan lokal di Windows, disarankan menggunakan [Laragon](https://laragon.org/) — sudah termasuk PHP, MySQL, dan Composer dalam satu paket.

---

## 1. Clone Repository

```bash
git clone <url-repository> guest-book
cd guest-book
```

---

## 2. Install Dependensi

```bash
composer install
npm install
```

---

## 3. Konfigurasi Environment

```bash
cp .env.example .env
php artisan key:generate
```

Buka file `.env` dan sesuaikan:

```dotenv
APP_NAME="Guest Book BPS"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=guest_book
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=database
QUEUE_CONNECTION=database
CACHE_STORE=database
```

---

## 4. Setup Database

```bash
# Pastikan service MySQL sudah berjalan dan database 'guest_book' telah dibuat
php artisan migrate
```

---

## 5. Konfigurasi Google Sheets API

Fitur sync data tamu membutuhkan akses ke Google Sheets API.

### 5a. Buat Service Account Google

1. Buka [Google Cloud Console](https://console.cloud.google.com/)
2. Buat atau pilih project yang sudah ada
3. Aktifkan **Google Sheets API** → `APIs & Services > Library`
4. Buat **Service Account** → `APIs & Services > Credentials > Create Credentials > Service Account`
   - Role: **Viewer**
5. Download credentials JSON → `Keys > Add Key > Create new key > JSON`

### 5b. Simpan Credentials

Letakkan file JSON credentials di:
```
storage/app/google-credentials.json
```

> ⚠️ Pastikan file ini sudah masuk `.gitignore`. Jangan pernah di-commit ke repository.

### 5c. Tambahkan ke .env

```dotenv
GOOGLE_SHEETS_ID=your_spreadsheet_id_here
GOOGLE_SHEETS_RANGE=NamaSheet!A2:R
```

ID Spreadsheet bisa ditemukan di URL:
`https://docs.google.com/spreadsheets/d/**[ID_DI_SINI]**/edit`

### 5d. Pastikan `config/services.php` memiliki:

```php
'google' => [
    'credentials' => storage_path('app/google-credentials.json'),
],
```

### 5e. Bagikan Spreadsheet ke Service Account

1. Buka spreadsheet Google
2. Klik **Share**
3. Tambahkan email service account (field `client_email` di file JSON)
4. Permission: **Viewer**

---

## 6. Build Frontend

```bash
npm run build
```

Untuk pengembangan dengan hot reload:
```bash
npm run dev
```

---

## 7. Jalankan Aplikasi

### Mode Cepat (Semua Service Sekaligus)

```bash
composer run dev
```

Perintah ini menjalankan sekaligus:
- PHP dev server (`php artisan serve`)
- Queue worker (`php artisan queue:listen`)
- Log viewer (`php artisan pail`)
- Vite dev server (`npm run dev`)

### Mode Manual (Terminal Terpisah)

```bash
# Terminal 1
php artisan serve

# Terminal 2 (wajib untuk fitur sync)
php artisan queue:listen --tries=1
```

Akses di: **http://localhost:8000**

---

## 8. Buat Akun Admin

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

---

## Troubleshooting

| Error | Solusi |
|---|---|
| `Class "Barryvdh\DomPDF\Facade\Pdf" not found` | Jalankan `composer require barryvdh/laravel-dompdf` |
| Google API `invalid_grant` | Pastikan waktu sistem sinkron. Cek apakah credentials JSON masih valid |
| Queue tidak berjalan | Pastikan `QUEUE_CONNECTION=database` dan `php artisan queue:listen` aktif |
| MySQL `Access denied for user` | Cek `DB_USERNAME` dan `DB_PASSWORD` di `.env` |
