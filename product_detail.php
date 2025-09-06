<?php 
// Page de détail produit
session_start();
require_once 'backend/db.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détail Produit - Ma Boutique</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="cart-styles.css">
    <style>
        .product-detail-container {
            min-height: 100vh;
            background: #f8f9fa;
            padding: 2rem 0;
        }
        
        .product-detail-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }
        
        .product-image {
            width: 100%;
            max-width: 400px;
            height: auto;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .product-title {
            color: #1a1a1a;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }
        
        .product-category {
            color: #666;
            font-size: 1.1rem;
            margin-bottom: 1rem;
        }
        
        .product-description {
            color: #555;
            line-height: 1.6;
            margin-bottom: 2rem;
        }
        
        .price-section {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 2rem;
        }
        
        .current-price {
            font-size: 2rem;
            font-weight: 700;
            color: #28a745;
        }
        
        .old-price {
            text-decoration: line-through;
            color: #999;
            font-size: 1.2rem;
            margin-right: 1rem;
        }
        
        .promo-badge {
            background: #dc3545;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
        }
        
        .action-buttons {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .btn-add-cart {
            background: linear-gradient(135deg, #1a1a1a 0%, #333 100%);
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-add-cart:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(26, 26, 26, 0.3);
            color: white;
        }
        
        .btn-buy-now {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-buy-now:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(40, 167, 69, 0.3);
            color: white;
        }
        
        .product-info {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 2rem;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid #e9ecef;
        }
        
        .info-row:last-child {
            border-bottom: none;
        }
        
        .info-label {
            font-weight: 600;
            color: #1a1a1a;
        }
        
        .info-value {
            color: #666;
        }
        
        .back-button {
            background: #6c757d;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 2rem;
            transition: all 0.3s ease;
        }
        
        .back-button:hover {
            background: #5a6268;
            color: white;
            transform: translateY(-2px);
        }
        
        @media (max-width: 768px) {
            .product-detail-card {
                padding: 1.5rem;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .product-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <?php include 'backend/navbar.php'; ?>
    
    <div class="product-detail-container">
        <div class="container">
            <a href="javascript:history.back()" class="back-button">
                <i class="bi bi-arrow-left me-2"></i>Retour
            </a>
            
            <div class="product-detail-card">
                <div class="row">
                    <div class="col-lg-6">
                        <img id="productImage" src="https://via.placeholder.com/400x400?text=Produit" alt="Produit" class="product-image">
                    </div>
                    <div class="col-lg-6">
                        <h1 id="productTitle" class="product-title">Chargement...</h1>
                        <p id="productCategory" class="product-category">Catégorie</p>
                        <p id="productDescription" class="product-description">Description du produit...</p>
                        
                        <div class="price-section">
                            <div id="priceDisplay">
                                <span id="currentPrice" class="current-price">0.00 €</span>
                                <span id="oldPrice" class="old-price" style="display: none;">0.00 €</span>
                                <span id="promoBadge" class="promo-badge" style="display: none;">PROMO</span>
                            </div>
                        </div>
                        
                        <div class="action-buttons">
                            <div class="quantity-selector mb-3">
                                <label for="quantity" class="form-label">Quantité :</label>
                                <div class="input-group" style="max-width: 200px;">
                                    <button type="button" class="btn btn-outline-secondary" onclick="changeQuantity(-1)">-</button>
                                    <input type="number" class="form-control text-center" id="quantity" value="1" min="1" max="99" style="border-left: 0; border-right: 0;">
                                    <button type="button" class="btn btn-outline-secondary" onclick="changeQuantity(1)">+</button>
                                </div>
                            </div>
                            <button class="btn btn-add-cart" onclick="addToCart()">
                                <i class="bi bi-cart-plus me-2"></i>Ajouter au panier
                            </button>
                            <button class="btn btn-buy-now" onclick="buyNow()">
                                <i class="bi bi-lightning me-2"></i>Acheter maintenant
                            </button>
                        </div>
                        
                        <div class="product-info">
                            <div class="info-row">
                                <span class="info-label">Référence :</span>
                                <span id="productRef" class="info-value">-</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Stock :</span>
                                <span id="productStock" class="info-value">-</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Catégorie :</span>
                                <span id="productCategoryInfo" class="info-value">-</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Ajouté le :</span>
                                <span id="productDate" class="info-value">-</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="cart-manager.js"></script>
    <script>
        // Récupérer l'ID du produit depuis l'URL
        const urlParams = new URLSearchParams(window.location.search);
        const productId = urlParams.get('id');
        
        if (!productId) {
            alert('Aucun produit sélectionné');
            window.location.href = 'products.php';
        }
        
        // Charger les détails du produit
        fetch(`backend/products.php?id=${productId}`)
            .then(res => {
                if (!res.ok) {
                    throw new Error(`HTTP error! status: ${res.status}`);
                }
                return res.json();
            })
            .then(product => {
                console.log('Produit reçu:', product); // Debug
                
                if (!product || product.error) {
                    alert('Produit non trouvé');
                    window.location.href = 'products.php';
                    return;
                }
                
                // Mettre à jour l'interface avec vérification des valeurs
                document.getElementById('productTitle').textContent = product.name || 'Nom non disponible';
                document.getElementById('productCategory').textContent = product.category || 'Non catégorisé';
                document.getElementById('productDescription').textContent = product.description || 'Aucune description disponible';
                document.getElementById('productRef').textContent = product.id || 'N/A';
                document.getElementById('productStock').textContent = product.stock || '0';
                document.getElementById('productCategoryInfo').textContent = product.category || 'Non catégorisé';
                document.getElementById('productDate').textContent = product.created_at ? new Date(product.created_at).toLocaleDateString('fr-FR') : 'Non disponible';
                
                // Gérer l'image
                if (product.image && product.image.trim() !== '') {
                    document.getElementById('productImage').src = product.image;
                } else {
                    document.getElementById('productImage').src = 'https://via.placeholder.com/400x400?text=Image+non+disponible';
                }
                
                // Gérer les prix avec vérification
                const price = parseFloat(product.price);
                const promoPrice = parseFloat(product.promo_price);
                
                if (!isNaN(price)) {
                    if (!isNaN(promoPrice) && promoPrice < price) {
                        document.getElementById('currentPrice').textContent = promoPrice.toFixed(2) + ' €';
                        document.getElementById('oldPrice').textContent = price.toFixed(2) + ' €';
                        document.getElementById('oldPrice').style.display = 'inline';
                        document.getElementById('promoBadge').style.display = 'inline';
                    } else {
                        document.getElementById('currentPrice').textContent = price.toFixed(2) + ' €';
                        document.getElementById('oldPrice').style.display = 'none';
                        document.getElementById('promoBadge').style.display = 'none';
                    }
                } else {
                    document.getElementById('currentPrice').textContent = 'Prix non disponible';
                }
                
                // Mettre à jour le titre de la page
                document.title = `${product.name || 'Produit'} - Ma Boutique`;
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Erreur lors du chargement du produit: ' + error.message);
            });
        

        
        // Fonction pour changer la quantité
        function changeQuantity(delta) {
            const quantityInput = document.getElementById('quantity');
            let newQuantity = parseInt(quantityInput.value) + delta;
            
            // Limiter la quantité entre 1 et 99
            if (newQuantity < 1) newQuantity = 1;
            if (newQuantity > 99) newQuantity = 99;
            
            quantityInput.value = newQuantity;
        }
        
        // Fonction pour acheter maintenant
        function buyNow() {
            if (!productId) {
                alert('Erreur: ID du produit non défini');
                return;
            }
            
            const quantity = document.getElementById('quantity').value;
            
            // Rediriger vers la page d'achat direct
            window.location.href = `direct_purchase.php?product_id=${productId}&quantity=${quantity}`;
        }
        
        // Créer une instance du gestionnaire de panier
        let cartManager;
        
        // Initialiser le gestionnaire de panier
        document.addEventListener('DOMContentLoaded', function() {
            cartManager = new CartManager();
        });
        
        // Fonction pour ajouter au panier avec la quantité sélectionnée
        function addToCart() {
            if (!productId) {
                alert('Erreur: ID du produit non défini');
                return;
            }
            
            const quantity = parseInt(document.getElementById('quantity').value);
            
            // Afficher un indicateur de chargement
            const addButton = document.querySelector('.btn-add-cart');
            const originalText = addButton.innerHTML;
            addButton.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Ajout en cours...';
            addButton.disabled = true;
            
            // Appeler la fonction d'ajout au panier via le gestionnaire
            if (cartManager) {
                cartManager.addToCart(parseInt(productId), quantity).then(success => {
                    if (success) {
                        // Succès
                        addButton.innerHTML = '<i class="bi bi-check-circle me-2"></i>Ajouté au panier !';
                        addButton.style.background = 'linear-gradient(135deg, #28a745 0%, #20c997 100%)';
                        
                        // Remettre le bouton à l'état normal après 2 secondes
                        setTimeout(() => {
                            addButton.innerHTML = originalText;
                            addButton.style.background = 'linear-gradient(135deg, #1a1a1a 0%, #333 100%)';
                            addButton.disabled = false;
                        }, 2000);
                    } else {
                        // Erreur
                        addButton.innerHTML = originalText;
                        addButton.disabled = false;
                    }
                });
            } else {
                // Fallback si le gestionnaire de panier n'est pas chargé
                alert('Erreur: Gestionnaire de panier non disponible');
                addButton.innerHTML = originalText;
                addButton.disabled = false;
            }
        }
    </script>
</body>
</html>
