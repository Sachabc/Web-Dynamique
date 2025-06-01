<?php
session_start();

if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'vider') {
            // Vider le panier
            $_SESSION['panier'] = [];
        } elseif ($_POST['action'] === 'modifier') {
            // Modifier les quantités
            if (isset($_POST['quantites']) && is_array($_POST['quantites'])) {
                foreach ($_POST['quantites'] as $id => $qty) {
                    $qty = intval($qty);
                    if ($qty > 0) {
                        $_SESSION['panier'][$id] = $qty;
                    } else {
                        unset($_SESSION['panier'][$id]);
                    }
                }
            }
        }
    }

    // Supprimer un article précis
    if (isset($_POST['supprimer'])) {
        $id_suppr = $_POST['supprimer'];
        unset($_SESSION['panier'][$id_suppr]);
    }
}

header('Location: panier.php');
exit;