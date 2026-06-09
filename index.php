<?php
session_start();

// If user is logged in, redirect to dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: admin/dashboard.php");
    exit();
}

// Otherwise redirect to login
header("Location: auth/login.php");
exit();
?>
