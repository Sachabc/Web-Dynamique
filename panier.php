<?php
session_start();
require_once 'config.php';

$conn = getDBConnection();

if (empty($_SESSION['panier'])) {
    echo '
    <div class="panier-vide">
        <p>Votre panier est vide.</p>
        <a href="tout_parcourir.php" class="btn">Retour au catalogue</a>
    </div>';
    exit();
}

$ids = array_keys($_SESSION['panier']);
$placeholders = implode(',', array_fill(0, count($ids), '?'));

$sql = "SELECT * FROM articles WHERE id IN ($placeholders)";
$stmt = $conn->prepare($sql);

$types = str_repeat('i', count($ids));
$stmt->bind_param($types, ...$ids);
$stmt->execute();
$result = $stmt->get_result();

$articles_panier = [];
while ($row = $result->fetch_assoc()) {
    $articles_panier[$row['id']] = $row;
}

$total = 0;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Panier - Agora Francia</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
.panier-vide {
    max-width: 400px;
    margin: 100px auto;
    text-align: center;
    background: #f8f9fa;
    padding: 40px 30px;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    font-size: 1.3rem;
    color: #555;
}

.panier-vide a.btn {
    margin-top: 20px;
    display: inline-block;
    background: #007BFF;
    color: white;
    padding: 12px 25px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: bold;
    transition: background 0.3s;
}

.panier-vide a.btn:hover {
    background: #0056b3;
}
.message {
  background: #ffdddd;
  color: #d8000c;
  padding: 10px;
  border-radius: 6px;
  max-width: 600px;
  margin: 20px auto; /* ça centre horizontalement */
  text-align: center;
  font-weight: bold;

  /* Si tu veux aussi verticalement centrer dans la page : */
  position: fixed;
  top: 160px;  /* ou 50% - ajuster à ta convenance */
  left: 50%;
  transform: translateX(-50%);
  z-index: 9999;
}
        .quantite-controls {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.quantite-controls a {
    background-color: #007BFF;
    color: white;
    padding: 6px 10px;
    border-radius: 4px;
    text-decoration: none;
    font-weight: bold;
    transition: background-color 0.3s;
}

.quantite-controls a:hover {
    background-color: #0056b3;
}
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
          flex: 1;
          max-width: 1000px;
          margin: 50px auto;
          background: rgba(255, 255, 255, 0.9);
          border-radius: 12px;
          box-shadow: 0 0 15px rgba(0,0,0,0.2);
          padding: 30px;
        }

        table {
          width: 100%;
          border-collapse: collapse;
        }

        th, td {
          padding: 15px;
          border: 1px solid #ddd;
          text-align: center;
          font-size: 1rem;
        }

        th {
          background-color: #2c3e50;
          color: white;
          text-transform: uppercase;
          letter-spacing: 1px;
        }

        tbody tr:hover {
          background-color: #f0f9f8;
        }

        tfoot td {
          font-weight: bold;
          font-size: 1.2rem;
          color: #2c3e50;
        }

        .btn {
          display: inline-block;
          background: #007BFF;
          color: white;
          padding: 12px 25px;
          border: none;
          border-radius: 8px;
          cursor: pointer;
          font-weight: bold;
          margin-top: 20px;
          text-decoration: none;
          transition: background 0.3s;
        }

        .btn:hover {
          background: #0056b3;
        }

        footer {
          background-color: #2c3e50;
          color: white;
          text-align: center;
          padding: 15px;
          flex-shrink: 0;
          margin-top: auto;
        }

        footer .contact {
          margin-bottom: 20px;
        }

        footer .contact h3 {
          margin-bottom: 15px;
          font-size: 1.5rem;
        }

        footer .contact-info p {
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
 <?php if (isset($_SESSION['message_erreur'])): ?>
      <div id="message-erreur" class="message">
          <?= htmlspecialchars($_SESSION['message_erreur']) ?>
      </div>
      <?php unset($_SESSION['message_erreur']); ?>
      <script>
        setTimeout(() => {
          const msg = document.getElementById('message-erreur');
          if (msg) {
            msg.style.transition = 'opacity 1s ease';
            msg.style.opacity = '0';
            setTimeout(() => { msg.remove(); }, 1000);
          }
        }, 5000);
      </script>
  <?php endif; ?>
<main>
    <table>
        <thead>
            <tr>
                <th>Produit</th>
                <th>Prix Unitaire</th>
                <th>Quantité</th>
                <th>Sous-total</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($_SESSION['panier'] as $id => $qty): 
            $article = $articles_panier[$id];
            $subtotal = $article['prix'] * $qty;
            $total += $subtotal;
        ?>
            <tr>
                <td><?= htmlspecialchars($article['nom']) ?></td>
                <td><?= number_format($article['prix'], 2, ',', ' ') ?> €</td>
                <td>
    <div class="quantite-controls">
        <a href="modifier_quantite.php?id=<?= $article['id'] ?>&action=diminuer">-</a>
        <?= $qty ?>
        <a href="modifier_quantite.php?id=<?= $article['id'] ?>&action=augmenter">+</a>
    </div>
</td>
                <td><?= number_format($subtotal, 2, ',', ' ') ?> €</td>
            </tr>
        <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" style="text-align:right;">Total :</td>
                <td><?= number_format($total, 2, ',', ' ') ?> €</td>
            </tr>
        </tfoot>
    </table>
    <div style="text-align:center; margin-top: 20px;">
    <a href="vider_panier.php" class="btn" style="background:#e74c3c;">Vider le panier</a>
    </div>
    <div style="text-align:center;">
        <a href="tout_parcourir.php" class="btn">Continuer vos achats</a>
    </div>
    <div style="text-align:center;">
        <a href="payement.php" class="btn">Procéder au payement</a>
    </div>

</main>

<footer>
  <section class="contact">
    <h3>Contactez-nous</h3>
    <div class="contact-info">
     <p>Email : <a href="mailto:contact@agorafrancia.com" style="color: #1abc9c;">contact@agorafrancia.com</a></p>
     <p>Téléphone : +33 1 23 45 67 89</p>
    </div>
  </section>
  &copy; <?= date('Y') ?> Agora Francia - Tous droits réservés
</footer>

</body>

</html>