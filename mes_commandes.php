<?php
session_start();

if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: mon_compte.php');
    exit();
}

require_once 'backend/Config.php';

$utilisateur_id = $_SESSION['utilisateur_id'];

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
    <script>
        function toggleDetails(id) {
            const details = document.getElementById('details-' + id);
            if (details.style.display === 'none') {
                details.style.display = 'table-row-group';
            } else {
                details.style.display = 'none';
            }
        }
    </script>
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

<section class="commande-wrapper">
    <div class="commande-container">
        <h2>Mes Commandes</h2>

        <?php if (empty($commandes)): ?>
            <p>Vous n'avez pas encore passé de commande.</p>
        <?php else: ?>
            <table class="commande-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Montant</th>
                        <th>Statut</th>
                        <th>Date</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                <?php foreach ($commandes as $commande): ?>
                    <tr>
                        <td><?= $commande['id'] ?></td>
                        <td><?= number_format($commande['montant_total'], 2) ?>€</td>
                        <td><?= $commande['statut'] ?></td>
                        <td><?= $commande['date_commande'] ?></td>
                        <td><button class="btn-details" onclick="toggleDetails(<?= $commande['id'] ?>)">Détails</button></td>
                        <td>
                           <button  class="btn-details"><a style="text-decoration:none;color:FFF" href="backend/payer_commande.php?id=<?= $commande['id'] ?>" class="btn-payer">Payer</a></button>
                           
                        </td>
                    </tr>

                  
                    <tbody id="details-<?= $commande['id'] ?>" class="commande-details" style="display: none;">
                        <tr>
                            <td colspan="5">
                                <table class="produits-table">
                                    <thead>
                                        <tr>
                                            <th>Produit</th>
                                            <th>Quantité</th>
                                            <th>Prix unitaire</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $stmt2 = $pdo->prepare("
                                                SELECT p.nom, dc.quantite, dc.prix_unitaire
                                                FROM details_commande dc
                                                JOIN produits p ON p.id = dc.produit_id
                                                WHERE dc.commande_id = ?
                                            ");
                                            $stmt2->execute([$commande['id']]);
                                            $details = $stmt2->fetchAll();
                                            foreach ($details as $ligne):
                                        ?>
                                            <tr>
                                                <td><?= htmlspecialchars($ligne['nom']) ?></td>
                                                <td><?= $ligne['quantite'] ?></td>
                                                <td><?= number_format($ligne['prix_unitaire'], 2) ?>€</td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</section>

</body>
</html>
