<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

require_once __DIR__ . '/classes/Clothing.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verkrijg en trim de invoer
    $brand = trim($_POST['brand']);
    $price = floatval($_POST['price']);
    $category = trim($_POST['category']);
    $size = trim($_POST['size']);
    $description = trim($_POST['description']);
    $image = trim($_POST['image']);

    // Server-side validaties
    if (empty($brand)) {
        $errors[] = "Brand is required.";
    }
    if ($price <= 0) {
        $errors[] = "Price must be a positive number.";
    }
    if (empty($category)) {
        $errors[] = "Category is required.";
    }
    if (empty($size)) {
        $errors[] = "Size is required.";
    }
    if (empty($description)) {
        $errors[] = "Description is required.";
    }
    if (empty($image) || !filter_var($image, FILTER_VALIDATE_URL)) {
        $errors[] = "A valid image URL is required.";
    }

    // Als er geen fouten zijn, probeer het product toe te voegen
    if (empty($errors)) {
        $result = \Faisalcollinet\Wardrobe\Clothing::addClothing($brand, $price, $category, $description, $image, $size);

        if ($result) {
            header('Location: admin_dashboard.php');
            exit();
        } else {
            $errors[] = "Error adding product. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="./css/dashboard.css">
</head>
<body>
    <div class="dashboard-container">
        <h2>Add New Product</h2>
        
        <!-- Fouten weergeven -->
        <?php if (!empty($errors)): ?>
            <ul class="error-messages">
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        
        <form action="add_product.php" method="post">
            <label for="brand">Brand</label>
            <input type="text" name="brand" value="<?php echo htmlspecialchars($brand ?? ''); ?>" required><br>

            <label for="price">Price</label>
            <input type="number" step="0.01" name="price" value="<?php echo htmlspecialchars($price ?? ''); ?>" required><br>

            <label for="category">Category</label>
            <input type="text" name="category" value="<?php echo htmlspecialchars($category ?? ''); ?>" required><br>

            <label for="size">Size</label>
            <input type="text" name="size" value="<?php echo htmlspecialchars($size ?? ''); ?>" required><br>

            <label for="description">Description</label>
            <textarea name="description" required><?php echo htmlspecialchars($description ?? ''); ?></textarea><br>

            <label for="image">Image URL</label>
            <input type="text" name="image" value="<?php echo htmlspecialchars($image ?? ''); ?>" required><br>

            <button type="submit">Add Product</button>
        </form>
    </div>
</body>
</html>