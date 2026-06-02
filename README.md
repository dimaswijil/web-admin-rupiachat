# 🛡️ RupiaChat Web Admin & CRM

Selamat datang di repositori **RupiaChat Web Admin**. Ini adalah dashboard administrasi berbasis web yang dibangun dengan framework **Laravel**. Panel ini digunakan oleh tim internal untuk mengelola pengguna dan memantau ekosistem aplikasi secara keseluruhan, yang beroperasi berdampingan dengan backend API RupiaChat.

## 🏗️ Arsitektur & Teknologi (Tech Stack)

*   **Framework:** Laravel
*   **Database:** Terhubung ke **TiDB Cloud** (Relational Database) yang didistribusikan secara global, berbagi basis data yang sama dengan API backend.
*   **Pola Desain:** MVC (Model-View-Controller) klasik dari Laravel, dikombinasikan dengan antarmuka web interaktif.

## ✨ Fitur Dashboard (Core Features)

Panel admin menyediakan fitur-fitur Customer Relationship Management (CRM) esensial:

*   **Manajemen Autentikasi Admin:** Sistem login dan logout aman untuk staf (`/admin/login`).
*   **Dashboard Statistik:** Menampilkan ringkasan metrik ekosistem RupiaChat (`/admin/dashboard`).
*   **Interactive CRM & User Management:** 
    *   Melihat profil dan data pengguna.
    *   Fitur **Toggle Status Pengguna** untuk memblokir (*ban*) atau mengaktifkan kembali pengguna dengan satu kali klik.

## 🛠️ Panduan Pengembangan Lokal (Local Development)

### Persyaratan Sistem (Prerequisites)
*   PHP `>= 8.2`
*   Composer `>= 2.0`
*   Node.js & NPM (untuk kompilasi aset Vite)

### Cara Menjalankan
1.  Salin konfigurasi lingkungan:
    ```bash
    cp .env.example .env
    ```
2.  Instal dependensi:
    ```bash
    composer install
    npm install
    ```
3.  Generate key aplikasi:
    ```bash
    php artisan key:generate
    ```
4.  Jalankan server dan aset lokal:
    ```bash
    php artisan serve
    npm run dev
    ```
