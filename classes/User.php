<?php
namespace Faisalcollinet\Wardrobe;

use PDO;

class User {

    public static function signup($firstname, $lastname, $email, $password) {
        $pdo = Db::getConnection();
        
        // Controleer of het e-mailadres al bestaat
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            return false; // Als het e-mailadres al bestaat, geef een foutmelding
        }

        // Versleutel het wachtwoord
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Voeg de gebruiker toe inclusief de 1000 digitale valuta
        $sql = "INSERT INTO users (firstname, lastname, email, password, role, currency) 
                VALUES (:firstname, :lastname, :email, :password, 'customer', 1000)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':firstname', $firstname);
        $stmt->bindParam(':lastname', $lastname);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);

        // Voer de query uit
        if ($stmt->execute()) {
            return true; // Als de registratie succesvol is, return true
        } else {
            return false; // Als er iets misgaat, return false
        }
    }

    public static function login($email, $password) {
        $pdo = Db::getConnection();

        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['firstname'] = $user['firstname'];

            if ($_SESSION['role'] == 'admin') {
                header('Location: admin_dashboard.php');
            } else {
                header('Location: index.php');
            }
            exit();
        } else {
            return false;
        }
    }

    // Methode om het saldo van de gebruiker op te halen
    public static function getUserBalance($userId) {
        $pdo = Db::getConnection();

        // Haal het saldo op voor de gebruiker uit de database
        $sql = "SELECT currency FROM users WHERE id = :user_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        // Haal het resultaat op
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Als de gebruiker wordt gevonden, retourneer het saldo, anders 0
        return $user ? (float)$user['currency'] : 0.0;
    }
}
?>