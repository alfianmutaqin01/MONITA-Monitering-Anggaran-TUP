# MONITA - Monitoring Anggaran Telkom University Purwokerto

MONITA adalah aplikasi web yang dirancang untuk menggantikan proses manual pengelolaan dan monitoring anggaran menggunakan spreadsheet di lingkungan Telkom University Purwokerto. Sistem ini bertujuan menyediakan dashboard terpusat, akses multi-user berbasis peran, dan laporan real-time untuk 33 unit kerja di kampus.

## 📋 Daftar Isi

- [Tujuan Proyek](#tujuan-proyek)
- [Fitur Utama](#fitur-utama)
- [Teknologi yang Digunakan](#teknologi-yang-digunakan)
- [Persyaratan Sistem](#persyaratan-sistem)
- [Instalasi dan Setup Lokal](#instalasi-dan-setup-lokal)
- [Konfigurasi Google Sheets API](#konfigurasi-google-sheets-api)
- [Keamanan dan Autentikasi](#keamanan-dan-autentikasi)

## 🎯 Tujuan Proyek

Tujuan utama sistem MONITA adalah mencapai efisiensi, akurasi, dan transparansi dalam pengelolaan anggaran:

- **Mengatasi Inkonsistensi Data**: Mengeliminasi risiko duplikasi dan bentrok data yang terjadi saat menggunakan file Excel/Spreadsheet
- **Monitoring Terpusat**: Menyediakan dashboard interaktif untuk analisis keuangan real-time bagi pimpinan dan staf keuangan
- **Akses Terkendali**: Menerapkan sistem akses multi-user dengan tingkatan hak akses yang berbeda (Admin Keuangan vs. User Unit)

## ✨ Fitur Utama

- **Dashboard Interaktif**: Menampilkan ringkasan saldo, perbandingan anggaran vs. realisasi, dan status serapan per unit dalam bentuk grafik
- **Manajemen Anggaran (CRUD)**: Kemampuan untuk mengimpor data dari file Excel/CSV/Google Sheets serta mencatat transaksi keuangan
- **Manajemen Akun**: Admin memiliki akses penuh untuk menambah, mengubah, dan menghapus akun unit, serta mengelola peran pengguna
- **Pelaporan Otomatis**: Menghasilkan laporan keuangan terperinci dan ringkasan (Summary RKA, RKM, Pengembangan) dalam format PDF
- **Pengaturan Tahun Anggaran**: Mengelola koneksi ke Google Sheets ID untuk tahun anggaran yang berbeda-beda

## 🛠️ Teknologi yang Digunakan

### Backend
- **PHP** 8.4.12
- **Laravel Framework** 12.28.1
- **Composer** 2.8.11

### Frontend
- **HTML5**, **CSS3**, **JavaScript ES6**
- **Bootstrap** 5.3

### Integrasi Data
- **Google Sheets API v4** (sebagai primary database)
- **Google API Client** 2.18.3
- **Laravel Google Sheets** 7.1.4

### Package Pendukung
- **Laravel DomPDF** 3.1.1 - untuk generate laporan PDF
- **Carbon** 3.10.3 - untuk manipulasi waktu dan tanggal
- **GuzzleHTTP** 7.10.0 - untuk HTTP client
- **Firebase JWT** 6.11.1 - untuk autentikasi token
- **PHP CORS** 1.3.0 - untuk pengaturan CORS

## 💻 Persyaratan Sistem

### Minimum Requirements
- **PHP**: 8.2 atau lebih tinggi
- **Composer**: 2.0 atau lebih tinggi
- **Node.js**: 14.x atau lebih tinggi (untuk asset compilation)
- **Ekstensi PHP**: BCMath, Ctype, cURL, DOM, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML

### Browser yang Didukung
- Google Chrome (rekomendasi)
- Mozilla Firefox
- Microsoft Edge
- Apple Safari

## 🚀 Instalasi dan Setup Lokal

### 1. Clone Repository
```bash
git clone https://github.com/alfianmutaqin01/MONITA-Monitering-Anggaran-TUP.git monita
cd monita
```

### 2. Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install JavaScript dependencies (jika diperlukan)
npm install
```

### 3. Konfigurasi Environment
```bash
# Duplikasi file environment
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Konfigurasi Database & Cache
Sistem menggunakan SQLite sebagai database default untuk session dan cache. Pastikan konfigurasi di `.env`:

```env
DB_CONNECTION=sqlite
CACHE_STORE=database
SESSION_DRIVER=database
QUEUE_CONNECTION=database
```

Setup tabel cache dan session:
```bash
php artisan migrate
```

### 5. Menjalankan Aplikasi
```bash
# Start development server
php artisan serve

# Akses aplikasi di browser
# http://localhost:8000
```

## 🔧 Konfigurasi Google Sheets API

### 1. Setup Service Account
- Buat Service Account di [Google Cloud Console](https://console.cloud.google.com/)
- Download file JSON credentials
- Simpan file credentials di: `storage/app/credentials/service-account.json`

### 2. Konfigurasi Google Sheets di Environment
Tambahkan konfigurasi berikut di file `.env`:

```env
# ID Google Sheet yang sedang aktif
GOOGLE_SPREADSHEET_ID="1fENIzciA4gJZryj9FQPHKcQqXrbSBgViiRLxqQhU18M"
ACTIVE_YEAR=2025

# Untuk penyimpanan historis
GOOGLE_SPREADSHEET_ID_YEAR_2025="1fENIzciA4gJZryj9FQPHKcQqXrbSBgViiRLxqQhU18M"


# Konfigurasi Tanda Tangan Laporan

```

### 3. Izin Akses Google Sheets
Pastikan Service Account (`keuangan-tup@monita-471208.iam.gserviceaccount.com`) memiliki izin **Editor** pada Google Sheets target.

## 🔒 Keamanan dan Autentikasi

Sistem menerapkan mekanisme keamanan yang kuat:

### Password Security
- **Salting Kustom**: Setiap password dienkripsi dengan salt unik berdasarkan username pengguna menggunakan `Hash::make({password} . {username})`
- **Rainbow Table Protection**: Teknik salting kustom mencegah serangan rainbow table

### Autentikasi
- **Session-based Authentication** untuk menjaga keamanan akses
- **Role-based Access Control (RBAC)** dengan dua level akses:
  - **Admin Keuangan**: Akses penuh ke semua fitur
  - **User Unit**: Akses terbatas hanya untuk data unit masing-masing

### Validasi Data
- Validasi keunikan untuk **Kode PP** dan **Username**
- Pembatasan akses data antar unit berdasarkan otorisasi
- Audit trail untuk memantau perubahan data

## 📊 Kebutuhan Non-Fungsional

| Parameter | Spesifikasi |
|-----------|-------------|
| **Availability** | Sistem beroperasi 24/7 dengan downtime maksimal 2 jam/bulan |
| **Reliability** | Import data Excel dengan tingkat kegagalan < 1% per bulan |
| **Performance** | Menampilkan data transaksi dalam < 3 detik |
| **Scalability** | Mendukung minimal 100 pengguna aktif bersamaan |
| **Security** | HTTPS encryption, autentikasi login, dan role-based access control |

## 📞 Support

Untuk pertanyaan atau bantuan teknis, silakan hubungi tim pengembang:
- Alfian Mutakim
- Hamid Khaeruman

---

**MONITA** - Sistem Monitoring Anggaran Telkom University Purwokerto  
*Efisiensi, Akurasi, dan Transparansi dalam Pengelolaan Anggaran*
