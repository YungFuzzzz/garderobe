<?php
session_start();

require_once __DIR__ . '/classes/ShoppingCart.php';

$shoppingCart = \Faisalcollinet\Wardrobe\ShoppingCart::getInstance();
$cartItems = $shoppingCart->getCartItems();
$subtotal = $shoppingCart->getSubtotal();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="css/cart.css">
</head>
<body>
    <div class="cart-container">
        <h1>Your Shopping Cart</h1>
        <?php if (empty($cartItems)): ?>
            <p>Your cart is empty!</p>
        <?php else: ?>
            <div class="cart-items">
                <?php foreach ($cartItems as $item): ?>
                    <div class="cart-item">
                        <img src="<?php echo htmlspecialchars($item['image'] ?? ''); ?>" alt="<?php echo htmlspecialchars($item['description'] ?? ''); ?>" class="cart-item-image">
                        <div class="cart-item-details">
                            <h4><?php echo htmlspecialchars($item['description'] ?? ''); ?></h4>
                            <p>€<?php echo number_format($item['price'], 2, ',', '.'); ?></p>
                        </div>
                        <form action="remove_from_cart.php" method="POST">
                            <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
                            <button type="submit" class="remove-btn">Remove</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="cart-total">
                <h3>Total: €<?php echo number_format($subtotal, 2, ',', '.'); ?></h3>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>