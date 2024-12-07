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

// Haal alle merken op uit de database
$brands = Faisalcollinet\Wardrobe\Clothing::getAllBrands();

// Controleer of er een merk is geselecteerd en haal de kledingitems op op basis van dat merk
if (isset($_POST['brand']) && !empty($_POST['brand'])) {
    $brand = $_POST['brand'];
    $clothingItems = Faisalcollinet\Wardrobe\Clothing::getAllClothingByBrand($brand);
} else {
    $clothingItems = Faisalcollinet\Wardrobe\Clothing::getAllClothing();
}

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
            <span>€<?php echo number_format($userBalance, 2); ?></span>
            
            <!-- Toevoegen van de Cart -->
            <a href="cart.php" class="cart-link">Cart (<?php echo $cartItemCount; ?>)</a>

            <a href="logout.php">Logout</a>
        </div>
    </div>

    <!-- Sorteren Formulier -->
    <form action="index.php" method="POST" class="sort-form">
        <select name="brand" id="brand">
            <option value="">Selecteer Merk</option>
            <?php foreach ($brands as $brand): ?>
                <option value="<?php echo htmlspecialchars($brand['brand']); ?>">
                    <?php echo htmlspecialchars($brand['brand']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit" class="sorter-btn">Filteren</button>
    </form>

    <!-- Kledingitems -->
    <div class="clothing-items">
        <div class="items-grid">
            <?php foreach ($clothingItems as $item): ?>
                <div class="item">
                    <a href="product_detail.php?id=<?php echo $item['id']; ?>">
                        <img src="<?php echo htmlspecialchars($item['image'] ?? ''); ?>" alt="<?php echo htmlspecialchars($item['name'] ?? ''); ?>" class="item-image">
                        <h4><?php echo htmlspecialchars($item['name'] ?? ''); ?></h4>
                    </a>
                    <p>€<?php echo number_format($item['price'], 2, ',', '.'); ?></p>
                    <p>Size: <?php echo htmlspecialchars($item['size'] ?? ''); ?></p>

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