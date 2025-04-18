<?php
require_once 'includes/auth.php';
require_once 'includes/functions.php';

try {
    // Perbaikan cara membaca data
    $students = readJSONFile("data_mahasiswa.json");
    $totalMahasiswa = count($students);

    // Inisialisasi nilai default
    $tertinggi = 0;
    $terendah = 100;
    $totalNilai = 0;
    $grades = ['A' => 0, 'B' => 0, 'C' => 0, 'D' => 0, 'E' => 0];

    if ($totalMahasiswa > 0) {
        foreach ($students as $student) {
            // Hitung statistik
            $nilai = (float)$student['nilai_akhir'];
            $tertinggi = max($tertinggi, $nilai);
            $terendah = min($terendah, $nilai);
            $totalNilai += $nilai;
            
            // Hitung distribusi grade
            if (isset($student['grade'])) {
                $grades[$student['grade']]++;
            }
        }
        $rataRata = $totalNilai / $totalMahasiswa;

        // Tambahkan sorting untuk ranking
        usort($students, function($a, $b) {
            return $b['nilai_akhir'] - $a['nilai_akhir'];
        });
        // Ambil 4 nilai tertinggi
        $topStudents = array_slice($students, 0, 4);
    } else {
        $rataRata = 0;
        $terendah = 0;
    }

} catch (Exception $e) {
    $error = "Gagal membaca data: " . $e->getMessage();
    $totalMahasiswa = 0;
    $tertinggi = 0;
    $terendah = 0;
    $rataRata = 0;
    $grades = ['A' => 0, 'B' => 0, 'C' => 0, 'D' => 0, 'E' => 0];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistik - E-Rapor Universitas Bakwan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main class="container mt-5">
        <h2 class="mb-4">Statistik Nilai</h2>
        
        <div class="row">
            <div class="col-md-3">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Total Mahasiswa</h5>
                        <h2 class="text-primary"><?= $totalMahasiswa ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Nilai Tertinggi</h5>
                        <h2 class="text-success"><?= number_format($tertinggi, 2) ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Nilai Terendah</h5>
                        <h2 class="text-danger"><?= number_format($terendah, 2) ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Rata-rata Nilai</h5>
                        <h2 class="text-primary"><?= number_format($rataRata, 2) ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <canvas id="gradeChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Top 4 Ranking</h5>
                        <div class="table-responsive">
                            <table class="table modern-table">
                                <thead>
                                    <tr>
                                        <th>Rank</th>
                                        <th>Nama</th>
                                        <th>Nilai</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($topStudents as $index => $student): ?>
                                    <tr>
                                        <td>
                                            <?php if ($index === 0): ?>
                                                <span class="rank-badge rank-gold">1</span>
                                            <?php elseif ($index === 1): ?>
                                                <span class="rank-badge rank-silver">2</span>
                                            <?php elseif ($index === 2): ?>
                                                <span class="rank-badge rank-bronze">3</span>
                                            <?php elseif ($index === 3): ?>
                                                <span class="rank-badge rank-fourth">4</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= htmlspecialchars($student['nama']) ?></td>
                                        <td><?= number_format($student['nilai_akhir'], 2) ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('gradeChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['A', 'B', 'C', 'D', 'E'],
                datasets: [{
                    label: 'Distribusi Nilai',
                    data: [
                        <?= $grades['A'] ?>,
                        <?= $grades['B'] ?>,
                        <?= $grades['C'] ?>,
                        <?= $grades['D'] ?>,
                        <?= $grades['E'] ?>
                    ],
                    backgroundColor: [
                        '#28a745',
                        '#17a2b8',
                        '#ffc107',
                        '#fd7e14',
                        '#dc3545'
                    ]
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
