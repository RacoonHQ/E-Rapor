<?php
require_once 'includes/auth.php';
require_once 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'nama' => sanitizeInput($_POST['nama']),
        'nim' => sanitizeInput($_POST['nim']),
        'tugas' => min(100, max(0, (float)$_POST['tugas'])),
        'uts' => min(100, max(0, (float)$_POST['uts'])),
        'uas' => min(100, max(0, (float)$_POST['uas'])),
        'nilai_akhir' => 0,
        'grade' => ''
    ];
    
    if (!validateNIM($data['nim'])) {
        $error = "NIM sudah terdaftar!";
    } else {
        $data['nilai_akhir'] = calculateFinalScore(
            $data['tugas'],
            $data['uts'],
            $data['uas']
        );
        $data['grade'] = calculateGrade($data['nilai_akhir']);
        
        addStudent($data);
        echo "<script>
            window.onload = function() {
                Swal.fire({
                    title: 'Berhasil!',
                    text: 'Data berhasil disimpan',
                    icon: 'success',
                    confirmButtonText: 'Tutup'
                });
            };
        </script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Nilai - E-Rapor Universitas Bakwan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main class="container mt-5">
        <h2 class="mb-4">Input Nilai Mahasiswa</h2>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" id="formNilai" onsubmit="return confirmSave(event)">
                            <div class="mb-3">
                                <label class="form-label">Nama Mahasiswa</label>
                                <input type="text" name="nama" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">NIM</label>
                                <input type="text" name="nim" class="form-control" required>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Nilai Tugas</label>
                                        <input type="number" name="tugas" class="form-control" min="0" max="100" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Nilai UTS</label>
                                        <input type="number" name="uts" class="form-control" min="0" max="100" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Nilai UAS</label>
                                        <input type="number" name="uas" class="form-control" min="0" max="100" required>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Tata Cara Pengisian</h5>
                        <ol>
                            <li>Isi nama mahasiswa secara lengkap sesuai dengan data resmi.</li>
                            <li>Masukkan Nomor Induk Mahasiswa (NIM) yang valid dan sesuai dengan data mahasiswa.</li>
                            <li>Masukkan nilai tugas dengan rentang 0 hingga 100.</li>
                            <li>Masukkan nilai Ujian Tengah Semester (UTS) dengan rentang 0 hingga 100.</li>
                            <li>Masukkan nilai Ujian Akhir Semester (UAS) dengan rentang 0 hingga 100.</li>
                            <li>Tekan tombol "Simpan" untuk menyimpan data yang telah diinput.</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="footer mt-auto">
        <div class="container">
            <p>&copy; <?php echo date("Y"); ?> E-Rapor Universitas Bakwan</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.min.js"></script>
    <script>
        function confirmSave(event) {
            event.preventDefault(); // Mencegah form langsung submit
            Swal.fire({
                title: 'Konfirmasi',
                text: 'Apakah Anda yakin ingin menyimpan data?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Simpan',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    event.target.submit(); // Submit form jika user menekan "Ya, Simpan"
                }
            });
        }
    </script>
</body>
</html>
