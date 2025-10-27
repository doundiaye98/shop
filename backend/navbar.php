<?php 
// Navbar simple avec gestion de connexion
// La base de données doit être incluse avant ce fichier
if (!isset($pdo)) {
    // Si la base de données n'est pas incluse, afficher une navbar simple
    ?>
    <nav class="navbar navbar-expand-lg bg-white">
        <div class="container-fluid">
            <div class="logo">
                <img src="WhatsApp Image 2025-08-10 à 11.14.54_18c39375.jpg" alt="Ma Boutique" style="height: 150px; width: auto;">
            </div>
            <ul class="nav-links">
                <li><a href="index.php">Accueil</a></li>
                <li><a href="a-propos.php">À propos</a></li>
                <li><a href="nouveautes.php">Nouveautés</a></li>
                <li><a href="bebe.php">Bébé</a></li>
                <li><a href="fille.php">Fille</a></li>
                <li><a href="garcon.php">Garçon</a></li>



                <li><a href="promos.php">Promos</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
            <div class="icons">
                <a href="login.php" class="icon-user" title="Se connecter">👤</a>
                <a href="register.php" class="icon-register" title="S'inscrire">📝</a>
                    </div>
    </div>
</nav>

<script>
// Fonctions pour le mini-panier
function showMiniCart(event) {
    const miniCart = document.getElementById('mini-cart');
    if (miniCart) {
        miniCart.style.display = 'block';
        
        // Charger le contenu du panier si pas encore fait
        if (window.cartManager) {
            window.cartManager.loadCart();
        }
    }
}

function hideMiniCart() {
    const miniCart = document.getElementById('mini-cart');
    if (miniCart) {
        miniCart.style.display = 'none';
    }
}

// Fermer le mini-panier en cliquant ailleurs
document.addEventListener('click', (e) => {
    const miniCart = document.getElementById('mini-cart');
    const cartIcon = document.querySelector('.cart-icon');
    
    if (miniCart && cartIcon && !miniCart.contains(e.target) && !cartIcon.contains(e.target)) {
        miniCart.style.display = 'none';
    }
});
</script>
    <?php
    return;
}

// Si la base de données est incluse, utiliser la navbar complète
// Inclure auth_check.php seulement si les fonctions ne sont pas déjà définies
if (!function_exists('isLoggedIn')) {
    require_once 'auth_check.php';
}
?>
<nav class="navbar navbar-expand-lg bg-white">
    <div class="container-fluid">
        <!-- Logo -->
        <div class="logo">
            <img src="WhatsApp Image 2025-08-10 à 11.14.54_18c39375.jpg" alt="Ma Boutique" style="height: 150px; width: auto;">
        </div>
        
        <!-- Liens de navigation -->
        <ul class="nav-links">
            <li><a href="index.php">Accueil</a></li>
            <li><a href="a-propos.php">À propos</a></li>
            <li><a href="nouveautes.php">Nouveautés</a></li>
            <li><a href="bebe.php">Bébé</a></li>
            <li><a href="fille.php">Fille</a></li>
            <li><a href="garcon.php">Garçon</a></li>



            <li><a href="promos.php">Promos</a></li>
            
            <li><a href="contact.php">Contact</a></li>
        </ul>
        
        <!-- Icônes -->
        <div class="icons">
            <?php if (isLoggedIn()): ?>
                <?php $user = getCurrentUser(); ?>
                <?php if ($user): ?>
                <!-- Menu profil utilisateur connecté -->
                <div class="user-profile-dropdown">
                    <button class="profile-toggle" title="Mon profil">
                        <span class="profile-icon">👤</span>
                        <span class="profile-name"><?php echo htmlspecialchars($user['username'] ?? 'Utilisateur'); ?></span>
                        <span class="dropdown-arrow">▼</span>
                    </button>
                    <div class="profile-dropdown-menu">
                        <div class="profile-header">
                            <div class="profile-avatar">👤</div>
                            <div class="profile-info">
                                <div class="profile-username"><?php echo htmlspecialchars($user['username'] ?? 'Utilisateur'); ?></div>
                                <div class="profile-email"><?php echo htmlspecialchars($user['email'] ?? 'email@example.com'); ?></div>
                            </div>
                        </div>
                        <div class="dropdown-divider"></div>
                        <a href="panier.php" class="dropdown-item cart-icon" onmouseover="showMiniCart(event)" onmouseout="hideMiniCart()">
                            <span class="dropdown-icon">🛒</span>
                            Mon panier
                            <span class="cart-badge" style="display: none;">0</span>
                        </a>
                        <div id="mini-cart" class="mini-cart" style="display: none;">
                            <div id="cart-container">
                                <div class="mini-cart-loading">Chargement...</div>
                            </div>
                        </div>
                        <a href="favorites.php" class="dropdown-item">
                            <span class="dropdown-icon">❤️</span>
                            Mes favoris
                        </a>
                        <a href="commande.php" class="dropdown-item">
                            <span class="dropdown-icon">📋</span>
                            Mes commandes
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="logout.php" class="dropdown-item logout-item">
                            <span class="dropdown-icon">🚪</span>
                            Se déconnecter
                        </a>
                    </div>
                </div>
                <?php else: ?>
                <!-- Fallback si getCurrentUser() échoue -->
                <a href="login.php" class="icon-user" title="Se connecter">👤</a>
                <a href="register.php" class="icon-register" title="S'inscrire">📝</a>
                <?php endif; ?>
            <?php else: ?>
                <a href="login.php" class="icon-user" title="Se connecter">👤</a>
                <a href="register.php" class="icon-register" title="S'inscrire">📝</a>
            <?php endif; ?>
        </div>
    </div>
</nav>