<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Styles d'Icônes Cœur - Ma Boutique</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="product-buttons.css">
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
        
        .heart-demo {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            margin-top: 1rem;
        }
        
        .heart-item {
            position: relative;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        
        .heart-item h4 {
            position: absolute;
            bottom: 10px;
            left: 50%;
            transform: translateX(-50%);
            color: white;
            font-size: 0.9rem;
            margin: 0;
            background: rgba(0, 0, 0, 0.5);
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
        }
        
        .heart-item::before {
            content: '❤️';
            font-size: 2rem;
            opacity: 0.3;
            position: absolute;
            top: 20px;
            left: 20px;
        }
        
        .code-example {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 6px;
            margin-top: 1rem;
            font-family: monospace;
            font-size: 0.9rem;
            overflow-x: auto;
        }
        
        .usage-guide {
            background: var(--light-blue);
            padding: 1.5rem;
            border-radius: 8px;
            margin-top: 1rem;
        }
        
        .usage-guide h3 {
            color: var(--primary-color);
            margin-bottom: 1rem;
        }
        
        .usage-guide ul {
            list-style: none;
            padding: 0;
        }
        
        .usage-guide li {
            margin: 0.5rem 0;
            padding: 0.5rem;
            background: white;
            border-radius: 4px;
            border-left: 4px solid var(--primary-color);
        }
    </style>
</head>
<body>
    <header>
        <?php include 'backend/navbar.php'; ?>
    </header>
    
    <main style="max-width: 1200px; margin: 0 auto; padding: 2rem;">
        <h1 style="text-align: center; color: var(--primary-color); margin-bottom: 3rem;">
            ❤️ Styles d'Icônes Cœur
        </h1>
        
        <!-- Style par défaut -->
        <div class="demo-section">
            <h2 class="demo-title">Style par Défaut (Sans Cercle)</h2>
            <div class="heart-demo">
                <div class="heart-item">
                    <button class="btn-favorite" onclick="toggleHeart(this)">
                        <i class="bi bi-heart"></i>
                    </button>
                    <h4>Style par défaut</h4>
                </div>
            </div>
            <div class="code-example">
                <code>&lt;button class="btn-favorite" onclick="toggleHeart(this)"&gt;
    &lt;i class="bi bi-heart"&gt;&lt;/i&gt;
&lt;/button&gt;</code>
            </div>
        </div>
        
        <!-- Variantes de styles -->
        <div class="demo-section">
            <h2 class="demo-title">Variantes de Styles</h2>
            <div class="heart-demo">
                <div class="heart-item">
                    <button class="btn-favorite bg-style" onclick="toggleHeart(this)">
                        <i class="bi bi-heart"></i>
                    </button>
                    <h4>Avec fond subtil</h4>
                </div>
                
                <div class="heart-item">
                    <button class="btn-favorite shadow-style" onclick="toggleHeart(this)">
                        <i class="bi bi-heart"></i>
                    </button>
                    <h4>Avec ombre portée</h4>
                </div>
                
                <div class="heart-item">
                    <button class="btn-favorite border-style" onclick="toggleHeart(this)">
                        <i class="bi bi-heart"></i>
                    </button>
                    <h4>Avec bordure</h4>
                </div>
                
                <div class="heart-item">
                    <button class="btn-favorite minimal-style" onclick="toggleHeart(this)">
                        <i class="bi bi-heart"></i>
                    </button>
                    <h4>Style minimaliste</h4>
                </div>
                
                <div class="heart-item">
                    <button class="btn-favorite glow-style" onclick="toggleHeart(this)">
                        <i class="bi bi-heart"></i>
                    </button>
                    <h4>Avec effet glow</h4>
                </div>
            </div>
        </div>
        
        <!-- Guide d'utilisation -->
        <div class="demo-section">
            <h2 class="demo-title">📖 Guide d'Utilisation</h2>
            <div class="usage-guide">
                <h3>Classes disponibles :</h3>
                <ul>
                    <li><strong>btn-favorite</strong> - Style par défaut (sans cercle)</li>
                    <li><strong>btn-favorite bg-style</strong> - Avec fond subtil et effet glassmorphism</li>
                    <li><strong>btn-favorite shadow-style</strong> - Avec ombre portée et effet de profondeur</li>
                    <li><strong>btn-favorite border-style</strong> - Avec bordure circulaire</li>
                    <li><strong>btn-favorite minimal-style</strong> - Style minimaliste pour fond clair</li>
                    <li><strong>btn-favorite glow-style</strong> - Avec effet de lueur</li>
                </ul>
                
                <h3>Exemple d'utilisation :</h3>
                <div class="code-example">
                    <code>&lt;!-- Style par défaut --&gt;
&lt;button class="btn-favorite" onclick="toggleHeart(this)"&gt;
    &lt;i class="bi bi-heart"&gt;&lt;/i&gt;
&lt;/button&gt;

&lt;!-- Avec fond subtil --&gt;
&lt;button class="btn-favorite bg-style" onclick="toggleHeart(this)"&gt;
    &lt;i class="bi bi-heart"&gt;&lt;/i&gt;
&lt;/button&gt;

&lt;!-- Style minimaliste --&gt;
&lt;button class="btn-favorite minimal-style" onclick="toggleHeart(this)"&gt;
    &lt;i class="bi bi-heart"&gt;&lt;/i&gt;
&lt;/button&gt;</code>
                </div>
            </div>
        </div>
        
        <!-- Caractéristiques -->
        <div class="demo-section">
            <h2 class="demo-title">✨ Caractéristiques</h2>
            <div class="usage-guide">
                <h3>Effets inclus :</h3>
                <ul>
                    <li><strong>Animation heartbeat</strong> - Quand le cœur est actif</li>
                    <li><strong>Effet de scale</strong> - Agrandissement au survol</li>
                    <li><strong>Changement de couleur</strong> - Rouge au survol et quand actif</li>
                    <li><strong>Text-shadow</strong> - Ombre portée pour la visibilité</li>
                    <li><strong>Transitions fluides</strong> - Animations douces</li>
                </ul>
                
                <h3>Avantages :</h3>
                <ul>
                    <li>✅ <strong>Plus élégant</strong> - Sans cercle, plus moderne</li>
                    <li>✅ <strong>Meilleure visibilité</strong> - Text-shadow sur fond sombre</li>
                    <li>✅ <strong>Animations fluides</strong> - Effets de survol attrayants</li>
                    <li>✅ <strong>Variantes multiples</strong> - Différents styles selon le contexte</li>
                    <li>✅ <strong>Responsive</strong> - S'adapte à tous les écrans</li>
                </ul>
            </div>
        </div>
    </main>
    
    <footer style="text-align: center; padding: 2rem; background: var(--light-gray); margin-top: 3rem;">
        <p>&copy; 2024 Ma Boutique. Tous droits réservés.</p>
    </footer>
    
    <script>
        function toggleHeart(button) {
            button.classList.toggle('active');
            
            // Changer l'icône
            const icon = button.querySelector('i');
            if (button.classList.contains('active')) {
                icon.className = 'bi bi-heart-fill';
            } else {
                icon.className = 'bi bi-heart';
            }
        }
    </script>
</body>
</html>
