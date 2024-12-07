<?php
require_once __DIR__ . '/classes/Db.php';

use Faisalcollinet\Wardrobe\Db;

$pdo = Db::getConnection();

$orderId = isset($_POST['order_id']) ? $_POST['order_id'] : null;

if ($orderId) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM reviews WHERE order_id = :order_id");
        $stmt->execute(['order_id' => $orderId]);

        $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($reviews) {
            echo json_encode(['success' => true, 'reviews' => $reviews]);
        } else {
            echo json_encode(['success' => false, 'message' => 'No reviews found']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Missing order ID']);
}
?>