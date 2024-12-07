<?php
session_start();

require_once __DIR__ . '/classes/ShoppingCart.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['user_id'];
$itemId = $_POST['item_id'] ?? null;

if ($itemId) {
    $shoppingCart = \Faisalcollinet\Wardrobe\ShoppingCart::getInstance();
    $shoppingCart->addItem($userId, $itemId);
    header('Location: index.php');
    exit();
}
?>