<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

require_once __DIR__ . '/classes/Clothing.php';

// Verkrijg het product op basis van de ID
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
    // Verkrijg de bijgewerkte gegevens
    $id = $_GET['id'];
    $brand = $_POST['brand'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $size = $_POST['size'];
    $description = $_POST['description'];
    $image = $_POST['image'];

    // Werk het product bij
    $result = \Faisalcollinet\Wardrobe\Clothing::updateClothing($id, $brand, $price, $category, $description, $image, $size);

    if ($result) {
        header('Location: admin_dashboard.php');
        exit();
    } else {
        echo "Error updating product!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="./css/dashboard.css">
</head>
<body>
    <div class="dashboard-container">
        <h2>Edit Product</h2>
        <form action="edit_product.php?id=<?php echo $product['id']; ?>" method="post">
            <label for="brand">Brand</label>
            <input type="text" name="brand" value="<?php echo htmlspecialchars($product['brand']); ?>" required><br>

            <label for="price">Price</label>
            <input type="number" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" required><br>

            <label for="category">Category</label>
            <input type="text" name="category" value="<?php echo htmlspecialchars($product['category']); ?>" required><br>

            <label for="size">Size</label>
            <input type="text" name="size" value="<?php echo htmlspecialchars($product['size']); ?>" required><br>

            <label for="description">Description</label>
            <textarea name="description" required><?php echo htmlspecialchars($product['description']); ?></textarea><br>

            <label for="image">Image URL</label>
            <input type="text" name="image" value="<?php echo htmlspecialchars($product['image']); ?>" required><br>

            <button type="submit">Update Product</button>
        </form>
    </div>
</body>
</html>