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
    <footer>
        <p>&copy; 2024 MaBoutique. Tous droits réservés.</p>
    </footer>
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
        
        // Charger les produits dynamiquement
        fetch('backend/products.php')
        .then(res => res.json())
        .then(products => {
            const container = document.getElementById('products-container');
            if (products.length === 0) {
                container.innerHTML = '<p>Aucun produit disponible.</p>';
                return;
            }
            container.innerHTML = products.map(prod => `
                <div class="product-card">
                    <img src="${prod.image || 'https://via.placeholder.com/200x200?text=Produit'}" alt="${prod.name}">
                    <h2>${prod.name}</h2>
                    <p class="category">${prod.category || ''}</p>
                    <p class="price">${prod.price} €</p>
                                                <div class="d-flex gap-2 justify-content-center">
                                                    <a href="product_detail.php?id=${prod.id}" class="btn-view-product">👁️ Voir</a>
                                                    <button onclick="addToCartFromList(${prod.id}, 1)" class="btn btn-success btn-sm">
                                                        <i class="bi bi-cart-plus"></i> Ajouter
                                                    </button>
                                                </div>
                </div>
            `).join('');
        });
    </script>
</body>
</html> 