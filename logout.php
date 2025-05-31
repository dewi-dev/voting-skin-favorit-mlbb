<?php
session_start();

// Hapus semua session
$_SESSION = [];
session_unset();
session_destroy();

// Arahkan ke login page
header("Location: login.php");
exit();