<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header('Location: login.php');
    exit();
}

$firstname = $_SESSION['firstname'];

// Include Clothing class to fetch items
require_once __DIR__ . '/classes/Clothing.php';
$clothingItems = Faisalcollinet\Wardrobe\Clothing::getAllClothing();
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
            <div class="items-grid">
                <?php foreach ($clothingItems as $item): ?>
                    <div class="item">
                        <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name'] ?? ''); ?>" class="item-image">
                        <h4><?php echo htmlspecialchars($item['name'] ?? ''); ?></h4>
                        <p><?php echo htmlspecialchars($item['description'] ?? ''); ?></p>
                        <p>Size: <?php echo htmlspecialchars($item['size'] ?? ''); ?></p>
                        <p>€<?php echo htmlspecialchars($item['price'] ?? ''); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="logout">
        <a href="logout.php">Logout</a>
    </div>
</body>
</html>