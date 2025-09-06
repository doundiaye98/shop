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
    <title>Nouveautés - Ma Boutique</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="product-buttons.css">
</head>
<body>
    <header>
        <?php include 'backend/navbar.php'; ?>
    </header>
    <main>
        <section class="products-list">
            <h1>Nouveautés</h1>
            <div id="products-container" class="products-grid"></div>
        </section>
    </main>
    <footer>
        <p>&copy; 2024 MaBoutique. Tous droits réservés.</p>
    </footer>
    <script src="script.js"></script>
    <script>
    fetch('backend/products.php')
        .then(res => res.json())
        .then(products => {
            // Trier par date de création décroissante
            products.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
            const container = document.getElementById('products-container');
            if (products.length === 0) {
                container.innerHTML = '<p>Aucun produit disponible.</p>';
                return;
            }
            container.innerHTML = products.slice(0, 12).map(prod => `
                <div class="product-card">
                    <img src="${prod.image || 'https://via.placeholder.com/200x200?text=Produit'}" alt="${prod.name}">
                    <h2>${prod.name}</h2>
                    <p class="category">${prod.category || ''}</p>
                    <p class="price">${prod.price} €</p>
                                                <a href="product_detail.php?id=${prod.id}" class="btn-view-product">👁️ Voir</a>
                </div>
            `).join('');
        });
    </script>
</body>
</html> 