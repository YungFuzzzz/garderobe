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

    public static function addClothing($brand, $price, $category, $size, $description, $additional_description, $image) {
        $pdo = Db::getConnection();
        $sql = "INSERT INTO products (brand, description, size, price, image, category, additional_description) 
                VALUES (:brand, :description, :size, :price, :image, :category, :additional_description)";
        $stmt = $pdo->prepare($sql);
    
        $stmt->bindParam(':brand', $brand);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':size', $size);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':image', $image);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':additional_description', $additional_description);
    
        return $stmt->execute();
    }
    // Werk een kledingitem bij, inclusief additional_description
    public static function updateClothing($brand, $price, $category, $size, $description, $additional_description, $image, $id) {
        $pdo = Db::getConnection();
        $sql = "UPDATE products 
                SET brand = :brand, price = :price, category = :category, description = :description, 
                    additional_description = :additional_description, image = :image, size = :size
                WHERE id = :id";
        $stmt = $pdo->prepare($sql);
    
        // Bind de parameters
        $stmt->bindParam(':brand', $brand);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':additional_description', $additional_description);
        $stmt->bindParam(':image', $image);
        $stmt->bindParam(':size', $size);
        $stmt->bindParam(':id', $id);
    
    
        // Voer de query uit
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