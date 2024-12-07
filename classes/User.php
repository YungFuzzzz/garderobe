<?php
namespace Faisalcollinet\Wardrobe;

use PDO;

class User
{
    public static function login($email, $password)
    {
        // Sessie wordt gestart in login.php, niet hier
        $pdo = Db::getConnection();

        // Zoek de gebruiker op basis van het e-mailadres
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Als het wachtwoord overeenkomt, sla de gebruiker op in de sessie
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['firstname'] = $user['firstname'];
            $_SESSION['lastname'] = $user['lastname'];
            $_SESSION['role'] = $user['role'];

            // Redirect naar het dashboard op basis van de rol
            if ($_SESSION['role'] == 'admin') {
                header('Location: admin_dashboard.php');
            } else {
                header('Location: index.php');
            }
            exit();
        }

        return false;
    }

    public static function signup($firstname, $lastname, $email, $password)
    {
        $pdo = Db::getConnection();

        // Controleer of de gebruiker al bestaat
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);

        // Als de gebruiker al bestaat, retourneren we false
        if ($existingUser) {
            return false;
        }

        // Versleutel het wachtwoord
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Voeg de nieuwe gebruiker toe aan de database, standaard rol is 'customer'
        $stmt = $pdo->prepare("INSERT INTO users (firstname, lastname, email, password, role) VALUES (:firstname, :lastname, :email, :password, :role)");
        $stmt->execute([
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
            'password' => $hashedPassword,
            'role' => 'customer'  // De rol is standaard 'customer'
        ]);

        return true;
    }

    public static function getUserBalance($userId)
    {
        $pdo = Db::getConnection();

        // Haal de balans op van de gebruiker
        $stmt = $pdo->prepare("SELECT currency FROM users WHERE id = :user_id");
        $stmt->execute(['user_id' => $userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ? $result['currency'] : 0;
    }

    public static function updateUserBalance($userId, $newBalance)
    {
        $pdo = Db::getConnection();

        // Update de balans van de gebruiker
        $stmt = $pdo->prepare("UPDATE users SET currency = :currency WHERE id = :user_id");
        $stmt->execute(['currency' => $newBalance, 'user_id' => $userId]);

        return true;
    }

    public static function getUserDetails($userId)
    {
        $pdo = Db::getConnection();

        // Haal de gegevens van de gebruiker op
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :user_id");
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function updatePassword($userId, $newPassword)
    {
        $pdo = Db::getConnection();

        // Update het wachtwoord van de gebruiker
        $stmt = $pdo->prepare("UPDATE users SET password = :password WHERE id = :user_id");
        $stmt->execute(['password' => $newPassword, 'user_id' => $userId]);

        return true;
    }

    public static function getUserById($id)
    {
        $pdo = Db::getConnection();
        
        // Haal de gebruiker op via id
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>