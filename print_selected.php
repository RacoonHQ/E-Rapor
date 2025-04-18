<?php
require_once 'includes/auth.php';

if (!isset($_POST['students'])) {
    die('No data selected');
}

$students = json_decode($_POST['students'], true);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Print Data Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <div class="no-print print-header">
        <div class="container d-flex justify-content-between align-items-center">
            <h4 class="mb-0 fw-bold text-primary">Preview Data Mahasiswa</h4>
            <div class="btn-group">
                <button onclick="window.print()" class="btn btn-primary">
                    <i class="bi bi-printer-fill me-2"></i> Print
                </button>
                <button onclick="window.close()" class="btn btn-secondary">
                    <i class="bi bi-x-circle-fill me-2"></i> Tutup
                </button>
            </div>
        </div>
    </div>

    <div class="container printable-content">
        <div class="print-content">
            <div class="table-header d-flex justify-content-between align-items-center mb-3">
                <h2 class="h4 mb-0">Data Nilai Mahasiswa</h2>
                <p class="timestamp mb-0">Dicetak pada: <?= date('d/m/Y H:i:s') ?></p>
            </div>
            
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>NIM</th>
                            <th>Nama</th>
                            <th class="text-center">Tugas</th>
                            <th class="text-center">UTS</th>
                            <th class="text-center">UAS</th>
                            <th class="text-center">Nilai Akhir</th>
                            <th class="text-center">Grade</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $student): ?>
                            <tr>
                                <td class="fw-bold"><?= htmlspecialchars($student['nim']) ?></td>
                                <td><?= htmlspecialchars($student['nama']) ?></td>
                                <td class="text-center"><?= $student['tugas'] ?></td>
                                <td class="text-center"><?= $student['uts'] ?></td>
                                <td class="text-center"><?= $student['uas'] ?></td>
                                <td class="text-center fw-bold"><?= number_format($student['nilai_akhir'], 2) ?></td>
                                <td class="text-center">
                                    <span class="grade-badge grade-<?= $student['grade'] ?>">
                                        <?= $student['grade'] ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <footer class="footer mt-auto">
        <div class="container">
            <p>&copy; <?php echo date("Y"); ?> E-Rapor Universitas Bakwan</p>
        </div>
    </footer>

    <script>
        window.onafterprint = function() {
            window.close();
        };
    </script>
</body>
</html>

<?php
function getGradeColor($grade) {
    switch ($grade) {
        case 'A': return 'success';
        case 'B': return 'primary';
        case 'C': return 'warning';
        case 'D': return 'danger';
        default: return 'secondary';
    }
}
?>
