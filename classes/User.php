<?php
namespace Faisalcollinet\Wardrobe;

use PDO;

class User
{
    public static function login($email, $password)
    {
        session_start();

        $pdo = Db::getConnection();

        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['firstname'] = $user['firstname'];
            $_SESSION['lastname'] = $user['lastname'];
            $_SESSION['role'] = $user['role'];

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

        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingUser) {
            return false;
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO users (firstname, lastname, email, password, role) VALUES (:firstname, :lastname, :email, :password, :role)");
        $stmt->execute([
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
            'password' => $hashedPassword,
            'role' => 'customer'
        ]);

        return true;
    }

    public static function getUserBalance($userId)
    {
        $pdo = Db::getConnection();

        $stmt = $pdo->prepare("SELECT currency FROM users WHERE id = :user_id");
        $stmt->execute(['user_id' => $userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ? $result['currency'] : 0;
    }

    public static function updateUserBalance($userId, $newBalance)
    {
        $pdo = Db::getConnection();

        $stmt = $pdo->prepare("UPDATE users SET currency = :currency WHERE id = :user_id");
        $stmt->execute(['currency' => $newBalance, 'user_id' => $userId]);

        return true;
    }

    public static function getUserDetails($userId)
    {
        $pdo = Db::getConnection();

        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :user_id");
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function updatePassword($userId, $newPassword)
    {
        $pdo = Db::getConnection();

        $stmt = $pdo->prepare("UPDATE users SET password = :password WHERE id = :user_id");
        $stmt->execute(['password' => $newPassword, 'user_id' => $userId]);

        return true;
    }

    public static function getUserById($id)
    {
        $pdo = Db::getConnection();
        
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>