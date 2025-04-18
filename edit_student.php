<?php
require_once 'includes/auth.php';
require_once 'includes/functions.php';

$students = readJSONFile("data/data_mahasiswa.json");
$student = null;

if (isset($_GET['nim'])) {
    foreach ($students as $s) {
        if ($s['nim'] === $_GET['nim']) {
            $student = $s;
            break;
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'nama' => $_POST['nama'] ?? $student['nama'] ?? '',
        'nim' => $_POST['nim'] ?? $student['nim'] ?? '',
        'tugas' => isset($_POST['tugas']) ? (float)$_POST['tugas'] : ($student['tugas'] ?? 0),
        'uts' => isset($_POST['uts']) ? (float)$_POST['uts'] : ($student['uts'] ?? 0),
        'uas' => isset($_POST['uas']) ? (float)$_POST['uas'] : ($student['uas'] ?? 0),
        'nilai_akhir' => 0,
        'grade' => '',
        'tanggal_lahir' => $_POST['tanggal_lahir'] ?? $student['tanggal_lahir'] ?? null
    ];
    
    $data['nilai_akhir'] = ($data['tugas'] * 0.3) + ($data['uts'] * 0.3) + ($data['uas'] * 0.4);
    $data['grade'] = calculateGrade($data['nilai_akhir']);
    
    updateStudent($_POST['nim_lama'] ?? $student['nim'], $data);

    // Redirect ke halaman edit_student untuk memuat ulang data terbaru
    header("Location: edit_student.php?nim=" . urlencode($data['nim']) . "&success=1");
    exit();
}

if (!$student) {
    header("Location: data_mahasiswa.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Nilai - E-Rapor Universitas Bakwan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main class="container mt-5">
        <h2 class="mb-4">Edit Nilai Mahasiswa</h2>
        <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
            <div id="successMessage" class="alert alert-success">Data berhasil diperbarui!</div>
        <?php endif; ?>
        <div id="successMessage" class="alert alert-success d-none">Data berhasil diperbarui!</div>
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card shadow">
                    <div class="card-body text-center position-relative">
                        <?php 
                        $photoPath = isset($student['pas_foto']) && file_exists($student['pas_foto']) 
                            ? $student['pas_foto'] 
                            : "assets/images/default-profile.png";
                        ?>
                        <img src="<?= htmlspecialchars($photoPath) ?>" 
                             alt="Pas Foto Mahasiswa" 
                             class="img-fluid rounded-circle mb-3" 
                             style="width: 150px; height: 150px; object-fit: cover;">
                        <h5 class="card-title"><?= htmlspecialchars($student['nama']) ?></h5>
                        <p class="text-muted" id="tanggalLahirText">
                            Tanggal Lahir: <span id="tanggalLahirDisplay"><?= isset($student['tanggal_lahir']) ? htmlspecialchars($student['tanggal_lahir']) : 'Tidak tersedia' ?></span>
                        </p>
                        <button class="gear-button position-absolute top-0 end-0" id="editPhotoButton" title="Edit Foto dan Tanggal Lahir">
                            <img src="assets/icon/gear.svg" alt="Edit">
                        </button>
                        <form id="editPhotoForm" class="d-none" method="POST" enctype="multipart/form-data">
                            <label class="custom-file-label" for="photoInput">Upload Foto</label>
                            <input type="file" name="photo" id="photoInput" class="custom-file-input">
                            <label class="custom-date-label" for="tanggalLahirInput">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" id="tanggalLahirInput" value="<?= isset($student['tanggal_lahir']) ? htmlspecialchars($student['tanggal_lahir']) : '' ?>" class="custom-date-input">
                            <button type="button" id="saveTanggalLahir" class="btn btn-primary btn-sm">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="nim_lama" value="<?= htmlspecialchars($student['nim']) ?>">
                            <div class="mb-3">
                                <label class="form-label">Nama Mahasiswa</label>
                                <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($student['nama']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">NIM</label>
                                <input type="text" name="nim" class="form-control" value="<?= htmlspecialchars($student['nim']) ?>" required>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Nilai Tugas</label>
                                        <input type="number" name="tugas" class="form-control" value="<?= $student['tugas'] ?>" min="0" max="100" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Nilai UTS</label>
                                        <input type="number" name="uts" class="form-control" value="<?= $student['uts'] ?>" min="0" max="100" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Nilai UAS</label>
                                        <input type="number" name="uas" class="form-control" value="<?= $student['uas'] ?>" min="0" max="100" required>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">Update</button>
                                <a href="data_mahasiswa.php" class="btn btn-secondary">Batal</a>
                            </div>
                        </form>
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
    <script>
        const editPhotoButton = document.getElementById('editPhotoButton');
        const editPhotoForm = document.getElementById('editPhotoForm');
        const tanggalLahirText = document.getElementById('tanggalLahirText');
        const gearIcon = editPhotoButton.querySelector('img');
        const saveTanggalLahirButton = document.getElementById('saveTanggalLahir');
        const tanggalLahirInput = document.getElementById('tanggalLahirInput');
        const tanggalLahirDisplay = document.getElementById('tanggalLahirDisplay');
        const successMessage = document.getElementById('successMessage');

        editPhotoButton.addEventListener('click', () => {
            if (editPhotoForm.classList.contains('d-none')) {
                editPhotoForm.classList.remove('d-none');
                tanggalLahirText.classList.add('d-none');
                gearIcon.classList.add('spinning');
                setTimeout(() => gearIcon.classList.remove('spinning'), 500);
            } else {
                editPhotoForm.classList.add('d-none');
                tanggalLahirText.classList.remove('d-none');
                gearIcon.classList.add('spinning-reverse');
                setTimeout(() => gearIcon.classList.remove('spinning-reverse'), 500);
            }
        });

        saveTanggalLahirButton.addEventListener('click', () => {
            const nim = "<?= htmlspecialchars($student['nim']) ?>";
            const tanggalLahir = tanggalLahirInput.value;

            fetch('update_tanggal_lahir.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ nim, tanggal_lahir: tanggalLahir }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    tanggalLahirDisplay.textContent = tanggalLahir;
                    editPhotoForm.classList.add('d-none');
                    tanggalLahirText.classList.remove('d-none');
                    // Tampilkan pesan sukses
                    successMessage.classList.remove('d-none');
                    successMessage.style.opacity = '1';
                    setTimeout(() => {
                        successMessage.style.transition = 'opacity 0.5s ease';
                        successMessage.style.opacity = '0';
                        setTimeout(() => successMessage.classList.add('d-none'), 500);
                    }, 5000);
                } else {
                    alert('Gagal memperbarui tanggal lahir.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });

        // Auto-hide success message after 5 seconds
        const successMessageElement = document.getElementById('successMessage');
        if (successMessageElement) {
            setTimeout(() => {
                successMessageElement.style.transition = 'opacity 0.5s ease';
                successMessageElement.style.opacity = '0';
                setTimeout(() => successMessageElement.remove(), 500); // Remove element after fade-out
            }, 5000);
        }
    </script>
</body>
</html>
