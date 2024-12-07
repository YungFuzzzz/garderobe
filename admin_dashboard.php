<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

require_once __DIR__ . '/classes/Clothing.php';

$errors = [];
$success_message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $brand = trim($_POST['brand']);
    $price = floatval($_POST['price']);
    $category = trim($_POST['category']);
    $size = trim($_POST['size']);
    $description = trim($_POST['description']);
    $additional_description = trim($_POST['additional_description']);
    $image = trim($_POST['image']);

    if (empty($brand)) $errors[] = "Brand is required.";
    if ($price <= 0 || !is_numeric($price)) $errors[] = "Price must be a positive number.";
    if (empty($category)) $errors[] = "Category is required.";
    if (empty($size)) $errors[] = "Size is required.";
    if (empty($description)) $errors[] = "Description is required.";
    if (empty($additional_description)) $errors[] = "Additional description is required.";
    if (empty($image) || !filter_var($image, FILTER_VALIDATE_URL)) $errors[] = "A valid image URL is required.";

    if (empty($errors)) {
        $result = \Faisalcollinet\Wardrobe\Clothing::addClothing(
            $brand, $price, $category, $size, $description, $additional_description, $image
        );
        if ($result) {
            $success_message = "Product added successfully!";
        } else {
            $errors[] = "Error adding product. Please try again.";
        }
    }
}

$clothingItems = \Faisalcollinet\Wardrobe\Clothing::getAllClothing();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
        <h2>Manage Products</h2>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Brand</th>
                    <th>Price</th>
                    <th>Category</th>
                    <th>Size</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clothingItems as $item): ?>
                    <tr>
                        <td><?php echo $item['id']; ?></td>
                        <td><?php echo htmlspecialchars($item['brand']); ?></td>
                        <td>€<?php echo number_format($item['price'], 2, ',', '.'); ?></td>
                        <td><?php echo htmlspecialchars($item['category']); ?></td>
                        <td><?php echo htmlspecialchars($item['size']); ?></td>
                        <td>
                            <a href="edit_product.php?id=<?php echo $item['id']; ?>">Edit</a> | 
                            <a href="delete_product.php?id=<?php echo $item['id']; ?>" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h3>Add New Product</h3>
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
        
        <form action="admin_dashboard.php" method="post">
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

            <label for="additional_description">Additional Description</label>
            <textarea name="additional_description"><?php echo htmlspecialchars($additional_description ?? ''); ?></textarea><br>

            <label for="image">Image URL</label>
            <input type="text" name="image" value="<?php echo htmlspecialchars($image ?? ''); ?>" required><br>

            <button type="submit">Add Product</button>
        </form>
    </div>
</body>
</html>