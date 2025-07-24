<?php
session_start();

if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: mon_compte.php');
    exit();
}

require_once 'backend/Config.php';

if (isset($_GET['reservation_id'])) {
    $reservation_id = $_GET['reservation_id'];

    $stmt = $pdo->prepare("SELECT * FROM reservations WHERE id = ? AND utilisateur_id = ?");
    $stmt->execute([$reservation_id, $_SESSION['utilisateur_id']]);
    $reservation = $stmt->fetch();

    if ($reservation) {
        $service = $reservation['service'];
        $date_reservation = $reservation['date_reservation'];
        $heure_reservation = $reservation['heure_reservation'];
        $message = $reservation['message'];
    } else {
        echo "Réservation invalide ou vous n'avez pas accès à cette réservation.";
        exit();
    }
} else {
    $stmt = $pdo->prepare("SELECT * FROM reservations WHERE utilisateur_id = ? ORDER BY id DESC LIMIT 1");
    $stmt->execute([$_SESSION['utilisateur_id']]);
    $reservation = $stmt->fetch();

    if ($reservation) {
        $service = $reservation['service'];
        $date_reservation = $reservation['date_reservation'];
        $heure_reservation = $reservation['heure_reservation'];
        $message = $reservation['message'];
    } else {
        echo "Aucune réservation trouvée.";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de Réservation | Déco Élégance</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>

<header>
    <nav>
        <div class="logo">Déco Élégance</div>
        <ul>
            <li><a href="index.html">Accueil</a></li>
            <li><a href="produits.php">Produits</a></li>
            <li><a href="services.html">Services</a></li>
            <li><a href="realisations.html">Réalisations</a></li>
            <li><a href="blog.html">Blog</a></li>
            <li><a href="a_propos.html">À propos</a></li>
            <li><a href="contact.html">Contact</a></li>
            <li><a href="mon_compte.php">Mon compte</a></li>
            <li><a href="panier.php">Panier</a></li>
        </ul>
    </nav>
</header>

<section class="confirmation">
    <div class="container">
        <h2>Merci pour votre réservation !</h2>
        <p>Votre réservation pour le service <strong><?= htmlspecialchars($service); ?></strong> a été confirmée.</p>
        <p><strong>Date : </strong><?= htmlspecialchars($date_reservation); ?></p>
        <p><strong>Heure : </strong><?= htmlspecialchars($heure_reservation); ?></p>
        <p><strong>Message : </strong><?= htmlspecialchars($message); ?></p>
        
    </div>
    <a href="mes_commandes.php" class="btn">Voir mes commandes</a>
</section>


<footer>
    <p>&copy; 2024 Déco Élégance. Tous droits réservés.</p>
</footer>

</body>
</html>
