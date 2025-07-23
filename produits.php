<?php
session_start();  // Démarrer la session

// Vérifier si le panier existe, sinon le créer
if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}

// Connexion à la base de données
require_once 'backend/Config.php';

// Récupérer tous les produits depuis la base de données
$stmt = $pdo->query("SELECT * FROM produits ORDER BY date_creation DESC");
$produits = $stmt->fetchAll();

// Gérer l'ajout au panier
if (isset($_POST['ajouter_panier'])) {
    $produit_id = $_POST['produit_id'];
    $quantite = 1; // On peut modifier cette logique pour ajouter des quantités spécifiques

    // Vérifier si le produit est déjà dans le panier
    if (isset($_SESSION['panier'][$produit_id])) {
        $_SESSION['panier'][$produit_id] += $quantite;
    } else {
        $_SESSION['panier'][$produit_id] = $quantite;
    }

    header('Location: panier.php');  // Rediriger vers le panier après ajout
    exit();
}
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
                <img src="<?= htmlspecialchars($produit['image']); ?>" alt="<?= htmlspecialchars($produit['nom']); ?>" class="produit-image">
                <h3><?= htmlspecialchars($produit['nom']); ?></h3>
                <p><?= htmlspecialchars($produit['description']); ?></p>
                <p class="prix"><?= htmlspecialchars($produit['prix']); ?>€</p>

                <!-- Formulaire pour ajouter au panier -->
                <form action="produits.php" method="POST">
                    <input type="hidden" name="produit_id" value="<?= $produit['id']; ?>">
                    <button type="submit" name="ajouter_panier" class="ajout-panier">Ajouter au panier</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<script src="assets/js/scripts.js"></script>

</body>
</html>
