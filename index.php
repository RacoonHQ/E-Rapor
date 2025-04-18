<?php 
session_start(); 
$isLoggedIn = isset($_SESSION['username']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Rapor Universitas Bakwan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body class="theme-mantis">
    <?php include 'includes/header.php'; ?>
    
    <main class="container mt-5">
        <section class="jumbotron text-center">
            <h1 class="display-4">Selamat Datang di E-Rapor</h1>
            <p class="lead">Sistem manajemen nilai mahasiswa yang modern dan efisien</p>
            <?php if(!$isLoggedIn): ?>
            <p>
                <a class="btn btn-primary btn-lg" href="login.php" role="button">Login</a>
                <a class="btn btn-outline-primary btn-lg" href="register.php" role="button">Register</a>
            </p>
            <?php endif; ?>
        </section>

        <section class="features mt-5">
            <div class="row">
                <div class="col-md-4">
                    <a href="input_nilai.php" class="text-decoration-none">
                        <div class="card theme-mantis-card">
                            <div class="card-body">
                                <h5 class="card-title">Input Nilai</h5>
                                <p class="card-text">Memudahkan penginputan nilai mahasiswa dengan cepat dan akurat.</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="data_mahasiswa.php" class="text-decoration-none">
                        <div class="card theme-mantis-card">
                            <div class="card-body">
                                <h5 class="card-title">Data Mahasiswa</h5>
                                <p class="card-text">Menampilkan dan mengelola data mahasiswa dengan mudah.</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="statistik.php" class="text-decoration-none">
                        <div class="card theme-mantis-card">
                            <div class="card-body">
                                <h5 class="card-title">Statistik Nilai</h5>
                                <p class="card-text">Menampilkan statistik nilai mahasiswa dalam bentuk grafik.</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </section>

        <!-- Tambahkan penjelasan pembuat website -->
        <section class="mt-5 text-center">
            <p class="text-muted">
                Website ini dibuat oleh <strong>Sayyid Abdullah Azzam</strong> (NIM: 2023230021), Teknologi Informasi.
            </p>
        </section>
    </main>

    <footer class="footer mt-auto theme-mantis-footer">
        <div class="container">
            <p>&copy; <?php echo date("Y"); ?> E-Rapor Universitas Bakwan</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
