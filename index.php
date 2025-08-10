<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ma Boutique - Accueil</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons (optionnel) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Ton CSS personnalisé -->
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'backend/navbar.php'; ?>

    <!-- Message de déconnexion -->
    <?php if (isset($_GET['logout']) && $_GET['logout'] === 'success'): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="margin: 0; border-radius: 0; text-align: center;">
            <strong>✅ Déconnexion réussie !</strong> Vous avez été déconnecté de votre compte.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Hero Banner -->
    <section class="hero-banner d-flex align-items-center justify-content-center text-center" style="background: linear-gradient(120deg, #1a1a1a 60%, #f7f7f7 100%); color: #fff; min-height: 320px;">
        <div class="container py-5">
            <h1 class="display-4">Des nouveautés et promos pour toute la famille !</h1>
            <p class="lead">Découvrez nos collections Bébé, Fille, Garçon, Chaussures, Jouets et plus encore. Livraison rapide et offres exclusives toute l'année.</p>
            <a href="products.php" class="btn btn-lg mt-3" style="background: var(--secondary-color); border-color: var(--secondary-color); color: white;">Voir les produits</a>
        </div>
    </section>

    <!-- Catégories -->
    <section class="container py-4">
        <div class="row g-4 justify-content-center">
            <div class="col-6 col-md-4 col-lg-2">
                <a href="bebe.php" class="text-decoration-none text-center d-block p-3 rounded shadow-sm h-100" style="background: var(--light-vert);">
                    <img src="https://cdn-icons-png.flaticon.com/512/2922/2922561.png" alt="Bébé" class="mb-2" style="width:48px;">
                    <div class="fw-bold" style="color: var(--primary-color);">Bébé</div>
                </a>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <a href="fille.php" class="text-decoration-none text-center d-block p-3 rounded shadow-sm h-100" style="background: var(--light-vert);">
                    <img src="https://cdn-icons-png.flaticon.com/512/2922/2922510.png" alt="Fille" class="mb-2" style="width:48px;">
                    <div class="fw-bold" style="color: var(--accent-color);">Fille</div>
                </a>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <a href="garcon.php" class="text-decoration-none text-center d-block p-3 rounded shadow-sm h-100" style="background: var(--light-vert);">
                    <img src="https://cdn-icons-png.flaticon.com/512/2922/2922515.png" alt="Garçon" class="mb-2" style="width:48px;">
                    <div class="fw-bold" style="color: var(--primary-color);">Garçon</div>
                </a>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <a href="chaussures.php" class="text-decoration-none text-center d-block p-3 rounded shadow-sm h-100" style="background: var(--light-vert);">
                    <img src="https://cdn-icons-png.flaticon.com/512/1046/1046857.png" alt="Chaussures" class="mb-2" style="width:48px;">
                    <div class="fw-bold" style="color: var(--secondary-color);">Chaussures</div>
                </a>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <a href="jouets.php" class="text-decoration-none text-center d-block p-3 rounded shadow-sm h-100" style="background: var(--light-vert);">
                    <img src="https://cdn-icons-png.flaticon.com/512/346/346399.png" alt="Jouets" class="mb-2" style="width:48px;">
                    <div class="fw-bold" style="color: var(--warm-orange);">Jouets</div>
                </a>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <a href="chambre.php" class="text-decoration-none text-center d-block p-3 rounded shadow-sm h-100" style="background: var(--light-vert);">
                    <img src="https://cdn-icons-png.flaticon.com/512/1046/1046875.png" alt="Chambre" class="mb-2" style="width:48px;">
                    <div class="fw-bold chambre-text">Chambre</div>
                </a>
            </div>
        </div>
    </section>

    <!-- Carrousel produits coups de cœur -->
    <section class="container py-5">
        <h2 class="text-center mb-4" style="color: var(--primary-color);">Nos coups de cœur</h2>
        <div class="row" id="carousel-list">
            <div class="text-center text-muted">Chargement des produits...</div>
        </div>
    </section>

    <footer class="text-center py-4 mt-5" style="background: var(--light-blue);">
        <p class="mb-0" style="color: var(--dark-blue);">&copy; 2024 MaBoutique. Tous droits réservés.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
    <script>
    // Carrousel produits coups de cœur
    fetch('backend/products.php')
        .then(res => res.json())
        .then(products => {
            const carousel = document.getElementById('carousel-list');
            if (!products.length) {
                carousel.innerHTML = '<div class="text-center text-muted">Aucun produit à afficher.</div>';
                return;
            }
            carousel.innerHTML = products.slice(0,8).map(prod => `
                <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                    <div class="card h-100 shadow-sm">
                        <img src="${prod.image || 'https://via.placeholder.com/200x200?text=Produit'}" class="card-img-top" alt="${prod.name}">
                        <div class="card-body text-center">
                            <h5 class="card-title">${prod.name}</h5>
                            <p class="card-text text-secondary mb-1">${prod.category || ''}</p>
                            <div class="mb-2">
                                ${prod.promo_price && parseFloat(prod.promo_price) < parseFloat(prod.price) ? `<span class='text-decoration-line-through text-muted me-2'>${prod.price} €</span> <span class='fw-bold text-danger'>${prod.promo_price} €</span>` : `<span class='fw-bold text-success'>${prod.price} €</span>`}
                            </div>
                            <a href="#" class="btn btn-outline-success btn-sm">Voir</a>
                        </div>
                    </div>
                </div>
            `).join('');
        });
    </script>
</body>
</html> 