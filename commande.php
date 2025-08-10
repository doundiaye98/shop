<?php 
// Page de commande 
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
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="cardNumber">Numéro de carte *</label>
                                            <input type="text" id="cardNumber" name="cardNumber" placeholder="1234 5678 9012 3456">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="cardCVC">CVC *</label>
                                            <input type="text" id="cardCVC" name="cardCVC" placeholder="123">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="cardExpiry">Date d'expiration *</label>
                                            <input type="text" id="cardExpiry" name="cardExpiry" placeholder="MM/AA">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="cardName">Nom sur la carte *</label>
                                            <input type="text" id="cardName" name="cardName">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Résumé de la commande -->
                <div class="col-lg-4">
                    <div class="order-card">
                        <h3><i class="bi bi-receipt me-2"></i>Résumé de la commande</h3>
                        
                        <div class="order-summary">
                            <div class="order-item">
                                <img src="https://via.placeholder.com/200x200?text=Produit" alt="Produit">
                                <div class="order-item-details">
                                    <div class="order-item-name">Body bébé coton bio</div>
                                    <div class="order-item-price">Quantité: 2</div>
                                </div>
                                <div class="order-item-price">39.98 €</div>
                            </div>
                            
                            <div class="order-item">
                                <img src="https://via.placeholder.com/200x200?text=Jouet" alt="Jouet">
                                <div class="order-item-details">
                                    <div class="order-item-name">Jouet d'éveil musical</div>
                                    <div class="order-item-price">Quantité: 1</div>
                                </div>
                                <div class="order-item-price">15.99 €</div>
                            </div>
                        </div>
                        
                        <div class="order-total">
                            Total: 55.97 €
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
    <script>
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
        function processOrder() {
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
            
            // Simulation du traitement de la commande
            setTimeout(() => {
                // Afficher la confirmation
                showOrderConfirmation();
            }, 2000);
        }

        function showOrderConfirmation() {
            const container = document.querySelector('.order-container .container');
            container.innerHTML = `
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="bi bi-check-circle text-success" style="font-size: 4rem;"></i>
                    </div>
                    <h2 class="text-success mb-3">Commande confirmée !</h2>
                    <p class="lead mb-4">Votre commande a été traitée avec succès. Vous allez recevoir un email de confirmation avec le numéro de suivi.</p>
                    <div class="alert alert-info">
                        <strong>Numéro de commande :</strong> #CMD-${Date.now().toString().slice(-6)}
                    </div>
                    <div class="mt-4">
                        <a href="index.php" class="btn btn-outline-primary me-2">
                            <i class="bi bi-house me-2"></i>Retour à l'accueil
                        </a>
                        <a href="panier.php" class="btn btn-primary">
                            <i class="bi bi-cart me-2"></i>Voir mes commandes
                        </a>
                    </div>
                </div>
            `;
        }

        // Formatage automatique des champs
        document.getElementById('cardNumber').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\s/g, '');
            value = value.replace(/(\d{4})/g, '$1 ').trim();
            e.target.value = value;
        });

        document.getElementById('cardExpiry').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length >= 2) {
                value = value.slice(0, 2) + '/' + value.slice(2, 4);
            }
            e.target.value = value;
        });
    </script>
</body>
</html> 