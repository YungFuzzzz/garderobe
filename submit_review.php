<?php
require_once __DIR__ . '/classes/Db.php';

use Faisalcollinet\Wardrobe\Db;

$pdo = Db::getConnection();

$orderId = isset($_POST['order_id']) ? $_POST['order_id'] : null;
$review = isset($_POST['review']) ? $_POST['review'] : null;

if ($orderId && $review) {
    try {
        $stmt = $pdo->prepare("INSERT INTO reviews (order_id, review) VALUES (:order_id, :review)");
        $stmt->execute([
            'order_id' => $orderId,
            'review' => $review
        ]);

        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Missing order ID or review']);
}
?>