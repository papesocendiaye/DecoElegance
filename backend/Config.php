<?php
$host = 'mysql-techweb.alwaysdata.net';  
$dbName = 'techweb_projet';  
$username = 'techweb';  
$password = 'passer1234'; 

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbName;charset=utf8",  
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,  
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    echo "Erreur de connexion MySQL : " . $e->getMessage();
    exit();
}
?>
