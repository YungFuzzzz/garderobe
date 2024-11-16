<?php
require_once 'vendor/autoload.php';

use Faisalcollinet\Wardrobe\User;

$errorMessage = '';

// Als er een POST-verzoek is, probeer in te loggen
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Probeer de login-methode aan te roepen
    if (User::login($email, $password)) {
        // Login succesvol, redirect gebeurt al in de login-methode
        exit(); // Stop verder uitvoeren van de code
    } else {
        // Foutmelding als inloggen niet lukt
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
        </div>
        <div class="flex-child image-container">
            <img src="./assets/login.webp" alt="Login image">
        </div>
    </div>
</body>
</html>