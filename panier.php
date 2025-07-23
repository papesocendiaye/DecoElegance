<?php
session_start();  // Démarrer la session

// Vérifier si le panier est dans le localStorage
if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Panier | Déco Élégance</title>
    <link rel="stylesheet" href="assets/css/styles.css">
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

<section class="panier container">
    <h2>Votre Panier</h2>
    <div class="panier-contenu">
        <ul id="panier-list">
            <!-- Les produits ajoutés au panier seront listés ici -->
        </ul>
        <p id="total">Total : 0€</p>
        <a href="backend/valider_commande.php">Valider la commande</a>
    </div>
</section>

<script>
    // Récupérer le panier du localStorage
    let panier = JSON.parse(localStorage.getItem('panier')) || [];
    const panierList = document.getElementById('panier-list');
    const totalElement = document.getElementById('total');
    
    if (panier.length === 0) {
        panierList.innerHTML = '<li>Votre panier est vide.</li>';
    } else {
        let total = 0;
        panier.forEach(item => {
            const productId = item.id;
            const productQuantity = item.quantite;

            // Récupérer les informations du produit depuis la base de données
            fetch(`get_product.php?id=${productId}`)
                .then(response => response.json())
                .then(product => {
                    const totalProductPrice = product.prix * productQuantity;
                    total += totalProductPrice;

                    const productItem = document.createElement('li');
                    productItem.textContent = `${product.nom} - ${productQuantity} x ${product.prix}€ = ${totalProductPrice}€`;
                    panierList.appendChild(productItem);

                    // Mettre à jour le total
                    totalElement.textContent = `Total : ${total}€`;
                });
        });
    }
</script>

</body>
</html>
