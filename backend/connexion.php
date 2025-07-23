<?php
session_start();  // Démarrer la session

// Connexion à la base de données
require_once 'Config.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];

    // Requête pour vérifier l'existence de l'utilisateur
    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    // Vérifier si l'utilisateur existe et si le mot de passe est correct
    if ($user && password_verify($mot_de_passe, $user['mot_de_passe'])) {
        // Sauvegarder les informations dans la session
        $_SESSION['utilisateur_id'] = $user['id'];
        $_SESSION['prenom'] = $user['prenom'];
        $_SESSION['nom'] = $user['nom'];
        header('Location: index.php');  // Redirection vers la page d'accueil
    } else {
        header('Location: mon_compte.php?error=Identifiants invalides');
    }
}
?>
