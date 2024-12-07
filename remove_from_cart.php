<?php
session_start();

require_once __DIR__ . '/classes/ShoppingCart.php';

$itemId = $_POST['item_id'] ?? null;

if ($itemId) {
    $shoppingCart = \Faisalcollinet\Wardrobe\ShoppingCart::getInstance();
    $shoppingCart->removeItem($itemId);
}

header('Location: cart.php');
exit();
?>