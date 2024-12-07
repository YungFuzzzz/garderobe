<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

require_once __DIR__ . '/classes/Clothing.php';

// Haal alle producten op
$clothingItems = \Faisalcollinet\Wardrobe\Clothing::getAllClothing();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="./css/dashboard.css">
</head>
<body>
    <div class="dashboard-container">
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['firstname']); ?>!</h2>
        <p>Here you can manage the website content, users, and other settings.</p>
        
        <h3>Manage Products</h3>

        <!-- Product List -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Brand</th>
                    <th>Price</th>
                    <th>Category</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clothingItems as $item): ?>
                    <tr>
                        <td><?php echo $item['id']; ?></td>
                        <td><?php echo htmlspecialchars($item['brand']); ?></td>
                        <td><?php echo $item['price']; ?></td>
                        <td><?php echo htmlspecialchars($item['category']); ?></td>
                        <td>
                            <a href="edit_product.php?id=<?php echo $item['id']; ?>">Edit</a> | 
                            <a href="delete_product.php?id=<?php echo $item['id']; ?>" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Add Product Form -->
        <h3>Add New Product</h3>
        <form action="add_product.php" method="post">
            <label for="brand">Brand</label>
            <input type="text" name="brand" required><br>

            <label for="price">Price</label>
            <input type="number" name="price" required><br>

            <label for="category">Category</label>
            <input type="text" name="category" required><br>

            <label for="size">Size</label>
            <input type="text" name="size" required><br>

            <label for="description">Description</label>
            <textarea name="description" required></textarea><br>

            <label for="image">Image URL</label>
            <input type="text" name="image" required><br>

            <button type="submit">Add Product</button>
        </form>

        <div class="logout">
            <a href="logout.php">Logout</a>
        </div>
    </div>
</body>
</html>