<?php
session_start();

// Als de gebruiker niet ingelogd is, doorsturen naar de login pagina
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header('Location: login.php');
    exit();
}

$firstname = $_SESSION['firstname'];
$user_id = $_SESSION['user_id'];

// Include de Clothing class om items op te halen
require_once __DIR__ . '/classes/Clothing.php';
$clothingItems = Faisalcollinet\Wardrobe\Clothing::getAllClothing();

// Include de User class om het saldo van de gebruiker op te halen
require_once __DIR__ . '/classes/User.php';
$userBalance = Faisalcollinet\Wardrobe\User::getUserBalance($user_id);

// Haal het aantal items in de winkelwagen op
$cartItemCount = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Garderobe</title>
    <link rel="stylesheet" href="./css/index.css">
</head>
<body>
    <!-- Navigatiebalk -->
    <div class="navbar">
        <div class="logo">Garderobe</div>
        <div class="user-info">
            <span><?php echo htmlspecialchars($firstname); ?></span>
            <span>€<?php echo number_format($userBalance, 2); ?></span> <!-- Saldoweergave -->
            
            <!-- Toevoegen van de Cart -->
            <a href="cart.php" class="cart-link">Cart (<?php echo $cartItemCount; ?>)</a> <!-- Aantal items in de winkelwagentje -->

            <a href="logout.php">Logout</a>
        </div>
    </div>

    <!-- Kledingitems -->
    <div class="clothing-items">
        <div class="items-grid">
            <?php foreach ($clothingItems as $item): ?>
                <div class="item">
                    <img src="<?php echo htmlspecialchars($item['image'] ?? ''); ?>" alt="<?php echo htmlspecialchars($item['name'] ?? ''); ?>" class="item-image">
                    <h4><?php echo htmlspecialchars($item['name'] ?? ''); ?></h4>
                    <p>€<?php echo number_format($item['price'], 2, ',', '.'); ?></p>
                    <p><?php echo htmlspecialchars($item['description'] ?? ''); ?></p>

                    <!-- Formulier om item toe te voegen aan winkelwagen -->
                    <form action="add_to_cart.php" method="POST">
                        <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
                        <button type="submit" class="add-to-cart-btn">Add to Cart</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>