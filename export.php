<?php
require_once 'includes/auth.php';
require_once 'includes/functions.php';

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="data_mahasiswa.csv"');

exportToCSV();
?>
