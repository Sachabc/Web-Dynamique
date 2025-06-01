<?php
session_start();

// Vider le panier
unset($_SESSION['panier']);

// Rediriger vers la page panier
header('Location: panier.php');
exit();