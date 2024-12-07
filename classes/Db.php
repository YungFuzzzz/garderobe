<?php

namespace Faisalcollinet\Wardrobe;

use PDO;
use PDOException;

class Db
{
    private static $conn = null;

    public static function getConnection()
    {
        if (self::$conn === null) {
            try {
                // Gebruik hier je Railway MySQL verbinding URL
                self::$conn = new PDO('mysql:host=autorack.proxy.rlwy.net;port=25993;dbname=railway', 'root', 'rEMenFWNlLqJVQnbFQDCAeYrMuqaiYQB');
                self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Database connection failed: " . $e->getMessage());
            }
        }
        return self::$conn;
    }
}