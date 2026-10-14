# Panduan Deploy Laravel ke Vercel + Neon.tech
*(100% GRATIS & BENAR-BENAR TANPA KARTU KREDIT)*

Setup ini menggunakan **Vercel** untuk hosting aplikasi web serverless (PHP 8.3 + Vite) dan **Neon.tech** untuk database PostgreSQL cloud gratis selamanya. Keduanya dapat didaftar langsung menggunakan akun **GitHub** tanpa perlu memasukkan kartu kredit.

---

## 🛠️ File Konfigurasi yang Telah Disiapkan di Project

1. **[api/index.php](file:///d:/Project/jordo/pelatihan-karyawan/api/index.php)**: Jembatan (*bridge*) serverless function Vercel ke `public/index.php` Laravel, sekaligus menyiapkan folder penyimpanan sementara di `/tmp`.
2. **[vercel.json](file:///d:/Project/jordo/pelatihan-karyawan/vercel.json)**: Konfigurasi routing Vercel, runtime PHP 8.3 (`vercel-php@0.7.4`), dan pemetaan cache serverless.
3. **[.vercelignore](file:///d:/Project/jordo/pelatihan-karyawan/.vercelignore)**: Mencegah file local/vendor diunggah agar proses deploy cepat.
4. **[config/database.php](file:///d:/Project/jordo/pelatihan-karyawan/config/database.php)**: Sudah mendukung variabel `DATABASE_URL` dari Neon.
5. **[bootstrap/app.php](file:///d:/Project/jordo/pelatihan-karyawan/bootstrap/app.php)**: Sudah dikonfigurasi `trustProxies(at: '*')` untuk reverse proxy Vercel.

---

## 📌 Langkah 1: Buat Database di Neon.tech (Gratis Tanpa Kartu)

1. Buka situs **[neon.tech](https://neon.tech)**.
2. Klik **Sign Up** -> pilih **Continue with GitHub**.
3. Buat project baru:
   - **Project name**: `pelatihan-karyawan`
   - **Region**: Pilih **Singapore (ap-southeast-1)** *(paling cepat untuk Indonesia)*
   - **Postgres version**: `16` atau `17` (default)
4. Klik **Create Project**.
5. Pada Dashboard Neon, Anda akan langsung melihat kotak **Connection Details**.
6. Pilih tab **Connection string** (pastikan centang *Pooled connection* atau *Direct connection*). Salin URL-nya, contohnya berformat:
   ```text
   postgresql://neondb_owner:npg_xxxx@ep-cool-fog-123456.ap-southeast-1.aws.neon.tech/neondb?sslmode=require
   ```

---

## 📌 Langkah 2: Jalankan Migrasi & Seeder dari Terminal Lokal

Karena Neon adalah database cloud publik dengan enkripsi SSL, Anda bisa langsung mengisi tabel dan data awal dari laptop Anda ke Neon tanpa perlu tools tambahan!

1. Buka terminal (PowerShell) di folder project ini:
2. Jalankan perintah migrasi & seeder menggunakan URL Neon yang Anda salin tadi:

```powershell
$env:DATABASE_URL="postgresql://neondb_owner:password_anda@ep-xxx.ap-southeast-1.aws.neon.tech/neondb?sslmode=require"
$env:DB_CONNECTION="pgsql"

php artisan migrate --force
php artisan db:seed --force
```

*(Ganti isi connection string di atas dengan URL asli dari dashboard Neon Anda).*

Setelah perintah di atas selesai, semua tabel dan user awal (`superadmin@pelatihan.com`, `hrd@pelatihan.com`, dll.) sudah tersimpan aman di database cloud Neon!

---

## 📌 Langkah 3: Push ke GitHub & Deploy di Vercel

1. **Commit & Push konfigurasi baru ke GitHub**:
   ```bash
   git add .
   git commit -m "Setup deployment Vercel dan Neon"
   git push origin main
   ```

2. Buka **[vercel.com](https://vercel.com)** dan login menggunakan **GitHub** (*Gratis, no card required*).

3. Klik tombol **Add New...** di pojok kanan atas -> pilih **Project**.

4. Cari repositori **pelatihan-karyawan**, lalu klik **Import**.

5. Di halaman konfigurasi deployment Vercel:
   - **Framework Preset**: Biarkan `Other` (jangan diubah).
   - **Root Directory**: `./` (default).
   - Buka accordion **Environment Variables**, lalu tambahkan variabel berikut:

| Name (Key) | Value |
|---|---|
| `APP_NAME` | `Sistem Pelatihan Karyawan` |
| `APP_ENV` | `production` |
| `APP_DEBUG` | `false` |
| `APP_KEY` | `base64:eeC0Fi7qt5qLipHd0Mbs4W/M+jfuGc3WcubuoEYepC0=` |
| `DB_CONNECTION` | `pgsql` |
| `DATABASE_URL` | *(Tempelkan connection string dari Neon.tech)* |

6. Klik tombol **Deploy**.

7. Tunggu sekitar 1 - 2 menit. Vercel akan otomatis mengompilasi CSS/JS Vite dan menyiapkan fungsi PHP serverless.

8. Setelah selesai, Vercel akan memberikan link domain Anda (misal: `https://pelatihan-karyawan-xxx.vercel.app`).

9. **(Opsional - Terakhir)**:
   - Masuk ke tab **Settings** > **Environment Variables** di Vercel.
   - Tambahkan variabel `APP_URL` dengan nilai URL domain Vercel Anda tadi (contoh: `https://pelatihan-karyawan.vercel.app`).
   - Lakukan **Redeploy** sekali agar URL tersinkronisasi sempurna.

---

## 👤 Akun Login Default:

- **Superadmin**: `superadmin@pelatihan.com` | `password123`
- **HRD**: `hrd@pelatihan.com` | `password123`
- **Admin IT**: `admin.it@pelatihan.com` | `password123`
- **Admin Engineering**: `admin.engineering@pelatihan.com` | `password123`
