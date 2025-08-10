<?php // Promos ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Promos - Ma Boutique</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <?php include 'backend/navbar.php'; ?>
    </header>
    <main>
        <section class="products-list">
            <h1>Promotions</h1>
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
            const container = document.getElementById('products-container');
            const promos = products.filter(prod => prod.promo_price && parseFloat(prod.promo_price) < parseFloat(prod.price));
            if (promos.length === 0) {
                container.innerHTML = '<p>Aucune promotion en cours.</p>';
                return;
            }
            container.innerHTML = promos.map(prod => `
                <div class="product-card">
                    <img src="${prod.image || 'https://via.placeholder.com/200x200?text=Promo'}" alt="${prod.name}">
                    <h2>${prod.name}</h2>
                    <p class="category">${prod.category || ''}</p>
                    <p class="old-price">${prod.price} €</p>
                    <p class="promo-price">${prod.promo_price} €</p>
                    <button class="cta">Voir</button>
                </div>
            `).join('');
        });
    </script>
</body>
</html> 