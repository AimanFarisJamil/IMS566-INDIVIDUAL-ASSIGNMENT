<?php
$host = "localhost";
$user = "root";  // Default user in XAMPP
$pass = "";      // Leave empty in XAMPP
$db = "uitm_eprofile";

// Connect to database
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
