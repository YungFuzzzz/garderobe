<?php
require_once 'vendor/autoload.php';
require_once 'classes/Db.php';

use Faisalcollinet\Wardrobe\User;

$errorMessage = '';

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Verwijder var_dump om uitvoer te voorkomen die headers verstoort
    // var_dump($email, $password); // Verwijder deze regel

    if (User::login($email, $password)) {
        // Redirect naar de juiste pagina (index.php bijvoorbeeld)
        header('Location: index.php'); // Of naar een dashboard als je dat hebt
        exit();
    } else {
        $errorMessage = "Invalid credentials.";
    }
}

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="./css/login.css">
</head>
<body>
    <div class="flex-container">
        <div class="flex-child form-container">
            <h2 class="bigtitle">Log In</h2>
            <form action="" method="post">
                <div class="form-field">
                    <input type="email" id="email" name="email" placeholder="Email" required>
                </div>
                <div class="form-field">
                    <input type="password" id="password" name="password" placeholder="Password" required>
                </div>
                <div class="form-field">
                    <input type="submit" value="Login" class="btn btn-primary">
                </div>
                <div class="different-link">
                    or <a href="signup.php">create an account.</a>
                </div>
            </form>
            <?php if (!empty($errorMessage)): ?>
                <div class="error-message">
                    <?php echo htmlspecialchars($errorMessage); ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="flex-child image-container">
            <img src="./assets/login.webp" alt="Login image">
        </div>
    </div>
</body>
</html>