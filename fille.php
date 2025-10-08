<?php 
// Fille 
session_start();
require_once 'backend/db.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fille - Ma Boutique</title>
    <link rel="stylesheet" href="fille.css">
    <link rel="stylesheet" href="product-buttons.css">
</head>
<body>
    <header>
        <?php include 'backend/navbar.php'; ?>
    </header>
    <main>
        <section class="products-list">
            <h1>Fille</h1>
            <div id="products-container" class="products-grid"></div>
        </section>
    </main>
    <?php include 'footer.php'; ?>
    <script src="script.js"></script>
    <script>
    fetch('backend/products.php?category=Fille')
        .then(res => res.json())
        .then(products => {
            const container = document.getElementById('products-container');
            if (!products.length) {
                container.innerHTML = '<div class="text-center text-muted">Aucun produit fille à afficher.</div>';
                return;
            }
            
            container.innerHTML = products.map(prod => `
                <div class="product-card">
                    <div class="product-image">
                        <img src="${prod.image || 'https://via.placeholder.com/200x200?text=Produit'}" alt="${prod.name}">
                        <button class="btn-favorite" onclick="toggleFavorite(${prod.id})" id="fav-${prod.id}">
                            <i class="bi bi-heart"></i>
                        </button>
                        ${prod.promo_price && parseFloat(prod.promo_price) < parseFloat(prod.price) ? '<span class="badge badge-sale">PROMO</span>' : ''}
                    </div>
                    <div class="product-info">
                        <h3>${prod.name}</h3>
                        <p class="product-category">${prod.category || ''}</p>
                        <div class="product-price">
                            ${prod.promo_price && parseFloat(prod.promo_price) < parseFloat(prod.price) ? 
                                `<span class="price-old">${prod.price} €</span> <span class="price-new">${prod.promo_price} €</span>` : 
                                `<span class="price">${prod.price} €</span>`
                            }
                        </div>
                        <div class="product-actions">
                            <a href="product_detail.php?id=${prod.id}" class="btn btn-outline-primary">Voir</a>
                            <button onclick="addToCart(${prod.id}, 1)" class="btn btn-primary">Ajouter au panier</button>
                        </div>
                    </div>
                </div>
            `).join('');
        });
        
        // Fonction pour basculer les favoris
        async function toggleFavorite(productId) {
            const button = document.getElementById(`fav-${productId}`);
            const isActive = button.classList.contains('active');
            
            try {
                const response = await fetch('backend/favorites_api.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        action: isActive ? 'remove' : 'add',
                        product_id: productId
                    })
                });
                
                if (response.ok) {
                    button.classList.toggle('active');
                }
            } catch (error) {
                console.error('Erreur:', error);
            }
        }
        
        // Fonction pour ajouter au panier
        async function addToCart(productId, quantity = 1) {
            try {
                const response = await fetch('backend/cart_api.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        action: 'add',
                        product_id: productId,
                        quantity: quantity
                    })
                });
                
                if (response.ok) {
                    alert('Produit ajouté au panier !');
                }
            } catch (error) {
                console.error('Erreur:', error);
                alert('Erreur lors de l\'ajout au panier');
            }
        }
    </script>
</body>
</html>