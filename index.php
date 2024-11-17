<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header('Location: login.php');
    exit();
}

$firstname = $_SESSION['firstname'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome, <?php echo htmlspecialchars($firstname); ?> - Kledingwinkel</title>
    <link rel="stylesheet" href="./css/index.css">
</head>
<body>
    <div class="welcome-container">
        <h2>Welcome, <?php echo htmlspecialchars($firstname); ?>!</h2>
        <p>Browse our latest collection of clothing and place your orders.</p>
        
        <div class="clothing-items">
            <h3>Our Latest Collection</h3>
            <div class="item">
                <p>Item 1</p>
            </div>
            <div class="item">
                <p>Item 2</p>
            </div>
        </div>
    </div>

    <div class="logout">
        <a href="logout.php">Logout</a>
    </div>
</body>
</html>