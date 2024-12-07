<?php
session_start();

// Controleer of de gebruiker is ingelogd
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Haal het kledingstuk op uit de database
require_once __DIR__ . '/classes/Clothing.php';
require_once __DIR__ . '/classes/User.php';

// Haal het kledingstuk op met de ID uit de URL
$itemId = isset($_GET['id']) ? $_GET['id'] : null;
$item = null;

if ($itemId) {
    $item = Faisalcollinet\Wardrobe\Clothing::getClothingById($itemId);
}

// Haal het gebruikerssaldo op
$userBalance = Faisalcollinet\Wardrobe\User::getUserBalance($user_id);

// Als het kledingstuk niet bestaat, redirect dan terug naar de index
if (!$item || !is_array($item)) {
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($item['name']); ?> - Garderobe</title>
    <link rel="stylesheet" href="./css/index.css"> <!-- Algemene stijlen -->
    <link rel="stylesheet" href="./css/productdetail.css"> <!-- Productdetail specifieke stijlen -->
</head>
<body>
    <!-- Navigatiebalk -->
    <div class="navbar">
        <div class="logo">Garderobe</div>
        <div class="user-info">
            <span><?php echo htmlspecialchars($_SESSION['firstname'] ?? 'Gast'); ?></span>
            <span>€<?php echo number_format($userBalance, 2); ?></span>
            <a href="cart.php" class="cart-link">Cart (<?php echo count($_SESSION['cart'] ?? []); ?>)</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <!-- Product Detail Pagina -->
    <div class="product-detail">
        <!-- Productafbeelding aan de linkerzijde -->
        <div class="product-image">
            <!-- Controleer of de afbeelding URL leeg is en geef een fallback afbeelding -->
            <img src="<?php echo htmlspecialchars($item['image'] ?? 'default-image.jpg'); ?>" alt="<?php echo htmlspecialchars($item['name'] ?? ''); ?>">
        </div>
        <!-- Productinformatie aan de rechterzijde -->
        <div class="product-info">
            <h1><?php echo htmlspecialchars($item['brand'] ?? ''); ?></h1>
            <h2><?php echo nl2br(htmlspecialchars($item['description'] ?? '')); ?></h2>
            <p><?php echo nl2br(htmlspecialchars($item['additional_description'] ?? '')); ?></p>
            <div class="product-details">
                <p><strong>Category:</strong> <?php echo htmlspecialchars($item['category'] ?? ''); ?></p>
                <p><strong>Size:</strong> <?php echo htmlspecialchars($item['size'] ?? ''); ?></p>
                <p class="price">€<?php echo number_format($item['price'], 2, ',', '.'); ?></p>
            </div>

            <!-- Voeg toe aan winkelwagen -->
            <form action="add_to_cart.php" method="POST">
                <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
                <button type="submit" class="add-to-cart-btn">Add to Cart</button>
            </form>
        </div>
    </div>
</body>
</html>