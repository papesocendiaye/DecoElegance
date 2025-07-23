<?php
session_start();  

if (!isset($_SESSION['panier']) || empty($_SESSION['panier'])) {
    echo "Votre panier est vide.";
    exit();
}

require_once 'backend/Config.php';

$panier = $_SESSION['panier'];
$produits_panier = [];

foreach ($panier as $produit_id => $quantite) {
    $stmt = $pdo->prepare("SELECT * FROM produits WHERE id = ?");
    $stmt->execute([$produit_id]);
    $produit = $stmt->fetch();
    if ($produit) {
        $produit['quantite'] = $quantite;
        $produits_panier[] = $produit;
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Panier | Déco Élégance</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>

<header>
    <nav>
        <div class="logo">Déco Élégance</div>
        <ul>
            <li><a href="index.html">Accueil</a></li>
            <li><a href="produits.php">Produits</a></li>
            <li><a href="panier.php">Panier</a></li>
            <li><a href="mon_compte.php">Mon compte</a></li>
        </ul>
    </nav>
</header>

<section class="panier container">
    <h2>Votre Panier</h2>
    <div class="panier-contenu">
        <ul>
            <?php
            $total = 0;
            foreach ($produits_panier as $produit):
                $total += $produit['prix'] * $produit['quantite'];
            ?>
                <li>
                    <p><?= htmlspecialchars($produit['nom']); ?> - <?= $produit['quantite']; ?> x <?= htmlspecialchars($produit['prix']); ?>€</p>
                </li>
            <?php endforeach; ?>
        </ul>
        <p><strong>Total : <?= $total; ?>€</strong></p>
        <button class="ajout-panier"><a href="backend/valider_commandes.php">Valider la commande</a></button>
        
    </div>
</section>

</body>
</html>
