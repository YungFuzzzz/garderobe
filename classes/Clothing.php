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

    // Nieuwe methode om kleding op te halen op basis van een specifiek ID
    public static function getClothingById($id) {
        $pdo = Db::getConnection();

        // Query om kleding op te halen met het gegeven ID
        $sql = "SELECT * FROM products WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        // Retourneer het kledingitem
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}