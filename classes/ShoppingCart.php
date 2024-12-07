<?php

namespace Faisalcollinet\Wardrobe;

// Voeg de autoloader toe als je Composer gebruikt
require_once __DIR__ . '/../vendor/autoload.php'; // Pas het pad aan als het nodig is

class ShoppingCart
{
    private static $instance = null;
    private $cartItems = [];

    private function __construct()
    {
        // Als de sessie al een winkelwagentje bevat, laad dan de items in
        if (isset($_SESSION['cart'])) {
            $this->cartItems = $_SESSION['cart'];
        }
    }

    // Zorg ervoor dat er slechts één instantie van de winkelwagen is
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new ShoppingCart();
        }

        return self::$instance;
    }

    // Voeg een item toe aan het winkelwagentje
    public function addItem($userId, $itemId)
    {
        $this->cartItems[] = ['user_id' => $userId, 'item_id' => $itemId];
        $_SESSION['cart'] = $this->cartItems; // Sla het bij in de sessie
    }

    // Verwijder een item uit het winkelwagentje
    public function removeItem($itemId)
    {
        foreach ($this->cartItems as $key => $item) {
            if ($item['item_id'] == $itemId) {
                unset($this->cartItems[$key]);
                break;
            }
        }
        $_SESSION['cart'] = array_values($this->cartItems); // Verwijder lege indexen
    }

    // Verkrijg de items in het winkelwagentje
    public function getCartItems()
    {
        $items = [];
        foreach ($this->cartItems as $cartItem) {
            $item = Clothing::getClothingById($cartItem['item_id']); // Haal het kledingitem op
            if ($item) {
                $items[] = [
                    'id' => $item['id'],
                    'description' => $item['description'],
                    'price' => $item['price'],
                    'image' => $item['image']
                ];
            }
        }
        return $items;
    }

    // Verkrijg het subtotaal van de winkelwagen (prijs van de items)
    public function getSubtotal()
    {
        $subtotal = 0;
        foreach ($this->cartItems as $cartItem) {
            $item = Clothing::getClothingById($cartItem['item_id']); // Haal het kledingitem op
            if ($item) {
                $subtotal += $item['price'];
            }
        }
        return $subtotal;
    }

    // Verkrijg het aantal items in de winkelwagen
    public function getItemCount()
    {
        return count($this->cartItems);
    }

    // Leeg de winkelwagen
    public function clearCart()
    {
        $this->cartItems = []; // Maak de array leeg
        $_SESSION['cart'] = []; // Maak de winkelwagen leeg door de sessiearray te resetten
    }
}