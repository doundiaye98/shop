<?php 
// Page produits 
session_start();
require_once 'backend/db.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nos Produits - Ma Boutique</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="product-buttons.css">
    <link rel="stylesheet" href="cart-styles.css">
</head>
<body>
    <header>
        <?php include 'backend/navbar.php'; ?>
    </header>
    <main>
        <section class="products-list">
            <h1>Nos Produits</h1>
            <div id="products-container" class="products-grid"></div>
        </section>
    </main>
    <?php include 'footer.php'; ?>
    <script src="script.js"></script>
    <script src="cart-manager.js"></script>
    <script>
        // Créer une instance du gestionnaire de panier
        let cartManager;
        
        // Initialiser le gestionnaire de panier
        document.addEventListener('DOMContentLoaded', function() {
            cartManager = new CartManager();
        });
        
        // Fonction pour ajouter au panier depuis la liste
        async function addToCartFromList(productId, quantity = 1) {
            if (!cartManager) {
                alert('Gestionnaire de panier non disponible');
                return;
            }
            
            try {
                const success = await cartManager.addToCart(productId, quantity);
                if (success) {
                    // Afficher une notification de succès
                    showQuickNotification('Produit ajouté au panier !', 'success');
                }
            } catch (error) {
                console.error('Erreur lors de l\'ajout au panier:', error);
                showQuickNotification('Erreur lors de l\'ajout au panier', 'error');
            }
        }
        
        // Fonction pour afficher une notification rapide
        function showQuickNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show position-fixed`;
            notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            notification.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;
            
            document.body.appendChild(notification);
            
            // Supprimer automatiquement après 3 secondes
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 3000);
        }
        
        // Charger les produits
        fetch('backend/products.php')
        .then(res => res.json())
        .then(products => {
            const container = document.getElementById('products-container');
            if (!products.length) {
                container.innerHTML = '<div class="text-center text-muted">Aucun produit à afficher.</div>';
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
                            <button onclick="addToCartFromList(${prod.id}, 1)" class="btn btn-primary">Ajouter au panier</button>
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
                    const message = isActive ? 'Retiré des favoris' : 'Ajouté aux favoris';
                    showQuickNotification(message, 'success');
                }
            } catch (error) {
                console.error('Erreur:', error);
                showQuickNotification('Erreur lors de la mise à jour des favoris', 'error');
            }
        }
    </script>
</body>
</html>