<?php
session_start();  

if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: mon_compte.php');
    exit();
}

require_once 'Config.php';

if (!isset($_SESSION['panier']) || empty($_SESSION['panier'])) {
    header('Location: ../panier.php');
    exit();
}

$total = 0;
foreach ($_SESSION['panier'] as $idProduit => $quantite) {
    $stmt = $pdo->prepare("SELECT prix FROM produits WHERE id = ?");
    $stmt->execute([$idProduit]);
    $prix = $stmt->fetchColumn();
    $total += $prix * $quantite;
}
$stmt = $pdo->prepare("INSERT INTO commandes (utilisateur_id, montant_total, statut, date_commande) VALUES (?, ?, ?, NOW())");
$stmt->execute([$_SESSION['utilisateur_id'], $total, 'en attente']);

$commande_id = $pdo->lastInsertId();

foreach ($_SESSION['panier'] as $idProduit => $quantite) {
    $stmt = $pdo->prepare("SELECT prix FROM produits WHERE id = ?");
    $stmt->execute([$idProduit]);
    $prix_unitaire = $stmt->fetchColumn();

    $stmt = $pdo->prepare("INSERT INTO details_commande (commande_id, produit_id, quantite, prix_unitaire) VALUES (?, ?, ?, ?)");
    $stmt->execute([$commande_id, $idProduit, $quantite, $prix_unitaire]);
}

unset($_SESSION['panier']);

header('Location: ../mes_commandes.php');
exit;
?>
