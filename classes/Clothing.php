<?php
namespace Faisalcollinet\Wardrobe;

require_once __DIR__ . '/Db.php';

class Clothing {
    // Haal alle kledingitems op
    public static function getAllClothing() {
        $stmt = Db::getConnection()->prepare("SELECT * FROM products");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Haal kleding op basis van merk
    public static function getAllClothingByBrand($brand) {
        $pdo = Db::getConnection();
        $sql = "SELECT * FROM products WHERE brand = :brand";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':brand', $brand);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Haal alle unieke merken op
    public static function getAllBrands() {
        $pdo = Db::getConnection();
        $sql = "SELECT DISTINCT brand FROM products";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Haal kleding op basis van ID
    public static function getClothingById($id) {
        $pdo = Db::getConnection();
        $sql = "SELECT * FROM products WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    // Voeg een nieuw kledingitem toe
    public static function addClothing($brand, $price, $category, $description, $image, $size) {
        $pdo = Db::getConnection();
        $sql = "INSERT INTO products (brand, price, category, description, image, size) 
                VALUES (:brand, :price, :category, :description, :image, :size)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':brand', $brand);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':image', $image);
        $stmt->bindParam(':size', $size);
        
        return $stmt->execute();
    }

    // Werk een kledingitem bij
    public static function updateClothing($id, $brand, $price, $category, $description, $image, $size) {
        $pdo = Db::getConnection();
        $sql = "UPDATE products 
                SET brand = :brand, price = :price, category = :category, description = :description, image = :image, size = :size
                WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':brand', $brand);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':image', $image);
        $stmt->bindParam(':size', $size);

        return $stmt->execute();
    }

    // Verwijder een kledingitem
    public static function deleteClothing($id) {
        $pdo = Db::getConnection();
        $sql = "DELETE FROM products WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>