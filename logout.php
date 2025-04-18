<?php
session_start();
session_destroy();
header("Location: index.php");  // Mengubah redirect ke index.php
exit();
?>
