<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matrix_number = $_POST['matrix_number'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // Handle file upload
    $photo = $_FILES['photo']['name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($photo);
    $max_file_size = 2 * 1024 * 1024; // 2MB
    $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];

    // Validate file type and size
    if ($_FILES['photo']['size'] > $max_file_size) {
        echo "<div class='error'>Sorry, your file is too large.</div>";
    } elseif (!in_array($image_file_type, $allowed_types)) {
        echo "<div class='error'>Sorry, only JPG, JPEG, PNG & GIF files are allowed.</div>";
    } else {
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
            // Check if matrix number already exists
            $check_user = $conn->prepare("SELECT * FROM users WHERE matrix_number = ?");
            $check_user->bind_param("s", $matrix_number);
            $check_user->execute();
            $result = $check_user->get_result();

            if ($result->num_rows > 0) {
                echo "<div class='error'>Matrix number already exists!</div>";
            } else {
                // Insert into users table
                $stmt = $conn->prepare("INSERT INTO users (matrix_number, password) VALUES (?, ?)");
                $stmt->bind_param("ss", $matrix_number, $password);
                $stmt->execute();

                // Insert into profiles table with the uploaded photo path
                $stmt2 = $conn->prepare("INSERT INTO profiles (matrix_number, name, email, phone, photo) VALUES (?, ?, ?, ?, ?)");
                $stmt2->bind_param("sssss", $matrix_number, $name, $email, $phone, $target_file);
                $stmt2->execute();

                echo "<div class='success'>Registration successful! <a href='login.php'>Login here</a></div>";
            }
        } else {
            echo "<div class='error'>Sorry, there was an error uploading your photo.</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Register</h2>
        <form method="post" enctype="multipart/form-data">
            <input type="text" name="matrix_number" placeholder="Matrix Number" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="phone" placeholder="Phone Number" required>
            <input type="file" name="photo" required>
            <button type="submit">Register</button>
        </form>
        <p class="register-link">Already have an account? <a href="login.php">Login here</a></p>
    </div>
    <footer class="text-center mt-5">
    <p>© 2025 UiTM Student E-Profile</p>
</footer>

</body>
</html>
