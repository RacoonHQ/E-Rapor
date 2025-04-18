<?php
require_once 'includes/auth.php';
require_once 'includes/functions.php';

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);

if (isset($input['nim']) && isset($input['tanggal_lahir'])) {
    $nim = $input['nim'];
    $tanggalLahir = $input['tanggal_lahir'];

    $students = readJSONFile("data/data_mahasiswa.json");
    foreach ($students as &$student) {
        if ($student['nim'] === $nim) {
            $student['tanggal_lahir'] = $tanggalLahir;
            writeJSONFile("data/data_mahasiswa.json", $students);
            echo json_encode(['success' => true]);
            exit();
        }
    }
}

echo json_encode(['success' => false]);
