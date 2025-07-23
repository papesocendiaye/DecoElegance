<?php
session_start();  // Démarrer la session

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: mon_compte.php');
    exit();
}

// Connexion à la base de données
require_once 'dbConfig.php';

// Récupérer l'utilisateur connecté
$utilisateur_id = $_SESSION['utilisateur_id'];

// Récupérer les commandes de l'utilisateur
$stmt = $pdo->prepare("SELECT * FROM commandes WHERE utilisateur_id = ? ORDER BY date_commande DESC");
$stmt->execute([$utilisateur_id]);
$commandes = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes Commandes | Déco Élégance</title>
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

<section class="mes-commandes container">
    <h2>Mes Commandes</h2>
    <?php if (empty($commandes)): ?>
        <p>Vous n'avez pas encore passé de commande.</p>
    <?php else: ?>
        <table>
            <tr>
                <th>Commande ID</th>
                <th>Montant</th>
                <th>Statut</th>
                <th>Date</th>
            </tr>
            <?php foreach ($commandes as $commande): ?>
                <tr>
                    <td><?= $commande['id']; ?></td>
                    <td><?= $commande['montant_total']; ?>€</td>
                    <td><?= $commande['statut']; ?></td>
                    <td><?= $commande['date_commande']; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</section>

</body>
</html>
