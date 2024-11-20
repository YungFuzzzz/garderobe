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
                self::$conn = new PDO('mysql:host=mysql.railway.internal;dbname=railway', 'root', 'rEMenFWNlLqJVQnbFQDCAeYrMuqaiYQB');
                self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Database connection failed: " . $e->getMessage());
            }
        }
        return self::$conn;
    }
}

//deze code doet da railway connection ding met de db niet meer lokaal!!!