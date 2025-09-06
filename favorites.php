<?php
// Page des favoris
session_start();
require_once 'backend/db.php';
require_once 'backend/auth_check.php';
requireLogin();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Favoris - Ma Boutique</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="modern-styles.css">
    <style>
        /* Styles spécifiques supplémentaires si nécessaire */
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <?php include 'backend/navbar.php'; ?>
    
    <!-- Header des favoris -->
    <div class="favorites-header">
        <div class="container">
            <h1><i class="bi bi-heart-fill me-3"></i>Mes Favoris</h1>
            <p>Vos produits préférés en un seul endroit</p>
        </div>
    </div>
    
    <div class="favorites-container">
        <div class="container">
            <!-- Conteneur des favoris -->
            <div id="favorites-container">
                <div class="loading-favorites">
                    <i class="bi bi-arrow-clockwise"></i>
                    <p>Chargement de vos favoris...</p>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Charger les favoris au chargement de la page
        document.addEventListener('DOMContentLoaded', function() {
            loadFavorites();
        });
        
        // Charger les favoris depuis l'API
        async function loadFavorites() {
            try {
                const response = await fetch('backend/favorites_api.php', {
                    method: 'GET',
                    credentials: 'same-origin'
                });
                
                if (response.ok) {
                    const data = await response.json();
                    displayFavorites(data);
                } else {
                    showError('Erreur lors du chargement des favoris');
                }
            } catch (error) {
                console.error('Erreur:', error);
                showError('Erreur de connexion');
            }
        }
        
        // Afficher les favoris
        function displayFavorites(data) {
            const container = document.getElementById('favorites-container');
            
            if (!data.success) {
                showError(data.message || 'Erreur lors du chargement');
                return;
            }
            
            if (data.favorites.length === 0) {
                container.innerHTML = `
                    <div class="empty-favorites">
                        <i class="bi bi-heart"></i>
                        <h3>Votre liste de favoris est vide</h3>
                        <p>Découvrez nos produits et ajoutez-les à vos favoris !</p>
                        <a href="index.php" class="btn btn-primary btn-modern">
                            <i class="bi bi-shop me-2"></i>Voir nos produits
                        </a>
                    </div>
                `;
                return;
            }
            
            // Afficher les favoris
            container.innerHTML = `
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h3><i class="bi bi-heart-fill me-2"></i>${data.count} produit(s) favori(s)</h3>
                    </div>
                    <div class="col-md-6 text-end">
                        <button class="btn btn-outline-danger" onclick="clearAllFavorites()">
                            <i class="bi bi-trash me-2"></i>Vider la liste
                        </button>
                    </div>
                </div>
                <div class="favorites-grid">
                    ${data.favorites.map(favorite => `
                        <div class="favorite-item animate-fadeInUp">
                            <img src="${favorite.image || 'https://via.placeholder.com/300x200?text=Produit'}" 
                                 alt="${favorite.name}" class="favorite-image">
                            <h5>${favorite.name}</h5>
                            <p class="text-muted mb-2">${favorite.category}</p>
                            <p class="price-modern">${favorite.price} €</p>
                            <div class="favorite-actions">
                                <a href="product_detail.php?id=${favorite.product_id}" class="btn btn-primary btn-modern">
                                    <i class="bi bi-eye me-2"></i>Voir
                                </a>
                                <button class="btn btn-success btn-modern" onclick="addToCart(${favorite.product_id})">
                                    <i class="bi bi-cart-plus me-2"></i>Ajouter
                                </button>
                                <button class="btn-remove-favorite" onclick="removeFavorite(${favorite.product_id})">
                                    <i class="bi bi-heart-fill"></i>
                                </button>
                            </div>
                        </div>
                    `).join('')}
                </div>
            `;
        }
        
        // Supprimer un favori
        async function removeFavorite(productId) {
            try {
                const response = await fetch('backend/favorites_api.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ product_id: productId })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showToast('success', 'Produit supprimé des favoris');
                    loadFavorites(); // Recharger la liste
                } else {
                    showToast('error', data.message || 'Erreur lors de la suppression');
                }
            } catch (error) {
                console.error('Erreur:', error);
                showToast('error', 'Erreur de connexion');
            }
        }
        
        // Ajouter au panier
        async function addToCart(productId) {
            try {
                const response = await fetch('backend/cart_api.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ 
                        product_id: productId, 
                        quantity: 1 
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showToast('success', 'Produit ajouté au panier !');
                } else {
                    showToast('error', data.message || 'Erreur lors de l\'ajout au panier');
                }
            } catch (error) {
                console.error('Erreur:', error);
                showToast('error', 'Erreur de connexion');
            }
        }
        
        // Vider tous les favoris
        async function clearAllFavorites() {
            if (!confirm('Êtes-vous sûr de vouloir vider votre liste de favoris ?')) {
                return;
            }
            
            try {
                // Récupérer tous les favoris
                const response = await fetch('backend/favorites_api.php');
                const data = await response.json();
                
                if (data.success && data.favorites.length > 0) {
                    // Supprimer chaque favori
                    for (const favorite of data.favorites) {
                        await fetch('backend/favorites_api.php', {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({ product_id: favorite.product_id })
                        });
                    }
                    
                    showToast('success', 'Liste de favoris vidée');
                    loadFavorites(); // Recharger la liste
                }
            } catch (error) {
                console.error('Erreur:', error);
                showToast('error', 'Erreur lors du vidage');
            }
        }
        
        // Afficher une erreur
        function showError(message) {
            const container = document.getElementById('favorites-container');
            container.innerHTML = `
                <div class="text-center text-danger">
                    <i class="bi bi-exclamation-triangle fs-1"></i>
                    <h3>Erreur</h3>
                    <p>${message}</p>
                    <button class="btn btn-primary" onclick="loadFavorites()">
                        <i class="bi bi-arrow-clockwise me-2"></i>Réessayer
                    </button>
                </div>
            `;
        }
        
        // Afficher un toast
        function showToast(type, message) {
            const toastId = type === 'success' ? 'successToast' : 'errorToast';
            const toastBodyId = type === 'success' ? 'successToastBody' : 'errorToastBody';
            
            // Créer le toast s'il n'existe pas
            if (!document.getElementById(toastId)) {
                const toastHtml = `
                    <div class="toast-container position-fixed top-0 end-0 p-3">
                        <div id="${toastId}" class="toast toast-modern ${type}" role="alert">
                            <div class="toast-body" id="${toastBodyId}">
                                ${message}
                            </div>
                        </div>
                    </div>
                `;
                document.body.insertAdjacentHTML('beforeend', toastHtml);
            }
            
            document.getElementById(toastBodyId).textContent = message;
            const toast = new bootstrap.Toast(document.getElementById(toastId));
            toast.show();
        }
    </script>
</body>
</html>
