<?php
function getDBConnection() {
    $host = "localhost";
    $username = "root";
    $password = "root";
    $database = "agora_francia";
    $port = 3306;

    // Connexion à la base
    $conn = new mysqli($host, $username, $password, $database, $port);

    // Gestion des erreurs
    if ($conn->connect_error) {
        die("❌ Erreur de connexion : " . $conn->connect_error);
    }

    // Encodage en UTF-8 (important si tu gères des accents/français)
    $conn->set_charset("utf8mb4");

    return $conn;
}
?>