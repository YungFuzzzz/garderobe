<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

require_once __DIR__ . '/classes/Clothing.php';
require_once __DIR__ . '/classes/User.php';

$itemId = isset($_GET['id']) ? $_GET['id'] : null;
$item = null;

if ($itemId) {
    $item = Faisalcollinet\Wardrobe\Clothing::getClothingById($itemId);
}

$userBalance = Faisalcollinet\Wardrobe\User::getUserBalance($user_id);

if (!$item || !is_array($item)) {
    header('Location: index.php');
    exit();
}

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($item['name']); ?> - Garderobe</title>
    <link rel="stylesheet" href="./css/index.css">
    <link rel="stylesheet" href="./css/productdetail.css">
</head>
<body>
    <div class="navbar">
        <div class="logo">Garderobe</div>
        <div class="user-info">
            <span><?php echo htmlspecialchars($_SESSION['firstname'] ?? 'Gast'); ?></span>
            <span>€<?php echo number_format($userBalance, 2); ?></span>
            <a href="cart.php" class="cart-link">Cart (<?php echo count($_SESSION['cart'] ?? []); ?>)</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>
    <div class="product-detail">
        <div class="product-image">
            <img src="<?php echo htmlspecialchars($item['image'] ?? 'default-image.jpg'); ?>" alt="<?php echo htmlspecialchars($item['name'] ?? ''); ?>">
        </div>
        <div class="product-info">
            <h1><?php echo htmlspecialchars($item['brand'] ?? ''); ?></h1>
            <h2><?php echo nl2br(htmlspecialchars($item['description'] ?? '')); ?></h2>
            <p><?php echo nl2br(htmlspecialchars($item['additional_description'] ?? '')); ?></p>
            <div class="product-details">
                <p><strong>Category:</strong> <?php echo htmlspecialchars($item['category'] ?? ''); ?></p>
                <p><strong>Size:</strong> <?php echo htmlspecialchars($item['size'] ?? ''); ?></p>
                <p class="price">€<?php echo number_format($item['price'], 2, ',', '.'); ?></p>
            </div>
            <form action="add_to_cart.php" method="POST">
                <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
                <button type="submit" class="add-to-cart-btn">Add to Cart</button>
            </form>
        </div>
    </div>
</body>
</html>