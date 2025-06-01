<?php
session_start();

// Connexion à la base (mets tes infos ici)
$host = "localhost";
$dbname = "agora_francia";
$user = "root";
$pass = "root";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
} catch (Exception $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Récupération des articles

$bestSellers = $pdo->query("
    SELECT * FROM articles 
    ORDER BY ventes DESC 
    LIMIT 4
")->fetchAll(PDO::FETCH_ASSOC);

$latest = $pdo->query("
    SELECT * FROM articles 
    WHERE id BETWEEN 18 AND 25
    ORDER BY RAND()
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);

$limitedStock = $pdo->query("
    SELECT * FROM articles 
    WHERE stock < 6
    ORDER BY RAND()
    LIMIT 4
")->fetchAll(PDO::FETCH_ASSOC);

$dailyPick = $pdo->query("
    SELECT * FROM articles 
    ORDER BY RAND()
    LIMIT 3
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>Agora Francia - Accueil</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <style>
    * {
      margin: 0; padding: 0; box-sizing: border-box;
    }
    body {
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
      max-width: 1200px;
      margin: auto;
      padding: 40px 20px;
    }
    .intro {
      margin-bottom: 40px;
      text-align: center;
    }
    .intro h2 {
      font-size: 1.8rem;
      margin-bottom: 10px;
    }
    .intro p {
      font-size: 1.1rem;
      color: #555;
    }
    .about-us {
      margin-bottom: 40px;
      text-align: center;
      color: #2c3e50;
    }
    .about-us h2 {
      font-size: 1.8rem;
      margin-bottom: 15px;
    }
    .carousels-container {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 40px;
      margin-bottom: 60px;

    }
    .carousel-section h2 {
      text-align: center;
      margin-bottom: 20px;

    }
    .carousel {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      overflow: hidden;
      position: relative;
      padding: 20px;
      border: 1px solid #ddd;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.08);
      background-color: #fff;
      gap: 10px;

    }
    .carousel a img {
      width: 300px;
      height: 200px;
      object-fit: contain;
      border-radius: 10px;
      transition: transform 0.3s ease;
      
    
    }
    .carousel .controls {
      display: flex;
      justify-content: center;
      gap: 30px;
      margin-top: 10px;
      
    }
    .carousel .controls button {
      background: transparent;
      border: none;
      font-size: 2rem;
      cursor: pointer;
      color: #2c3e50;
      transition: transform 0.2s;
    }
    .map {
      width: 100%;
      height: 300px;
      border: 0;
      border-radius: 10px;
      margin-top: 40px;
    }
    footer {
      background-color: #2c3e50;
      color: white;
      text-align: center;
      padding: 15px;
      margin-top: 40px;
    }
    .contact {
      margin-bottom: 40px;
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
  <a href="vendre.php">Vendre</a>
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
  <section class="intro">
    <h2>Bienvenue sur Agora Francia</h2>
    <p>Inspiré du marché grec antique, Agora Francia est votre plateforme de commerce électronique moderne où vous pouvez acheter, vendre, négocier et enchérir en ligne.</p>
  </section>

  <section class="about-us">
    <h2>Qui sommes-nous ?</h2>
    <p>Chez <strong>Agora Francia</strong>, on réinvente le marché en ligne avec transparence, sécurité et communauté. Acheteurs, vendeurs, tous réunis pour une expérience unique.</p>
  </section>

  <div class="carousels-container">

    <!-- Best Sellers -->
    <section class="carousel-section">
      <h2>Les plus vendus</h2>
      <div class="carousel">
        <?php foreach($bestSellers as $article): ?>
          <a href="produit.php?id=<?= $article['id'] ?>">
             <img src="imagess/<?= htmlspecialchars($article['image']) ?>" alt="<?= htmlspecialchars($article['nom']) ?>">
          </a>
        <?php endforeach; ?>
        <div class="controls">
          <button class="prev">⬅️</button>
          <button class="next">➡️</button>
        </div>
      </div>
    </section>

    <!-- Latest -->
    <section class="carousel-section">
      <h2>Les plus récents</h2>
      <div class="carousel">
        <?php foreach($latest as $article): ?>
          <a href="produit.php?id=<?= $article['id'] ?>">
             <img src="imagess/<?= htmlspecialchars($article['image']) ?>" alt="<?= htmlspecialchars($article['nom']) ?>">
          </a>
        <?php endforeach; ?>
        <div class="controls">
          <button class="prev">⬅️</button>
          <button class="next">➡️</button>
        </div>
      </div>
    </section>

    <!-- Limited Stock -->
    <section class="carousel-section">
      <h2>Il en reste peu</h2>
      <div class="carousel">
        <?php foreach($limitedStock as $article): ?>
          <a href="produit.php?id=<?= $article['id'] ?>">
             <img src="imagess/<?= htmlspecialchars($article['image']) ?>" alt="<?= htmlspecialchars($article['nom']) ?>">
          </a>
        <?php endforeach; ?>
        <div class="controls">
          <button class="prev">⬅️</button>
          <button class="next">➡️</button>
        </div>
      </div>
    </section>

    <!-- Daily Pick -->
    <section class="carousel-section">
      <h2>Le coup de coeur du jour</h2>
      <div class="carousel">
        <?php foreach($dailyPick as $article): ?>
          <a href="produit.php?id=<?= $article['id'] ?>">
             <img src="imagess/<?= htmlspecialchars($article['image']) ?>" alt="<?= htmlspecialchars($article['nom']) ?>">
          </a>
        <?php endforeach; ?>
        <div class="controls">
          <button class="prev">⬅️</button>
          <button class="next">➡️</button>
        </div>
      </div>
    </section>

  </div>

  <!-- Carte Google Maps -->
  <iframe
    class="map"
    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2624.999308747038!2d2.292292615673621!3d48.85837347928708!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e66fef2a1a1a63%3A0x99732b6d01a8543b!2sTour%20Eiffel!5e0!3m2!1sfr!2sfr!4v1685113839389!5m2!1sfr!2sfr"
    allowfullscreen=""
    loading="lazy"
    referrerpolicy="no-referrer-when-downgrade"
  ></iframe>

  <!-- Contact -->
  
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $(document).ready(function(){
    $('.carousel').each(function(){
      var $carousel = $(this);
      var $images = $carousel.find('a');
      var indexImg = $images.length - 1;
      var i = 0;
      $images.hide().eq(i).show();

      $carousel.find('.next').click(function(){
        i = (i < indexImg) ? i + 1 : 0;
        $images.hide().eq(i).fadeIn();
      });

      $carousel.find('.prev').click(function(){
        i = (i > 0) ? i - 1 : indexImg;
        $images.hide().eq(i).fadeIn();
      });

      function slideImg(){
        setTimeout(function(){
          i = (i < indexImg) ? i + 1 : 0;
          $images.hide().eq(i).fadeIn();
          slideImg();
        }, 4000);
      }
      slideImg();
    });
  });
</script>

</body>
</html>