<?php
require_once 'config.php';
session_start();

// Message de débogage
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $conn = getDBConnection();
    
    // Vérification de la connexion
    if ($conn->connect_error) {
        die("❌ Erreur de connexion à la base de données: " . $conn->connect_error);
    }

    // Sécurisation et récupération des données
    $nom = $conn->real_escape_string($_POST['nom'] ?? '');
    $type_vente = $conn->real_escape_string($_POST['type_vente'] ?? '');
    $prix = floatval($_POST['prix'] ?? 0);
    $description = $conn->real_escape_string($_POST['description'] ?? '');
    $categorie = $conn->real_escape_string($_POST['categorie'] ?? '');
    $date_achat = $conn->real_escape_string($_POST['date_achat'] ?? '');

    // Valeurs par défaut
    $stock = 1;
    $ventes = 0;
    $online = 1;
    $photoDBPath = '';

    // Gestion de la photo uploadée
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $photoTmpName = $_FILES['photo']['tmp_name'];
        $photoName = basename($_FILES['photo']['name']);
        $photoExt = strtolower(pathinfo($photoName, PATHINFO_EXTENSION));
        
        // Validation de l'extension
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($photoExt, $allowedExtensions)) {
            die("❌ Seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés.");
        }

        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $newPhotoName = uniqid('photo_', true) . '.' . $photoExt;
        $destination = $uploadDir . $newPhotoName;

        if (move_uploaded_file($photoTmpName, $destination)) {
            $photoDBPath = $conn->real_escape_string($destination);
        } else {
            die("❌ Erreur lors de l'upload de la photo.");
        }
    } else {
        die("❌ Photo non envoyée ou erreur: " . ($_FILES['photo']['error'] ?? 'Aucun fichier envoyé'));
    }

    // Préparation de la requête avec les bons types de paramètres
    $sql = "INSERT INTO articles (nom, type_vente, prix, image, stock, description, categorie, ventes, online, date_achat) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("❌ Erreur préparation requête : " . $conn->error);
    }

    // Vérification des types de paramètres
    // Les types doivent correspondre à ceux de la table:
    // s=string, d=double, i=integer
    $stmt->bind_param(
        "ssdsiisiss", 
        $nom,
        $type_vente,
        $prix,
        $photoDBPath,
        $stock,
        $description,
        $categorie,
        $ventes,
        $online,
        $date_achat
    );

    // Exécution
    if ($stmt->execute()) {
        // Vérification du nombre de lignes affectées
        if ($stmt->affected_rows > 0) {
            echo "✅ Objet ajouté en base avec succès. ID: " . $stmt->insert_id;
            // header("Location: confirmation_vente.php");
            // exit;
        } else {
            echo "⚠️ Aucune ligne n'a été affectée dans la base de données.";
        }
    } else {
        echo "❌ Erreur SQL : " . $stmt->error;
        
        // Affichage des valeurs pour débogage
        echo "<pre>";
        var_dump($nom, $type_vente, $prix, $photoDBPath, $stock, $description, $categorie, $ventes, $online, $date_achat);
        echo "</pre>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Vendre un objet - Agora Francia</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        /* (Ton CSS ici, inchangé pour la partie formulaire) */
        * { margin:0; padding:0; box-sizing:border-box; }
        html, body {
          height: 100%;
          font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
          background: url('imagefond.jpg') no-repeat center center fixed;
          background-size: cover;
          color: #333;
          line-height: 1.6;
          display: flex;
          flex-direction: column;
          min-height: 100vh;
        }
        header { background-color: #2c3e50; color: white; padding: 20px 0; flex-shrink: 0; }
        header h1 { text-align: center; font-size: 2rem; }
        nav {
          background-color: #34495e;
          display: flex;
          justify-content: center;
          gap: 30px;
          padding: 10px 0;
          flex-wrap: wrap;
          flex-shrink: 0;
        }
        nav a, nav span {
          color: white;
          text-decoration: none;
          font-weight: bold;
          padding: 8px 15px;
          transition: background 0.3s;
        }
        nav a:hover { background-color: #1abc9c; border-radius: 6px; }
        footer {
          background-color: #2c3e50;
          color: white;
          text-align: center;
          padding: 15px;
          flex-shrink: 0;
          margin-top: auto;
        }
        footer .contact { margin-bottom: 20px; }
        footer .contact h3 { margin-bottom: 15px; font-size: 1.5rem; }
        footer .contact-info p { margin: 5px 0; }
        form.vendre-form {
            background: rgba(255,255,255,0.95);
            padding: 30px;
            border-radius: 12px;
            max-width: 700px;
            margin: 50px auto;
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
        }
        form.vendre-form label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
            margin-top: 20px;
        }
        form.vendre-form input,
        form.vendre-form textarea,
        form.vendre-form select {
            width: 100%;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 1rem;
        }
        form.vendre-form button {
            margin-top: 30px;
            background: #007BFF;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
        }
        form.vendre-form button:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>

<header>
  <h1>Agora Francia</h1>
</header>

<nav>
  <a href="index.php">Accueil</a>
  <a href="tout_parcourir.php">Tout Parcourir</a>
  <a href="panier.php">Panier</a>
  <?php if (isset($_SESSION["prenom"])): ?>
    <span>Bonjour, <?= htmlspecialchars($_SESSION["prenom"]) ?></span>
    <a href="logout.php">Déconnexion</a>
  <?php else: ?>
    <a href="formulaire_clients.php">Votre Compte</a>
  <?php endif; ?>
</nav>

<main>
    <form class="vendre-form" action="vendre.php" method="POST" enctype="multipart/form-data">
    <h2 style="text-align:center;">Vendre un objet</h2>

    <label for="nom">Nom de l'objet</label>
    <input type="text" id="nom" name="nom" required>

    <label for="prix">Prix (€)</label>
    <input type="number" step="0.01" id="prix" name="prix" required>

    <label for="description">Description</label>
    <textarea id="description" name="description" rows="5" required></textarea>

    <label for="photo">Photo de l'objet</label>
    <input type="file" id="photo" name="photo" accept="image/*" required>

    <label for="date_achat">Date d'achat</label>
    <input type="date" id="date_achat" name="date_achat" required>

    <label for="categorie">Catégorie</label>
    <select id="categorie" name="categorie" required>
        <option value="">-- Sélectionnez une catégorie --</option>
        <option value="salle de bain">Salle de bain</option>
        <option value="mode">Mode</option>
        <option value="décoration">Décoration</option>
        <option value="fournitures scolaires">Fournitures scolaires</option>
        <option value="accessoires">Accessoires</option>
        <option value="cuisine">Cuisine</option>
        <option value="animalerie">Animalerie</option>
        <option value="fournitures">Fournitures</option>
        <option value="électronique">Électronique</option>
        <option value="informatique">Informatique</option>
        <option value="accessoires déco">Accessoires déco</option>
        <option value="décoration geek">Décoration geek</option>
        <option value="autre">Autre</option>
    </select>

    <label for="type_vente">Type de vente</label>
    <select id="type_vente" name="type_vente" required>
        <option value="">-- Sélectionnez le type de vente --</option>
        <option value="meilleure offre">Meilleure offre</option>
        <option value="transaction client vendeur">Transaction client vendeur</option>
        <option value="achat immediat">Achat immédiat</option>
    </select>

    <button type="submit">Mettre en vente</button>
</form>
</main>

<footer>
  <section class="contact">
    <h3>Contactez-nous</h3>
    <div class="contact-info">
     <p>Email : <a href="mailto:contact@agorafrancia.com" style="color: #1abc9c;">contact@agorafrancia.com</a></p>
     <p>Téléphone : 01 23 45 67 89</p>
     <p>Adresse : 123 Rue de Paris, 75000 Paris</p>
    </div>
  </section>
</footer>

</body>
</html>