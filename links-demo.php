<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Styles de Liens - Ma Boutique</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="links-styles.css">
    <style>
        .demo-section {
            margin: 2rem 0;
            padding: 2rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .demo-title {
            color: var(--primary-color);
            margin-bottom: 1rem;
            font-size: 1.5rem;
            font-weight: 700;
        }
        
        .demo-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }
        
        .demo-item {
            padding: 1rem;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            text-align: center;
        }
        
        .demo-item h4 {
            margin-bottom: 0.5rem;
            color: var(--text-dark);
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <header>
        <?php include 'backend/navbar.php'; ?>
    </header>
    
    <main style="max-width: 1200px; margin: 0 auto; padding: 2rem;">
        <h1 style="text-align: center; color: var(--primary-color); margin-bottom: 3rem;">
            🎨 Styles de Liens Disponibles
        </h1>
        
        <!-- Liens de base -->
        <div class="demo-section">
            <h2 class="demo-title">Liens de Base</h2>
            <div class="demo-grid">
                <div class="demo-item">
                    <h4>Lien Simple</h4>
                    <a href="#">Lien normal</a>
                </div>
                <div class="demo-item">
                    <h4>Soulignement Animé</h4>
                    <a href="#" class="link-underline">Lien avec soulignement</a>
                </div>
                <div class="demo-item">
                    <h4>Fond au Survol</h4>
                    <a href="#" class="link-bg">Lien avec fond</a>
                </div>
                <div class="demo-item">
                    <h4>Bordure</h4>
                    <a href="#" class="link-border">Lien avec bordure</a>
                </div>
            </div>
        </div>
        
        <!-- Liens avec effets -->
        <div class="demo-section">
            <h2 class="demo-title">Liens avec Effets</h2>
            <div class="demo-grid">
                <div class="demo-item">
                    <h4>Gradient</h4>
                    <a href="#" class="link-gradient">Lien gradient</a>
                </div>
                <div class="demo-item">
                    <h4>Brillance</h4>
                    <a href="#" class="link-shine">Lien brillant</a>
                </div>
                <div class="demo-item">
                    <h4>Pulsation</h4>
                    <a href="#" class="link-pulse">Lien pulsant</a>
                </div>
                <div class="demo-item">
                    <h4>Rebond</h4>
                    <a href="#" class="link-bounce">Lien rebond</a>
                </div>
            </div>
        </div>
        
        <!-- Liens avec animations -->
        <div class="demo-section">
            <h2 class="demo-title">Liens avec Animations</h2>
            <div class="demo-grid">
                <div class="demo-item">
                    <h4>Rotation</h4>
                    <a href="#" class="link-rotate">Lien rotatif</a>
                </div>
                <div class="demo-item">
                    <h4>Glissement</h4>
                    <a href="#" class="link-slide">Lien glissant</a>
                </div>
                <div class="demo-item">
                    <h4>Vague</h4>
                    <a href="#" class="link-wave">Lien vague</a>
                </div>
                <div class="demo-item">
                    <h4>Zoom</h4>
                    <a href="#" class="link-zoom">Lien zoom</a>
                </div>
            </div>
        </div>
        
        <!-- Liens spéciaux -->
        <div class="demo-section">
            <h2 class="demo-title">Liens Spéciaux</h2>
            <div class="demo-grid">
                <div class="demo-item">
                    <h4>Flottement</h4>
                    <a href="#" class="link-float">Lien flottant</a>
                </div>
                <div class="demo-item">
                    <h4>Produit</h4>
                    <a href="#" class="product-link">Lien produit</a>
                </div>
                <div class="demo-item">
                    <h4>Catégorie</h4>
                    <a href="#" class="category-link">Bébé</a>
                </div>
                <div class="demo-item">
                    <h4>Footer</h4>
                    <a href="#" class="footer-link">Lien footer</a>
                </div>
            </div>
        </div>
        
        <!-- Guide d'utilisation -->
        <div class="demo-section">
            <h2 class="demo-title">📖 Comment Utiliser</h2>
            <div style="background: var(--light-blue); padding: 1.5rem; border-radius: 8px; margin-top: 1rem;">
                <h3 style="color: var(--primary-color); margin-bottom: 1rem;">Classes disponibles :</h3>
                <ul style="list-style: none; padding: 0;">
                    <li style="margin: 0.5rem 0;"><code>link-underline</code> - Soulignement animé</li>
                    <li style="margin: 0.5rem 0;"><code>link-bg</code> - Fond au survol</li>
                    <li style="margin: 0.5rem 0;"><code>link-border</code> - Bordure au survol</li>
                    <li style="margin: 0.5rem 0;"><code>link-gradient</code> - Gradient coloré</li>
                    <li style="margin: 0.5rem 0;"><code>link-shine</code> - Effet de brillance</li>
                    <li style="margin: 0.5rem 0;"><code>link-pulse</code> - Animation de pulsation</li>
                    <li style="margin: 0.5rem 0;"><code>link-bounce</code> - Effet de rebond</li>
                    <li style="margin: 0.5rem 0;"><code>link-rotate</code> - Rotation au survol</li>
                    <li style="margin: 0.5rem 0;"><code>link-slide</code> - Glissement de couleur</li>
                    <li style="margin: 0.5rem 0;"><code>link-wave</code> - Effet de vague</li>
                    <li style="margin: 0.5rem 0;"><code>link-zoom</code> - Zoom au survol</li>
                    <li style="margin: 0.5rem 0;"><code>link-float</code> - Effet de flottement</li>
                </ul>
                
                <h3 style="color: var(--primary-color); margin: 1.5rem 0 1rem 0;">Exemple d'utilisation :</h3>
                <pre style="background: #f8f9fa; padding: 1rem; border-radius: 6px; overflow-x: auto;"><code>&lt;a href="produit.php" class="link-gradient"&gt;Voir le produit&lt;/a&gt;
&lt;a href="categorie.php" class="category-link"&gt;Bébé&lt;/a&gt;
&lt;a href="contact.php" class="link-shine"&gt;Nous contacter&lt;/a&gt;</code></pre>
            </div>
        </div>
    </main>
    
    <footer style="text-align: center; padding: 2rem; background: var(--light-gray); margin-top: 3rem;">
        <p>&copy; 2024 Ma Boutique. Tous droits réservés.</p>
    </footer>
</body>
</html>
