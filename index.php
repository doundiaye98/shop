<?php
// Démarrer la session et inclure la base de données
session_start();
require_once 'backend/db.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ma Boutique - Accueil</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons (optionnel) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Ton CSS personnalisé -->
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="modern-styles.css">
    <link rel="stylesheet" href="product-buttons.css">
    <link rel="stylesheet" href="cart-styles.css">
</head>
<body>
    <?php include 'backend/navbar.php'; ?>

    <!-- Message de déconnexion -->
    <?php if (isset($_GET['logout']) && $_GET['logout'] === 'success'): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="margin: 0; border-radius: 0; text-align: center;">
            <strong>✅ Déconnexion réussie !</strong> Vous avez été déconnecté de votre compte.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Hero Banner -->
    <section class="hero-banner d-flex align-items-center justify-content-center text-center" style="background: linear-gradient(120deg, #1a1a1a 60%, #f7f7f7 100%); color: #fff; min-height: 320px;">
        <div class="container py-5">
            <h1 class="display-4">Des nouveautés et promos pour toute la famille !</h1>
                            <p class="lead">Découvrez nos collections Bébé, Fille et Garçon. Livraison rapide et offres exclusives toute l'année.</p>
            <a href="products.php" class="btn btn-lg mt-3" style="background: var(--secondary-color); border-color: var(--secondary-color); color: white;">Voir les produits</a>
        </div>
    </section>



    <!-- Carrousel produits coups de cœur -->
    <section class="container py-5">
        <h2 class="text-center mb-4" style="color: var(--primary-color);">Nos coups de cœur</h2>
        <div class="row" id="carousel-list">
            <div class="text-center text-muted">Chargement des produits...</div>
        </div>
    </section>



    <footer class="text-center py-4 mt-5" style="background: var(--light-blue);">
        <p class="mb-0" style="color: var(--dark-blue);">&copy; 2024 MaBoutique. Tous droits réservés.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
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
        
        // Carrousel produits coups de cœur
        fetch('backend/products.php')
        .then(res => res.json())
        .then(products => {
            const carousel = document.getElementById('carousel-list');
            if (!products.length) {
                carousel.innerHTML = '<div class="text-center text-muted">Aucun produit à afficher.</div>';
                return;
            }
            carousel.innerHTML = products.slice(0,8).map(prod => `
                <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                    <div class="card h-100 modern-product-card animate-fadeInUp">
                        <div class="position-relative">
                            <img src="${prod.image || 'https://via.placeholder.com/200x200?text=Produit'}" class="card-img-top" alt="${prod.name}">
                            <button class="btn-favorite" onclick="toggleFavorite(${prod.id})" id="fav-${prod.id}">
                                <i class="bi bi-heart"></i>
                            </button>
                            ${prod.promo_price && parseFloat(prod.promo_price) < parseFloat(prod.price) ? '<span class="badge badge-sale position-absolute top-0 start-0 m-2">PROMO</span>' : ''}
                        </div>
                        <div class="card-body text-center">
                            <h5 class="card-title">${prod.name}</h5>
                            <p class="card-text text-secondary mb-1">${prod.category || ''}</p>
                            <div class="mb-2">
                                ${prod.promo_price && parseFloat(prod.promo_price) < parseFloat(prod.price) ? 
                                    `<span class="price-old">${prod.price} €</span> <span class="price-modern">${prod.promo_price} €</span>` : 
                                    `<span class="price-modern">${prod.price} €</span>`
                                }
                            </div>
                            <div class="d-flex gap-2 justify-content-center">
                                <a href="product_detail.php?id=${prod.id}" class="btn btn-outline-primary btn-modern">
                                    <i class="bi bi-eye me-1"></i>Voir
                                </a>
                                <button onclick="addToCartFromList(${prod.id}, 1)" class="btn btn-success btn-modern">
                                    <i class="bi bi-cart-plus me-1"></i>Ajouter
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `).join('');
        });

        // Fonction pour basculer les favoris
        async function toggleFavorite(productId) {
            const button = document.getElementById(`fav-${productId}`);
            const icon = button.querySelector('i');
            
            try {
                // Vérifier si le produit est déjà en favoris
                const response = await fetch('backend/favorites_api.php');
                const data = await response.json();
                
                if (data.success) {
                    const isFavorite = data.favorites.some(fav => fav.product_id == productId);
                    
                    if (isFavorite) {
                        // Supprimer des favoris
                        const deleteResponse = await fetch('backend/favorites_api.php', {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({ product_id: productId })
                        });
                        
                        if (deleteResponse.ok) {
                            button.classList.remove('active');
                            icon.className = 'bi bi-heart';
                            showQuickNotification('Produit supprimé des favoris', 'info');
                        }
                    } else {
                        // Ajouter aux favoris
                        const addResponse = await fetch('backend/favorites_api.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({ product_id: productId })
                        });
                        
                        if (addResponse.ok) {
                            button.classList.add('active');
                            icon.className = 'bi bi-heart-fill';
                            showQuickNotification('Produit ajouté aux favoris !', 'success');
                        }
                    }
                }
            } catch (error) {
                console.error('Erreur:', error);
                showQuickNotification('Erreur lors de la gestion des favoris', 'error');
            }
        }

        // Charger l'état des favoris au chargement de la page
        async function loadFavoritesState() {
            try {
                const response = await fetch('backend/favorites_api.php');
                const data = await response.json();
                
                if (data.success) {
                    data.favorites.forEach(favorite => {
                        const button = document.getElementById(`fav-${favorite.product_id}`);
                        if (button) {
                            button.classList.add('active');
                            button.querySelector('i').className = 'bi bi-heart-fill';
                        }
                    });
                }
            } catch (error) {
                console.error('Erreur lors du chargement des favoris:', error);
            }
        }

        // Charger l'état des favoris après le chargement des produits
        setTimeout(loadFavoritesState, 1000);

    </script>
</body>
</html> 