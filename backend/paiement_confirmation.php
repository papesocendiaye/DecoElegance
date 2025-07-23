<?php
session_start();

if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: mon_compte.php');
    exit();
}

require_once 'Config.php';


if (isset($_GET['commande_id'])) {
    $commande_id = $_GET['commande_id'];

    $stmt = $pdo->prepare("SELECT * FROM commandes WHERE id = ? AND utilisateur_id = ?");
    $stmt->execute([$commande_id, $_SESSION['utilisateur_id']]);
    $commande = $stmt->fetch();

    if ($commande) {
      
        $stmt = $pdo->prepare("UPDATE commandes SET statut = ? WHERE id = ?");
        $stmt->execute(['payée', $commande_id]);

        $message = "Merci pour votre paiement ! Votre commande #{$commande['id']} a été payée avec succès et est maintenant en traitement.";
    } else {
        $message = "Commande invalide ou vous n'avez pas le droit d'y accéder.";
    }
} else {
    $message = "ID de commande manquant.";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Confirmation de Paiement | Déco Élégance</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>

<section class="confirmation-wrapper">
    <div class="confirmation-container">
        <h2>Confirmation de Paiement</h2>
        <p><?= $message; ?></p>
        <a href="../mes_commandes.php" class="btn-back-to-orders">Voir mes commandes</a>
    </div>
</section>

</body>
</html>
