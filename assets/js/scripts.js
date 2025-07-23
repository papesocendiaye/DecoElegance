document.querySelectorAll('.ajout-panier').forEach(button => {
    button.addEventListener('click', function(){
        const productId = this.dataset.id;
        const quantity = 1; 
        

        let panier = JSON.parse(localStorage.getItem('panier')) || [];

        const productInCart = panier.find(item => item.id === productId);
        if (productInCart) {
            productInCart.quantite += quantity; 
        } else {
            panier.push({ id: productId, quantite: quantity }); 
        }

        localStorage.setItem('panier', JSON.stringify(panier));

        alert('Produit ajouté au panier !');
    });
});
