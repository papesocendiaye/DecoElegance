<?php
// Paramètres de connexion à la base de données MySQL
$host = 'mysql-techweb.alwaysdata.net';  // Remplace par l'adresse IP de ton serveur si nécessaire
$dbName = 'techweb_projet';  // Nom de ta base de données
$username = 'techweb';  // Nom de l'utilisateur MySQL
$password = 'passer1234';  // Mot de passe de l'utilisateur MySQL

try {
    // Création de la connexion PDO avec MySQL
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbName;charset=utf8",  // Connexion MySQL avec charset UTF-8
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,  // Activation du mode erreur
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,  // Récupérer les résultats sous forme de tableau associatif
        ]
    );
    // Affichage de la réussite de la connexion
    echo "Connexion réussie à MySQL.";
} catch (PDOException $e) {
    // Si une erreur survient, on l'affiche
    echo "Erreur de connexion MySQL : " . $e->getMessage();
    exit();
}
?>
