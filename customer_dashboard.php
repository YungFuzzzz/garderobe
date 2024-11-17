<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header('Location: login.php');
    exit();
}

// Haal de firstname uit de sessie
$firstname = $_SESSION['firstname'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard</title>
    <link rel="stylesheet" href="./css/dashboard.css">
</head>
<body>
    <div class="dashboard-container">
        <h2>Welcome, <?php echo htmlspecialchars($firstname); ?>!</h2> <!-- Gebruik de firstname hier -->
        <p>Here you can view your account information, orders, and more.</p>
        <div class="logout">
            <a href="logout.php">Logout</a>
        </div>
    </div>
</body>
</html>