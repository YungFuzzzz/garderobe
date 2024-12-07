<?php
session_start();

// Include Clothing class om de kledingitems te tonen
require_once __DIR__ . '/classes/Clothing.php';
require_once __DIR__ . '/classes/User.php'; // Zorg ervoor dat User.php ook ingeladen is

$cartItems = [];

// Haal de items uit de sessie winkelwagentje
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $itemId) {
        $item = Faisalcollinet\Wardrobe\Clothing::getClothingById($itemId);
        $cartItems[] = $item;
    }
}

// Haal het saldo van de gebruiker op
$userId = $_SESSION['user_id']; // Gebruikers-ID uit sessie
$currentBalance = Faisalcollinet\Wardrobe\User::getUserBalance($userId);

// Subtotaal berekenen
$subtotal = 0;
foreach ($cartItems as $item) {
    $subtotal += $item['price'];
}

// Verwijder een item uit de cart
if (isset($_POST['remove_item'])) {
    $itemIdToRemove = $_POST['remove_item'];

    // Verwijder het item uit de sessie winkelwagentje
    if (($key = array_search($itemIdToRemove, $_SESSION['cart'])) !== false) {
        unset($_SESSION['cart'][$key]);
    }

    header('Location: cart.php'); // Redirect terug naar cart.php
    exit();
}

// Verwerken van de aankoop
if (isset($_POST['purchase'])) {
    if ($currentBalance >= $subtotal) {
        // Verminder het saldo van de gebruiker
        $newBalance = $currentBalance - $subtotal;
        Faisalcollinet\Wardrobe\User::updateUserBalance($userId, $newBalance);

        // Verwijder de items uit de cart
        unset($_SESSION['cart']);

        // Redirect naar index.php na succesvolle aankoop
        header('Location: index.php'); 
        exit();
    } else {
        echo "Je hebt niet genoeg saldo om deze aankoop te doen.";
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Winkelwagen</title>
    <link rel="stylesheet" href="./css/cart.css">
</head>
<body>
    <div class="navbar">
        <div class="logo">Garderobe</div>
        <div class="user-info">
            <span>Welkom, <?php echo htmlspecialchars($_SESSION['firstname']); ?></span>
            <span>Saldo: €<?php echo number_format($currentBalance, 2); ?></span>
            <a href="logout.php">Logout</a>
        </div>
        <div class="cart-info">
            <a href="cart.php">Winkelwagen (<?php echo count($_SESSION['cart'] ?? []); ?>)</a>
        </div>
    </div>

    <div class="cart-container">
        <h1>Je Winkelwagentje</h1>

        <?php if (empty($cartItems)): ?>
            <p>Je winkelwagentje is leeg.</p>
        <?php else: ?>
            <ul class="cart-items-list">
                <?php foreach ($cartItems as $item): ?>
                    <li class="cart-item">
                        <img src="<?php echo htmlspecialchars($item['image'] ?? 'default_image.jpg'); ?>" alt="<?php echo htmlspecialchars($item['name'] ?? 'Onbekend product'); ?>" class="item-image">
                        <div class="item-details">
                            <p><strong><?php echo htmlspecialchars($item['name'] ?? 'Onbekend product'); ?></strong></p>
                            <p>Prijs: €<?php echo number_format($item['price'] ?? 0, 2); ?></p>
                        </div>
                        <form action="cart.php" method="POST" class="remove-item-form">
                            <input type="hidden" name="remove_item" value="<?php echo htmlspecialchars($item['id'] ?? ''); ?>">
                            <button type="submit" class="remove-item-btn">Verwijder</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>

            <div class="cart-summary">
                <p><strong>Subtotaal: €<?php echo number_format($subtotal, 2); ?></strong></p>

                <?php if ($currentBalance >= $subtotal): ?>
                    <form method="POST">
                        <button type="submit" name="purchase" class="purchase-btn">Bevestig Aankoop</button>
                    </form>
                <?php else: ?>
                    <p>Je hebt niet genoeg saldo om deze aankoop te doen.</p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>