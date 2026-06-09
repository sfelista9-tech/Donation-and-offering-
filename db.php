<?php
$host = "localhost";
$user = "root";
$password = "aronClement@123";
$dbname = "smart_donation_system";

// Create connection
$conn = mysqli_connect($host, $user, $password, $dbname);

// Check connection
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Set charset to utf8
mysqli_set_charset($conn, "utf8");
?>
