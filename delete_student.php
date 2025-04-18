<?php
require_once 'includes/auth.php';
require_once 'includes/functions.php';

if (isset($_GET['nim']) && !empty($_GET['nim'])) {
    try {
        deleteStudent($_GET['nim']);
        header("Location: data_mahasiswa.php?status=deleted");
    } catch (Exception $e) {
        header("Location: data_mahasiswa.php?error=delete_failed");
    }
    exit();
}
header("Location: data_mahasiswa.php");
?>
