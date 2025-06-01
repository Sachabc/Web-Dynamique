<?php
session_start();
require_once 'config.php';

$conn = getDBConnection();

if (!isset($_GET['id'])) {
    die("ID de l'article manquant.");
}

$id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT * FROM articles WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Article non trouvé.");
}

$article = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($article['nom']) ?> - Détails du produit</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
       * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

html, body {
    height: 100%;
}

body {
    display: flex;
    flex-direction: column;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: url('imagefond.jpg') no-repeat center center fixed;
    background-size: cover;
    color: #333;
    line-height: 1.6;
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
    flex: 1;
}

.conteneur {
    display: flex;
    background: #fff;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
    max-width: 900px;
    margin: 50px auto;
}

.conteneur img {
    width: 300px;
    height: auto;
    border-radius: 10px;
    object-fit: contain;
    margin-right: 30px;
}

.infos {
    flex: 1;
}

h1 {
    font-size: 24px;
    margin-bottom: 10px;
}

.prix {
    font-size: 22px;
    color: green;
    margin: 10px 0;
}

.categorie, .stock {
    font-size: 16px;
    color: #555;
}

.description {
    margin-top: 15px;
    line-height: 1.5;
}

.bouton-ajouter {
    display: inline-block;
    margin-top: 20px;
    background: #007BFF;
    color: white;
    padding: 10px 20px;
    font-weight: bold;
    border-radius: 5px;
    border: none;
    cursor: pointer;
    transition: background 0.3s;
}

.bouton-ajouter:hover {
    background: #0056b3;
}

.retour {
    text-align: center;
    margin: 30px 0;
}

.retour a {
    text-decoration: none;
    color: #333;
    background: #e0e0e0;
    padding: 8px 16px;
    border-radius: 6px;
    transition: background 0.3s;
}

.retour a:hover {
    background: #ccc;
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
  <a href="#">Notifications</a>
  <a href="panier.php">Panier</a>
  <?php if (isset($_SESSION["prenom"])): ?>
    <span>Bonjour, <?= htmlspecialchars($_SESSION["prenom"]) ?></span>
    <a href="logout.php">Déconnexion</a>
  <?php else: ?>
    <a href="formulaire_clients.php">Votre Compte</a>
  <?php endif; ?>
</nav>

<main>
    <div class="conteneur">
    <img src="imagess/<?= htmlspecialchars($article['image']) ?>" alt="<?= htmlspecialchars($article['nom']) ?>">

    <div class="infos">
        <h1><?= htmlspecialchars($article['nom']) ?></h1>
        <div class="prix"><?= htmlspecialchars($article['prix']) ?> €</div>
        <div class="categorie">Catégorie : <?= htmlspecialchars($article['categorie']) ?></div>
        <div class="stock">Stock restant : <?= htmlspecialchars($article['stock']) ?></div>
        <div class="description"><?= nl2br(htmlspecialchars($article['description'])) ?></div>

        <!-- Formulaire ajout au panier -->
       <form method="POST" action="
<?php 
    if ($article['type_vente'] === 'achat immediat') {
        echo 'ajouter_panier.php';
    } elseif ($article['type_vente'] === 'meilleure offre') {
        echo 'encheres.php?id=' . $article['id'];
    } elseif ($article['type_vente'] === 'transaction client vendeur') {
        echo 'negociations.php?id=' . $article['id'];
    } else {
        echo '#'; // fallback au cas où
    }
?>">
    <input type="hidden" name="id" value="<?= $article['id'] ?>">

    <?php if ($article['type_vente'] === 'achat immediat'): ?>
    <form method="POST" action="ajouter_panier.php">
        <input type="hidden" name="id" value="<?= $article['id'] ?>">
        <button type="submit" class="bouton-ajouter">Ajouter au panier</button>
    </form>
<?php elseif ($article['type_vente'] === 'meilleure offre'): ?>
    <button type="button" class="bouton-ajouter" onclick="window.location.href='encheres.php?id=<?= $article['id'] ?>'">Lancer les enchères</button>
<?php elseif ($article['type_vente'] === 'transaction client vendeur'): ?>
    <button type="button" class="bouton-ajouter" onclick="window.location.href='negociations.php?id=<?= $article['id'] ?>'">Lancer les négociations</button>
<?php else: ?>
    <button disabled>Type de vente inconnu</button>
<?php endif; ?>
</form>
    </div>
</div>

<div class="retour">
    <a href="tout_parcourir.php">← Retour au catalogue</a> | 
    <a href="index.php">← Retour à l'accueil</a>
    <div style="text-align:center; margin: 30px 0;">
    <form action="payement.php" method="GET">
        <button type="submit" class="bouton-ajouter" style="padding: 12px 25px; font-size: 18px;">
            Procéder au paiement
        </button>
    </form>
</div>
</div>
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