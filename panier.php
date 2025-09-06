<?php 
// Page Panier 
session_start(); // Démarrer la session AVANT tout
require_once 'backend/db.php';
require_once 'backend/auth_check.php';
requireLogin(); // Redirige vers login.php si non connecté
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon Panier - Ma Boutique</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .cart-container {
            min-height: 100vh;
            background: #f8f9fa;
            padding: 2rem 0;
        }
        
        .cart-header {
            background: linear-gradient(135deg, #1a1a1a 0%, #333 100%);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
        }
        
        .cart-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin: 0;
        }
        
        .cart-header p {
            font-size: 1.1rem;
            margin: 0.5rem 0 0 0;
            opacity: 0.9;
        }
        
        .cart-item {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease;
        }
        
        .cart-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        }
        
        .cart-item img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
        }
        
        .cart-item-title {
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 0.5rem;
        }
        
        .cart-item-category {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }
        
        .cart-item-price {
            font-weight: 700;
            color: #1a1a1a;
            font-size: 1.1rem;
        }
        
        .cart-item-promo {
            color: #dc3545;
            font-weight: 700;
        }
        
        .cart-item-old-price {
            text-decoration: line-through;
            color: #999;
            font-size: 0.9rem;
            margin-right: 0.5rem;
        }
        
        .quantity-controls {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .quantity-btn {
            width: 32px;
            height: 32px;
            border: 1px solid #ddd;
            background: white;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .quantity-btn:hover {
            background: #f8f9fa;
            border-color: #1a1a1a;
        }
        
        .quantity-input {
            width: 50px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 0.25rem;
        }
        
        .remove-btn {
            background: none;
            border: none;
            color: #dc3545;
            cursor: pointer;
            font-size: 1.2rem;
            transition: color 0.2s ease;
        }
        
        .remove-btn:hover {
            color: #c82333;
        }
        
        .cart-summary {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 2rem;
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #eee;
        }
        
        .summary-total {
            display: flex;
            justify-content: space-between;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 2px solid #1a1a1a;
            font-weight: 700;
            font-size: 1.2rem;
        }
        
        .checkout-btn {
            background: #28a745;
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 8px;
            font-weight: 600;
            width: 100%;
            margin-top: 1rem;
            transition: all 0.3s ease;
        }
        
        .checkout-btn:hover {
            background: #218838;
            transform: translateY(-2px);
        }
        
        .checkout-btn:disabled {
            background: #6c757d;
            cursor: not-allowed;
            transform: none;
        }
        
        .empty-cart {
            text-align: center;
            padding: 3rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .empty-cart i {
            font-size: 4rem;
            color: #ccc;
            margin-bottom: 1rem;
        }
        
        .empty-cart h3 {
            color: #666;
            margin-bottom: 1rem;
        }
        
        .empty-cart p {
            color: #999;
            margin-bottom: 2rem;
        }
        
        .continue-shopping {
            background: #1a1a1a;
            color: white;
            text-decoration: none;
            padding: 0.75rem 2rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .continue-shopping:hover {
            background: #333;
            color: white;
            transform: translateY(-2px);
        }
        
        .loading {
            text-align: center;
            padding: 2rem;
            color: #666;
        }
        
        .loading i {
            font-size: 2rem;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        @media (max-width: 768px) {
            .cart-header h1 {
                font-size: 2rem;
            }
            
            .cart-item {
                padding: 1rem;
            }
            
            .cart-summary {
                margin-top: 2rem;
                position: static;
            }
        }
    </style>
</head>
<body>
    <?php include 'backend/navbar.php'; ?>

    <!-- Header du panier -->
    <div class="cart-header">
        <div class="container">
            <h1><i class="bi bi-cart3 me-3"></i>Mon Panier</h1>
            <p>Gérez vos articles et passez votre commande</p>
        </div>
    </div>

    <div class="cart-container">
        <div class="container">
            <div class="row">
                <!-- Liste des articles -->
                <div class="col-lg-8">
                    <div id="cart-items">
                        <div class="loading">
                            <i class="bi bi-arrow-clockwise"></i>
                            <p>Chargement de votre panier...</p>
                        </div>
                    </div>
                </div>
                
                <!-- Résumé du panier -->
                <div class="col-lg-4">
                    <div class="cart-summary" id="cart-summary" style="display: none;">
                        <h4 class="mb-3">Résumé de la commande</h4>
                        
                        <div class="summary-row">
                            <span>Sous-total (0 article)</span>
                            <span>0.00 €</span>
                        </div>
                        
                        <div class="summary-row">
                            <span>Livraison</span>
                            <span>Gratuite</span>
                        </div>
                        
                        <div class="summary-row" id="discount-row" style="display: none;">
                            <span>Réduction</span>
                            <span>-5.00 €</span>
                        </div>
                        
                        <div class="summary-total">
                            <span>Total</span>
                            <span>0.00 €</span>
                        </div>
                        
                        <button class="checkout-btn" id="checkout-btn" disabled onclick="goToCheckout()">
                            <i class="bi bi-credit-card me-2"></i>Passer la commande
                        </button>
                        
                        <div class="text-center mt-3">
                            <small class="text-muted">
                                <i class="bi bi-shield-check me-1"></i>
                                Paiement sécurisé
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="cart-manager.js"></script>
    <script>
        let cartManager;
        
        // Initialiser le gestionnaire de panier
        document.addEventListener('DOMContentLoaded', function() {
            cartManager = new CartManager();
            loadCartDisplay();
        });
        
        // Charger l'affichage du panier
        async function loadCartDisplay() {
            try {
                const response = await fetch('backend/cart_api.php', {
                    method: 'GET',
                    credentials: 'same-origin'
                });
                
                if (response.ok) {
                    const data = await response.json();
                    displayCart(data);
                } else {
                    showEmptyCart('Erreur lors du chargement du panier');
                }
            } catch (error) {
                console.error('Erreur:', error);
                showEmptyCart('Erreur lors du chargement du panier');
            }
        }
        
        // Afficher le contenu du panier
        function displayCart(data) {
            const cartItems = document.getElementById('cart-items');
            const cartSummary = document.getElementById('cart-summary');
            
            if (!data.cart_items || data.cart_items.length === 0) {
                showEmptyCart();
                return;
            }
            
            // Afficher les articles
            cartItems.innerHTML = data.cart_items.map(item => `
                <div class="cart-item" data-item-id="${item.product_id}">
                    <div class="row align-items-center">
                        <div class="col-md-2">
                            <img src="${item.image || 'https://via.placeholder.com/200x200?text=Produit'}" alt="${item.name}" class="img-fluid">
                        </div>
                        <div class="col-md-4">
                            <div class="cart-item-title">${item.name}</div>
                            <div class="cart-item-category">${item.category}</div>
                            <div class="cart-item-price">
                                ${item.promo_price && item.promo_price < item.price 
                                    ? `<span class="cart-item-old-price">${item.price} €</span><span class="cart-item-promo">${item.promo_price} €</span>`
                                    : `<span>${item.price} €</span>`
                                }
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="quantity-controls">
                                <button class="quantity-btn" onclick="updateQuantity(${item.product_id}, -1)">-</button>
                                <input type="number" class="quantity-input" value="${item.quantity}" min="1" max="10" id="qty-${item.product_id}">
                                <button class="quantity-btn" onclick="updateQuantity(${item.product_id}, 1)">+</button>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="cart-item-price">${item.total_price.toFixed(2)} €</div>
                        </div>
                        <div class="col-md-1">
                            <button class="remove-btn" onclick="removeItem(${item.product_id})">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `).join('');
            
            // Afficher le résumé
            updateSummary(data);
            cartSummary.style.display = 'block';
        }
        
        // Mettre à jour le résumé
        function updateSummary(data) {
            const subtotal = data.total;
            const itemCount = data.item_count;
            const discountRow = document.getElementById('discount-row');
            const checkoutBtn = document.getElementById('checkout-btn');
            
            // Mettre à jour le sous-total - Utiliser des sélecteurs plus précis
            const subtotalElement = document.querySelector('.summary-row:first-child span:last-child');
            const subtotalTextElement = document.querySelector('.summary-row:first-child span:first-child');
            const totalElement = document.querySelector('.summary-total span:last-child');
            
            // Vérifier que les éléments existent avant de les modifier
            if (subtotalElement) {
                subtotalElement.textContent = subtotal.toFixed(2) + ' €';
            }
            
            if (subtotalTextElement) {
                subtotalTextElement.textContent = `Sous-total (${itemCount} article${itemCount > 1 ? 's' : ''})`;
            }
            
            // Appliquer la réduction si applicable
            let finalTotal = subtotal;
            if (subtotal > 50) {
                finalTotal = subtotal - 5;
                if (discountRow) {
                    discountRow.style.display = 'block';
                }
            } else {
                if (discountRow) {
                    discountRow.style.display = 'none';
                }
            }
            
            // Mettre à jour le total
            if (totalElement) {
                totalElement.textContent = finalTotal.toFixed(2) + ' €';
            }
            
            // Activer le bouton de commande
            if (checkoutBtn) {
                checkoutBtn.disabled = false;
            }
        }
        
        // Mettre à jour la quantité
        async function updateQuantity(productId, change) {
            const input = document.getElementById(`qty-${productId}`);
            let newValue = parseInt(input.value) + change;
            if (newValue < 1) newValue = 1;
            if (newValue > 10) newValue = 10;
            
            try {
                const success = await cartManager.updateQuantity(productId, newValue);
                if (success) {
                    input.value = newValue;
                    await loadCartDisplay(); // Recharger l'affichage
                }
            } catch (error) {
                console.error('Erreur lors de la mise à jour:', error);
            }
        }
        
        // Supprimer un article
        async function removeItem(productId) {
            if (confirm('Êtes-vous sûr de vouloir supprimer cet article ?')) {
                try {
                    const success = await cartManager.removeFromCart(productId);
                    if (success) {
                        await loadCartDisplay(); // Recharger l'affichage
                    }
                } catch (error) {
                    console.error('Erreur lors de la suppression:', error);
                }
            }
        }
        
        // Afficher le panier vide
        function showEmptyCart(message = null) {
            const cartItems = document.getElementById('cart-items');
            const cartSummary = document.getElementById('cart-summary');
            
            cartItems.innerHTML = `
                <div class="empty-cart">
                    <i class="bi bi-cart-x"></i>
                    <h3>Votre panier est vide</h3>
                    <p>${message || 'Ajoutez des produits pour commencer vos achats'}</p>
                    <a href="index.php" class="continue-shopping">
                        <i class="bi bi-arrow-left me-2"></i>
                        Continuer mes achats
                    </a>
                </div>
            `;
            
            cartSummary.style.display = 'none';
        }
        
        // Rediriger vers la page de commande
        function goToCheckout() {
            // Vérifier que le bouton est activé
            const checkoutBtn = document.getElementById('checkout-btn');
            if (checkoutBtn && !checkoutBtn.disabled) {
                // Rediriger vers la page de commande
                window.location.href = 'commande.php';
            } else {
                console.error('Le bouton de commande est désactivé');
            }
        }
    </script>
</body>
</html> 