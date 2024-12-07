<?php
session_start();

// Als de gebruiker niet ingelogd is, doorsturen naar de login pagina
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Verkrijg de gebruikersinformatie uit de sessie
$user_id = $_SESSION['user_id'];
$firstname = isset($_SESSION['firstname']) ? $_SESSION['firstname'] : 'Gast';
$userBalance = isset($_SESSION['user_balance']) ? $_SESSION['user_balance'] : 0;
$cartItemCount = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;

// Vereiste: Composer autoloader inladen
require_once __DIR__ . '/vendor/autoload.php';

// Initialiseer fouten en succes berichten
$error = '';
$success = '';

// Verwerk het formulier wanneer het is ingediend
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Verkrijg de gebruiker uit de database
    $user = Faisalcollinet\Wardrobe\User::getUserDetails($user_id);

    // Controleer of het huidige wachtwoord correct is
    if (password_verify($current_password, $user['password'])) {
        // Controleer of het nieuwe wachtwoord overeenkomt met de bevestiging
        if ($new_password === $confirm_password) {
            // Wachtwoord bijwerken in de database
            $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);
            Faisalcollinet\Wardrobe\User::updatePassword($user_id, $hashed_new_password);
            $success = 'Wachtwoord succesvol bijgewerkt!';
        } else {
            $error = 'De nieuwe wachtwoorden komen niet overeen.';
        }
    } else {
        $error = 'Het huidige wachtwoord is incorrect.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link rel="stylesheet" href="./css/changepassword.css">
</head>
<body>
<div class="navbar">
    <div class="logo">Garderobe</div>
    <div class="user-info">
        <a href="index.php">Home</a>
        <a href="change_password.php"><?php echo htmlspecialchars($firstname); ?></a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="change-password-container">
    <div class="flex-container">
        <h2>Verander Wachtwoord</h2>

        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <div class="flex-child form-container">
            <form action="change_password.php" method="POST">
                <div class="form-field">
                    <label for="current_password">Huidig Wachtwoord</label>
                    <input type="password" id="current_password" name="current_password" required placeholder="Huidig Wachtwoord">
                </div>
                <div class="form-field">
                    <label for="new_password">Nieuw Wachtwoord</label>
                    <input type="password" id="new_password" name="new_password" required placeholder="Nieuw Wachtwoord">
                </div>
                <div class="form-field">
                    <label for="confirm_password">Bevestig Nieuw Wachtwoord</label>
                    <input type="password" id="confirm_password" name="confirm_password" required placeholder="Bevestig Nieuw Wachtwoord">
                </div>
                <div class="form-field">
                    <input type="submit" value="Wachtwoord Bijwerken" class="btn btn-primary">
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>