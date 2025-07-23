<?php
require_once 'Config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prenom = $_POST['prenom'];
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $mot_de_passe = password_hash($_POST['mot_de_passe'], PASSWORD_BCRYPT);

    try {
        $stmt = $pdo->prepare("INSERT INTO utilisateurs (prenom, nom, email, mot_de_passe) VALUES (?, ?, ?, ?)");
        $stmt->execute([$prenom, $nom, $email, $mot_de_passe]);
        header('Location: ../mon_compte.php?success=inscription');
    } catch (PDOException $e) {
        header('Location: ../mon_compte.php?error=' . urlencode($e->getMessage()));
    }
}
?>
