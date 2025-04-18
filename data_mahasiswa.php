<?php
require_once 'includes/auth.php';
require_once 'includes/functions.php';

try {
    // 1. Ambil semua data
    $allStudents = readJSONFile("data_mahasiswa.json");
    
    // 2. Terapkan filter
    $search = isset($_GET['search']) ? strtolower($_GET['search']) : '';
    $gradeFilter = isset($_GET['grade']) ? $_GET['grade'] : '';
    
    // 3. Filter data
    $filteredStudents = $allStudents;
    if (!empty($search) || !empty($gradeFilter)) {
        $filteredStudents = array_filter($allStudents, function($student) use ($search, $gradeFilter) {
            $matchSearch = empty($search) || 
                          strpos(strtolower($student['nim']), $search) !== false ||
                          strpos(strtolower($student['nama']), $search) !== false;
            
            $matchGrade = empty($gradeFilter) || $student['grade'] === $gradeFilter;
            
            return $matchSearch && $matchGrade;
        });
    }

    // 4. Hitung total dan pagination
    $total = count($filteredStudents);
    $perPage = 5;
    $totalPages = max(1, ceil($total / $perPage));
    $page = isset($_GET['page']) ? min(max(1, (int)$_GET['page']), $totalPages) : 1;
    $offset = ($page - 1) * $perPage;

    // 5. Slice data untuk halaman saat ini
    $currentPageStudents = array_slice(array_values($filteredStudents), $offset, $perPage);

    // 6. Generate pagination links dengan mempertahankan filter
    $paginationLinks = [];
    for ($i = 1; $i <= $totalPages; $i++) {
        $queryParams = $_GET;
        $queryParams['page'] = $i;
        $paginationLinks[] = http_build_query($queryParams);
    }

} catch (Exception $e) {
    $error = "Gagal membaca data: " . $e->getMessage();
    $currentPageStudents = [];
    $totalPages = 1;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Mahasiswa - E-Rapor Universitas Bakwan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main class="container mt-5">
        <h2 class="mb-4">Data Nilai Mahasiswa</h2>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3">
                    <div class="d-flex gap-2 w-50">
                        <input type="text" id="searchInput" class="form-control" placeholder="Cari mahasiswa...">
                        <select class="form-select w-auto" id="filterGrade">
                            <option value="">Semua Grade</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                            <option value="D">D</option>
                            <option value="E">E</option>
                        </select>
                    </div>
                    <div>
                        <a href="export.php" class="btn btn-primary me-2">Export CSV</a>
                        <button onclick="printSelectedData()" class="btn btn-primary">Print</button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>NIM</th>
                                <th>Nama</th>
                                <th>Tugas</th>
                                <th>UTS</th>
                                <th>UAS</th>
                                <th>Nilai Akhir</th>
                                <th>Grade</th>
                                <th>Aksi</th>
                                <th class="text-center"><input type="checkbox" id="selectAll"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($currentPageStudents)): ?>
                            <tr>
                                <td colspan="9" class="text-center">Tidak ada data yang sesuai dengan pencarian</td>
                            </tr>
                            <?php else: ?>
                                <?php foreach ($currentPageStudents as $student): ?>
                                <tr>
                                    <td><?= htmlspecialchars($student['nim']) ?></td>
                                    <td class="text-left"><?= htmlspecialchars($student['nama']) ?></td>
                                    <td><?= $student['tugas'] ?></td>
                                    <td><?= $student['uts'] ?></td>
                                    <td><?= $student['uas'] ?></td>
                                    <td><?= number_format($student['nilai_akhir'], 2) ?></td>
                                    <td>
                                        <span class="grade-badge grade-<?= $student['grade'] ?>">
                                            <?= $student['grade'] ?>
                                        </span>
                                    </td>
                                    <td class="action-buttons">
                                        <a href="edit_student.php?nim=<?= $student['nim'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                        <button class="btn btn-sm btn-danger delete-btn" data-nim="<?= $student['nim'] ?>">Hapus</button>
                                    </td>
                                    <td>
                                        <input type="checkbox" class="student-checkbox" data-student='<?= json_encode($student) ?>'>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <nav>
                    <ul class="pagination justify-content-center">
                        <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                            <a class="page-link" href="?<?= $paginationLinks[max(0, $page - 2)] ?>">Previous</a>
                        </li>
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                            <a class="page-link" href="?<?= $paginationLinks[$i-1] ?>"><?= $i ?></a>
                        </li>
                        <?php endfor; ?>
                        <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                            <a class="page-link" href="?<?= $paginationLinks[min($totalPages - 1, $page)] ?>">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </main>

    <footer class="footer mt-auto">
        <div class="container">
            <p>&copy; <?php echo date("Y"); ?> E-Rapor Universitas Bakwan</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="assets/js/script.js"></script>
    <script>
        function printSelectedData() {
            const selectedStudents = Array.from(document.querySelectorAll('.student-checkbox:checked')).map(checkbox => JSON.parse(checkbox.dataset.student));
            if (selectedStudents.length === 0) {
                Swal.fire({
                    title: 'Peringatan',
                    text: 'Silakan pilih data yang ingin dicetak',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
                return;
            }

            // Kirim data ke print_selected.php menggunakan form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'print_selected.php';
            form.target = '_blank';
            form.style.display = 'none';

            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'students';
            input.value = JSON.stringify(selectedStudents);

            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);
        }

        document.getElementById('selectAll').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.student-checkbox');
            checkboxes.forEach(checkbox => checkbox.checked = this.checked);
        });
    </script>
</body>
</html>
