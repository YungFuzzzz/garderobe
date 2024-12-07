<?php

namespace Faisalcollinet\Wardrobe;

use PDO;
use PDOException;
use Dotenv\Dotenv;

class Db
{
    private static $conn = null;

    public static function getConnection()
    {
        if (self::$conn === null) {
            try {
                // Laad de .env bestand vanuit de root directory
                $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');  // Dit gaat twee niveaus omhoog naar de root van je project.
                $dotenv->load();

                // Verkrijg de database instellingen uit de .env
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