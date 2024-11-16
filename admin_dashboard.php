<?php
session_start();

// Controleer of de gebruiker is ingelogd en een admin is
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    // Als de gebruiker niet is ingelogd of geen admin is, stuur hem dan terug naar de loginpagina
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="./css/dashboard.css">
</head>
<body>
    <div class="dashboard-container">
        <h2>Welcome Admin</h2>
        <p>Here you can manage the website content, users, and other settings.</p>
        
        <div class="actions">
            <a href="manage_users.php">Manage Users</a>
            <a href="settings.php">Settings</a>
        </div>

        <div class="logout">
            <a href="logout.php">Logout</a>
        </div>
    </div>
</body>
</html>