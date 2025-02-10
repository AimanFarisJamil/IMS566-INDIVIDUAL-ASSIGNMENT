<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['matrix_number'])) {
    header("Location: login.php");
    exit();
}

$matrix_number = $_SESSION['matrix_number'];
$stmt = $conn->prepare("SELECT * FROM profiles WHERE matrix_number = ?");
$stmt->bind_param("s", $matrix_number);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Fetch photo for the logged-in user
$photo = !empty($user['photo']) ? $user['photo'] : 'uploads/default-avatar.png';

// Fetch other profiles (limit to 5)
$other_profiles_stmt = $conn->prepare("SELECT * FROM profiles WHERE matrix_number != ? LIMIT 5");
$other_profiles_stmt->bind_param("s", $matrix_number);
$other_profiles_stmt->execute();
$other_profiles = $other_profiles_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <style>
        .card-img-top {
            height: 150px;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="profile-card text-center mb-4">
            <h2>Welcome, <?php echo htmlspecialchars($user['name']); ?>!</h2>
            <div class="mb-3">
                <img src="<?php echo htmlspecialchars($photo); ?>" alt="Profile Picture" class="rounded-circle" width="150" height="150">
            </div>
            <h3>Profile Information</h3>
            <p><strong>Matrix Number:</strong> <?php echo htmlspecialchars($user['matrix_number']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($user['phone']); ?></p>
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>

        <h3 class="text-center mb-4">Other Student Profiles</h3>
        <div class="row">
            <?php while ($profile = $other_profiles->fetch_assoc()): ?>
                <div class="col-md-4 col-lg-3 mb-4">
                    <div class="card h-100">
                        <img src="<?php echo htmlspecialchars(!empty($profile['photo']) ? $profile['photo'] : 'uploads/default-avatar.png'); ?>" alt="Profile Picture" class="card-img-top">
                        <div class="card-body text-center">
                            <h5 class="card-title"><?php echo htmlspecialchars($profile['name']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($profile['matrix_number']); ?></p>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <footer class="text-center mt-5">
        <p>© 2025 UiTM Student E-Profile</p>
    </footer>
</body>
</html>
