// Fonction pour ajouter un produit au panier
document.querySelectorAll('.ajout-panier').forEach(button => {
    button.addEventListener('click', function() {
        const productId = this.dataset.id;
        const quantity = 1; // Par défaut, on ajoute 1 produit

        let panier = JSON.parse(localStorage.getItem('panier')) || [];

        // Vérifier si le produit est déjà dans le panier
        const productInCart = panier.find(item => item.id === productId);
        if (productInCart) {
            productInCart.quantite += quantity; // Si produit existant, on incrémente la quantité
        } else {
            panier.push({ id: productId, quantite: quantity }); // Sinon, on l'ajoute au panier
        }

        // Sauvegarder le panier dans le localStorage
        localStorage.setItem('panier', JSON.stringify(panier));

        // Afficher une alerte ou un message de confirmation
        alert('Produit ajouté au panier !');
    });
});
