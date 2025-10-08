<?php 
// Page de démonstration de la nouvelle navbar e-commerce
session_start();
require_once 'backend/db.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle Navbar E-commerce - Ma Boutique</title>
    
    <!-- CSS de la nouvelle navbar -->
    <link rel="stylesheet" href="navbar-ecommerce.css">
    
    <!-- CSS supplémentaire pour la démo -->
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            margin: 0;
            background: #f8f9fa;
        }
        
        .demo-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
        }
        
        .demo-section {
            background: white;
            padding: 30px;
            margin-bottom: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .demo-title {
            color: #2c3e50;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin-top: 30px;
        }
        
        .feature-card {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #e74c3c;
        }
        
        .feature-card h3 {
            color: #2c3e50;
            margin: 0 0 10px 0;
            font-size: 18px;
        }
        
        .feature-card p {
            color: #666;
            margin: 0;
            line-height: 1.6;
        }
        
        .demo-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
            margin: 30px 0;
        }
        
        .demo-btn {
            padding: 12px 24px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }
        
        .demo-btn-primary {
            background: #e74c3c;
            color: white;
        }
        
        .demo-btn-primary:hover {
            background: #c0392b;
            transform: translateY(-2px);
        }
        
        .demo-btn-secondary {
            background: white;
            color: #2c3e50;
            border: 2px solid #e74c3c;
        }
        
        .demo-btn-secondary:hover {
            background: #e74c3c;
            color: white;
        }
        
        .comparison-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        .comparison-table th,
        .comparison-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #e5e5e5;
        }
        
        .comparison-table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #2c3e50;
        }
        
        .status-old {
            color: #dc3545;
            font-weight: 600;
        }
        
        .status-new {
            color: #28a745;
            font-weight: 600;
        }
        
        .highlight-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            border-radius: 10px;
            text-align: center;
            margin: 30px 0;
        }
        
        .highlight-box h3 {
            margin: 0 0 10px 0;
            font-size: 22px;
        }
        
        .highlight-box p {
            margin: 0;
            opacity: 0.9;
        }
        
        @media (max-width: 768px) {
            .demo-container {
                padding: 20px 15px;
            }
            
            .demo-section {
                padding: 20px;
            }
            
            .demo-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .demo-btn {
                width: 100%;
                max-width: 250px;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <!-- Nouvelle Navbar E-commerce -->
    <?php include 'navbar-ecommerce.php'; ?>
    
    <div class="demo-container">
        <div class="demo-section">
            <h1 class="demo-title">🚀 Nouvelle Navbar E-commerce Type Vertbaudet</h1>
            
            <div class="highlight-box">
                <h3>✨ Navbar Moderne Installée !</h3>
                <p>Découvrez votre nouvelle interface de navigation professionnelle avec mega-menus, recherche avancée et design responsive.</p>
            </div>
            
            <div class="demo-buttons">
                <a href="index.php" class="demo-btn demo-btn-primary">
                    🏠 Voir sur l'accueil
                </a>
                <a href="products.php" class="demo-btn demo-btn-secondary">
                    🛍️ Tester sur les produits
                </a>
                <a href="admin_dashboard.php" class="demo-btn demo-btn-secondary">
                    ⚙️ Interface admin
                </a>
            </div>
        </div>
        
        <div class="demo-section">
            <h2>🎯 Fonctionnalités Principales</h2>
            
            <div class="feature-grid">
                <div class="feature-card">
                    <h3>📱 Design Responsive</h3>
                    <p>Adaptation automatique sur desktop, tablette et mobile avec menu burger élégant.</p>
                </div>
                
                <div class="feature-card">
                    <h3>🎪 Mega-Menus</h3>
                    <p>Menus déroulants riches avec sous-catégories organisées par colonnes (Bébé, Fille, Garçon).</p>
                </div>
                
                <div class="feature-card">
                    <h3>🔍 Recherche Avancée</h3>
                    <p>Barre de recherche avec dropdown et suggestions en temps réel (optionnel).</p>
                </div>
                
                <div class="feature-card">
                    <h3>👤 Gestion Utilisateur</h3>
                    <p>Menu profil complet avec dropdown pour compte, commandes, favoris et déconnexion.</p>
                </div>
                
                <div class="feature-card">
                    <h3>🛒 Panier Intelligent</h3>
                    <p>Compteur dynamique et mini-panier au survol avec aperçu des articles.</p>
                </div>
                
                <div class="feature-card">
                    <h3>❤️ Favoris & Plus</h3>
                    <p>Accès rapide aux favoris, compte client et toutes les fonctionnalités e-commerce.</p>
                </div>
            </div>
        </div>
        
        <div class="demo-section">
            <h2>📊 Comparaison Avant/Après</h2>
            
            <table class="comparison-table">
                <thead>
                    <tr>
                        <th>Fonctionnalité</th>
                        <th>Ancienne Navbar</th>
                        <th>Nouvelle Navbar</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Design</strong></td>
                        <td><span class="status-old">❌ Basique</span></td>
                        <td><span class="status-new">✅ Moderne & Professionnel</span></td>
                    </tr>
                    <tr>
                        <td><strong>Responsive</strong></td>
                        <td><span class="status-old">❌ Limité</span></td>
                        <td><span class="status-new">✅ Parfaitement Adaptatif</span></td>
                    </tr>
                    <tr>
                        <td><strong>Mega-Menus</strong></td>
                        <td><span class="status-old">❌ Aucun</span></td>
                        <td><span class="status-new">✅ Mega-menus Complets</span></td>
                    </tr>
                    <tr>
                        <td><strong>Recherche</strong></td>
                        <td><span class="status-old">❌ Absente</span></td>
                        <td><span class="status-new">✅ Recherche Avancée</span></td>
                    </tr>
                    <tr>
                        <td><strong>Panier</strong></td>
                        <td><span class="status-old">❌ Lien Simple</span></td>
                        <td><span class="status-new">✅ Compteur + Mini-panier</span></td>
                    </tr>
                    <tr>
                        <td><strong>UX Mobile</strong></td>
                        <td><span class="status-old">❌ Difficile</span></td>
                        <td><span class="status-new">✅ Menu Burger Fluide</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <div class="demo-section">
            <h2>🎮 Testez les Fonctionnalités</h2>
            <p style="text-align: center; margin-bottom: 25px; color: #666; line-height: 1.6;">
                Explorez toutes les fonctionnalités de votre nouvelle navbar directement depuis cette page !
            </p>
            
            <div class="feature-grid">
                <div class="feature-card">
                    <h3>🎪 Mega-Menus</h3>
                    <p>Survolez "Bébé", "Fille" ou "Garçon" dans la navbar pour voir les mega-menus avec sous-catégories.</p>
                </div>
                
                <div class="feature-card">
                    <h3>🔍 Recherche</h3>
                    <p>Cliquez sur l'icône de recherche (🔍) pour ouvrir la barre de recherche avec dropdown.</p>
                </div>
                
                <div class="feature-card">
                    <h3>👤 Menu Utilisateur</h3>
                    <p>Si connecté, survolez votre nom pour voir le menu profil. Sinon, cliquez sur "Se connecter".</p>
                </div>
                
                <div class="feature-card">
                    <h3>🛒 Mini-Panier</h3>
                    <p>Survolez l'icône panier pour voir le mini-panier avec aperçu des articles.</p>
                </div>
                
                <div class="feature-card">
                    <h3>📱 Mode Mobile</h3>
                    <p>Réduisez la fenêtre ou testez sur mobile pour voir le menu burger et la navigation tactile.</p>
                </div>
                
                <div class="feature-card">
                    <h3>🎯 Promo Badge</h3>
                    <p>Remarquez l'effet d'animation sur le badge "PROMO" dans la navbar.</p>
                </div>
            </div>
        </div>
        
        <div class="demo-section">
            <h2>🔧 Installation & Intégration</h2>
            
            <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0;">
                <h3 style="margin-top: 0; color: #2c3e50;">📁 Fichiers Créés :</h3>
                <ul style="margin: 0; color: #666;">
                    <li><strong>navbar-ecommerce.php</strong> - Structure HTML de la navbar</li>
                    <li><strong>navbar-ecommerce.css</strong> - Styles modernes et responsive</li>
                    <li><strong>navbar-ecommerce.js</strong> - Interactions et animations</li>
                </ul>
            </div>
            
            <div style="background: #e8f5e8; padding: 20px; border-radius: 8px; margin: 20px 0;">
                <h3 style="margin-top: 0; color: #2c3e50;">✅ Pour Utiliser :</h3>
                <ol style="margin: 0; color: #666;">
                    <li>Remplacez <code>include 'backend/navbar.php';</code> par <code>include 'navbar-ecommerce.php';</code></li>
                    <li>Ajoutez <code>&lt;link rel="stylesheet" href="navbar-ecommerce.css"&gt;</code> dans le &lt;head&gt;</li>
                    <li>Ajoutez <code>&lt;script src="navbar-ecommerce.js"&gt;&lt;/script&gt;</code> avant &lt;/body&gt;</li>
                </ol>
            </div>
            
            <div class="demo-buttons">
                <a href="#" onclick="alert('Navbar prête à utiliser ! Suivez les instructions ci-dessus.')" class="demo-btn demo-btn-primary">
                    🚀 Installer maintenant
                </a>
            </div>
        </div>
    </div>
    
    <!-- JavaScript de la navbar -->
    <script src="navbar-ecommerce.js"></script>
    
    <!-- Script de démo -->
    <script>
        // Simuler quelques articles dans le panier pour la démo
        if (!localStorage.getItem('cart')) {
            const demoCart = [
                { id: 1, name: 'T-shirt Enfant', price: 15.99, quantity: 2, image: 'https://via.placeholder.com/50x50' },
                { id: 2, name: 'Robe Fille', price: 25.50, quantity: 1, image: 'https://via.placeholder.com/50x50' }
            ];
            localStorage.setItem('cart', JSON.stringify(demoCart));
        }
        
        // Déclencher la mise à jour du compteur panier
        document.dispatchEvent(new CustomEvent('cartUpdated'));
        
        console.log('🎉 Navbar E-commerce chargée avec succès !');
        console.log('💡 Testez les mega-menus en survolant les catégories');
        console.log('🔍 Testez la recherche en cliquant sur l\'icône loupe');
        console.log('📱 Testez le responsive en réduisant la fenêtre');
    </script>
</body>
</html>
