<?php

namespace Faisalcollinet\Wardrobe;

require_once __DIR__ . '/../vendor/autoload.php';

class ShoppingCart
{
    private static $instance = null;
    private $cartItems = [];

    private function __construct()
    {
        if (isset($_SESSION['cart'])) {
            $this->cartItems = $_SESSION['cart'];
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new ShoppingCart();
        }

        return self::$instance;
    }

    public function addItem($userId, $itemId)
    {
        $this->cartItems[] = ['user_id' => $userId, 'item_id' => $itemId];
        $_SESSION['cart'] = $this->cartItems;
    }

    public function removeItem($itemId)
    {
        foreach ($this->cartItems as $key => $item) {
            if ($item['item_id'] == $itemId) {
                unset($this->cartItems[$key]);
                break;
            }
        }
        $_SESSION['cart'] = array_values($this->cartItems);
    }

    public function getCartItems()
    {
        $items = [];
        foreach ($this->cartItems as $cartItem) {
            $item = Clothing::getClothingById($cartItem['item_id']);
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

    public function getSubtotal()
    {
        $subtotal = 0;
        foreach ($this->cartItems as $cartItem) {
            $item = Clothing::getClothingById($cartItem['item_id']);
            if ($item) {
                $subtotal += $item['price'];
            }
        }
        return $subtotal;
    }

    public function getItemCount()
    {
        return count($this->cartItems);
    }

    public function clearCart()
    {
        $this->cartItems = [];
        $_SESSION['cart'] = [];
    }
}