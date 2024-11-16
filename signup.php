<?php
require_once 'vendor/autoload.php';

use Faisalcollinet\Wardrobe\User;

$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (User::signup($firstname, $lastname, $email, $password)) {
        header('Location: login.php');
        exit();
    } else {
        $errorMessage = "This account already exists.";
    }
}
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <link rel="stylesheet" href="./css/create.css">
</head>
<body>
<div class="flex-container">
        <div class="flex-child form-container">
            <h2 class="bigtitle">Create Account</h2>
            <form action="" method="post">
                <div class="form-field name-field">
                    <input type="text" id="firstname" name="firstname" placeholder="First Name" required>
                    <input type="text" id="lastname" name="lastname" placeholder="Last Name" required>
                </div>
                <div class="form-field">
                    <input type="email" id="email" name="email" placeholder="Email" required>
                </div>
                <div class="form-field">
                    <input type="password" id="password" name="password" placeholder="Password" required>
                </div>
                <div class="form-field">
                    <input type="submit" value="Sign up" class="btn btn-primary">
                </div>
                <div class="login-link">
                    or <a href="login.php">login</a>
                </div>
                <?php if (!empty($errorMessage)): ?>
                    <div class="error-message">
                        <?php echo htmlspecialchars($errorMessage); ?>
                    </div>
                <?php endif; ?>
            </form>
        </div>
        <div class="flex-child image-container">
            <img src="./assets/signup.webp" alt="Signup image">
        </div>
    </div>
</body>
</html>