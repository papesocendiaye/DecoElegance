<?php
require_once 'backend/Config.php';  // Connexion à la base de données

// Récupérer tous les produits de la base de données
$stmt = $pdo->query("SELECT * FROM produits ORDER BY date_creation DESC");
$produits = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Nos Produits | Déco Élégance</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>

<header>
    <nav>
        <div class="logo">Déco Élégance</div>
        <ul>
            <li><a href="index.php">Accueil</a></li>
            <li><a href="produits.php">Produits</a></li>
            <li><a href="panier.php">Panier</a></li>
            <li><a href="mon_compte.php">Mon compte</a></li>
        </ul>
    </nav>
</header>

<section class="catalogue container">
    <h2>Nos Produits</h2>
    <div class="produits">
        <?php foreach ($produits as $produit): ?>
            <div class="produit">
                <img src="assets/images/<?= htmlspecialchars($produit['image']); ?>" alt="<?= htmlspecialchars($produit['nom']); ?>">
                <h3><?= htmlspecialchars($produit['nom']); ?></h3>
                <p><?= htmlspecialchars($produit['description']); ?></p>
                <p class="prix"><?= htmlspecialchars($produit['prix']); ?>€</p>
               <button class="ajout-panier" data-id="<?= $produit['id']; ?>">Ajouter au panier</button>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<script src="assets/js/scripts.js"></script>

</body>
</html>
