<?php
namespace Faisalcollinet\Wardrobe;

require_once __DIR__ . '/Db.php';

class Clothing {
    // Bestaande methode om alle kledingitems op te halen
    public static function getAllClothing() {
        $stmt = Db::getConnection()->prepare("SELECT * FROM products");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Methode om kleding op te halen op basis van merk
    public static function getAllClothingByBrand($brand) {
        $pdo = Db::getConnection();
        $sql = "SELECT * FROM products WHERE brand = :brand";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':brand', $brand);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Methode om unieke merken op te halen
    public static function getAllBrands() {
        $pdo = Db::getConnection();
        $sql = "SELECT DISTINCT brand FROM products";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Methode om kleding op te halen op basis van ID
    public static function getClothingById($id) {
        $pdo = Db::getConnection();
        $sql = "SELECT * FROM products WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}