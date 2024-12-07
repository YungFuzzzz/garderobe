<?php
session_start();

require_once __DIR__ . '/classes/ShoppingCart.php';
require_once __DIR__ . '/classes/User.php';
require_once __DIR__ . '/classes/Db.php';

$user_id = $_SESSION['user_id'];
$user = \Faisalcollinet\Wardrobe\User::getUserById($user_id);

$shoppingCart = \Faisalcollinet\Wardrobe\ShoppingCart::getInstance();
$cartItems = $shoppingCart->getCartItems();
$subtotal = $shoppingCart->getSubtotal();

$userBalance = $user['currency'];

if ($userBalance < $subtotal) {
    die("Not enough funds to complete the order.");
}

$newBalance = $userBalance - $subtotal;
\Faisalcollinet\Wardrobe\User::updateUserBalance($user_id, $newBalance);

$db = \Faisalcollinet\Wardrobe\Db::getConnection();
$orderData = [
    'user_id' => $user_id,
    'total' => $subtotal,
    'status' => 'pending',
    'created_at' => date('Y-m-d H:i:s')
];
if (!$db->insert('orders', $orderData)) {
    die("Error placing the order.");
}

$order_id = $db->lastInsertId();
foreach ($cartItems as $item) {
    $orderItemData = [
        'order_id' => $order_id,
        'item_id' => $item['id'],
        'quantity' => 1,
        'price' => $item['price']
    ];
    if (!$db->insert('order_items', $orderItemData)) {
        die("Error inserting order item.");
    }
}

$shoppingCart->clearCart();

header('Location: order_confirmation.php');
exit();
?>