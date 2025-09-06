<?php 
// Page de commande 
session_start(); // Démarrer la session AVANT tout
require_once 'backend/db.php';
require_once 'backend/auth_check.php';
requireLogin(); // Redirige vers login.php si non connecté
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Finaliser ma commande - Ma Boutique</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .order-container {
            min-height: 100vh;
            background: #f8f9fa;
            padding: 2rem 0;
        }
        
        .order-header {
            background: linear-gradient(135deg, #1a1a1a 0%, #333 100%);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
        }
        
        .order-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin: 0;
        }
        
        .order-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }
        
        .order-card h3 {
            color: #1a1a1a;
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 0.5rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #1a1a1a;
            font-weight: 600;
            font-size: 0.95rem;
        }
        
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }
        
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #1a1a1a;
            background: white;
            box-shadow: 0 0 0 3px rgba(26, 26, 26, 0.1);
        }
        
        .order-summary {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1rem;
        }
        
        .order-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid #e9ecef;
        }
        
        .order-item:last-child {
            border-bottom: none;
        }
        
        .order-item img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 6px;
            margin-right: 1rem;
        }
        
        .order-item-details {
            flex-grow: 1;
        }
        
        .order-item-name {
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 0.25rem;
        }
        
        .order-item-price {
            color: #666;
            font-size: 0.9rem;
        }
        
        .order-total {
            background: #1a1a1a;
            color: white;
            padding: 1rem;
            border-radius: 8px;
            text-align: center;
            font-size: 1.2rem;
            font-weight: 600;
            margin-top: 1rem;
        }
        
        .payment-methods {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        .payment-method {
            flex: 1;
            padding: 1rem;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .payment-method:hover {
            border-color: #1a1a1a;
            background: #f8f9fa;
        }
        
        .payment-method.selected {
            border-color: #1a1a1a;
            background: #1a1a1a;
            color: white;
        }
        
        .btn-confirm-order {
            background: linear-gradient(135deg, #1a1a1a 0%, #333 100%);
            border: none;
            color: white;
            padding: 1rem 2rem;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s ease;
        }
        
        .btn-confirm-order:hover {
            background: linear-gradient(135deg, #333 0%, #1a1a1a 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            color: white;
        }
        
        .btn-confirm-order:disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
        }
        
        .progress-steps {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2rem;
            padding: 0 1rem;
        }
        
        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            flex: 1;
        }
        
        .step-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e9ecef;
            color: #666;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .step.active .step-number {
            background: #1a1a1a;
            color: white;
        }
        
        .step.completed .step-number {
            background: #28a745;
            color: white;
        }
        
        .step-label {
            font-size: 0.9rem;
            color: #666;
            text-align: center;
        }
        
        .step.active .step-label {
            color: #1a1a1a;
            font-weight: 600;
        }
        
        @media (max-width: 768px) {
            .order-header h1 {
                font-size: 2rem;
            }
            
            .order-card {
                padding: 1.5rem;
            }
            
            .payment-methods {
                flex-direction: column;
            }
        }
        
        .loading {
            text-align: center;
            padding: 2rem;
            color: #666;
        }
        
        .loading i {
            font-size: 2rem;
            animation: spin 1s linear infinite;
            margin-bottom: 1rem;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .error-message {
            text-align: center;
            padding: 2rem;
            color: #dc3545;
        }
        
        .error-message i {
            font-size: 2rem;
            margin-bottom: 1rem;
        }
        
        .empty-cart {
            text-align: center;
            padding: 2rem;
            color: #666;
        }
        
        .empty-cart i {
            font-size: 2rem;
            color: #ccc;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <?php include 'backend/navbar.php'; ?>

    <!-- Header de la commande -->
    <div class="order-header">
        <div class="container">
            <h1><i class="bi bi-cart-check me-3"></i>Finaliser ma commande</h1>
        </div>
    </div>

    <div class="order-container">
        <div class="container">
            <!-- Étapes de la commande -->
            <div class="progress-steps">
                <div class="step active">
                    <div class="step-number">1</div>
                    <div class="step-label">Livraison</div>
                </div>
                <div class="step">
                    <div class="step-number">2</div>
                    <div class="step-label">Paiement</div>
                </div>
                <div class="step">
                    <div class="step-number">3</div>
                    <div class="step-label">Confirmation</div>
                </div>
            </div>

            <div class="row">
                <!-- Formulaire de commande -->
                <div class="col-lg-8">
                    <form id="orderForm">
                        <!-- Informations de livraison -->
                        <div class="order-card">
                            <h3><i class="bi bi-truck me-2"></i>Informations de livraison</h3>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="firstName">Prénom *</label>
                                        <input type="text" id="firstName" name="firstName" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="lastName">Nom *</label>
                                        <input type="text" id="lastName" name="lastName" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="email">Email *</label>
                                <input type="email" id="email" name="email" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="phone">Téléphone *</label>
                                <input type="tel" id="phone" name="phone" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="address">Adresse *</label>
                                <input type="text" id="address" name="address" required>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="postalCode">Code postal *</label>
                                        <input type="text" id="postalCode" name="postalCode" required>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="city">Ville *</label>
                                        <input type="text" id="city" name="city" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="country">Pays *</label>
                                <select id="country" name="country" required>
                                    <option value="">Sélectionner un pays</option>
                                    <option value="FR">France</option>
                                    <option value="BE">Belgique</option>
                                    <option value="CH">Suisse</option>
                                    <option value="CA">Canada</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="notes">Notes de livraison</label>
                                <textarea id="notes" name="notes" rows="3" placeholder="Instructions spéciales pour la livraison..."></textarea>
                            </div>
                        </div>

                        <!-- Méthode de paiement -->
                        <div class="order-card">
                            <h3><i class="bi bi-credit-card me-2"></i>Méthode de paiement</h3>
                            
                            <div class="payment-methods">
                                <div class="payment-method" data-method="card">
                                    <i class="bi bi-credit-card fs-3"></i>
                                    <div>Carte bancaire</div>
                                </div>
                                <div class="payment-method" data-method="paypal">
                                    <i class="bi bi-paypal fs-3"></i>
                                    <div>PayPal</div>
                                </div>
                                <div class="payment-method" data-method="transfer">
                                    <i class="bi bi-bank fs-3"></i>
                                    <div>Virement</div>
                                </div>
                            </div>
                            
                            <div id="cardDetails" class="payment-details" style="display: none;">
                                <div class="form-group">
                                    <label for="card-element">Carte bancaire *</label>
                                    <div id="card-element" class="form-control" style="padding: 12px;"></div>
                                    <div id="card-errors" class="text-danger mt-2"></div>
                                </div>
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle me-2"></i>
                                    <strong>Test :</strong> Utilisez 4242 4242 4242 4242 pour un paiement réussi
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Résumé de la commande -->
                <div class="col-lg-4">
                    <div class="order-card">
                        <h3><i class="bi bi-receipt me-2"></i>Résumé de la commande</h3>
                        
                        <div class="order-summary" id="order-summary">
                            <div class="loading">
                                <i class="bi bi-arrow-clockwise"></i>
                                <p>Chargement du résumé...</p>
                            </div>
                        </div>
                        
                        <div class="order-total" id="order-total">
                            Total: 0.00 €
                        </div>
                        
                        <button type="button" class="btn btn-confirm-order" onclick="processOrder()">
                            <i class="bi bi-lock me-2"></i>
                            Confirmer ma commande
                        </button>
                        
                        <div class="text-center mt-3">
                            <small class="text-muted">
                                <i class="bi bi-shield-check me-1"></i>
                                Paiement sécurisé SSL
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        // Variables globales pour Stripe
        let stripe, elements, card;
        let clientSecret = null;
        let orderId = null;
        
        // Charger le résumé de la commande au chargement de la page
        document.addEventListener('DOMContentLoaded', function() {
            loadOrderSummary();
            initializeStripe();
        });
        
        // Initialiser Stripe
        function initializeStripe() {
            // Remplacer par votre vraie clé publique Stripe
            stripe = Stripe('pk_test_votre_cle_publique_de_test');
            elements = stripe.elements();
            
            // Créer l'élément de carte
            card = elements.create('card', {
                style: {
                    base: {
                        fontSize: '16px',
                        color: '#424770',
                        '::placeholder': {
                            color: '#aab7c4',
                        },
                    },
                    invalid: {
                        color: '#9e2146',
                    },
                },
            });
            
            // Monter l'élément de carte
            card.mount('#card-element');
            
            // Gérer les erreurs de validation
            card.addEventListener('change', ({error}) => {
                const displayError = document.getElementById('card-errors');
                if (error) {
                    displayError.textContent = error.message;
                } else {
                    displayError.textContent = '';
                }
            });
        }
        
        // Charger le résumé de la commande depuis le panier
        async function loadOrderSummary() {
            try {
                const response = await fetch('backend/cart_api.php', {
                    method: 'GET',
                    credentials: 'same-origin'
                });
                
                if (response.ok) {
                    const data = await response.json();
                    displayOrderSummary(data);
                } else {
                    showOrderError('Erreur lors du chargement du panier');
                }
            } catch (error) {
                console.error('Erreur:', error);
                showOrderError('Erreur lors du chargement du panier');
            }
        }
        
        // Afficher le résumé de la commande
        function displayOrderSummary(data) {
            const orderSummary = document.getElementById('order-summary');
            const orderTotal = document.getElementById('order-total');
            
            if (!data.cart_items || data.cart_items.length === 0) {
                orderSummary.innerHTML = `
                    <div class="empty-cart">
                        <i class="bi bi-cart-x"></i>
                        <p>Votre panier est vide</p>
                    </div>
                `;
                orderTotal.textContent = 'Total: 0.00 €';
                return;
            }
            
            // Afficher les articles
            orderSummary.innerHTML = data.cart_items.map(item => `
                <div class="order-item">
                    <img src="${item.image || 'https://via.placeholder.com/200x200?text=Produit'}" alt="${item.name}">
                    <div class="order-item-details">
                        <div class="order-item-name">${item.name}</div>
                        <div class="order-item-price">Quantité: ${item.quantity}</div>
                    </div>
                    <div class="order-item-price">${item.total_price.toFixed(2)} €</div>
                </div>
            `).join('');
            
            // Mettre à jour le total
            const total = data.total;
            orderTotal.textContent = `Total: ${total.toFixed(2)} €`;
        }
        
        // Afficher une erreur
        function showOrderError(message) {
            const orderSummary = document.getElementById('order-summary');
            orderSummary.innerHTML = `
                <div class="error-message">
                    <i class="bi bi-exclamation-triangle"></i>
                    <p>${message}</p>
                </div>
            `;
        }
        
        // Sélection de la méthode de paiement
        document.querySelectorAll('.payment-method').forEach(method => {
            method.addEventListener('click', function() {
                // Retirer la sélection précédente
                document.querySelectorAll('.payment-method').forEach(m => m.classList.remove('selected'));
                // Ajouter la sélection actuelle
                this.classList.add('selected');
                
                // Afficher les détails de la carte si nécessaire
                const methodType = this.dataset.method;
                const cardDetails = document.getElementById('cardDetails');
                
                if (methodType === 'card') {
                    cardDetails.style.display = 'block';
                } else {
                    cardDetails.style.display = 'none';
                }
            });
        });

        // Traitement de la commande
        async function processOrder() {
            const form = document.getElementById('orderForm');
            const confirmBtn = document.querySelector('.btn-confirm-order');
            
            // Validation du formulaire
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }
            
            // Vérifier qu'une méthode de paiement est sélectionnée
            const selectedMethod = document.querySelector('.payment-method.selected');
            if (!selectedMethod) {
                alert('Veuillez sélectionner une méthode de paiement.');
                return;
            }
            
            // Désactiver le bouton pendant le traitement
            confirmBtn.disabled = true;
            confirmBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Traitement en cours...';
            
            try {
                // Récupérer les données du formulaire
                const formData = new FormData(form);
                const orderData = {
                    firstName: formData.get('firstName'),
                    lastName: formData.get('lastName'),
                    email: formData.get('email'),
                    phone: formData.get('phone'),
                    address: formData.get('address'),
                    postalCode: formData.get('postalCode'),
                    city: formData.get('city'),
                    country: formData.get('country'),
                    notes: formData.get('notes'),
                    paymentMethod: selectedMethod.dataset.method
                };
                
                // Créer l'intention de paiement
                const response = await fetch('backend/payment_api.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(orderData)
                });
                
                const data = await response.json();
                
                if (!data.success) {
                    throw new Error(data.error);
                }
                
                // Sauvegarder les données pour la confirmation
                clientSecret = data.client_secret;
                orderId = data.order_id;
                
                // Confirmer le paiement avec Stripe
                const {error} = await stripe.confirmCardPayment(clientSecret, {
                    payment_method: {
                        card: card,
                        billing_details: {
                            name: orderData.firstName + ' ' + orderData.lastName,
                            email: orderData.email,
                        },
                    }
                });
                
                if (error) {
                    throw new Error(error.message);
                }
                
                // Paiement réussi
                showOrderConfirmation(orderId);
                
            } catch (error) {
                console.error('Erreur de paiement:', error);
                alert('Erreur lors du paiement: ' + error.message);
                
                // Réactiver le bouton
                confirmBtn.disabled = false;
                confirmBtn.innerHTML = '<i class="bi bi-lock me-2"></i>Confirmer ma commande';
            }
        }

        function showOrderConfirmation(orderId) {
            const container = document.querySelector('.order-container .container');
            container.innerHTML = `
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="bi bi-check-circle text-success" style="font-size: 4rem;"></i>
                    </div>
                    <h2 class="text-success mb-3">Paiement réussi !</h2>
                    <p class="lead mb-4">Votre commande a été traitée avec succès. Vous allez recevoir un email de confirmation avec le numéro de suivi.</p>
                    <div class="alert alert-info">
                        <strong>Numéro de commande :</strong> #${orderId}
                    </div>
                    <div class="alert alert-success">
                        <i class="bi bi-shield-check me-2"></i>
                        <strong>Paiement sécurisé :</strong> Votre transaction a été traitée par Stripe
                    </div>
                    <div class="mt-4">
                        <a href="index.php" class="btn btn-outline-primary me-2">
                            <i class="bi bi-house me-2"></i>Retour à l'accueil
                        </a>
                        <a href="order_confirmation.php?order_id=${orderId}" class="btn btn-primary">
                            <i class="bi bi-receipt me-2"></i>Voir la confirmation
                        </a>
                    </div>
                </div>
            `;
        }

        // Formatage automatique des champs (supprimé car géré par Stripe)
    </script>
</body>
</html> 