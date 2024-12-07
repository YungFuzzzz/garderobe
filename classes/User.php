<?php
namespace Faisalcollinet\Wardrobe;

use PDO;

class User
{
    // De methode voor inloggen
    public static function login($email, $password)
    {
        session_start(); // Start de sessie

        // Verkrijg de databaseverbinding
        $pdo = Db::getConnection();

        // Zoek de gebruiker op basis van het ingevoerde email
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Controleer of de gebruiker bestaat en het wachtwoord klopt
        if ($user && password_verify($password, $user['password'])) {
            // Zet de sessievariabelen voor de gebruiker
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['firstname'] = $user['firstname'];
            $_SESSION['lastname'] = $user['lastname'];
            $_SESSION['role'] = $user['role'];  // Sla de rol van de gebruiker op in de sessie

            // Redirect naar de juiste pagina op basis van de rol
            if ($_SESSION['role'] == 'admin') {
                header('Location: admin_dashboard.php');
            } else {
                header('Location: index.php');
            }
            exit(); // Stop verder uitvoeren van de code
        }

        return false; // Return false als login mislukt
    }

    // De methode voor registratie
    public static function signup($firstname, $lastname, $email, $password)
    {
        $pdo = Db::getConnection();

        // Controleer of het emailadres al bestaat
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingUser) {
            return false; // Account bestaat al
        }

        // Voeg de gebruiker toe aan de database
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (firstname, lastname, email, password) VALUES (:firstname, :lastname, :email, :password)");
        $stmt->execute(['firstname' => $firstname, 'lastname' => $lastname, 'email' => $email, 'password' => $hashedPassword]);

        return true; // Account succesvol aangemaakt
    }

    // Haal het saldo (currency) van de gebruiker op
    public static function getUserBalance($userId)
    {
        $pdo = Db::getConnection();

        // Haal de currency op uit de database voor de gebruiker
        $stmt = $pdo->prepare("SELECT currency FROM users WHERE id = :user_id");
        $stmt->execute(['user_id' => $userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Return de currency of 0 als er geen currency is
        return $result ? $result['currency'] : 0;
    }

    // Methode om de currency van de gebruiker bij te werken
    public static function updateUserBalance($userId, $newBalance)
    {
        $pdo = Db::getConnection();

        // Update de currency van de gebruiker in de database
        $stmt = $pdo->prepare("UPDATE users SET currency = :currency WHERE id = :user_id");
        $stmt->execute(['currency' => $newBalance, 'user_id' => $userId]);

        return true; // Return true als de update geslaagd is
    }

    // Haal andere gegevens van de gebruiker op (optioneel)
    public static function getUserDetails($userId)
    {
        $pdo = Db::getConnection();

        // Haal de gegevens van de gebruiker uit de database
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :user_id");
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update het wachtwoord van een gebruiker
    public static function updatePassword($userId, $newPassword)
    {
        $pdo = Db::getConnection();

        // Update het wachtwoord in de database
        $stmt = $pdo->prepare("UPDATE users SET password = :password WHERE id = :user_id");
        $stmt->execute(['password' => $newPassword, 'user_id' => $userId]);

        return true; // Return true als de update geslaagd is
    }
}
?>