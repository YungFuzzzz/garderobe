<?php
session_start();

// Controleer of de winkelwagen nog niet is geïnitieerd
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = []; // Maak een lege winkelwagen als deze nog niet bestaat
}

// Haal het item ID uit het POST-verzoek
$itemId = $_POST['item_id'] ?? null;

// Voeg het item toe aan de winkelwagen als het ID geldig is
if ($itemId) {
    $_SESSION['cart'][] = $itemId; // Voeg het item ID toe aan de winkelwagen
}

// Redirect terug naar de indexpagina (of naar een andere pagina naar keuze)
header('Location: index.php');
exit();
?>