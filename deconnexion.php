<?php
session_start();
session_unset();     // Vide toutes les variables de session
session_destroy();   // Détruit la session

header('Location: index.php');  // Redirige vers ta page d’accueil classique
exit();