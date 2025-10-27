<?php 
session_start();
require_once 'backend/db.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🍔 Test Menu Hamburger Simple</title>
    <link rel="stylesheet" href="navbar-ecommerce.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        /* Améliorations responsive pour tous les écrans */
        @media (max-width: 992px) {
            .location-content {
                flex-direction: column !important;
            }
            
            .map-wrapper {
                height: 300px !important;
                margin-top: 1.5rem;
            }
            
            .interactive-map {
                height: 300px !important;
            }
            
            .map-overlay {
                position: static !important;
                margin-top: 1rem !important;
                margin-bottom: 0 !important;
            }
            
            .map-actions {
                flex-direction: column !important;
            }
            
            .map-btn {
                width: 100% !important;
                margin-bottom: 0.5rem;
            }
        }
        
        @media (max-width: 768px) {
            .hero-title {
                font-size: clamp(1.5rem, 6vw, 2.5rem) !important;
            }
            
            .hero-subtitle {
                font-size: clamp(0.9rem, 3vw, 1.1rem) !important;
            }
            
            .demo-grid {
                grid-template-columns: 1fr !important;
                gap: 1.5rem !important;
            }
            
            .gallery-grid {
                grid-template-columns: 1fr !important;
            }
            
            .contact-info {
                grid-template-columns: 1fr !important;
            }
            
            .contact-card {
                flex-direction: column !important;
                text-align: center !important;
            }
            
            .contact-icon {
                font-size: 2.5rem !important;
                margin-bottom: 1rem !important;
            }
            
            .location-details {
                grid-template-columns: 1fr !important;
            }
            
            .detail-item {
                flex-direction: column !important;
                text-align: center !important;
            }
            
            .detail-icon {
                font-size: 2rem !important;
                margin-bottom: 0.5rem !important;
            }
            
            .location-section {
                padding: 2rem 1rem !important;
            }
            
            .demo-section {
                padding: 1.5rem !important;
            }
        }
        
        @media (max-width: 480px) {
            body {
                padding-top: 70px !important;
            }
            
            .hero-section {
                padding: 2rem 0 !important;
                margin: -1rem -0.75rem 1.5rem -0.75rem !important;
            }
            
            .content-grid {
                gap: 1rem !important;
                margin-bottom: 1.5rem !important;
            }
            
            .instruction-card,
            .demo-card {
                padding: 1.5rem 1rem !important;
            }
            
            .status-grid {
                grid-template-columns: 1fr !important;
                gap: 0.75rem !important;
            }
            
            .status-item {
                flex-direction: column !important;
                text-align: center !important;
                padding: 1rem 0.5rem !important;
            }
            
            .status-label,
            .status-value {
                display: block !important;
                width: 100% !important;
            }
            
            .demo-icon {
                font-size: 2.5rem !important;
            }
            
            .gallery-image {
                height: 180px !important;
            }
            
            .interactive-map {
                height: 250px !important;
            }
            
            .map-info h4 {
                font-size: 0.9rem !important;
            }
            
            .map-info p {
                font-size: 0.8rem !important;
            }
            
            .card-header h3 {
                font-size: 1.1rem !important;
            }
            
            .card-content {
                font-size: 0.9rem !important;
            }
        }
        
        /* Optimisations pour très petits écrans */
        @media (max-width: 360px) {
            .hero-title {
                font-size: 1.25rem !important;
            }
            
            .hero-subtitle {
                font-size: 0.85rem !important;
            }
            
            .status-panel,
            .demo-section {
                padding: 1rem !important;
            }
            
            .instruction-list li,
            .test-steps li {
                font-size: 0.85rem !important;
                padding: 0.4rem 0 !important;
            }
            
            .contact-card,
            .detail-item {
                padding: 1rem 0.75rem !important;
            }
            
            .map-btn {
                font-size: 0.75rem !important;
                padding: 0.5rem 0.75rem !important;
            }
        }
        
        /* Améliorations pour écrans moyens */
        @media (min-width: 481px) and (max-width: 768px) {
            .content-grid {
                grid-template-columns: repeat(2, 1fr) !important;
            }
            
            .status-grid {
                grid-template-columns: repeat(2, 1fr) !important;
            }
            
            .contact-info {
                grid-template-columns: repeat(2, 1fr) !important;
            }
        }
        
        /* Optimisations pour tablettes en mode paysage */
        @media (min-width: 769px) and (max-width: 1024px) {
            .gallery-grid {
                grid-template-columns: repeat(2, 1fr) !important;
            }
            
            .location-content {
                flex-direction: column !important;
            }
            
            .map-wrapper {
                height: 350px !important;
            }
        }
        
        /* Améliorations pour les écrans très larges */
        @media (min-width: 1400px) {
            .container {
                max-width: 1400px !important;
            }
            
            .gallery-grid {
                grid-template-columns: repeat(4, 1fr) !important;
            }
            
            .demo-grid {
                grid-template-columns: repeat(3, 1fr) !important;
            }
        }
        
        /* Fix pour la carte interactive */
        .map-container {
            width: 100%;
            overflow: hidden;
        }
        
        .map-wrapper {
            position: relative;
            padding-bottom: 56.25%; /* 16:9 aspect ratio */
            height: 0;
            overflow: hidden;
            border-radius: 1rem;
        }
        
        .interactive-map {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: none;
        }
        
        @media (max-width: 768px) {
            .map-wrapper {
                padding-bottom: 75%; /* 4:3 aspect ratio sur mobile */
            }
        }
        
        /* Amélioration de la typographie responsive */
        h1, h2, h3, h4 {
            line-height: 1.2 !important;
        }
        
        /* Amélioration des transitions */
        * {
            transition: all 0.3s ease;
        }
        
        /* Smooth scroll */
        html {
            scroll-behavior: smooth;
        }
        
        /* Amélioration de la performance sur mobile */
        @media (max-width: 768px) {
            img {
                image-rendering: -webkit-optimize-contrast;
                image-rendering: crisp-edges;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar corrigée -->
    <?php include 'navbar-ecommerce.php'; ?>
    
    <!-- Contenu principal responsive -->
    <main class="main-content">
        <div class="container">
            <div class="hero-section">
                <h1 class="hero-title">🍔 Test Menu Hamburger</h1>
                <p class="hero-subtitle">Interface responsive moderne</p>
            </div>
            
            <div class="content-grid">
                <!-- Instructions Desktop -->
                <div class="instruction-card desktop-card">
                    <div class="card-header">
                        <h3>🖥️ Sur Desktop</h3>
                    </div>
                    <div class="card-content">
                        <ul class="instruction-list">
                            <li>Le menu hamburger doit être <strong>INVISIBLE</strong></li>
                            <li>Le menu horizontal doit être <strong>VISIBLE</strong></li>
                            <li>Navigation fluide avec mega menus</li>
                        </ul>
                    </div>
                </div>
                
                <!-- Instructions Mobile -->
                <div class="instruction-card mobile-card">
                    <div class="card-header">
                        <h3>📱 Sur Mobile</h3>
                    </div>
                    <div class="card-content">
                        <ul class="instruction-list">
                            <li>Le menu hamburger doit être <strong>VISIBLE</strong></li>
                            <li>Cliquer dessus doit ouvrir le menu latéral</li>
                            <li>Les 3 barres doivent se transformer en X</li>
                        </ul>
                    </div>
                </div>
                
                <!-- Instructions Test -->
                <div class="instruction-card test-card">
                    <div class="card-header">
                        <h3>🔧 Pour Tester</h3>
                    </div>
                    <div class="card-content">
                        <ol class="test-steps">
                            <li>Ouvrez les outils développeur (F12)</li>
                            <li>Activez le mode responsive</li>
                            <li>Testez différentes tailles d'écran</li>
                            <li>Vérifiez que le hamburger apparaît/disparaît correctement</li>
                        </ol>
                    </div>
                </div>
                
                <!-- Status Panel -->
                <div class="status-panel">
                    <h3>📊 Status en Temps Réel</h3>
                    <div class="status-grid">
                        <div class="status-item">
                            <span class="status-label">Largeur actuelle :</span>
                            <span class="status-value" id="currentWidth">-</span>
                        </div>
                        <div class="status-item">
                            <span class="status-label">Mode :</span>
                            <span class="status-value" id="currentMode">-</span>
                        </div>
                        <div class="status-item">
                            <span class="status-label">Hamburger visible :</span>
                            <span class="status-value" id="hamburgerStatus">-</span>
                        </div>
                        <div class="status-item">
                            <span class="status-label">Breakpoint :</span>
                            <span class="status-value" id="breakpointInfo">-</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Section démonstration -->
            <div class="demo-section">
                <h2>🎨 Démonstration Responsive</h2>
                <div class="demo-grid">
                    <div class="demo-card">
                        <div class="demo-icon">📱</div>
                        <h4>Mobile First</h4>
                        <p>Design optimisé pour les petits écrans</p>
                        <div class="demo-image">
                            <img src="https://via.placeholder.com/300x200/667eea/ffffff?text=Mobile+Design" 
                                 alt="Design mobile" 
                                 loading="lazy"
                                 class="responsive-image">
                        </div>
                    </div>
                    <div class="demo-card">
                        <div class="demo-icon">💻</div>
                        <h4>Desktop Ready</h4>
                        <p>Expérience complète sur grand écran</p>
                        <div class="demo-image">
                            <img src="https://via.placeholder.com/300x200/764ba2/ffffff?text=Desktop+Design" 
                                 alt="Design desktop" 
                                 loading="lazy"
                                 class="responsive-image">
                        </div>
                    </div>
                    <div class="demo-card">
                        <div class="demo-icon">⚡</div>
                        <h4>Performance</h4>
                        <p>Chargement rapide et fluide</p>
                        <div class="demo-image">
                            <img src="https://via.placeholder.com/300x200/e74c3c/ffffff?text=Performance" 
                                 alt="Performance" 
                                 loading="lazy"
                                 class="responsive-image">
                        </div>
                    </div>
                </div>
                
                <!-- Section images responsives -->
                <div class="responsive-gallery">
                    <h3>🖼️ Galerie Responsive</h3>
                    <div class="gallery-grid">
                        <div class="gallery-item">
                            <img src="https://via.placeholder.com/400x300/ff6b6b/ffffff?text=Image+1" 
                                 alt="Image responsive 1" 
                                 loading="lazy"
                                 class="gallery-image">
                            <div class="gallery-overlay">
                                <span>Image 1</span>
                            </div>
                        </div>
                        <div class="gallery-item">
                            <img src="https://via.placeholder.com/400x300/4ecdc4/ffffff?text=Image+2" 
                                 alt="Image responsive 2" 
                                 loading="lazy"
                                 class="gallery-image">
                            <div class="gallery-overlay">
                                <span>Image 2</span>
                            </div>
                        </div>
                        <div class="gallery-item">
                            <img src="https://via.placeholder.com/400x300/45b7d1/ffffff?text=Image+3" 
                                 alt="Image responsive 3" 
                                 loading="lazy"
                                 class="gallery-image">
                            <div class="gallery-overlay">
                                <span>Image 3</span>
                            </div>
                        </div>
                        <div class="gallery-item">
                            <img src="https://via.placeholder.com/400x300/96ceb4/ffffff?text=Image+4" 
                                 alt="Image responsive 4" 
                                 loading="lazy"
                                 class="gallery-image">
                            <div class="gallery-overlay">
                                <span>Image 4</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Section Localisation -->
            <div class="location-section">
                <div class="location-header">
                    <h2>📍 Notre Localisation</h2>
                    <p>Venez nous rendre visite dans notre magasin au cœur de Paris</p>
                </div>
                
                <div class="location-content">
                    <!-- Informations de contact -->
                    <div class="contact-info">
                        <div class="contact-card">
                            <div class="contact-icon">📍</div>
                            <div class="contact-details">
                                <h3>Adresse</h3>
                                <p>123 Rue de la Mode<br>75001 Paris, France</p>
                            </div>
                        </div>
                        
                        <div class="contact-card">
                            <div class="contact-icon">🕒</div>
                            <div class="contact-details">
                                <h3>Horaires</h3>
                                <p>Lun-Ven: 9h-19h<br>Sam: 10h-18h<br>Dim: Fermé</p>
                            </div>
                        </div>
                        
                        <div class="contact-card">
                            <div class="contact-icon">📞</div>
                            <div class="contact-details">
                                <h3>Contact</h3>
                                <p>01 23 45 67 89<br>contact@monshop.fr</p>
                            </div>
                        </div>
                        
                        <div class="contact-card">
                            <div class="contact-icon">🚇</div>
                            <div class="contact-details">
                                <h3>Transport</h3>
                                <p>Métro: Châtelet-Les Halles<br>Bus: 21, 38, 47</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Carte interactive -->
                    <div class="map-container">
                        <div class="map-wrapper">
                            <iframe 
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2624.991440608281!2d2.3522219156744144!3d48.85661407928747!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e66e1f06e2b70f%3A0x40b82c3688c9460!2sParis%2C%20France!5e0!3m2!1sen!2sfr!4v1634567890123!5m2!1sen!2sfr"
                                style="border:0;" 
                                allowfullscreen="" 
                                loading="lazy" 
                                referrerpolicy="no-referrer-when-downgrade"
                                class="interactive-map">
                            </iframe>
                            
                            <!-- Overlay avec informations -->
                            <div class="map-overlay">
                                <div class="map-info">
                                    <h4>🏪 MonShop Paris</h4>
                                    <p>123 Rue de la Mode, 75001 Paris</p>
                                    <div class="map-actions">
                                        <a href="https://maps.google.com/?q=123+Rue+de+la+Mode,+75001+Paris" 
                                           target="_blank" 
                                           class="map-btn primary">
                                            🗺️ Ouvrir dans Google Maps
                                        </a>
                                        <a href="https://www.google.com/maps/dir//123+Rue+de+la+Mode,+75001+Paris" 
                                           target="_blank" 
                                           class="map-btn secondary">
                                            🧭 Itinéraire
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Informations supplémentaires -->
                        <div class="location-details">
                            <div class="detail-item">
                                <span class="detail-icon">🚗</span>
                                <div>
                                    <strong>Parking</strong>
                                    <p>Parking public à 200m (Parc de la Bourse)</p>
                                </div>
                            </div>
                            
                            <div class="detail-item">
                                <span class="detail-icon">♿</span>
                                <div>
                                    <strong>Accessibilité</strong>
                                    <p>Magasin entièrement accessible aux PMR</p>
                                </div>
                            </div>
                            
                            <div class="detail-item">
                                <span class="detail-icon">🛍️</span>
                                <div>
                                    <strong>Services</strong>
                                    <p>Essayage, retouches, conseils personnalisés</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <script src="navbar-ecommerce.js"></script>
    <script>
        // Fonction améliorée pour mettre à jour le statut responsive
        function updateStatus() {
            const width = window.innerWidth;
            const hamburger = document.getElementById('mobileMenuToggle');
            
            document.getElementById('currentWidth').textContent = width;
            
            // Détection du mode avec plus de précision
            let mode, modeColor;
            if (width >= 1200) {
                mode = 'Desktop Large';
                modeColor = '#007bff';
            } else if (width >= 992) {
                mode = 'Desktop';
                modeColor = '#007bff';
            } else if (width >= 768) {
                mode = 'Tablette';
                modeColor = '#ffc107';
            } else if (width >= 480) {
                mode = 'Mobile';
                modeColor = '#28a745';
            } else {
                mode = 'Mobile Small';
                modeColor = '#dc3545';
            }
            
            document.getElementById('currentMode').textContent = mode;
            document.getElementById('currentMode').style.color = modeColor;
            
            // Vérification de la visibilité du hamburger
            if (hamburger) {
                const isVisible = window.getComputedStyle(hamburger).display !== 'none';
                document.getElementById('hamburgerStatus').textContent = isVisible ? 'OUI' : 'NON';
                document.getElementById('hamburgerStatus').style.color = isVisible ? '#28a745' : '#dc3545';
            }
            
            // Mise à jour des informations de breakpoint
            updateBreakpointInfo(width);
        }
        
        // Fonction pour afficher les informations de breakpoint
        function updateBreakpointInfo(width) {
            let breakpointInfo = '';
            if (width >= 1400) {
                breakpointInfo = 'XL (≥1400px)';
            } else if (width >= 1200) {
                breakpointInfo = 'LG (≥1200px)';
            } else if (width >= 992) {
                breakpointInfo = 'MD (≥992px)';
            } else if (width >= 768) {
                breakpointInfo = 'SM (≥768px)';
            } else if (width >= 576) {
                breakpointInfo = 'XS (≥576px)';
            } else {
                breakpointInfo = 'XXS (<576px)';
            }
            
            // Ajouter l'info de breakpoint si l'élément existe
            const breakpointElement = document.getElementById('breakpointInfo');
            if (breakpointElement) {
                breakpointElement.textContent = breakpointInfo;
            }
        }
        
        // Fonction pour tester la responsivité
        function testResponsive() {
            console.log('🧪 Test de responsivité démarré');
            
            // Test des éléments critiques
            const tests = [
                {
                    name: 'Navbar visible',
                    test: () => document.querySelector('.ecommerce-navbar') !== null
                },
                {
                    name: 'Menu hamburger fonctionnel',
                    test: () => document.getElementById('mobileMenuToggle') !== null
                },
                {
                    name: 'Images responsives',
                    test: () => document.querySelectorAll('img[loading="lazy"]').length > 0
                },
                {
                    name: 'Grid responsive',
                    test: () => document.querySelector('.content-grid') !== null
                }
            ];
            
            tests.forEach(test => {
                const result = test.test();
                console.log(`${result ? '✅' : '❌'} ${test.name}: ${result ? 'PASS' : 'FAIL'}`);
            });
            
            // Test des breakpoints
            const breakpoints = [360, 480, 768, 992, 1200, 1400];
            console.log('📱 Test des breakpoints:');
            breakpoints.forEach(bp => {
                const currentWidth = window.innerWidth;
                const status = currentWidth >= bp ? '✅' : '❌';
                console.log(`${status} ${bp}px: ${currentWidth >= bp ? 'Actif' : 'Inactif'}`);
            });
        }
        
        // Mettre à jour au chargement et redimensionnement
        updateStatus();
        window.addEventListener('resize', updateStatus);
        
        // Test automatique après chargement
        setTimeout(() => {
            testResponsive();
        }, 1000);
        
        // Log pour debug
        console.log('🍔 Page de test responsive chargée');
        console.log('📱 Redimensionnez pour tester le comportement');
        console.log('🔧 Utilisez F12 > Device Toolbar pour tester différents appareils');
        
        // Vérifier si les éléments existent
        setTimeout(() => {
            const hamburger = document.getElementById('mobileMenuToggle');
            const menu = document.getElementById('mobileMenu');
            const overlay = document.getElementById('mobileOverlay');
            
            console.log('🔍 Éléments trouvés:', {
                hamburger: !!hamburger,
                menu: !!menu,
                overlay: !!overlay
            });
            
            // Test de performance
            const startTime = performance.now();
            updateStatus();
            const endTime = performance.now();
            console.log(`⚡ Performance: ${(endTime - startTime).toFixed(2)}ms`);
        }, 1000);
        
        // Gestion des erreurs
        window.addEventListener('error', (e) => {
            console.error('❌ Erreur détectée:', e.error);
        });
        
        // Fonctionnalités pour la section localisation
        function initLocationFeatures() {
            console.log('📍 Initialisation des fonctionnalités de localisation...');
            
            // Animation des cartes de contact au scroll
            const contactCards = document.querySelectorAll('.contact-card');
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, observerOptions);
            
            contactCards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = `opacity 0.6s ease ${index * 0.1}s, transform 0.6s ease ${index * 0.1}s`;
                observer.observe(card);
            });
            
            // Gestion des boutons de carte
            const mapButtons = document.querySelectorAll('.map-btn');
            mapButtons.forEach(btn => {
                btn.addEventListener('click', (e) => {
                    console.log('🗺️ Clic sur bouton de carte:', e.target.textContent);
                    
                    // Animation de clic
                    btn.style.transform = 'scale(0.95)';
                    setTimeout(() => {
                        btn.style.transform = '';
                    }, 150);
                });
            });
            
            // Détection de la géolocalisation (optionnel)
            if (navigator.geolocation) {
                const locationBtn = document.createElement('button');
                locationBtn.innerHTML = '📍 Ma Position';
                locationBtn.className = 'map-btn secondary';
                locationBtn.style.marginTop = '0.5rem';
                
                locationBtn.addEventListener('click', () => {
                    navigator.geolocation.getCurrentPosition(
                        (position) => {
                            const lat = position.coords.latitude;
                            const lng = position.coords.longitude;
                            console.log('📍 Position détectée:', lat, lng);
                            
                            // Ouvrir Google Maps avec la position actuelle
                            const mapsUrl = `https://www.google.com/maps/dir/${lat},${lng}/123+Rue+de+la+Mode,+75001+Paris`;
                            window.open(mapsUrl, '_blank');
                        },
                        (error) => {
                            console.log('❌ Erreur géolocalisation:', error.message);
                            alert('Impossible d\'accéder à votre position. Veuillez autoriser la géolocalisation.');
                        }
                    );
                });
                
                // Ajouter le bouton à l'overlay de la carte
                const mapActions = document.querySelector('.map-actions');
                if (mapActions) {
                    mapActions.appendChild(locationBtn);
                }
            }
            
            // Animation de la carte au scroll
            const mapContainer = document.querySelector('.map-container');
            if (mapContainer) {
                const mapObserver = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.style.opacity = '1';
                            entry.target.style.transform = 'scale(1)';
                        }
                    });
                }, { threshold: 0.3 });
                
                mapContainer.style.opacity = '0';
                mapContainer.style.transform = 'scale(0.95)';
                mapContainer.style.transition = 'opacity 0.8s ease, transform 0.8s ease';
                mapObserver.observe(mapContainer);
            }
            
            // Test de la responsivité de la carte
            function testMapResponsive() {
                const map = document.querySelector('.interactive-map');
                if (map) {
                    const mapWidth = map.offsetWidth;
                    const mapHeight = map.offsetHeight;
                    console.log(`🗺️ Dimensions de la carte: ${mapWidth}x${mapHeight}px`);
                    
                    // Vérifier si la carte est bien responsive
                    const isResponsive = mapWidth > 0 && mapHeight > 0;
                    console.log(`✅ Carte responsive: ${isResponsive ? 'OUI' : 'NON'}`);
                }
            }
            
            // Test après chargement
            setTimeout(testMapResponsive, 1000);
            
            console.log('✅ Fonctionnalités de localisation initialisées');
        }
        
        // Initialiser les fonctionnalités de localisation
        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(initLocationFeatures, 500);
        });
    </script>
</body>
</html>
