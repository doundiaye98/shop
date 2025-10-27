<?php 
// Nouveautés 
session_start();
require_once 'backend/db.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveautés - Mada Kids</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="product-buttons.css">
    <link rel="stylesheet" href="nouveautes.css">
    <style>
        :root {
            --primary-color: #ff6b6b;
            --secondary-color: #4ecdc4;
            --accent-color: #45b7d1;
            --dark-color: #2c3e50;
            --light-color: #f8f9fa;
            --text-color: #333;
            --gradient-primary: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
            --gradient-secondary: linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%);
            --gradient-dark: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--light-color);
        }

        .hero-section {
            background: linear-gradient(135deg, #1a1a1a 0%, #2c2c2c 100%);
            color: white;
            padding: 4rem 0;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/><circle cx="10" cy="60" r="0.5" fill="white" opacity="0.1"/><circle cx="90" cy="40" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .hero-subtitle {
            font-size: 1.3rem;
            opacity: 0.95;
            max-width: 600px;
            margin: 0 auto;
            font-weight: 300;
        }

        .floating-elements {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            pointer-events: none;
            z-index: 1;
        }

        .floating-icon {
            position: absolute;
            font-size: 2rem;
            opacity: 0.1;
            animation: float 6s ease-in-out infinite;
        }

        .floating-icon:nth-child(1) { top: 20%; left: 10%; animation-delay: 0s; }
        .floating-icon:nth-child(2) { top: 60%; right: 15%; animation-delay: 2s; }
        .floating-icon:nth-child(3) { bottom: 30%; left: 20%; animation-delay: 4s; }
        .floating-icon:nth-child(4) { top: 40%; right: 30%; animation-delay: 1s; }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
        }

        .filters-section {
            background: white;
            padding: 2rem 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .filter-group {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
            justify-content: center;
        }

        .filter-btn {
            background: white;
            border: 2px solid #e9ecef;
            color: var(--text-color);
            padding: 0.5rem 1.5rem;
            border-radius: 25px;
            transition: all 0.3s ease;
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .filter-btn:hover,
        .filter-btn.active {
            background: var(--gradient-primary);
            color: white;
            border-color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 107, 107, 0.3);
        }

        .sort-dropdown {
            background: white;
            border: 2px solid #e9ecef;
            border-radius: 25px;
            padding: 0.5rem 1rem;
            color: var(--text-color);
            font-weight: 500;
        }

        .products-section {
            padding: 3rem 0;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .product-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.4s ease;
            position: relative;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .product-image {
            position: relative;
            height: 250px;
            overflow: hidden;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .product-card:hover .product-image img {
            transform: scale(1.1);
        }

        .product-badges {
            position: absolute;
            top: 1rem;
            left: 1rem;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .badge-new {
            background: var(--gradient-primary);
            color: white;
            padding: 0.3rem 0.8rem;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 10px rgba(255, 107, 107, 0.3);
        }

        .badge-sale {
            background: var(--gradient-secondary);
            color: white;
            padding: 0.3rem 0.8rem;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 10px rgba(78, 205, 196, 0.3);
        }

        .btn-favorite {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: rgba(255, 255, 255, 0.9);
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            cursor: pointer;
            backdrop-filter: blur(10px);
        }

        .btn-favorite:hover {
            background: var(--primary-color);
            color: white;
            transform: scale(1.1);
        }

        .btn-favorite.active {
            background: var(--primary-color);
            color: white;
        }

        .product-info {
            padding: 1.5rem;
        }

        .product-category {
            color: var(--secondary-color);
            font-size: 0.9rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
        }

        .product-name {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 1rem;
            line-height: 1.3;
        }

        .product-price {
            margin-bottom: 1.5rem;
        }

        .price-old {
            text-decoration: line-through;
            color: #999;
            font-size: 0.9rem;
            margin-right: 0.5rem;
        }

        .price-new {
            color: var(--primary-color);
            font-size: 1.3rem;
            font-weight: 700;
        }

        .price {
            color: var(--dark-color);
            font-size: 1.3rem;
            font-weight: 700;
        }

        .product-actions {
            display: flex;
            gap: 0.5rem;
        }

        .btn-view {
            flex: 1;
            background: transparent;
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            padding: 0.7rem 1rem;
            border-radius: 25px;
            font-weight: 600;
            text-decoration: none;
            text-align: center;
            transition: all 0.3s ease;
        }

        .btn-view:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-2px);
        }

        .btn-add-cart {
            flex: 1;
            background: var(--gradient-primary);
            border: none;
            color: white;
            padding: 0.7rem 1rem;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-add-cart:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 107, 107, 0.3);
        }

        .loading-spinner {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 200px;
        }

        .spinner {
            width: 50px;
            height: 50px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: #666;
        }

        .empty-state i {
            font-size: 4rem;
            color: #ddd;
            margin-bottom: 1rem;
        }

        .empty-state h3 {
            color: var(--dark-color);
            margin-bottom: 1rem;
        }

        .stats-bar {
            background: var(--gradient-dark);
            color: white;
            padding: 1rem 0;
            text-align: center;
        }

        .stats-item {
            display: inline-block;
            margin: 0 2rem;
            font-size: 0.9rem;
        }

        .stats-number {
            font-weight: 700;
            font-size: 1.2rem;
            color: var(--secondary-color);
        }

        .fade-in {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s ease;
        }

        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .products-grid {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
                gap: 1.5rem;
            }
            
            .filter-group {
                flex-direction: column;
                align-items: stretch;
            }
            
            .filter-btn {
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <header>
        <?php include 'backend/navbar.php'; ?>
    </header>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="floating-elements">
            <i class="bi bi-star floating-icon"></i>
            <i class="bi bi-gift floating-icon"></i>
            <i class="bi bi-heart floating-icon"></i>
            <i class="bi bi-emoji-smile floating-icon"></i>
        </div>
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">
                    <i class="bi bi-sparkles me-3"></i>Nouveautés
                </h1>
                <p class="hero-subtitle">
                    Découvrez les dernières tendances et les nouveaux arrivages de Mada Kids
                </p>
            </div>
        </div>
    </section>

    <!-- Stats Bar -->
    <div class="stats-bar">
        <div class="container">
            <div class="stats-item">
                <span class="stats-number" id="total-products">0</span> Produits
            </div>
            <div class="stats-item">
                <span class="stats-number" id="new-this-week">0</span> Nouveautés cette semaine
            </div>
            <div class="stats-item">
                <span class="stats-number" id="categories-count">0</span> Catégories
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <section class="filters-section">
        <div class="container">
            <div class="filter-group">
                <a href="#" class="filter-btn active" data-filter="all">
                    <i class="bi bi-grid-3x3-gap"></i> Tous
                </a>
                <a href="#" class="filter-btn" data-filter="bebe">
                    <i class="bi bi-heart"></i> Bébé
                </a>
                <a href="#" class="filter-btn" data-filter="fille">
                    <i class="bi bi-flower1"></i> Fille
                </a>
                <a href="#" class="filter-btn" data-filter="garcon">
                    <i class="bi bi-lightning"></i> Garçon
                </a>
                <a href="#" class="filter-btn" data-filter="promo">
                    <i class="bi bi-percent"></i> Promotions
                </a>
                <select class="sort-dropdown" id="sort-select">
                    <option value="newest">Plus récents</option>
                    <option value="price-low">Prix croissant</option>
                    <option value="price-high">Prix décroissant</option>
                    <option value="name">Nom A-Z</option>
                </select>
            </div>
        </div>
    </section>

    <!-- Products Section -->
    <main class="products-section">
        <div class="container">
            <div id="products-container" class="products-grid">
                <div class="loading-spinner">
                    <div class="spinner"></div>
                </div>
            </div>
        </div>
    </main>

    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let allProducts = [];
        let filteredProducts = [];
        let currentFilter = 'all';
        let currentSort = 'newest';

        // Charger les produits au démarrage
        document.addEventListener('DOMContentLoaded', function() {
            loadProducts();
            setupEventListeners();
        });

        async function loadProducts() {
            try {
                const response = await fetch('backend/products.php?new=true');
                const products = await response.json();
                
                allProducts = products;
                filteredProducts = [...products];
                
                updateStats();
                renderProducts();
            } catch (error) {
                console.error('Erreur lors du chargement des produits:', error);
                showEmptyState('Erreur lors du chargement des produits');
            }
        }

        function updateStats() {
            document.getElementById('total-products').textContent = allProducts.length;
            
            // Simuler les nouveautés de la semaine (en réalité, cela devrait venir de la base de données)
            const newThisWeek = Math.floor(allProducts.length * 0.3);
            document.getElementById('new-this-week').textContent = newThisWeek;
            
            // Compter les catégories uniques
            const categories = [...new Set(allProducts.map(p => p.category).filter(Boolean))];
            document.getElementById('categories-count').textContent = categories.length;
        }

        function renderProducts() {
            const container = document.getElementById('products-container');
            
            if (filteredProducts.length === 0) {
                showEmptyState('Aucun produit trouvé pour cette catégorie');
                return;
            }

            container.innerHTML = filteredProducts.map(product => `
                <div class="product-card fade-in">
                    <div class="product-image">
                        <img src="${product.image || 'https://via.placeholder.com/300x250?text=Produit'}" 
                             alt="${product.name}" 
                             loading="lazy">
                        <div class="product-badges">
                            <span class="badge-new">NOUVEAU</span>
                            ${product.promo_price && parseFloat(product.promo_price) < parseFloat(product.price) ? 
                                '<span class="badge-sale">PROMO</span>' : ''
                            }
                        </div>
                        <button class="btn-favorite" onclick="toggleFavorite(${product.id})" id="fav-${product.id}">
                            <i class="bi bi-heart"></i>
                        </button>
                    </div>
                    <div class="product-info">
                        <div class="product-category">${product.category || 'Général'}</div>
                        <h3 class="product-name">${product.name}</h3>
                        <div class="product-price">
                            ${product.promo_price && parseFloat(product.promo_price) < parseFloat(product.price) ? 
                                `<span class="price-old">${product.price} €</span> <span class="price-new">${product.promo_price} €</span>` : 
                                `<span class="price">${product.price} €</span>`
                            }
                        </div>
                        <div class="product-actions">
                            <a href="product_detail.php?id=${product.id}" class="btn-view">
                                <i class="bi bi-eye me-1"></i>Voir
                            </a>
                            <button onclick="addToCart(${product.id}, 1)" class="btn-add-cart">
                                <i class="bi bi-cart-plus me-1"></i>Ajouter
                            </button>
                        </div>
                    </div>
                </div>
            `).join('');

            // Animation d'apparition
            setTimeout(() => {
                document.querySelectorAll('.fade-in').forEach((el, index) => {
                    setTimeout(() => {
                        el.classList.add('visible');
                    }, index * 100);
                });
            }, 100);
        }

        function showEmptyState(message) {
            const container = document.getElementById('products-container');
            container.innerHTML = `
                <div class="empty-state">
                    <i class="bi bi-box"></i>
                    <h3>${message}</h3>
                    <p>Revenez bientôt pour découvrir nos nouvelles collections !</p>
                </div>
            `;
        }

        function setupEventListeners() {
            // Filtres
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Mettre à jour l'état actif
                    document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    
                    // Appliquer le filtre
                    currentFilter = this.dataset.filter;
                    applyFilters();
                });
            });

            // Tri
            document.getElementById('sort-select').addEventListener('change', function() {
                currentSort = this.value;
                applyFilters();
            });
        }

        function applyFilters() {
            // Filtrer par catégorie
            if (currentFilter === 'all') {
                filteredProducts = [...allProducts];
            } else if (currentFilter === 'promo') {
                filteredProducts = allProducts.filter(p => 
                    p.promo_price && parseFloat(p.promo_price) < parseFloat(p.price)
                );
            } else {
                filteredProducts = allProducts.filter(p => 
                    p.category && p.category.toLowerCase().includes(currentFilter.toLowerCase())
                );
            }

            // Trier
            switch (currentSort) {
                case 'newest':
                    filteredProducts.sort((a, b) => new Date(b.created_at || 0) - new Date(a.created_at || 0));
                    break;
                case 'price-low':
                    filteredProducts.sort((a, b) => {
                        const priceA = parseFloat(a.promo_price || a.price);
                        const priceB = parseFloat(b.promo_price || b.price);
                        return priceA - priceB;
                    });
                    break;
                case 'price-high':
                    filteredProducts.sort((a, b) => {
                        const priceA = parseFloat(a.promo_price || a.price);
                        const priceB = parseFloat(b.promo_price || b.price);
                        return priceB - priceA;
                    });
                    break;
                case 'name':
                    filteredProducts.sort((a, b) => a.name.localeCompare(b.name));
                    break;
            }

            renderProducts();
        }

        // Fonction pour basculer les favoris
        async function toggleFavorite(productId) {
            const button = document.getElementById(`fav-${productId}`);
            const isActive = button.classList.contains('active');
            
            try {
                const response = await fetch('backend/favorites_api.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        action: isActive ? 'remove' : 'add',
                        product_id: productId
                    })
                });
                
                if (response.ok) {
                    button.classList.toggle('active');
                    
                    // Animation de feedback
                    button.style.transform = 'scale(1.2)';
                    setTimeout(() => {
                        button.style.transform = 'scale(1)';
                    }, 200);
                }
            } catch (error) {
                console.error('Erreur:', error);
            }
        }
        
        // Fonction pour ajouter au panier
        async function addToCart(productId, quantity = 1) {
            const button = event.target;
            const originalText = button.innerHTML;
            
            try {
                // Animation de chargement
                button.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Ajout...';
                button.disabled = true;
                
                const response = await fetch('backend/cart_api.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        action: 'add',
                        product_id: productId,
                        quantity: quantity
                    })
                });
                
                if (response.ok) {
                    // Animation de succès
                    button.innerHTML = '<i class="bi bi-check me-1"></i>Ajouté !';
                    button.style.background = 'var(--gradient-secondary)';
                    
                    setTimeout(() => {
                        button.innerHTML = originalText;
                        button.style.background = '';
                        button.disabled = false;
                    }, 2000);
                } else {
                    throw new Error('Erreur lors de l\'ajout');
                }
            } catch (error) {
                console.error('Erreur:', error);
                button.innerHTML = '<i class="bi bi-x me-1"></i>Erreur';
                button.style.background = '#dc3545';
                
                setTimeout(() => {
                    button.innerHTML = originalText;
                    button.style.background = '';
                    button.disabled = false;
                }, 2000);
            }
        }

        // Animation d'apparition au scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, observerOptions);

        // Observer tous les éléments avec la classe fade-in
        document.querySelectorAll('.fade-in').forEach(el => {
            observer.observe(el);
        });
    </script>
</body>
</html>