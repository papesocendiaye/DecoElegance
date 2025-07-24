<?php
session_start();

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: mon_compte.php');
    exit();
}

require_once 'backend/Config.php';

// Vérifier que le service est passé en paramètre
$service = isset($_GET['service']) ? $_GET['service'] : null;

if (!$service) {
    echo "Aucun service sélectionné.";
    exit();
}

// Logique pour traiter la réservation
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer les données du formulaire
    $utilisateur_id = $_SESSION['utilisateur_id'];
    $date_reservation = $_POST['date_reservation'];
    $heure_reservation = $_POST['heure_reservation'];
    $message = $_POST['message'];

    // Connexion à la base de données
    require_once 'backend/Config.php';

    // Insérer la réservation dans la base de données
    $stmt = $pdo->prepare("INSERT INTO reservations (utilisateur_id, service, date_reservation, heure_reservation, message) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$utilisateur_id, $service, $date_reservation, $heure_reservation, $message]);

    // Confirmation de la réservation
    header("Location: reservation_confirmation.php?service=$service");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réservation | Déco Élégance</title>
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

<section class="reservation">
    <div class="container">
        <h2>Réservez pour le service : <?= htmlspecialchars($service); ?></h2>
        <form action="reservation.php?service=<?= htmlspecialchars($service); ?>" method="POST">
            <label for="date_reservation">Date de réservation :</label>
            <input type="date" id="date_reservation" name="date_reservation" required>

            <label for="heure_reservation">Heure de réservation :</label>
            <input type="time" id="heure_reservation" name="heure_reservation" required>

            <label for="message">Message (optionnel) :</label>
            <textarea id="message" name="message" rows="4" placeholder="Votre message ici..."></textarea>

            <button type="submit" class="btn">Confirmer la réservation</button>
        </form>
    </div>
</section>

<footer>
    <p>&copy; 2024 Déco Élégance. Tous droits réservés.</p>
</footer>

</body>
</html>
