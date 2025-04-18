<?php
require_once 'config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$current_page = basename($_SERVER['PHP_SELF']);
?>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="index.php">E-Rapor UB</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page == 'index.php') ? 'active' : '' ?>" href="index.php">Beranda</a>
                </li>
                <?php if(isset($_SESSION['username'])): ?>
                    <li class="nav-item">
                        <a class="nav-link <?= ($current_page == 'tentang.php') ? 'active' : '' ?>" href="tentang.php">Tentang</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($current_page == 'input_nilai.php') ? 'active' : '' ?>" href="input_nilai.php">Input Nilai</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($current_page == 'data_mahasiswa.php') ? 'active' : '' ?>" href="data_mahasiswa.php">Data Mahasiswa</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($current_page == 'statistik.php') ? 'active' : '' ?>" href="statistik.php">Statistik</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link <?= ($current_page == 'login.php') ? 'active' : '' ?>" href="login.php">Login</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
