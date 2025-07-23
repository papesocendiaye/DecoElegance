<?php
session_start();  // Démarrer la session

// Vérifier si l'utilisateur est connecté, sinon rediriger vers la page de connexion
if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: mon_compte.php');
    exit();
}

// Connexion à la base de données
require_once 'Config.php';

// Récupérer le panier de l'utilisateur
if (!isset($_SESSION['panier']) || empty($_SESSION['panier'])) {
    echo "Votre panier est vide.";
    exit();
}

// Calculer le montant total du panier
$total = 0;
foreach ($_SESSION['panier'] as $idProduit => $quantite) {
    // Récupérer les informations du produit
    $stmt = $pdo->prepare("SELECT * FROM produits WHERE id = ?");
    $stmt->execute([$idProduit]);
    $produit = $stmt->fetch();
    $total += $produit['prix'] * $quantite;
}

// Enregistrer la commande dans la table commandes
$stmt = $pdo->prepare("INSERT INTO commandes (utilisateur_id, montant_total, statut) VALUES (?, ?, ?)");
$stmt->execute([$_SESSION['utilisateur_id'], $total, 'en attente']);

// Récupérer l'ID de la commande récemment ajoutée
$commande_id = $pdo->lastInsertId();

// Enregistrer les détails de la commande dans la table details_commande
foreach ($_SESSION['panier'] as $idProduit => $quantite) {
    $stmt = $pdo->prepare("SELECT * FROM produits WHERE id = ?");
    $stmt->execute([$idProduit]);
    $produit = $stmt->fetch();
    $prix_unitaire = $produit['prix'];

    // Insérer les détails de la commande
    $stmt = $pdo->prepare("INSERT INTO details_commande (commande_id, produit_id, quantite, prix_unitaire) VALUES (?, ?, ?, ?)");
    $stmt->execute([$commande_id, $idProduit, $quantite, $prix_unitaire]);
}

// Vider le panier de l'utilisateur après la commande
unset($_SESSION['panier']);

// Afficher un message de confirmation
echo "Commande validée ! Votre commande est en attente de traitement.";
header('Location: ../mes_commandes.php');
exit;
?>
