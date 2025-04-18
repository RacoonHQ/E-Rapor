<?php 
session_start(); 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang - E-Rapor Universitas Bakwan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main class="container mt-5">
        <h1 class="mb-4">Tentang E-Rapor Universitas Bakwan</h1>
        <section class="mb-5">
            <h2>Fitur</h2>
            <ul>
                <li>Autentikasi user (login/register).</li>
                <li>Input nilai mahasiswa dengan validasi.</li>
                <li>Manajemen data nilai mahasiswa.</li>
                <li>Statistik nilai dalam bentuk grafik.</li>
                <li>Export data ke CSV.</li>
                <li>Print laporan nilai mahasiswa.</li>
            </ul>
        </section>
        <section class="mb-5">
            <h2>Teknologi</h2>
            <ul>
                <li>PHP 7+ untuk backend.</li>
                <li>Bootstrap 5 untuk desain responsif.</li>
                <li>Chart.js untuk visualisasi data.</li>
                <li>SweetAlert2 untuk notifikasi interaktif.</li>
                <li>JSON sebagai penyimpanan data.</li>
            </ul>
        </section>
        <section class="mb-5">
            <h2>Detail Proyek</h2>
            <p>Website ini dibuat oleh <strong>Sayyid Abdullah Azzam</strong> (NIM: 2023230021), mahasiswa Teknologi Informasi. Proyek ini bertujuan untuk mempermudah pengelolaan nilai mahasiswa secara digital.</p>
        </section>
    </main>

    <footer class="footer mt-auto">
        <div class="container">
            <p>&copy; <?php echo date("Y"); ?> E-Rapor Universitas Bakwan</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
