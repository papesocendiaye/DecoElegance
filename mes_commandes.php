<?php
session_start();  // Démarrer la session

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: mon_compte.php');
    exit();
}

// Connexion à la base de données
require_once 'backend/Config.php';

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

<section class="mes-commandes-container">
    <h2>Mes Commandes</h2>

    <?php if (empty($commandes)): ?>
        <p class="aucune-commande">Vous n'avez pas encore passé de commande.</p>
    <?php else: ?>
        <table class="mes-commandes-table">
            <thead>
                <tr>
                    <th>Commande ID</th>
                    <th>Montant</th>
                    <th>Statut</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($commandes as $commande): ?>
                    <tr>
                        <td><?= $commande['id']; ?></td>
                        <td><?= $commande['montant_total']; ?>€</td>
                        <td><?= $commande['statut']; ?></td>
                        <td><?= $commande['date_commande']; ?></td>
                    </tr>
                    <tr class="commande-details-row" id="details-commande-<?= $commande['id']; ?>" style="display: none;">
                        <td colspan="4">
                            <div class="commande-details-box">
                                <strong>Détails de la commande #<?= $commande['id']; ?></strong>
                                <ul class="commande-details-list">
                                    <?php
                                    $stmtDetails = $pdo->prepare("
                                        SELECT p.nom, c.quantite, c.prix_unitaire
                                        FROM commande_produit c
                                        JOIN produits p ON c.produit_id = p.id
                                        WHERE c.commande_id = ?
                                    ");
                                    $stmtDetails->execute([$commande['id']]);
                                    $produits = $stmtDetails->fetchAll();
                                    foreach ($produits as $produit):
                                    ?>
                                        <li>
                                            <?= htmlspecialchars($produit['nom']); ?> — 
                                            <?= $produit['quantite']; ?> × 
                                            <?= number_format($produit['prix_unitaire'], 2); ?>€
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" class="commande-btn-cell">
                            <button class="commande-detail-toggle" onclick="toggleDetails(<?= $commande['id']; ?>)">Détails</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</section>

<script>
function toggleDetails(id) {
    const row = document.getElementById('details-commande-' + id);
    row.style.display = (row.style.display === "none") ? "table-row" : "none";
}
</script>

</body>
</html>
