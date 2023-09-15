
# Panduan Instalasi dan Penggunaan Laravel News API

Ini adalah repositori Laravel yang berisi sebuah API berita dengan fitur User, news dan comment, serta menggunakan Passport untuk autentikasi.

## Persyaratan

Sebelum Anda dapat menginstal dan menjalankan proyek ini, pastikan Anda memiliki persyaratan berikut:

- PHP 8.0 atau yang lebih baru
- Composer
- MySQL
- Command Prompt
- Git

## Langkah 1: Clone Repositori dari GitHub

Anda dapat meng-clone repositori ini dari GitHub dengan menjalankan perintah berikut di terminal Anda:

```bash
git clone https://github.com/tommygz8387/jds-api-test.git
```

## Langkah 2: Instal Dependensi

Pindah ke direktori proyek yang baru saja Anda clone dengan perintah:

```bash
cd jd-api-test
```
Selanjutnya, instal semua dependensi yang diperlukan dengan Composer:

```bash
composer install
```
## Langkah 3: Konfigurasi Database

Buat salinan file .env.example dan beri nama .env. Kemudian, atur konfigurasi database Anda di dalam file .env. Misalnya:

```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database_anda
DB_USERNAME=nama_pengguna_database_anda
DB_PASSWORD=kata_sandi_anda
```
Jangan lupa untuk membuat database sesuai dengan nama yang Anda konfigurasikan.

## Langkah 4: Generate Kunci Aplikasi dan Migrasi Database

Selanjutnya, Anda perlu meng-generate kunci aplikasi Laravel dan menjalankan migrasi database. Ketik perintah berikut:

```bash
php artisan key:generate
php artisan migrate
```

## Langkah 5: Instal Passport untuk Autentikasi

Gunakan Composer untuk menginstal Passport:

```bash
composer require laravel/passport
```
Selanjutnya, jalankan perintah berikut untuk mengkonfigurasi Passport:

```bash
php artisan passport:install
```
Setelah itu, Anda akan mendapatkan dua kunci rahasia Passport yang perlu Anda tambahkan ke file .env:

```bash
PASSPORT_CLIENT_ID=your-client-id
PASSPORT_CLIENT_SECRET=your-client-secret
```

## Langkah 6: Mulai di Server Lokal

Terakhir, jalankan server Laravel Anda secara lokal:

```bash
php artisan serve
```
Proyek Laravel News API sekarang sudah berjalan di http://localhost:8000.


## Authors

- [@toms](https://www.github.com/tommygz8387)