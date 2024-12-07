<?php
session_start();
require_once __DIR__ . '/classes/Clothing.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

$id = $_GET['id'] ?? null;
$errors = [];
$success_message = '';

if ($id) {
    $product = \Faisalcollinet\Wardrobe\Clothing::getClothingById($id);
    
    if (!$product) {
        $errors[] = "Product not found.";
    }
} else {
    $errors[] = "Product ID is missing.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $brand = $_POST['brand'] ?? '';
    $price = $_POST['price'] ?? '';
    $category = $_POST['category'] ?? '';
    $size = $_POST['size'] ?? '';
    $description = $_POST['description'] ?? '';
    $additional_description = $_POST['additional_description'] ?? '';
    $image = $_POST['image'] ?? '';

    if (empty($brand) || empty($price) || empty($category) || empty($size) || empty($description) || empty($image)) {
        $errors[] = 'All fields are required.';
    }

    if (empty($errors)) {
        $update_success = \Faisalcollinet\Wardrobe\Clothing::updateClothing(
            $brand, $price, $category, $size, $description, $additional_description, $image, $id
        );

        if ($update_success) {
            $success_message = "Product updated successfully!";
            header('Location: admin_dashboard.php');
            exit();
        } else {
            $errors[] = "Error updating product.";
        }
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
    <link href="https://fonts.googleapis.com/css2?family=Mononoki&display=swap" rel="stylesheet">
</head>
<body>
    <div class="navbar">
        <div class="logo">Admin Dashboard</div>
        <div class="user-info">
            <span>Welcome, <?php echo htmlspecialchars($_SESSION['firstname']); ?>!</span>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <div class="dashboard-container">
        <h2>Edit Product</h2>

        <?php if (!empty($errors)): ?>
            <ul class="error-messages">
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        
        <?php if (!empty($success_message)): ?>
            <p class="success-message"><?php echo htmlspecialchars($success_message); ?></p>
        <?php endif; ?>

        <!-- Formulier voor bewerken van product -->
        <form action="edit_product.php?id=<?php echo $product['id']; ?>" method="post">
            <label for="brand">Brand</label>
            <input type="text" name="brand" value="<?php echo htmlspecialchars($product['brand']); ?>" required><br>

            <label for="price">Price</label>
            <input type="number" step="0.01" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" required><br>

            <label for="category">Category</label>
            <input type="text" name="category" value="<?php echo htmlspecialchars($product['category']); ?>" required><br>

            <label for="size">Size</label>
            <input type="text" name="size" value="<?php echo htmlspecialchars($product['size']); ?>" required><br>

            <label for="description">Description</label>
            <textarea name="description" required><?php echo htmlspecialchars($product['description']); ?></textarea><br>

            <label for="additional_description">Additional Description</label>
            <textarea name="additional_description"><?php echo htmlspecialchars($product['additional_description']); ?></textarea><br>

            <label for="image">Image URL</label>
            <input type="text" name="image" value="<?php echo htmlspecialchars($product['image']); ?>" required><br>

            <button type="submit">Update Product</button>
        </form>

        <div class="logout">
            <a href="logout.php">Logout</a>
        </div>
    </div>
</body>
</html>