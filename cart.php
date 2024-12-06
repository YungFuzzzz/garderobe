<?php
session_start();

// Include Clothing class om de kledingitems te tonen
require_once __DIR__ . '/classes/Clothing.php';

$cartItems = [];

if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $itemId) {
        // Haal kledingitem op op basis van item_id
        $item = Faisalcollinet\Wardrobe\Clothing::getClothingById($itemId); // Voeg deze functie toe in je Clothing class
        $cartItems[] = $item;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <link rel="stylesheet" href="./css/index.css">
</head>
<body>
    <!-- Navigatiebalk -->
    <div class="navbar">
        <div class="logo">Garderobe</div>
        <div class="user-info">
            <span><?php echo htmlspecialchars($firstname); ?></span>
            <a href="logout.php">Logout</a>
        </div>
        <div class="cart-info">
            <a href="cart.php">Cart (<?php echo count($_SESSION['cart'] ?? []); ?>)</a>
        </div>
    </div>

    <!-- Winkelwagen Items -->
    <div class="cart-items">
        <?php if (empty($cartItems)): ?>
            <p>Your cart is empty.</p>
        <?php else: ?>
            <ul>
                <?php foreach ($cartItems as $item): ?>
                    <li>
                        <img src="<?php echo htmlspecialchars($item['image'] ?? ''); ?>" alt="<?php echo htmlspecialchars($item['name'] ?? ''); ?>" class="item-image">
                        <p><?php echo htmlspecialchars($item['name'] ?? ''); ?></p>
                        <p>€<?php echo htmlspecialchars($item['price'] ?? ''); ?></p>
                        <form action="remove_from_cart.php" method="POST">
                            <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
                            <button type="submit">Remove</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>

    <a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>
</body>
</html>