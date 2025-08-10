<?php 
// Page Panier 
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
            background: #1a1a1a;
            color: white;
            border: none;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .quantity-btn:hover {
            background: #333;
            transform: scale(1.1);
        }
        
        .quantity-input {
            width: 60px;
            text-align: center;
            border: 2px solid #e9ecef;
            border-radius: 6px;
            padding: 0.5rem;
            font-weight: 600;
        }
        
        .remove-btn {
            background: #dc3545;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-size: 0.9rem;
            transition: all 0.2s ease;
        }
        
        .remove-btn:hover {
            background: #c82333;
            transform: scale(1.05);
        }
        
        .cart-summary {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 2rem;
        }
        
        .summary-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #e9ecef;
        }
        
        .summary-total {
            font-size: 1.3rem;
            font-weight: 700;
            color: #1a1a1a;
            border-top: 2px solid #1a1a1a;
            padding-top: 1rem;
            margin-top: 1rem;
        }
        
        .checkout-btn {
            background: linear-gradient(135deg, #1a1a1a 0%, #333 100%);
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            width: 100%;
            margin-top: 1.5rem;
            transition: all 0.3s ease;
        }
        
        .checkout-btn:hover {
            background: linear-gradient(135deg, #333 0%, #1a1a1a 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            color: white;
        }
        
        .empty-cart {
            text-align: center;
            padding: 4rem 2rem;
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
                        <!-- Exemple d'article dans le panier -->
                        <div class="cart-item" data-item-id="1">
                            <div class="row align-items-center">
                                <div class="col-md-2">
                                    <img src="https://via.placeholder.com/200x200?text=Produit" alt="Produit" class="img-fluid">
                                </div>
                                <div class="col-md-4">
                                    <div class="cart-item-title">Body bébé coton bio</div>
                                    <div class="cart-item-category">Bébé - Body</div>
                                    <div class="cart-item-price">
                                        <span class="cart-item-old-price">24.99 €</span>
                                        <span class="cart-item-promo">19.99 €</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="quantity-controls">
                                        <button class="quantity-btn" onclick="updateQuantity(1, -1)">-</button>
                                        <input type="number" class="quantity-input" value="2" min="1" max="10" id="qty-1">
                                        <button class="quantity-btn" onclick="updateQuantity(1, 1)">+</button>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="cart-item-price">39.98 €</div>
                                </div>
                                <div class="col-md-1">
                                    <button class="remove-btn" onclick="removeItem(1)">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Deuxième article -->
                        <div class="cart-item" data-item-id="2">
                            <div class="row align-items-center">
                                <div class="col-md-2">
                                    <img src="https://via.placeholder.com/200x200?text=Jouet" alt="Jouet" class="img-fluid">
                                </div>
                                <div class="col-md-4">
                                    <div class="cart-item-title">Jouet d'éveil musical</div>
                                    <div class="cart-item-category">Jouets - Éveil</div>
                                    <div class="cart-item-price">15.99 €</div>
                                </div>
                                <div class="col-md-3">
                                    <div class="quantity-controls">
                                        <button class="quantity-btn" onclick="updateQuantity(2, -1)">-</button>
                                        <input type="number" class="quantity-input" value="1" min="1" max="10" id="qty-2">
                                        <button class="quantity-btn" onclick="updateQuantity(2, 1)">+</button>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="cart-item-price">15.99 €</div>
                                </div>
                                <div class="col-md-1">
                                    <button class="remove-btn" onclick="removeItem(2)">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Résumé de la commande -->
                <div class="col-lg-4">
                    <div class="cart-summary">
                        <div class="summary-title">Résumé de la commande</div>
                        
                        <div class="summary-row">
                            <span>Sous-total (3 articles)</span>
                            <span>55.97 €</span>
                        </div>
                        
                        <div class="summary-row">
                            <span>Livraison</span>
                            <span>Gratuite</span>
                        </div>
                        
                        <div class="summary-row">
                            <span>Réduction</span>
                            <span class="text-danger">-5.00 €</span>
                        </div>
                        
                        <div class="summary-row summary-total">
                            <span>Total</span>
                            <span>50.97 €</span>
                        </div>
                        
                        <button class="checkout-btn" onclick="window.location.href='commande.php'">
                            <i class="bi bi-credit-card me-2"></i>
                            Passer la commande
                        </button>
                        
                        <div class="text-center mt-3">
                            <a href="index.php" class="continue-shopping">
                                <i class="bi bi-arrow-left me-2"></i>
                                Continuer mes achats
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Fonctions pour gérer le panier
        function updateQuantity(itemId, change) {
            const input = document.getElementById(`qty-${itemId}`);
            let newValue = parseInt(input.value) + change;
            if (newValue < 1) newValue = 1;
            if (newValue > 10) newValue = 10;
            input.value = newValue;
            
            // Mettre à jour le prix de l'article
            updateItemPrice(itemId);
            updateCart();
        }

        function updateItemPrice(itemId) {
            const item = document.querySelector(`[data-item-id="${itemId}"]`);
            const quantity = parseInt(document.getElementById(`qty-${itemId}`).value);
            const priceElement = item.querySelector('.cart-item-price');
            const totalElement = item.querySelector('.col-md-2 .cart-item-price');
            
            // Récupérer le prix unitaire (enlever le prix total actuel)
            let unitPrice = 0;
            if (itemId == 1) {
                unitPrice = 19.99; // Prix promo du body
            } else if (itemId == 2) {
                unitPrice = 15.99; // Prix du jouet
            }
            
            // Calculer le nouveau prix total pour cet article
            const newTotal = (unitPrice * quantity).toFixed(2);
            totalElement.textContent = newTotal + ' €';
        }

        function removeItem(itemId) {
            if (confirm('Êtes-vous sûr de vouloir supprimer cet article ?')) {
                const item = document.querySelector(`[data-item-id="${itemId}"]`);
                if (item) {
                    item.remove();
                    updateCart();
                }
            }
        }

        function updateCart() {
            let totalItems = 0;
            let subtotal = 0;
            
            // Calculer le nombre total d'articles et le sous-total
            document.querySelectorAll('.cart-item').forEach(item => {
                const quantity = parseInt(item.querySelector('.quantity-input').value);
                const priceText = item.querySelector('.col-md-2 .cart-item-price').textContent;
                const price = parseFloat(priceText.replace(' €', ''));
                
                totalItems += quantity;
                subtotal += price;
            });
            
            // Mettre à jour l'affichage du résumé
            const summarySubtotal = document.querySelector('.summary-row:first-child span:last-child');
            const summaryTotal = document.querySelector('.summary-total span:last-child');
            
            if (summarySubtotal) {
                summarySubtotal.textContent = subtotal.toFixed(2) + ' €';
            }
            
            if (summaryTotal) {
                // Appliquer la réduction de 5€ si le total est > 50€
                let finalTotal = subtotal;
                if (subtotal > 50) {
                    finalTotal = subtotal - 5;
                }
                summaryTotal.textContent = finalTotal.toFixed(2) + ' €';
            }
            
            // Mettre à jour le nombre d'articles dans le résumé
            const summaryItems = document.querySelector('.summary-row:first-child span:first-child');
            if (summaryItems) {
                summaryItems.textContent = `Sous-total (${totalItems} article${totalItems > 1 ? 's' : ''})`;
            }
            
            // Vérifier si le panier est vide
            const cartItems = document.querySelectorAll('.cart-item');
            if (cartItems.length === 0) {
                showEmptyCart();
            }
        }

        function showEmptyCart() {
            const cartContainer = document.querySelector('.col-lg-8');
            cartContainer.innerHTML = `
                <div class="empty-cart">
                    <i class="bi bi-cart-x"></i>
                    <h3>Votre panier est vide</h3>
                    <p>Ajoutez des produits pour commencer vos achats</p>
                    <a href="index.php" class="continue-shopping">
                        <i class="bi bi-arrow-left me-2"></i>
                        Continuer mes achats
                    </a>
                </div>
            `;
            
            // Masquer le résumé si le panier est vide
            const summary = document.querySelector('.cart-summary');
            if (summary) {
                summary.style.display = 'none';
            }
        }

        // Initialiser le panier au chargement de la page
        document.addEventListener('DOMContentLoaded', function() {
            updateCart();
        });
    </script>
</body>
</html> 