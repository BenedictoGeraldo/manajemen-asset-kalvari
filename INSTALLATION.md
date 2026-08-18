# Panduan Instalasi Projek Pelita (Manajemen Aset Kalvari)

Dokumen ini berisi langkah-langkah untuk menyiapkan lingkungan pengembangan Projek Pelita menggunakan Docker (Laravel Sail).

## Prasyarat
- Docker Desktop (Windows/Mac) atau Docker Engine & Compose (Linux).
- Git.

---

## Langkah-langkah Instalasi

### 1. Persiapan File Environment
Salin file `.env.example` menjadi `.env`:
```bash
cp .env.example .env
```
Pastikan pengaturan database di `.env` sudah sesuai dengan konfigurasi Docker:
```env
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=manajemen_asset
DB_USERNAME=laravel
DB_PASSWORD=root
```

### 2. Menjalankan Docker (Laravel Sail)
Gunakan Sail untuk menjalankan kontainer:
```bash
./vendor/bin/sail up -d
```
Jika Anda belum memiliki vendor folder, Anda bisa menjalankan perintah ini sekali untuk menginstall dependencies menggunakan kontainer sementara:
```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php82-composer:latest \
    composer install --ignore-platform-reqs
```

### 3. Generate Application Key
```bash
./vendor/bin/sail artisan key:generate
```

### 4. Migrasi dan Seeding Database (Data Gereja Kalvari)
Jalankan migrasi bersih beserta seeder untuk mengisi data master asli Gereja Kalvari (Lokasi, Pengelola, Kategori, Kondisi, dan Data Organisasi):
```bash
./vendor/bin/sail artisan migrate:fresh --seed
```

### 5. Kompilasi Asset Frontend (Vite)
Instal dependensi NPM dan buat manifest file agar desain aplikasi muncul dengan benar:
```bash
./vendor/bin/sail npm install
./vendor/bin/sail npm run build
```

---

## Akses Aplikasi
Setelah semua langkah selesai, Anda dapat mengakses aplikasi di:
- **URL:** [http://localhost](http://localhost) (atau port lain jika diubah di docker-compose.yml)
- **Login Default:** 
  - Email: `itkreatif@gmail.com`
  - Password: `itkalvari`

## Perintah Penting Lainnya

- **Menghentikan Projek:** `./vendor/bin/sail down`
- **Menjalankan Queue:** `./vendor/bin/sail artisan queue:work`
- **Akses Bash Kontainer:** `docker exec -it pelita-app bash`
