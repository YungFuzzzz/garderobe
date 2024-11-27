<?php
namespace Faisalcollinet\Wardrobe;

require_once __DIR__ . '/Db.php';

class Clothing {
    public static function getAllClothing() {
        $stmt = Db::getConnection()->prepare("SELECT * FROM products");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}