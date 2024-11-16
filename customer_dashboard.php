<?php
session_start();

// Controleer of de gebruiker is ingelogd en een klant is
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    // Als de gebruiker niet is ingelogd of geen klant is, stuur hem dan terug naar de loginpagina
    header('Location: login.php');
    exit();
}
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
        <h2>Welcome Customer</h2>
        <p>Here you can view your account information, orders, and more.</p>
        
        <div class="actions">
            <a href="account_settings.php">Account Settings</a>
            <a href="orders.php">Your Orders</a>
        </div>

        <div class="logout">
            <a href="logout.php">Logout</a>
        </div>
    </div>
</body>
</html>