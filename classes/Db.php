<?php

namespace Faisalcollinet\Wardrobe;

use PDO;
use PDOException;

require_once __DIR__ . '/../loadEnv.php';

class Db
{
    private static $conn = null;

    public static function getConnection()
    {
        if (self::$conn === null) {
            try {
                $envFilePath = __DIR__ . '/../.env'; // Het bestand staat in de root van je project
                loadEnv($envFilePath);  // Laad de variabelen uit het .env bestand

                // Verkrijg de database instellingen uit de $_ENV variabelen
                $host = $_ENV['DB_HOST'];
                $port = $_ENV['DB_PORT'];
                $dbname = $_ENV['DB_NAME'];
                $user = $_ENV['DB_USER'];
                $password = $_ENV['DB_PASSWORD'];

                // Maak de verbinding
                self::$conn = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $user, $password);
                self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Database connection failed: " . $e->getMessage());
            }
        }
        return self::$conn;
    }
}