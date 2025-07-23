<?php
session_start();

if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: mon_compte.php');
    exit();
}

require_once 'Config.php';

if (isset($_GET['id'])) {
    $commande_id = $_GET['id'];

    $stmt = $pdo->prepare("SELECT * FROM commandes WHERE id = ? AND utilisateur_id = ?");
    $stmt->execute([$commande_id, $_SESSION['utilisateur_id']]);
    $commande = $stmt->fetch();

    if ($commande) {
        
        $stmt = $pdo->prepare("UPDATE commandes SET statut = ? WHERE id = ?");
        $stmt->execute(['payée', $commande_id]);

      
        header('Location: paiement_confirmation.php?commande_id=' . $commande_id);
        exit();
    } else {
        echo "Commande invalide ou vous n'avez pas le droit d'y accéder.";
    }
} else {
    echo "ID de commande manquant.";
}
?>
