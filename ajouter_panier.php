<?php
session_start();

if (!isset($_POST['id'])) {
    die("ID produit manquant");
}

$id = intval($_POST['id']);

// Initialise panier si pas existant
if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}

// Ajoute produit au panier avec quantité
if (isset($_SESSION['panier'][$id])) {
    $_SESSION['panier'][$id]++;
} else {
    $_SESSION['panier'][$id] = 1;
}

// Redirection vers page panier
header('Location: panier.php');
exit();