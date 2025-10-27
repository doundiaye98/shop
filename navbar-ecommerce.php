<!-- Navbar E-commerce Responsive -->
<nav class="ecommerce-navbar">
    <div class="navbar-container">
        <!-- Logo -->
        <div class="navbar-logo">
            <a href="index.php">
                <img src="https://via.placeholder.com/50x50/e74c3c/ffffff?text=LOGO" alt="Logo" class="logo-img">
                <span class="logo-text">MonShop</span>
            </a>
        </div>

        <!-- Menu principal (Desktop) -->
        <div class="navbar-menu">
            <ul class="navbar-nav">
                <li class="nav-item mega-dropdown">
                    <a href="#" class="nav-link">
                        Femmes
                        <span class="dropdown-icon">▼</span>
                    </a>
                    <div class="mega-menu">
                        <div class="mega-content">
                            <div class="mega-column">
                                <h4>Vêtements</h4>
                                <ul>
                                    <li><a href="#">Robes</a></li>
                                    <li><a href="#">Tops</a></li>
                                    <li><a href="#">Pantalons</a></li>
                                    <li><a href="#">Jupes</a></li>
                                </ul>
                            </div>
                            <div class="mega-column">
                                <h4>Accessoires</h4>
                                <ul>
                                    <li><a href="#">Sacs</a></li>
                                    <li><a href="#">Chaussures</a></li>
                                    <li><a href="#">Bijoux</a></li>
                                    <li><a href="#">Montres</a></li>
                                </ul>
                            </div>
                            <div class="mega-column">
                                <h4>Promotions</h4>
                                <ul>
                                    <li><a href="#" class="promo-link">Soldes -50% <span class="promo-badge">HOT</span></a></li>
                                    <li><a href="#">Nouveautés</a></li>
                                    <li><a href="#">Best-sellers</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </li>
                
                <li class="nav-item mega-dropdown">
                    <a href="#" class="nav-link">
                        Hommes
                        <span class="dropdown-icon">▼</span>
                    </a>
                    <div class="mega-menu">
                        <div class="mega-content">
                            <div class="mega-column">
                                <h4>Vêtements</h4>
                                <ul>
                                    <li><a href="#">Chemises</a></li>
                                    <li><a href="#">T-shirts</a></li>
                                    <li><a href="#">Pantalons</a></li>
                                    <li><a href="#">Jeans</a></li>
                                </ul>
                            </div>
                            <div class="mega-column">
                                <h4>Accessoires</h4>
                                <ul>
                                    <li><a href="#">Chaussures</a></li>
                                    <li><a href="#">Montres</a></li>
                                    <li><a href="#">Ceintures</a></li>
                                    <li><a href="#">Cravates</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </li>
                
                <li class="nav-item">
                    <a href="#" class="nav-link">Enfants</a>
                </li>
                
                <li class="nav-item">
                    <a href="#" class="nav-link">Maison</a>
                </li>
                
                <li class="nav-item">
                    <a href="#" class="nav-link">Promotions</a>
                </li>
            </ul>
        </div>

        <!-- Icônes de droite -->
        <div class="navbar-icons">
            <!-- Recherche -->
            <div class="search-container">
                <button class="search-toggle" id="searchToggle">
                    <span class="icon-search">🔍</span>
                </button>
                <div class="search-dropdown" id="searchDropdown">
                    <form class="search-form">
                        <input type="text" class="search-input" placeholder="Rechercher un produit...">
                        <button type="submit" class="search-submit">Rechercher</button>
                    </form>
                </div>
            </div>

            <!-- Utilisateur -->
            <div class="user-dropdown">
                <button class="user-toggle">
                    <span class="user-avatar">👤</span>
                    <span class="user-name">Mon Compte</span>
                </button>
                <div class="user-dropdown-menu">
                    <div class="user-header">
                        <span class="user-avatar">👤</span>
                        <div>
                            <div class="username">Utilisateur</div>
                            <div class="user-email">user@example.com</div>
                        </div>
                    </div>
                    <a href="#" class="dropdown-link">
                        <span class="link-icon">👤</span>
                        Mon Profil
                    </a>
                    <a href="#" class="dropdown-link">
                        <span class="link-icon">📦</span>
                        Mes Commandes
                    </a>
                    <a href="#" class="dropdown-link">
                        <span class="link-icon">❤️</span>
                        Favoris
                    </a>
                    <a href="#" class="dropdown-link logout">
                        <span class="link-icon">🚪</span>
                        Déconnexion
                    </a>
                </div>
            </div>

            <!-- Panier -->
            <div class="cart-container">
                <a href="#" class="cart-link">
                    <span class="icon-text">🛒</span>
                    <span class="cart-count" id="cartCount">0</span>
                </a>
                <div class="mini-cart" id="miniCart">
                    <div class="mini-cart-header">
                        <h4>Mon Panier</h4>
                    </div>
                    <div class="mini-cart-content" id="miniCartContent">
                        <div class="mini-cart-empty">Votre panier est vide</div>
                    </div>
                    <div class="mini-cart-footer">
                        <a href="#" class="btn-view-cart">Voir le panier</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Menu hamburger mobile -->
        <button class="mobile-menu-toggle" id="mobileMenuToggle">
            <span class="burger-line"></span>
            <span class="burger-line"></span>
            <span class="burger-line"></span>
        </button>
    </div>

    <!-- Menu mobile -->
    <div class="mobile-menu" id="mobileMenu">
        <div class="mobile-menu-header">
            <h3>Menu</h3>
            <button class="mobile-menu-close" id="mobileMenuClose">✕</button>
        </div>
        
        <div class="mobile-menu-content">
            <ul class="mobile-nav">
                <li class="mobile-dropdown">
                    <button class="mobile-dropdown-toggle">
                        Femmes
                        <span class="dropdown-icon">▼</span>
                    </button>
                    <ul class="mobile-dropdown-menu">
                        <li><a href="#">Robes</a></li>
                        <li><a href="#">Tops</a></li>
                        <li><a href="#">Pantalons</a></li>
                        <li><a href="#">Jupes</a></li>
                    </ul>
                </li>
                
                <li class="mobile-dropdown">
                    <button class="mobile-dropdown-toggle">
                        Hommes
                        <span class="dropdown-icon">▼</span>
                    </button>
                    <ul class="mobile-dropdown-menu">
                        <li><a href="#">Chemises</a></li>
                        <li><a href="#">T-shirts</a></li>
                        <li><a href="#">Pantalons</a></li>
                        <li><a href="#">Jeans</a></li>
                    </ul>
                </li>
                
                <li><a href="#">Enfants</a></li>
                <li><a href="#">Maison</a></li>
                <li><a href="#">Promotions</a></li>
            </ul>
            
            <!-- Section utilisateur mobile -->
            <div class="mobile-user-section">
                <div class="mobile-user-info">
                    <span class="mobile-user-avatar">👤</span>
                    <div class="mobile-user-details">
                        <div class="mobile-username">Utilisateur</div>
                        <div class="mobile-user-email">user@example.com</div>
                    </div>
                </div>
                
                <a href="#" class="mobile-nav-link">
                    <i>👤</i>
                    Mon Profil
                </a>
                <a href="#" class="mobile-nav-link">
                    <i>📦</i>
                    Mes Commandes
                </a>
                <a href="#" class="mobile-nav-link">
                    <i>❤️</i>
                    Favoris
                </a>
                <a href="#" class="mobile-nav-link mobile-logout">
                    <i>🚪</i>
                    Déconnexion
                </a>
            </div>
        </div>
    </div>

    <!-- Overlay mobile -->
    <div class="mobile-overlay" id="mobileOverlay"></div>
</nav>
