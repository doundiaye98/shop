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

    <!-- CSS personnalisé -->
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="modern-styles.css">
    <link rel="stylesheet" href="product-buttons.css">
    <link rel="stylesheet" href="cart-styles.css">
    <link rel="stylesheet" href="modern-home.css">
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

    <!-- Hero Banner Moderne -->
    <section class="hero-section">
        <div class="hero-background">
            <div class="hero-overlay"></div>
        </div>
        <div class="container">
            <div class="row align-items-center min-vh-60">
                <div class="col-lg-6">
                    <div class="hero-content">
                        <h1 class="hero-title">
                            <span class="hero-highlight">Nouveautés</span> et 
                            <span class="hero-promo">Promos</span> 
                            pour toute la famille !
                        </h1>
                        <p class="hero-subtitle">
                            Découvrez nos collections tendance pour Bébé, Fille et Garçon. 
                            Livraison rapide et offres exclusives toute l'année.
                        </p>
                        <div class="hero-buttons">
                            <a href="products.php" class="btn btn-primary btn-hero">
                                <i class="bi bi-bag-heart me-2"></i>
                                Découvrir nos produits
                            </a>
                            <a href="promos.php" class="btn btn-outline-light btn-hero">
                                <i class="bi bi-percent me-2"></i>
                                Voir les promos
                            </a>
                        </div>
                        <div class="hero-stats">
                            <div class="stat-item">
                                <span class="stat-number">500+</span>
                                <span class="stat-label">Produits</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number">1000+</span>
                                <span class="stat-label">Clients satisfaits</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number">24h</span>
                                <span class="stat-label">Livraison rapide</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="hero-image">
                        <div class="hero-cards">
                            <div class="hero-card card-bebe">
                                <img src="backend/madakids/ensemble 2 pièces.jpg" alt="Bébé" class="hero-card-img">
                                <div class="hero-card-overlay">
                                    <h4>Bébé</h4>
                                    <a href="bebe.php" class="btn btn-sm btn-light">Voir collection</a>
                                </div>
                            </div>
                            <div class="hero-card card-fille">
                                <img src="backend/madakids-fille/robe.jpg" alt="Fille" class="hero-card-img">
                                <div class="hero-card-overlay">
                                    <h4>Fille</h4>
                                    <a href="fille.php" class="btn btn-sm btn-light">Voir collection</a>
                                </div>
                            </div>
                            <div class="hero-card card-garcon">
                                <img src="backend/madakids/jean.jpg" alt="Garçon" class="hero-card-img">
                                <div class="hero-card-overlay">
                                    <h4>Garçon</h4>
                                    <a href="garcon.php" class="btn btn-sm btn-light">Voir collection</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>



    <!-- Section produits coups de cœur -->
    <section class="products-section py-5">
        <div class="container">
            <div class="section-header text-center mb-5">
                <h2 class="section-title">Nos coups de cœur</h2>
                <p class="section-subtitle">Découvrez nos produits les plus populaires</p>
                <div class="section-divider"></div>
            </div>
            
            <div class="row" id="carousel-list">
                <div class="col-12 text-center">
                    <div class="loading-spinner">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Chargement...</span>
                        </div>
                        <p class="mt-3 text-muted">Chargement des produits...</p>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-4">
                <a href="products.php" class="btn btn-outline-primary btn-lg">
                    <i class="bi bi-grid-3x3-gap me-2"></i>
                    Voir tous les produits
                </a>
            </div>
        </div>
    </section>

    <!-- Section avantages -->
    <section class="advantages-section py-5 bg-light">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="advantage-card text-center">
                        <div class="advantage-icon">
                            <i class="bi bi-truck"></i>
                        </div>
                        <h4>Livraison Rapide</h4>
                        <p>Livraison gratuite dès 29€ et sous 24h</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="advantage-card text-center">
                        <div class="advantage-icon">
                            <i class="bi bi-arrow-clockwise"></i>
                        </div>
                        <h4>Retour Gratuit</h4>
                        <p>Retournez vos articles sous 14 jours</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="advantage-card text-center">
                        <div class="advantage-icon">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h4>Paiement Sécurisé</h4>
                        <p>Vos données sont protégées et sécurisées</p>
                    </div>
                </div>
            </div>
        </div>
    </section>



    <?php include 'footer.php'; ?>

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
                    <div class="product-card-wrapper">
                        <div class="product-card animate-fadeInUp">
                            <div class="product-image-container">
                                <img src="${prod.image || 'https://via.placeholder.com/200x200?text=Produit'}" 
                                     class="product-image" 
                                     alt="${prod.name}"
                                     loading="lazy">
                                <div class="product-overlay">
                                    <button class="btn btn-sm btn-outline-light product-btn" 
                                            onclick="addToCartFromList(${prod.id}, 1)">
                                        <i class="bi bi-cart-plus"></i>
                                    </button>
                                    <a href="product_detail.php?id=${prod.id}" 
                                       class="btn btn-sm btn-outline-light product-btn">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </div>
                                <button class="favorite-btn" onclick="toggleFavorite(${prod.id})" id="fav-${prod.id}">
                                    <i class="bi bi-heart"></i>
                                </button>
                                ${prod.promo_price && parseFloat(prod.promo_price) < parseFloat(prod.price) ? 
                                    '<span class="promo-badge">PROMO</span>' : ''}
                            </div>
                            <div class="product-info">
                                <h5 class="product-name">${prod.name}</h5>
                                <p class="product-category">${prod.category || ''}</p>
                                <div class="product-price">
                                    ${prod.promo_price && parseFloat(prod.promo_price) < parseFloat(prod.price) ? 
                                        `<span class="price-original">${prod.price} €</span> 
                                         <span class="price-current">${prod.promo_price} €</span>` : 
                                        `<span class="price-current">${prod.price} €</span>`
                                    }
                                </div>
                                <div class="product-actions">
                                    <a href="product_detail.php?id=${prod.id}" 
                                       class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-eye me-1"></i>Voir
                                    </a>
                                    <button onclick="addToCartFromList(${prod.id}, 1)" 
                                            class="btn btn-primary btn-sm">
                                        <i class="bi bi-cart-plus me-1"></i>Ajouter
                                    </button>
                                </div>
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