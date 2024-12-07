<?php
session_start();

// Zorg ervoor dat je de nodige klassen importeert
require_once __DIR__ . '/classes/ShoppingCart.php';
require_once __DIR__ . '/classes/User.php';
require_once __DIR__ . '/classes/Db.php'; // Zorg ervoor dat de Db klasse geïmporteerd is

// Haal de gebruiker op (zorg ervoor dat de gebruiker ingelogd is)
$user_id = $_SESSION['user_id']; // Of hoe je het user_id ook opslaat
$user = \Faisalcollinet\Wardrobe\User::getUserById($user_id); // Zorg ervoor dat deze methode bestaat en werkt

// Haal de winkelwagen op
$shoppingCart = \Faisalcollinet\Wardrobe\ShoppingCart::getInstance();
$cartItems = $shoppingCart->getCartItems();
$subtotal = $shoppingCart->getSubtotal();

// Haal het saldo van de gebruiker op
$userBalance = $user['currency']; // Aangepast naar het juiste veld, bijvoorbeeld 'currency'

// Controleer of de gebruiker genoeg saldo heeft
if ($userBalance < $subtotal) {
    die("Not enough funds to complete the order.");
}

// Voer de betaling uit (verminder het saldo van de gebruiker)
$newBalance = $userBalance - $subtotal;
// Update het saldo van de gebruiker in de database
\Faisalcollinet\Wardrobe\User::updateUserBalance($user_id, $newBalance); // Zorg ervoor dat deze methode in User bestaat en werkt

// Maak een order aan (bijvoorbeeld in een Orders tabel in de database)
$db = \Faisalcollinet\Wardrobe\Db::getConnection(); // Zorg ervoor dat getConnection() werkt
$orderData = [
    'user_id' => $user_id,
    'total' => $subtotal,
    'status' => 'pending',
    'created_at' => date('Y-m-d H:i:s')
];
if (!$db->insert('orders', $orderData)) {
    die("Error placing the order.");
}

// Voeg de items toe aan de order_items tabel
$order_id = $db->lastInsertId(); // Haal het laatst ingevoerde ID op voor de order
foreach ($cartItems as $item) {
    $orderItemData = [
        'order_id' => $order_id,
        'item_id' => $item['id'],
        'quantity' => 1, // Pas dit aan als je een hoeveelheid wilt bijhouden
        'price' => $item['price']
    ];
    if (!$db->insert('order_items', $orderItemData)) {
        die("Error inserting order item.");
    }
}

// Maak de winkelwagen leeg na het plaatsen van de bestelling
$shoppingCart->clearCart();

// Redirect naar een pagina om de bestelling te bevestigen
header('Location: order_confirmation.php');
exit();
?>