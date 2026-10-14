# Panduan Deploy Laravel ke Render

Project ini telah dikonfigurasi penuh agar siap di-deploy ke **Render** menggunakan arsitektur container **Docker (PHP 8.3 + Nginx + PHP-FPM + Vite Build)** yang ringan, cepat, dan hemat memori (sangat cocok untuk paket Free Tier Render).

---

## 🛠️ File Konfigurasi yang Telah Dibuat & Disesuaikan

1. **[Dockerfile](file:///d:/Project/jordo/pelatihan-karyawan/Dockerfile)**:
   - **Stage 1 (Node 22)**: Melakukan kompilasi aset frontend Vite (`npm run build`).
   - **Stage 2 (PHP 8.3-FPM Alpine)**: Nginx + PHP-FPM + Ekstensi lengkap (`pdo`, `pdo_pgsql`, `pdo_mysql`, `gd`, `zip`, `opcache`, `intl`, `bcmath`, `pcntl`).
2. **[docker/entrypoint.sh](file:///d:/Project/jordo/pelatihan-karyawan/docker/entrypoint.sh)**:
   - Otomatis menyesuaikan port Nginx ke port dinamis Render (`$PORT` / `10000`).
   - Otomatis menjalankan migrasi database (`php artisan migrate --force`) jika `RUN_MIGRATIONS=true`.
   - Otomatis menjalankan seeder akun awal jika `RUN_SEEDERS=true`.
   - Melakukan cache config, route, dan view untuk optimasi production.
3. **[docker/nginx.conf](file:///d:/Project/jordo/pelatihan-karyawan/docker/nginx.conf)**: Konfigurasi server block Nginx production khusus Laravel.
4. **[docker/php.ini](file:///d:/Project/jordo/pelatihan-karyawan/docker/php.ini)**: Konfigurasi Opcache dan memory limit.
5. **[docker/supervisord.conf](file:///d:/Project/jordo/pelatihan-karyawan/docker/supervisord.conf)**: Menjalankan Nginx & PHP-FPM secara bersamaan dalam 1 container.
6. **[render.yaml](file:///d:/Project/jordo/pelatihan-karyawan/render.yaml)**: Blueprint Infrastructure-as-Code untuk deploy 1-klik (Web Service + Database PostgreSQL gratis).
7. **[bootstrap/app.php](file:///d:/Project/jordo/pelatihan-karyawan/bootstrap/app.php)** & **[app/Providers/AppServiceProvider.php](file:///d:/Project/jordo/pelatihan-karyawan/app/Providers/AppServiceProvider.php)**:
   - Dikonfigurasi dengan `trustProxies(at: '*')` dan `URL::forceScheme('https')` agar URL asset, form, redirect, dan session cookie aman di balik SSL proxy Render.
8. **[config/database.php](file:///d:/Project/jordo/pelatihan-karyawan/config/database.php)**:
   - Ditambahkan dukungan variabel `DATABASE_URL` bawaan PostgreSQL Render.

---

## 🚀 Cara 1: Deploy Otomatis Menggunakan Blueprint (Sangat Direkomendasikan)

Render menyediakan fitur **Blueprint** yang akan membaca file `render.yaml`. Fitur ini otomatis membuat Web Service dan Database PostgreSQL gratis sekaligus menghubungkan keduanya.

### Langkah-langkah:
1. **Commit dan Push project ke GitHub / GitLab**:
   ```bash
   git add .
   git commit -m "Setup project untuk deployment di Render"
   git push origin main
   ```
2. Buka dashboard [Render](https://dashboard.render.com/).
3. Klik tombol **New +** di pojok kanan atas, lalu pilih **Blueprint**.
4. Hubungkan repositori GitHub/GitLab Anda.
5. Render akan mendeteksi `render.yaml`:
   - **pelatihan-karyawan** (Web Service Docker)
   - **pelatihan-db** (PostgreSQL Free Tier)
6. Klik **Apply**.
7. Tunggu proses build selesai. Render akan mengompilasi Vite, menginstal dependensi PHP, membuat database, dan menjalankan migrasi serta seeder secara otomatis.

---

## 📋 Cara 2: Deploy Manual Melalui Dashboard Render

Jika ingin membuat service satu per satu secara manual:

### Langkah A: Buat Database PostgreSQL
1. Di Dashboard Render, klik **New +** -> **PostgreSQL**.
2. Beri nama database, misal: `pelatihan-db`.
3. Pilih Region (disarankan **Singapore** untuk akses cepat dari Indonesia).
4. Pilih Plan **Free**.
5. Klik **Create Database**.
6. Setelah database aktif, salin nilai **Internal Database URL** (misal: `postgresql://user:pass@dpg-xxx:5432/dbname`).

### Langkah B: Buat Web Service
1. Klik **New +** -> **Web Service**.
2. Pilih repository project Anda.
3. Isi informasi berikut:
   - **Name**: `pelatihan-karyawan` (atau nama pilihan Anda)
   - **Region**: Sama dengan region database (misal: **Singapore**)
   - **Branch**: `main`
   - **Runtime**: Pilih **Docker** *(Bukan PHP / Node)*
   - **Plan**: **Free**
4. Gulir ke bagian **Environment Variables** dan tambahkan key-value berikut:

| Key | Value | Keterangan |
|---|---|---|
| `APP_NAME` | `Sistem Pelatihan Karyawan` | Nama aplikasi |
| `APP_ENV` | `production` | Mode production |
| `APP_DEBUG` | `false` | Matikan debug demi keamanan |
| `APP_KEY` | *(Generate via `php artisan key:generate --show`)* | Kunci enkripsi aplikasi |
| `APP_URL` | `https://<nama-service>.onrender.com` | URL Render web service Anda |
| `LOG_CHANNEL` | `stderr` | Agar log tampil di tab Logs Render |
| `DB_CONNECTION` | `pgsql` | Driver database PostgreSQL |
| `DATABASE_URL` | *(Tempel Internal Database URL dari Langkah A)* | Koneksi database otomatis |
| `RUN_MIGRATIONS` | `true` | Menjalankan `php artisan migrate --force` |
| `RUN_SEEDERS` | `true` | Menjalankan seeder user/departemen pada deploy pertama |
| `FORCE_HTTPS` | `true` | Memaksa HTTPS |

5. Klik **Create Web Service**.

---

## 🔑 Akun Default Setelah Seeder Dijalankan

Setelah aplikasi selesai dideploy dengan `RUN_SEEDERS=true`, Anda dapat langsung login menggunakan akun berikut:

| Role | Email | Password |
|---|---|---|
| **Superadmin** | `superadmin@pelatihan.com` | `password123` |
| **HRD** | `hrd@pelatihan.com` | `password123` |
| **Admin IT** | `admin.it@pelatihan.com` | `password123` |
| **Admin Engineering** | `admin.engineering@pelatihan.com` | `password123` |

> 💡 **Tips Keamanan**: Setelah deployment pertama berhasil dan data terisi, Anda dapat mengubah `RUN_SEEDERS` menjadi `false` di tab Environment Variables agar seeder tidak dijalankan ulang di deploy berikutnya.

---

## 💡 Catatan Tambahan Terkait Render Free Tier

1. **Spin-down Saat Idle**: Pada paket Free Render, web service akan "tidur" jika tidak menerima request selama 15 menit. Request pertama setelah tidur membutuhkan waktu ~30-50 detik untuk bangun (cold start).
2. **Render Shell (Akses Artisan)**: Anda dapat menjalankan perintah artisan langsung melalui tab **Shell** di dashboard Render, contoh:
   ```bash
   php artisan cache:clear
   php artisan route:list
   ```
3. **Masa Aktif PostgreSQL Free**: Database PostgreSQL Free Tier di Render memiliki masa aktif 30 hari. Jika ingin gratis permanen tanpa batas waktu, Anda juga dapat menghubungkan database eksternal seperti [Supabase](https://supabase.com), [Neon.tech](https://neon.tech), atau [Aiven](https://aiven.io) cukup dengan menempelkan connection string-nya ke variabel `DATABASE_URL`.
