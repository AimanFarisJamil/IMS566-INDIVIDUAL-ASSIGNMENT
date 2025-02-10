<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matrix_number = $_POST['matrix_number'];
    $password = $_POST['password'];

    // Fetch user details
    $stmt = $conn->prepare("SELECT * FROM users WHERE matrix_number = ?");
    $stmt->bind_param("s", $matrix_number);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['matrix_number'] = $row['matrix_number'];
            header("Location: profile.php"); // Redirect to profile page
            exit();
        } else {
            echo "<div class='error'>Invalid password!</div>";
        }
    } else {
        echo "<div class='error'>Matrix number not found!</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <form method="post">
            <input type="text" name="matrix_number" placeholder="Matrix Number" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <p class="register-link">Don't have an account? <a href="register.php">Register here</a></p>
    </div>
    <footer class="text-center mt-5">
    <p>© 2025 UiTM Student E-Profile</p>
</footer>
</body>
</html>
