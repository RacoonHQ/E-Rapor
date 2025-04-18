<?php
require_once 'config.php';

function readJSONFile($filename) {
    $fullPath = DATA_PATH . '/' . basename($filename);
    if (file_exists($fullPath)) {
        $jsonData = file_get_contents($fullPath);
        $data = json_decode($jsonData, true);
        
        // Untuk data_mahasiswa.json
        if (isset($data['students'])) {
            return $data['students'];
        }
        // Untuk user_login.json
        else if (isset($data['users'])) {
            return $data['users'];
        }
        return [];
    }
    return [];
}

function writeJSONFile($filename, $data) {
    $fullPath = DATA_PATH . '/' . basename($filename);
    
    // Menentukan struktur berdasarkan filename
    if (basename($filename) === 'data_mahasiswa.json') {
        $jsonData = ['students' => $data];
    } else if (basename($filename) === 'user_login.json') {
        $jsonData = ['users' => $data];
    }
    
    file_put_contents($fullPath, json_encode($jsonData, JSON_PRETTY_PRINT));
}

function validateUser($username, $password) {
    $users = readJSONFile("user_login.json");
    foreach ($users as $user) {
        if ($user['username'] === $username && 
            password_verify($password, $user['password'])) {
            return true;
        }
    }
    return false;
}

function addUser($username, $password) {
    $users = readJSONFile("user_login.json");
    foreach ($users as $user) {
        if ($user['username'] === $username) {
            return false;
        }
    }
    $users[] = [
        'username' => $username,
        'password' => password_hash($password, PASSWORD_DEFAULT)
    ];
    writeJSONFile("user_login.json", $users);
    return true;
}

function addStudent($data) {
    $students = readJSONFile("data_mahasiswa.json");
    $students[] = $data;
    writeJSONFile("data_mahasiswa.json", $students);
}

function updateStudent($nim, $newData) {
    $students = readJSONFile("data_mahasiswa.json");
    foreach ($students as &$student) {
        if ($student['nim'] === $nim) {
            $student = $newData;
            break;
        }
    }
    writeJSONFile("data_mahasiswa.json", $students);
}

function deleteStudent($nim) {
    $students = readJSONFile("data_mahasiswa.json");
    $students = array_filter($students, function($student) use ($nim) {
        return $student['nim'] !== $nim;
    });
    writeJSONFile("data_mahasiswa.json", array_values($students));
}

function calculateGrade($score) {
    foreach (GRADE_RANGES as $grade => $minScore) {
        if ($score >= $minScore) return $grade;
    }
    return 'E';
}

function calculateFinalScore($tugas, $uts, $uas) {
    return ($tugas * BOBOT_TUGAS) + 
           ($uts * BOBOT_UTS) + 
           ($uas * BOBOT_UAS);
}

function validateNIM($nim) {
    $students = readJSONFile("data_mahasiswa.json");
    foreach ($students as $student) {
        if ($student['nim'] === $nim) {
            return false;
        }
    }
    return true;
}

function exportToCSV() {
    $students = readJSONFile("data_mahasiswa.json");
    $output = fopen('php://output', 'w');
    
    fputcsv($output, ['NIM', 'Nama', 'Tugas', 'UTS', 'UAS', 'Nilai Akhir', 'Grade']);
    
    foreach ($students as $student) {
        fputcsv($output, [
            $student['nim'],
            $student['nama'],
            $student['tugas'],
            $student['uts'],
            $student['uas'],
            $student['nilai_akhir'],
            $student['grade']
        ]);
    }
    fclose($output);
}

function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function saveStudentPhotoAndDate($nim, $photoFile, $tanggalLahir) {
    $students = readJSONFile("data_mahasiswa.json");
    foreach ($students as &$student) {
        if ($student['nim'] === $nim) {
            // Simpan tanggal lahir
            $student['tanggal_lahir'] = $tanggalLahir;

            // Simpan pas foto jika ada file yang diunggah
            if ($photoFile && $photoFile['error'] === UPLOAD_ERR_OK) {
                $targetDir = "assets/images/pas-foto/";
                $targetFile = $targetDir . basename($photoFile['name']);
                $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

                // Validasi tipe file
                $allowedTypes = ['jpg', 'jpeg', 'png'];
                if (in_array($fileType, $allowedTypes)) {
                    // Pindahkan file ke direktori target
                    if (move_uploaded_file($photoFile['tmp_name'], $targetFile)) {
                        $student['pas_foto'] = $targetFile;
                    }
                }
            }
            break;
        }
    }
    writeJSONFile("data_mahasiswa.json", $students);
}
?>
