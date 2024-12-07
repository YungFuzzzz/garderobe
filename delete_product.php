<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

require_once __DIR__ . '/classes/Clothing.php';

if (!isset($_GET['id'])) {
    header('Location: admin_dashboard.php');
    exit();
}

$product = \Faisalcollinet\Wardrobe\Clothing::getClothingById($_GET['id']);

if (!$product) {
    echo "Product not found!";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = \Faisalcollinet\Wardrobe\Clothing::deleteClothing($_GET['id']);

    if ($result) {
        header('Location: admin_dashboard.php');
        exit();
    } else {
        echo "Error deleting product!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Product</title>
    <link rel="stylesheet" href="./css/dashboard.css">
</head>
<body>
    <div class="dashboard-container">
        <h2>Delete Product</h2>
        <p>Are you sure you want to delete the product: <strong><?php echo htmlspecialchars($product['brand']); ?></strong>?</p>
        <form action="delete_product.php?id=<?php echo $product['id']; ?>" method="post">
            <button type="submit">Yes, Delete</button>
            <a href="admin_dashboard.php">Cancel</a>
        </form>
    </div>
</body>
</html>