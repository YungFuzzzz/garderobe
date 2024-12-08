<?php

namespace Faisalcollinet\Wardrobe;

use PDO;
use PDOException;

require_once __DIR__ . '/../loadEnv.php';  // Dit laad de loadEnv functie vanuit de root van je project

class Db
{
    private static $conn = null;

    public static function getConnection()
    {
        if (self::$conn === null) {
            try {
                // Bepaal het pad naar het .env bestand op basis van de omgeving
                $envPath = '/app/.env';  // Standaard pad voor Railway omgeving
                if (isset($_ENV['RAILWAY_ENVIRONMENT_NAME']) && $_ENV['RAILWAY_ENVIRONMENT_NAME'] === 'production') {
                    $envPath = '/workspace/.env';  // Probeer dit pad voor productieomgeving op Railway
                }

                // Laad het .env bestand
                loadEnv($envPath);

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