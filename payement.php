<?php
session_start();
require_once 'config.php'; // doit contenir la fonction getDBConnection()

$conn = getDBConnection();

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $numero = $_POST['numero_carte'] ?? '';
    $date = $_POST['date_validite'] ?? '';
    $nom = $_POST['nom_titulaire'] ?? '';
    $cvv = $_POST['cryptogramme'] ?? '';

    // Validation basique
    if (empty($numero) || empty($date) || empty($nom) || empty($cvv)) {
        $erreur = "Tous les champs sont obligatoires.";
    } else {
        try {
            $stmt = $conn->prepare("INSERT INTO carte (numero_carte, date_validite, nom_titulaire, cryptogramme) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $numero, $date, $nom, $cvv);
            $stmt->execute();
            $success = true;
        } catch (Exception $e) {
            $erreur = "Erreur lors de l'enregistrement : " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Paiement</title>
    <style> <!-- si tu as une CSS globale -->
    * {
          margin: 0;
          padding: 0;
          box-sizing: border-box;
        }

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

        header {
          background-color: #2c3e50;
          color: white;
          padding: 20px 0;
          flex-shrink: 0;
        }

        header h1 {
          text-align: center;
          font-size: 2rem;
        }

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

        nav a:hover {
          background-color: #1abc9c;
          border-radius: 6px;
        }
        main {
            max-width: 450px;
            margin: 50px auto;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            margin-bottom: 25px;
        }
        label {
            display: block;
            margin: 15px 0 5px;
            font-weight: bold;
        }
        input[type="text"], input[type="month"], input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1.5px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
        }
        button {
            margin-top: 25px;
            width: 100%;
            background-color: #007BFF;
            color: white;
            font-weight: bold;
            border: none;
            padding: 15px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 18px;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #0056b3;
        }
footer {
    background-color: #2c3e50;
    color: white;
    text-align: center;
    padding: 15px;
    flex-shrink: 0;
}

.contact {
    margin-bottom: 20px;
}

.contact h3 {
    margin-bottom: 15px;
    font-size: 1.5rem;
}

.contact-info p {
    margin: 5px 0;
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

<main style="padding: 30px; max-width: 600px; margin: auto;">
    <h2>Informations de paiement</h2>

    <?php if (!empty($success)): ?>
        <p style="color: green;">✅ Paiement enregistré avec succès !</p>
    <?php elseif (!empty($erreur)): ?>
        <p style="color: red;">❌ <?= $erreur ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Numéro de carte :</label><br>
        <input type="text" name="numero_carte" required maxlength="19" pattern="\d{16,19}"><br><br>

        <label>Date de validité :</label><br>
        <input type="month" name="date_validite" required><br><br>

        <label>Nom du titulaire :</label><br>
        <input type="text" name="nom_titulaire" required><br><br>

        <label>Cryptogramme visuel :</label><br>
        <input type="text" name="cryptogramme" required maxlength="4" pattern="\d{3,4}"><br><br>

        <button type="submit">Valider le paiement</button>
    </form>
</main>

<footer>
  <section class="contact">
    <h3>Contactez-nous</h3>
    <div class="contact-info">
     <p>Email : <a href="mailto:contact@agorafrancia.com">contact@agorafrancia.com</a></p>
     <p>Téléphone : +33 1 23 45 67 89</p>
    </div>
  </section>
  &copy; <?= date('Y') ?> Agora Francia - Tous droits réservés
</footer>

</body>
</html>