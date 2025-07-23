<?php
session_start();  

if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: mon_compte.php');
    exit();
}

require_once 'Config.php';

if (!isset($_SESSION['panier']) || empty($_SESSION['panier'])) {
    echo "Votre panier est vide.";
    exit();
}

$total = 0;
foreach ($_SESSION['panier'] as $idProduit => $quantite) {
    $stmt = $pdo->prepare("SELECT * FROM produits WHERE id = ?");
    $stmt->execute([$idProduit]);
    $produit = $stmt->fetch();
    $total += $produit['prix'] * $quantite;
}

$stmt = $pdo->prepare("INSERT INTO commandes (utilisateur_id, montant_total, statut) VALUES (?, ?, ?)");
$stmt->execute([$_SESSION['utilisateur_id'], $total, 'en attente']);

$commande_id = $pdo->lastInsertId();

foreach ($_SESSION['panier'] as $idProduit => $quantite) {
    $stmt = $pdo->prepare("SELECT * FROM produits WHERE id = ?");
    $stmt->execute([$idProduit]);
    $produit = $stmt->fetch();
    $prix_unitaire = $produit['prix'];

    $stmt = $pdo->prepare("INSERT INTO details_commande (commande_id, produit_id, quantite, prix_unitaire) VALUES (?, ?, ?, ?)");
    $stmt->execute([$commande_id, $idProduit, $quantite, $prix_unitaire]);
}

unset($_SESSION['panier']);

echo "Commande validée ! Votre commande est en attente de traitement.";
header('Location: ../mes_commandes.php');
exit;
?>
