-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:8889
-- Généré le : mar. 27 mai 2025 à 14:59
-- Version du serveur : 8.0.40
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `agora_francia`
--

-- --------------------------------------------------------

--
-- Structure de la table `articles`
--

CREATE TABLE `articles` (
  `id` int NOT NULL,
  `nom` varchar(255) NOT NULL,
  `type_vente` enum('meilleure offre','transaction client vendeur','achat immediat') NOT NULL,
  `prix` decimal(10,2) NOT NULL,
  `image` varchar(255) NOT NULL,
  `stock` int NOT NULL,
  `description` text,
  `categorie` varchar(100) DEFAULT NULL,
  `ventes` int DEFAULT '0',
  `online` int DEFAULT '0',
  `date_achat` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `articles`
--

INSERT INTO `articles` (`id`, `nom`, `type_vente`, `prix`, `image`, `stock`, `description`, `categorie`, `ventes`, `online`, `date_achat`) VALUES
(1, 'brosse toilette pistolet', 'meilleure offre', 39.99, 'brosse_toilette_pistolet.jpg', 5, 'Transforme une corvée en mission badass avec cette brosse toilette en forme de pistolet. Fun, insolite et redoutablement efficace.', 'salle de bain', 950, 1, NULL),
(2, 'chaussettes cornichons', 'meilleure offre', 14.99, 'chaussettes cornichons.jpg', 7, 'Chaussettes cornichons pour ceux qui osent l’originalité jusqu’au bout des pieds. Un style piquant et unique.', 'mode', 800, 1, NULL),
(3, 'coussin avocat géant', 'meilleure offre', 45.00, 'coussin_avocat_géant.jpg', 3, 'Un coussin avocat géant moelleux à souhait. Parfait pour chiller avec classe et croquer la vie à pleines dents.', 'décoration', 700, 1, NULL),
(4, 'gomme géante chat', 'meilleure offre', 25.50, 'gomme geante chat.jpg', 4, 'La gomme géante chat, l’alliée mignonne et pratique pour effacer tes erreurs avec douceur et style.', 'fournitures scolaires', 150, 1, NULL),
(5, 'paillassons tacos', 'meilleure offre', 28.00, 'paillassons tacos.jpg', 6, 'Un paillasson tacos qui dit clairement : \"Ici on a du goût\". Donne faim dès le seuil de la porte.', 'décoration', 120, 1, NULL),
(6, 'tee shirt licorne', 'meilleure offre', 32.99, 'tee shirt licorne.jpg', 5, 'Un tee-shirt licorne pour briller sans complexe. Magique, coloré, et carrément stylé.', 'mode', 900, 1, NULL),
(7, 'boucle d\'oreille nouille', 'transaction client vendeur', 4.99, 'boucle_d\'oreille nouille.jpg', 12, 'Ajoute une touche WTF à ton style avec ces boucles d’oreilles nouilles. L’Asie dans tes lobes.', 'accessoires', 60, 1, NULL),
(8, 'boucle d\'oreille sushi', 'transaction client vendeur', 6.99, 'boucle_d\'oreille_sushi.jpg', 10, 'Pour les amoureux de sushi qui veulent l’afficher fièrement jusque sur leurs oreilles. Fraîcheur garantie.', 'accessoires', 300, 1, NULL),
(9, 'poêle à smiley', 'transaction client vendeur', 8.50, 'poele a smiley.jpg', 9, 'Poêle à smiley : pour des pancakes ou œufs frits qui te donnent la banane dès le matin.', 'cuisine', 45, 1, NULL),
(10, 'porte-clé pain', 'transaction client vendeur', 3.99, 'porte_clé_pain.jpg', 15, 'Le porte-clé pain, pour les vrais fans de carbo-vibes. Discret, drôle, et croustillant.', 'accessoires', 200, 1, NULL),
(11, 'pistolet à ketchup', 'transaction client vendeur', 6.00, 'pistolet à ketchup.jpg', 8, 'Un pistolet à ketchup pour tirer la sauce comme un vrai cow-boy. Parfait pour ambiancer les barbecues.', 'cuisine', 75, 1, NULL),
(12, 'sacoche smoothie', 'transaction client vendeur', 5.00, 'sacoche smoothie.jpg', 14, 'La sacoche smoothie, c’est la boisson stylée à emporter façon mode. Pratique et décalée.', 'mode', 30, 1, NULL),
(13, 'coussin brioche', 'achat immediat', 18.90, 'coussin brioche.jpg', 10, 'Ce coussin brioche est une masterclass de moelleux. Idéal pour une déco sucrée-salée.', 'décoration', 40, 1, NULL),
(14, 'coussin baguette', 'achat immediat', 16.00, 'coussin-baguette.jpg', 11, 'Le coussin baguette, pour les patriotes gourmands. Ultra réaliste, et méchamment confortable.', 'décoration', 20, 1, NULL),
(15, 'tapis de souris manga', 'achat immediat', 9.99, 'tapis de souris manga.jpg', 13, 'Tapis de souris manga : performance et passion réunies. Otaku dans l’âme ? Ce tapis est pour toi.', 'informatique', 15, 1, NULL),
(16, 'boite bijoux burger', 'meilleure offre', 29.90, 'boite bijoux burger.jpg', 6, 'Une boîte à bijoux en forme de burger pour les fans de junk food et de bling.', 'accessoires déco', 100, 1, NULL),
(17, 'brosse chat main', 'transaction client vendeur', 4.99, 'brosse chat main .jpg', 20, 'Brosse en forme de patte de chat pour des sessions de toilettage stylées.', 'animalerie', 180, 1, NULL),
(18, 'calendrier pigeon', 'transaction client vendeur', 6.99, 'calendrier pigeon.jpg', 13, 'Chaque mois, un pigeon différent. Oui, c’est chelou mais on adore.', 'fournitures', 50, 1, NULL),
(19, 'casque chat lumineux', 'meilleure offre', 49.99, 'casque chat lumineux.jpg', 4, 'Casque audio avec oreilles de chat LED. Pour briller même dans le RER.', 'électronique', 25, 1, NULL),
(20, 'coussin nicolas', 'achat immediat', 17.50, 'coussin nicolas .jpg', 9, 'Coussin personnalisé avec la tête de Nicolas. Ironique ou iconique ? À toi de voir.', 'décoration', 10, 1, NULL),
(21, 'crayons', 'transaction client vendeur', 2.99, 'crayons.jpg', 25, 'Lot de crayons fun pour écrire des idées encore plus folles.', 'fournitures scolaires', 55, 1, NULL),
(22, 'figurine shrek', 'meilleure offre', 35.00, 'figurine schrek .jpg', 5, 'La figurine Shrek officielle pour un max de swag ogresque sur ton étagère.', 'décoration geek', 65, 1, NULL),
(23, 'i love frog', 'achat immediat', 12.90, 'i love frog.jpg', 9, 'Poster ou objet déco \"I Love Frog\" pour les fans d’amphibiens engagés.', 'décoration', 35, 1, NULL),
(24, 'panier chat forme requin', 'meilleure offre', 34.99, 'panier chat forme requin.jpg', 6, 'Un panier pour chat en forme de requin. Mignon, stylé, et un peu terrifiant.', 'animalerie', 80, 1, NULL),
(25, 'pyjama pizza', 'achat immediat', 24.90, 'pyjama pizza.jpg', 7, 'Un pyjama pizza pour dormir comme une part de bonheur fondue.', 'mode', 1000, 1, NULL),
(26, 'statue chaton illuminé', 'meilleure offre', 22.00, 'statue chaton illuminé.jpg', 3, 'Statue de chaton lumineux, parfaite pour une touche magique sur ta commode.', 'décoration lumineuse', 0, 0, NULL),
(27, 'tapis forme chien', 'achat immediat', 19.90, 'tapis forme chien.jpg', 8, 'Tapis en forme de chien pour les pet lovers stylés.', 'décoration', 0, 0, NULL);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `articles`
--
ALTER TABLE `articles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
