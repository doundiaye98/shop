<?php 
// Navbar e-commerce moderne type Vertbaudet
// La base de données doit être incluse avant ce fichier
if (isset($pdo)) {
    require_once 'backend/auth_check.php';
}
?>

<!-- Navbar E-commerce Moderne -->
<nav class="ecommerce-navbar">
    <div class="navbar-container">
        <!-- Logo -->
        <div class="navbar-logo">
            <a href="index.php">
                <img src="WhatsApp Image 2025-08-10 à 11.14.54_18c39375.jpg" alt="Ma Boutique" class="logo-img">
                <span class="logo-text">Ma Boutique</span>
            </a>
        </div>
        
        <!-- Menu Principal (Desktop) -->
        <div class="navbar-menu" id="navbarMenu">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a href="index.php" class="nav-link">Accueil</a>
                </li>
                
                <!-- Mega Menu Bébé -->
                <li class="nav-item mega-dropdown">
                    <a href="bebe.php" class="nav-link mega-trigger">
                        Bébé <i class="dropdown-icon">▼</i>
                    </a>
                    <div class="mega-menu">
                        <div class="mega-content">
                            <div class="mega-column">
                                <h4>Vêtements</h4>
                                <ul>
                                    <li><a href="bebe.php?sub=bodies">Bodies</a></li>
                                    <li><a href="bebe.php?sub=pyjamas">Pyjamas</a></li>
                                    <li><a href="bebe.php?sub=robes">Robes</a></li>
                                    <li><a href="bebe.php?sub=pantalons">Pantalons</a></li>
                                </ul>
                            </div>
                            <div class="mega-column">
                                <h4>Accessoires</h4>
                                <ul>
                                    <li><a href="bebe.php?sub=bonnets">Bonnets</a></li>
                                    <li><a href="bebe.php?sub=chaussons">Chaussons</a></li>
                                    <li><a href="bebe.php?sub=bavoirs">Bavoirs</a></li>
                                </ul>
                            </div>
                            <div class="mega-column">
                                <h4>Par âge</h4>
                                <ul>
                                    <li><a href="bebe.php?age=0-3m">0-3 mois</a></li>
                                    <li><a href="bebe.php?age=3-6m">3-6 mois</a></li>
                                    <li><a href="bebe.php?age=6-12m">6-12 mois</a></li>
                                    <li><a href="bebe.php?age=12-24m">12-24 mois</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </li>
                
                <!-- Mega Menu Fille -->
                <li class="nav-item mega-dropdown">
                    <a href="fille.php" class="nav-link mega-trigger">
                        Fille <i class="dropdown-icon">▼</i>
                    </a>
                    <div class="mega-menu">
                        <div class="mega-content">
                            <div class="mega-column">
                                <h4>Vêtements</h4>
                                <ul>
                                    <li><a href="fille.php?sub=robes">Robes</a></li>
                                    <li><a href="fille.php?sub=tops">Tops & T-shirts</a></li>
                                    <li><a href="fille.php?sub=pantalons">Pantalons</a></li>
                                    <li><a href="fille.php?sub=jupes">Jupes</a></li>
                                    <li><a href="fille.php?sub=pyjamas">Pyjamas</a></li>
                                </ul>
                            </div>
                            <div class="mega-column">
                                <h4>Chaussures</h4>
                                <ul>
                                    <li><a href="fille.php?sub=baskets">Baskets</a></li>
                                    <li><a href="fille.php?sub=sandales">Sandales</a></li>
                                    <li><a href="fille.php?sub=bottes">Bottes</a></li>
                                </ul>
                            </div>
                            <div class="mega-column">
                                <h4>Accessoires</h4>
                                <ul>
                                    <li><a href="fille.php?sub=sacs">Sacs</a></li>
                                    <li><a href="fille.php?sub=bijoux">Bijoux</a></li>
                                    <li><a href="fille.php?sub=cheveux">Accessoires cheveux</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </li>
                
                <!-- Mega Menu Garçon -->
                <li class="nav-item mega-dropdown">
                    <a href="garcon.php" class="nav-link mega-trigger">
                        Garçon <i class="dropdown-icon">▼</i>
                    </a>
                    <div class="mega-menu">
                        <div class="mega-content">
                            <div class="mega-column">
                                <h4>Vêtements</h4>
                                <ul>
                                    <li><a href="garcon.php?sub=t-shirts">T-shirts</a></li>
                                    <li><a href="garcon.php?sub=chemises">Chemises</a></li>
                                    <li><a href="garcon.php?sub=pantalons">Pantalons</a></li>
                                    <li><a href="garcon.php?sub=shorts">Shorts</a></li>
                                    <li><a href="garcon.php?sub=pyjamas">Pyjamas</a></li>
                                </ul>
                            </div>
                            <div class="mega-column">
                                <h4>Chaussures</h4>
                                <ul>
                                    <li><a href="garcon.php?sub=baskets">Baskets</a></li>
                                    <li><a href="garcon.php?sub=sandales">Sandales</a></li>
                                    <li><a href="garcon.php?sub=chaussures-ville">Chaussures de ville</a></li>
                                </ul>
                            </div>
                            <div class="mega-column">
                                <h4>Accessoires</h4>
                                <ul>
                                    <li><a href="garcon.php?sub=casquettes">Casquettes</a></li>
                                    <li><a href="garcon.php?sub=sacs">Sacs</a></li>
                                    <li><a href="garcon.php?sub=montres">Montres</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </li>
                
                <li class="nav-item">
                    <a href="nouveautes.php" class="nav-link">Nouveautés</a>
                </li>
                
                <li class="nav-item">
                    <a href="promos.php" class="nav-link promo-link">
                        <span class="promo-badge">PROMO</span>
                    </a>
                </li>
            </ul>
        </div>
        
        <!-- Icônes de droite -->
        <div class="navbar-icons">
            <!-- Recherche -->
            <div class="search-container">
                <button class="search-toggle" id="searchToggle">
                    <i class="icon-search">🔍</i>
                </button>
                <div class="search-dropdown" id="searchDropdown">
                    <form class="search-form" action="search.php" method="GET">
                        <input type="text" name="q" placeholder="Rechercher un produit..." class="search-input">
                        <button type="submit" class="search-submit">🔍</button>
                    </form>
                </div>
            </div>
            
            <!-- Compte utilisateur -->
            <div class="user-menu">
                <?php if (isset($pdo) && isLoggedIn()): ?>
                    <?php $user = getCurrentUser(); ?>
                    <div class="user-dropdown">
                        <button class="user-toggle">
                            <i class="icon-user">👤</i>
                            <span class="user-name"><?php echo htmlspecialchars($user['username'] ?? 'Mon compte'); ?></span>
                            <i class="dropdown-arrow">▼</i>
                        </button>
                        <div class="user-dropdown-menu">
                            <div class="user-header">
                                <div class="user-avatar">👤</div>
                                <div class="user-info">
                                    <div class="username"><?php echo htmlspecialchars($user['username'] ?? 'Utilisateur'); ?></div>
                                    <div class="user-email"><?php echo htmlspecialchars($user['email'] ?? ''); ?></div>
                                </div>
                            </div>
                            <hr>
                            <a href="profile.php" class="dropdown-link">
                                <i class="link-icon">⚙️</i> Mon profil
                            </a>
                            <a href="commande.php" class="dropdown-link">
                                <i class="link-icon">📋</i> Mes commandes
                            </a>
                            <hr>
                            <a href="logout.php" class="dropdown-link logout">
                                <i class="link-icon">🚪</i> Se déconnecter
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="login.php" class="icon-link">
                        <i class="icon-user">👤</i>
                        <span class="icon-text">Se connecter</span>
                    </a>
                <?php endif; ?>
            </div>
            
            <!-- Favoris -->
            <a href="favorites.php" class="icon-link">
                <i class="icon-heart">❤️</i>
                <span class="icon-text">Favoris</span>
            </a>
            
            <!-- Panier -->
            <div class="cart-container">
                <a href="panier.php" class="cart-link" id="cartLink">
                    <i class="icon-cart">🛒</i>
                    <span class="icon-text">Panier</span>
                    <span class="cart-count" id="cartCount">0</span>
                </a>
                <!-- Mini panier (hover) -->
                <div class="mini-cart" id="miniCart">
                    <div class="mini-cart-header">
                        <h4>Mon panier</h4>
                    </div>
                    <div class="mini-cart-content" id="miniCartContent">
                        <div class="mini-cart-empty">Votre panier est vide</div>
                    </div>
                    <div class="mini-cart-footer">
                        <a href="panier.php" class="btn-view-cart">Voir le panier</a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Menu burger (Mobile) -->
        <div class="mobile-menu-toggle" id="mobileMenuToggle">
            <span class="burger-line"></span>
            <span class="burger-line"></span>
            <span class="burger-line"></span>
        </div>
    </div>
    
    <!-- Menu mobile -->
    <div class="mobile-menu" id="mobileMenu">
        <div class="mobile-menu-header">
            <h3>Menu</h3>
            <button class="mobile-menu-close" id="mobileMenuClose">✕</button>
        </div>
        <div class="mobile-menu-content">
            <ul class="mobile-nav">
                <li><a href="index.php">Accueil</a></li>
                <li class="mobile-dropdown">
                    <button class="mobile-dropdown-toggle">Bébé <i class="dropdown-icon">▼</i></button>
                    <ul class="mobile-dropdown-menu">
                        <li><a href="bebe.php">Tous les produits bébé</a></li>
                        <li><a href="bebe.php?sub=bodies">Bodies</a></li>
                        <li><a href="bebe.php?sub=pyjamas">Pyjamas</a></li>
                        <li><a href="bebe.php?sub=robes">Robes</a></li>
                    </ul>
                </li>
                <li class="mobile-dropdown">
                    <button class="mobile-dropdown-toggle">Fille <i class="dropdown-icon">▼</i></button>
                    <ul class="mobile-dropdown-menu">
                        <li><a href="fille.php">Tous les produits fille</a></li>
                        <li><a href="fille.php?sub=robes">Robes</a></li>
                        <li><a href="fille.php?sub=tops">Tops & T-shirts</a></li>
                        <li><a href="fille.php?sub=pantalons">Pantalons</a></li>
                    </ul>
                </li>
                <li class="mobile-dropdown">
                    <button class="mobile-dropdown-toggle">Garçon <i class="dropdown-icon">▼</i></button>
                    <ul class="mobile-dropdown-menu">
                        <li><a href="garcon.php">Tous les produits garçon</a></li>
                        <li><a href="garcon.php?sub=t-shirts">T-shirts</a></li>
                        <li><a href="garcon.php?sub=chemises">Chemises</a></li>
                        <li><a href="garcon.php?sub=pantalons">Pantalons</a></li>
                    </ul>
                </li>
                <li><a href="nouveautes.php">Nouveautés</a></li>
                <li><a href="promos.php">Promotions</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Overlay pour mobile -->
<div class="mobile-overlay" id="mobileOverlay"></div>
