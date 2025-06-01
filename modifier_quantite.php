<?php
session_start();
require_once 'config.php';

if (!isset($_GET['id']) || !isset($_GET['action'])) {
    header('Location: panier.php');
    exit;
}

$id = intval($_GET['id']);
$action = $_GET['action'];

$conn = getDBConnection();

// Récupérer le stock dispo pour l'article
$stmt = $conn->prepare("SELECT stock FROM articles WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: panier.php');
    exit;
}

$article = $result->fetch_assoc();
$stockDispo = intval($article['stock']);

// Init panier si pas encore créé
if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}

// Quantité actuelle dans le panier
$currentQty = $_SESSION['panier'][$id] ?? 0;

if ($action === 'augmenter') {
    if ($currentQty < $stockDispo) {
        $_SESSION['panier'][$id] = $currentQty + 1;
    } else {
        // Stock max atteint, message flash
        $_SESSION['message_erreur'] = "Stock insuffisant, victime de son succès 😎";
    }
} elseif ($action === 'diminuer') {
    if ($currentQty > 1) {
        $_SESSION['panier'][$id] = $currentQty - 1;
    } else {
        unset($_SESSION['panier'][$id]);
    }
}

header('Location: panier.php');
exit;
?>