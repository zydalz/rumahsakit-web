# Rumah Sakit Web

Aplikasi sistem informasi rumah sakit berbasis web yang dibangun menggunakan **Laravel 12**.

## Prasyarat Sistem
* PHP >= 8.2
* Composer
* MySQL / MariaDB

## Cara Instalasi di Lokal
1. Clone repositori ini:
   ```bash
   git clone <URL_REPOSITORI_GITHUB>
   ```
2. Masuk ke folder proyek:
   ```bash
   cd rumahsakit-web
   ```
3. Install dependensi library:
   ```bash
   composer install
   ```
4. Salin file konfigurasi environment:
   ```bash
   cp .env.example .env
   ```
5. Generate security key aplikasi:
   ```bash
   php artisan key:generate
   ```
6. Sesuaikan konfigurasi database Anda di file `.env`, lalu jalankan migrasi:
   ```bash
   php artisan migrate
   ```
7. Jalankan server lokal:
   ```bash
   php artisan serve
   ```
