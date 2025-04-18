# E-Rapor Universitas Bakwan

Sistem penilaian mahasiswa berbasis web menggunakan PHP dan JSON.

## Struktur Folder
```
WEB/
├── assets/           # Asset statis
│   ├── css/         # File CSS
│   ├── js/          # File JavaScript
│   └── images/      # Gambar (termasuk pas foto mahasiswa)
├── data/            # Data JSON
├── includes/        # File PHP yang digunakan bersama
├── index.php        # Halaman utama
├── login.php        # Halaman login
├── register.php     # Halaman registrasi
├── input_nilai.php  # Halaman input nilai mahasiswa
├── data_mahasiswa.php # Halaman data mahasiswa
├── statistik.php    # Halaman statistik nilai
├── tentang.php      # Halaman tentang proyek
└── ...
```

## Fitur
- **Autentikasi User**:
  - Login dan registrasi dengan validasi password yang kuat.
- **Input Nilai Mahasiswa**:
  - Input nilai tugas, UTS, dan UAS dengan validasi rentang nilai.
- **Manajemen Data Mahasiswa**:
  - Tambah, edit, dan hapus data mahasiswa.
  - Upload pas foto mahasiswa dan simpan tanggal lahir.
- **Statistik Nilai**:
  - Menampilkan statistik nilai tertinggi, terendah, rata-rata, dan distribusi grade dalam bentuk grafik.
- **Export dan Print**:
  - Export data mahasiswa ke file CSV.
  - Print data mahasiswa yang dipilih.
- **Pencarian dan Filter**:
  - Cari mahasiswa berdasarkan nama atau NIM.
  - Filter data berdasarkan grade.
- **Keamanan**:
  - Hash password menggunakan `password_hash`.
  - Proteksi file JSON dengan `.htaccess`.

## Teknologi
- **Backend**:
  - PHP 7+ untuk logika server.
  - JSON sebagai penyimpanan data.
- **Frontend**:
  - Bootstrap 5 untuk desain responsif.
  - SweetAlert2 untuk notifikasi interaktif.
  - Chart.js untuk visualisasi data.
- **Keamanan**:
  - Validasi input untuk mencegah serangan XSS.
  - Proteksi file sensitif menggunakan `.htaccess`.

## Instalasi
1. Copy folder ke direktori `htdocs` di XAMPP.
2. Buat folder `data/` jika belum ada.
3. Pastikan folder `data/` memiliki izin tulis (writeable).
4. Akses melalui browser: `http://localhost/WEB`.

## Cara Penggunaan
1. **Login atau Registrasi**:
   - Gunakan halaman login untuk masuk atau halaman registrasi untuk membuat akun baru.
2. **Input Nilai**:
   - Masukkan data mahasiswa dan nilai mereka melalui halaman "Input Nilai".
3. **Kelola Data Mahasiswa**:
   - Gunakan halaman "Data Mahasiswa" untuk melihat, mengedit, atau menghapus data.
4. **Lihat Statistik**:
   - Kunjungi halaman "Statistik" untuk melihat grafik distribusi nilai dan ranking mahasiswa.
5. **Export dan Print**:
   - Export data ke CSV atau print data mahasiswa yang dipilih.

## Kontributor
- **Sayyid Abdullah Azzam** (NIM: 2023230021) - Teknologi Informasi
