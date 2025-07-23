<?php
require_once 'Config.php';

header('Content-Type: application/json');
$data = json_decode(file_get_contents('php://input'), true);
$panier = $data['panier'];

if(empty($panier)) {
    echo json_encode([]);
    exit;
}

$ids = implode(",", array_map(fn($p) => intval($p['id']), $panier));

$stmt = $pdo->query("SELECT * FROM produits WHERE id IN ($ids)");
$produits = $stmt->fetchAll();

foreach ($produits as &$produit) {
    foreach ($panier as $item) {
        if ($item['id'] == $produit['id']) {
            $produit['quantite'] = $item['quantite'];
        }
    }
}

echo json_encode($produits);
