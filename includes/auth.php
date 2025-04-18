<?php
require_once 'config.php';
session_start();

// Session timeout after 30 minutes
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > SESSION_TIMEOUT)) {
    session_unset();
    session_destroy();
    header("Location: login.php?timeout=1");
    exit();
}

$_SESSION['last_activity'] = time();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>
