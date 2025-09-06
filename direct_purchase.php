<?php
// Page d'achat direct
session_start();
require_once 'backend/db.php';
require_once 'backend/auth_check.php';

// Vérifier que l'utilisateur est connecté
if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

// Récupérer les paramètres de l'URL
$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;
$quantity = isset($_GET['quantity']) ? intval($_GET['quantity']) : 1;

if (!$product_id) {
    header('Location: products.php');
    exit;
}

// Récupérer les informations du produit
try {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$product) {
        header('Location: products.php');
        exit;
    }
    
    // Calculer le prix final
    $final_price = $product['promo_price'] && $product['promo_price'] < $product['price'] 
                  ? $product['promo_price'] 
                  : $product['price'];
    
    $total_amount = $final_price * $quantity;
    
} catch (Exception $e) {
    header('Location: products.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Achat Direct - <?php echo htmlspecialchars($product['name']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="cart-styles.css">
    <style>
        .purchase-container {
            min-height: 100vh;
            background: #f8f9fa;
            padding: 2rem 0;
        }
        
        .purchase-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }
        
        .product-summary {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .product-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
        }
        
        .price-highlight {
            font-size: 1.5rem;
            font-weight: 700;
            color: #28a745;
        }
        
        .old-price {
            text-decoration: line-through;
            color: #999;
            font-size: 1.1rem;
        }
        
        .promo-badge {
            background: #dc3545;
            color: white;
            padding: 0.3rem 0.8rem;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .form-section {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .form-section h4 {
            color: #1a1a1a;
            margin-bottom: 1rem;
            font-size: 1.2rem;
        }
        
        .btn-purchase {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            transition: all 0.3s ease;
            width: 100%;
        }
        
        .btn-purchase:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(40, 167, 69, 0.3);
            color: white;
        }
        
        .btn-purchase:disabled {
            background: #6c757d;
            transform: none;
            box-shadow: none;
        }
        
        .total-section {
            background: linear-gradient(135deg, #1a1a1a 0%, #333 100%);
            color: white;
            border-radius: 10px;
            padding: 1.5rem;
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .total-amount {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .back-link {
            color: #6c757d;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            margin-bottom: 2rem;
        }
        
        .back-link:hover {
            color: #495057;
        }
    </style>
</head>
<body>
    <?php include 'backend/navbar.php'; ?>
    
    <div class="purchase-container">
        <div class="container">
            <a href="product_detail.php?id=<?php echo $product_id; ?>" class="back-link">
                <i class="bi bi-arrow-left me-2"></i>Retour au produit
            </a>
            
            <div class="purchase-card">
                <h1 class="text-center mb-4">Finaliser votre achat</h1>
                
                <!-- Résumé du produit -->
                <div class="product-summary">
                    <div class="row align-items-center">
                        <div class="col-md-2">
                            <img src="<?php echo htmlspecialchars($product['image'] ?: 'https://via.placeholder.com/100x100?text=Produit'); ?>" 
                                 alt="<?php echo htmlspecialchars($product['name']); ?>" 
                                 class="product-image">
                        </div>
                        <div class="col-md-7">
                            <h4><?php echo htmlspecialchars($product['name']); ?></h4>
                            <p class="text-muted mb-1"><?php echo htmlspecialchars($product['category']); ?></p>
                            <p class="mb-0">Quantité : <strong><?php echo $quantity; ?></strong></p>
                        </div>
                        <div class="col-md-3 text-end">
                            <?php if ($product['promo_price'] && $product['promo_price'] < $product['price']): ?>
                                <div class="price-highlight"><?php echo number_format($product['promo_price'], 2); ?> €</div>
                                <div class="old-price"><?php echo number_format($product['price'], 2); ?> €</div>
                                <span class="promo-badge">PROMO</span>
                            <?php else: ?>
                                <div class="price-highlight"><?php echo number_format($product['price'], 2); ?> €</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Total -->
                <div class="total-section">
                    <div class="total-amount">Total : <?php echo number_format($total_amount, 2); ?> €</div>
                    <small>Livraison incluse</small>
                </div>
                
                <!-- Formulaire d'achat -->
                <form id="purchaseForm">
                    <input type="hidden" id="productId" value="<?php echo $product_id; ?>">
                    <input type="hidden" id="quantity" value="<?php echo $quantity; ?>">
                    
                    <!-- Informations de livraison -->
                    <div class="form-section">
                        <h4><i class="bi bi-truck me-2"></i>Adresse de livraison</h4>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="firstName" class="form-label">Prénom *</label>
                                <input type="text" class="form-control" id="firstName" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="lastName" class="form-label">Nom *</label>
                                <input type="text" class="form-control" id="lastName" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Adresse *</label>
                            <input type="text" class="form-control" id="address" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="city" class="form-label">Ville *</label>
                                <input type="text" class="form-control" id="city" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="postalCode" class="form-label">Code postal *</label>
                                <input type="text" class="form-control" id="postalCode" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Téléphone *</label>
                            <input type="tel" class="form-control" id="phone" required>
                        </div>
                    </div>
                    
                    <!-- Méthode de paiement -->
                    <div class="form-section">
                        <h4><i class="bi bi-credit-card me-2"></i>Méthode de paiement</h4>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="paymentMethod" id="card" value="card" checked>
                                <label class="form-check-label" for="card">
                                    <i class="bi bi-credit-card me-2"></i>Carte bancaire
                                </label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="paymentMethod" id="paypal" value="paypal">
                                <label class="form-check-label" for="paypal">
                                    <i class="bi bi-paypal me-2"></i>PayPal
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Bouton d'achat -->
                    <button type="submit" class="btn btn-purchase" id="purchaseBtn">
                        <i class="bi bi-lightning me-2"></i>Confirmer l'achat - <?php echo number_format($total_amount, 2); ?> €
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('purchaseForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const purchaseBtn = document.getElementById('purchaseBtn');
            const originalText = purchaseBtn.innerHTML;
            
            // Désactiver le bouton
            purchaseBtn.disabled = true;
            purchaseBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Traitement en cours...';
            
            try {
                // Récupérer les données du formulaire
                const formData = {
                    product_id: parseInt(document.getElementById('productId').value),
                    quantity: parseInt(document.getElementById('quantity').value),
                    shipping_address: `${document.getElementById('firstName').value} ${document.getElementById('lastName').value}, ${document.getElementById('address').value}, ${document.getElementById('postalCode').value} ${document.getElementById('city').value}, Tél: ${document.getElementById('phone').value}`,
                    payment_method: document.querySelector('input[name="paymentMethod"]:checked').value
                };
                
                // Envoyer la requête d'achat
                const response = await fetch('backend/direct_purchase_api.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify(formData)
                });
                
                const result = await response.json();
                
                if (result.success) {
                    // Succès
                    purchaseBtn.innerHTML = '<i class="bi bi-check-circle me-2"></i>Achat confirmé !';
                    purchaseBtn.style.background = 'linear-gradient(135deg, #28a745 0%, #20c997 100%)';
                    
                    // Afficher un message de succès
                    alert(`Félicitations ! Votre commande #${result.order_id} a été créée avec succès.\n\nProduit : ${result.product_name}\nQuantité : ${result.quantity}\nTotal : ${result.total_amount.toFixed(2)} €\n\nVous recevrez un email de confirmation.`);
                    
                    // Rediriger vers une page de confirmation
                    setTimeout(() => {
                        window.location.href = `order_confirmation.php?order_id=${result.order_id}`;
                    }, 2000);
                    
                } else {
                    // Erreur
                    alert('Erreur lors de la création de la commande : ' + result.error);
                    purchaseBtn.innerHTML = originalText;
                    purchaseBtn.disabled = false;
                }
                
            } catch (error) {
                console.error('Erreur:', error);
                alert('Erreur lors du traitement de votre achat');
                purchaseBtn.innerHTML = originalText;
                purchaseBtn.disabled = false;
            }
        });
    </script>
</body>
</html>
