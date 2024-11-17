<?php
namespace Faisalcollinet\Wardrobe;

use PDO;

class User {

    // Methode om een nieuwe gebruiker aan te maken
    public static function signup($firstname, $lastname, $email, $password) {
        $pdo = Db::getConnection();
        
        // Controleer of het emailadres al bestaat
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            return false; // Als het emailadres al bestaat, return false
        }

        // Voeg de nieuwe gebruiker toe aan de database
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (firstname, lastname, email, password, role) VALUES (:firstname, :lastname, :email, :password, 'customer')";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':firstname', $firstname);
        $stmt->bindParam(':lastname', $lastname);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Methode voor het inloggen van een gebruiker
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
            $_SESSION['firstname'] = $user['firstname']; // Voeg de firstname toe aan de sessie

            // Redirect naar de juiste pagina afhankelijk van de rol
            if ($_SESSION['role'] == 'admin') {
                header('Location: admin_dashboard.php');
            } else {
                header('Location: customer_dashboard.php');
            }
            exit();
        } else {
            return false; // Als de login niet succesvol is
        }
    }
}
?>