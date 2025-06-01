<?php
require_once 'config.php';
$mysqli = getDBConnection();

$search = isset($_GET['recherche']) ? trim($_GET['recherche']) : '';

// Requête avec ou sans filtre
if ($search !== '') {
    $stmt = $mysqli->prepare("SELECT * FROM articles WHERE nom LIKE ? ORDER BY FIELD(type_vente, 'meilleure offre', 'transaction client vendeur', 'achat immediat'), id");
    $like = "%$search%";
    $stmt->bind_param("s", $like);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $sql = "SELECT * FROM articles ORDER BY FIELD(type_vente, 'meilleure offre', 'transaction client vendeur', 'achat immediat'), id";
    $result = $mysqli->query($sql);
}

// Organisation des articles
$articles = [
    'meilleure offre' => [],
    'transaction client vendeur' => [],
    'achat immediat' => []
];

while ($row = $result->fetch_assoc()) {
    $articles[$row['type_vente']][] = $row;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Catalogue - Agora Francia</title>
    <link rel="stylesheet" href="style_catalogue.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: url('imagefond.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #333;
            line-height: 1.6;
        }

        header {
            width: 100vw;
            background-color: #2c3e50;
            color: white;
            padding: 20px 0;
            position: relative;
            top: 0;
            left: 0;
        }

        header h1 {
            text-align: center;
            font-size: 2rem;
            color: white;
            margin: 0;
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

        .barre-recherche {
            display: flex;
            justify-content: center;
            margin: 20px auto;
            padding: 10px;
        }

        .barre-recherche form {
            display: flex;
            gap: 10px;
        }

        .barre-recherche input {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            min-width: 300px;
        }

        .barre-recherche button {
            padding: 10px 15px;
            background-color: #1abc9c;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
        }

        .barre-recherche button:hover {
            background-color: #16a085;
        }

        h1 {
            text-align: center;
            margin: 40px 0 20px 0;
        }

        .categorie {
            margin-bottom: 40px;
        }

        .categorie h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 1.5rem;
        }

        .carte-article {
            display: inline-block;
            width: 200px;
            margin: 10px;
            padding: 10px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
            text-align: center;
            text-decoration: none;
            color: inherit;
            transition: transform 0.2s;
        }

        .carte-article:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(0,0,0,0.15);
        }

        .carte-article img {
            max-width: 100%;
            height: 150px;
            object-fit: contain;
            border-radius: 8px;
        }

        .nom { font-weight: bold; margin: 10px 0; }
        .prix { color: green; font-weight: bold; }
        .stock { color: #666; font-size: 0.9em; }

        .btn-retour {
            display: inline-block;
            margin: 40px auto;
            text-decoration: none;
            color: #333;
            background-color: #eee;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: bold;
            text-align: center;
        }

        footer {
            background-color: #2c3e50;
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: 40px;
        }

        .contact {
            margin-bottom: 15px;
        }

        .contact h3 {
            margin-bottom: 10px;
            font-size: 1.3rem;
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

<div class="barre-recherche">
    <form method="get" action="tout_parcourir.php">
        <input type="text" name="recherche" placeholder="Rechercher un article..." value="<?= htmlspecialchars($search) ?>">
        <button type="submit">🔍 Rechercher</button>
    </form>
</div>

<h1>Catalogue complet des articles</h1>

<?php
$articlesAffiches = false;
foreach ($articles as $categorie => $liste):
    if (count($liste) > 0):
        $articlesAffiches = true;
?>
    <div class="categorie">
        <h2><?= ucfirst($categorie) ?></h2>
        <?php foreach ($liste as $article): ?>
            <a class="carte-article" href="produit.php?id=<?= $article['id'] ?>">
                <img src="imagess/<?= htmlspecialchars($article['image']) ?>" alt="<?= htmlspecialchars($article['nom']) ?>">
                <div class="nom"><?= htmlspecialchars($article['nom']) ?></div>
                <div class="prix"><?= $article['prix'] ?> €</div>
                <div class="stock"><?= $article['stock'] ?> en stock</div>
            </a>
        <?php endforeach; ?>
    </div>
<?php
    endif;
endforeach;

if (!$articlesAffiches): ?>
    <p style="text-align: center; margin: 40px 0; font-size: 1.2rem;">Aucun article ne correspond à votre recherche.</p>
<?php endif; ?>

<div style="text-align: center;">
    <a href="index.php" class="btn-retour">← Retour à l'accueil</a>
</div>

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