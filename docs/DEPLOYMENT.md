# 🚀 DEPLOYMENT — Panduan Deploy ke Shared / Cloud Hosting

Dokumen ini menjelaskan cara men-deploy aplikasi **Guest Book BPS** ke layanan **shared hosting atau cloud hosting** berbasis cPanel (seperti Niagahoster, Hostinger, Domainesia, dll).

> Pendekatan ini **tidak memerlukan akses root/SSH penuh**. Sebagian besar langkah dilakukan melalui cPanel dan FTP/SFTP.

---

## Gambaran Umum Proses

```
Mesin Lokal                    Shared Hosting
─────────────                  ──────────────
1. Build frontend (npm)
2. Siapkan file produksi   →   3. Upload via FTP/SFTP
                               4. Buat database MySQL di cPanel
                               5. Konfigurasi .env
                               6. php artisan migrate (SSH/Terminal)
                               7. Konfigurasi cron job di cPanel
                               8. Arahkan document root ke /public
```

---

## Langkah-Langkah

### 1. Build Frontend di Lokal

Sebelum upload, build asset frontend terlebih dahulu di mesin lokal:

```bash
npm install
npm run build
```

Pastikan folder `public/build/` sudah berisi hasil build Vite.

---

### 2. Siapkan File untuk Upload

Folder dan file yang perlu diupload ke server:

```
✅ Upload:
├── app/
├── bootstrap/
├── config/
├── database/
├── public/
├── resources/
├── routes/
├── storage/
├── vendor/          ← hasil composer install
├── artisan
├── composer.json
├── composer.lock
└── .htaccess        ← jika ada di root

❌ Jangan upload:
├── node_modules/
├── .env             ← buat langsung di server
└── .git/
```

> **Tips**: Jalankan `composer install --no-dev --optimize-autoloader` di lokal sebelum upload agar `vendor/` sudah siap untuk produksi.

---

### 3. Upload ke Server

**Via File Manager cPanel:**
1. Login ke cPanel → **File Manager**
2. Navigasi ke folder yang akan digunakan (misal: `public_html/guestbook/`)
3. Upload semua file (bisa dalam bentuk ZIP, lalu extract di sana)

**Via FTP/SFTP:**
Gunakan aplikasi seperti [FileZilla](https://filezilla-project.org/):
- **Host**: domain atau IP hosting
- **Username/Password**: dari cPanel → FTP Accounts
- Upload semua file ke direktori yang ditentukan

---

### 4. Buat Database MySQL di cPanel

1. Login ke cPanel → **MySQL Databases**
2. Buat database baru (contoh: `user_guestbook`)
3. Buat MySQL user baru dengan password kuat
4. Tambahkan user ke database dengan privilege **All Privileges**
5. Catat nama database, username, dan password — akan dipakai di `.env`

---

### 5. Buat File `.env` di Server

Buat file `.env` di root project di server (bukan di `public/`). Isi minimal yang diperlukan:

```dotenv
APP_NAME="Guest Book BPS"
APP_ENV=production
APP_KEY=                        # akan diisi oleh artisan key:generate
APP_DEBUG=false
APP_URL=https://domain-anda.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=nama_database
DB_USERNAME=nama_user_db
DB_PASSWORD=password_db

SESSION_DRIVER=database
QUEUE_CONNECTION=database
CACHE_STORE=database
LOG_CHANNEL=single
LOG_LEVEL=error

GOOGLE_SHEETS_ID=your_spreadsheet_id_here
GOOGLE_SHEETS_RANGE=NamaSheet!A2:R
```

> Sesuaikan `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD` dengan yang dibuat di langkah 4.

---

### 6. Jalankan Perintah Artisan via SSH / Terminal cPanel

Buka **Terminal** di cPanel (atau SSH jika tersedia), lalu navigasi ke folder project:

```bash
cd ~/public_html/guestbook    # sesuaikan path-nya
```

Jalankan berurutan:

```bash
# Generate application key
php artisan key:generate

# Jalankan migrasi database
php artisan migrate --force

# Optimasi untuk produksi
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

### 7. Upload Google Credentials

Upload file JSON credentials service account ke:
```
storage/app/google-credentials.json
```

Lakukan via File Manager cPanel atau FTP. Pastikan file ini **tidak dapat diakses publik**.

---

### 8. Atur Document Root ke Folder `public/`

Agar URL langsung mengarah ke folder `public/` Laravel (bukan root project):

**Opsi A — Subdomain/Add-on Domain di cPanel:**
1. cPanel → **Domains** → **Create A New Domain** atau **Subdomains**
2. Saat mengisi **Document Root**, arahkan ke: `public_html/guestbook/public`

**Opsi B — Menggunakan `public_html` Langsung:**
Jika project diletakkan langsung di `public_html/`, buat file `.htaccess` di `public_html/` dengan isi:

```apache
RewriteEngine On
RewriteRule ^(.*)$ public/$1 [L]
```

Atau symlink folder `public/` ke `public_html/` jika hosting mendukung.

---

### 9. Setup Cron Job untuk Queue Worker

Karena shared hosting tidak mendukung Supervisor, gunakan **Cron Job di cPanel** sebagai pengganti queue worker:

1. cPanel → **Cron Jobs**
2. Set interval: **Setiap menit** (`* * * * *`)
3. Command:

```bash
php /home/username/public_html/guestbook/artisan queue:work --stop-when-empty --tries=3 2>/dev/null
```

> Ganti `/home/username/public_html/guestbook/` dengan path absolut project Anda.

Untuk mengetahui path absolut, jalankan di Terminal cPanel:
```bash
pwd
```

---

### 10. Aktifkan HTTPS

Hampir semua shared/cloud hosting modern sudah menyediakan SSL gratis:

1. cPanel → **SSL/TLS** → **Let's Encrypt SSL** (atau fitur serupa)
2. Aktifkan untuk domain yang digunakan
3. Pastikan `.env` sudah menggunakan `APP_URL=https://...`

---

## Checklist Deploy

- [ ] `npm run build` sudah dijalankan di lokal
- [ ] `vendor/` sudah ada (hasil `composer install --no-dev`)
- [ ] Semua file sudah terupload ke server
- [ ] Database MySQL sudah dibuat di cPanel
- [ ] File `.env` sudah dikonfigurasi di server
- [ ] `php artisan key:generate` sudah dijalankan
- [ ] `php artisan migrate --force` berhasil
- [ ] File Google credentials sudah diupload ke `storage/app/`
- [ ] Document root sudah diarahkan ke folder `public/`
- [ ] Cron job queue sudah dikonfigurasi di cPanel
- [ ] HTTPS aktif
- [ ] `APP_DEBUG=false` di `.env`

---

## Update / Redeploy

Saat ada perubahan kode:

1. Di lokal: jalankan `npm run build` (jika ada perubahan frontend)
2. Upload file yang berubah via FTP/File Manager
3. Jika ada perubahan `composer.json`: upload ulang folder `vendor/`
4. Jika ada migrasi baru: jalankan `php artisan migrate --force` via Terminal cPanel
5. Bersihkan cache: `php artisan optimize:clear` → lalu `php artisan optimize`

---

## Catatan Penting untuk Shared Hosting

> [!NOTE]
> Queue worker berjalan via Cron Job (setiap menit), bukan Supervisor. Ini berarti ada delay maksimal 1 menit antara request sync dan eksekusi. Untuk dataset kecil-menengah, ini sudah cukup memadai.

> [!WARNING]
> Beberapa shared hosting membatasi waktu eksekusi PHP (max execution time). Jika spreadsheet sangat besar, sync bisa timeout. Pertimbangkan untuk membagi range data menjadi lebih kecil.

> [!CAUTION]
> Jangan pernah upload file `.env` atau `storage/app/google-credentials.json` ke repository Git. Pastikan keduanya ada di `.gitignore`.
