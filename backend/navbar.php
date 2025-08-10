<?php 
// Navbar simple avec gestion de connexion
require_once 'auth_check.php';
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
            <li><a href="chaussures.php">Chaussures</a></li>
            <li><a href="jouets.php">Jouets</a></li>
            <li><a href="chambre.php">Chambre</a></li>
            <li><a href="promos.php">Promos</a></li>
            
            <li><a href="contact.php">Contact</a></li>
        </ul>
        
        <!-- Icônes -->
        <div class="icons">
            <?php if (isLoggedIn()): ?>
                <?php $user = getCurrentUser(); ?>
                <!-- Menu profil utilisateur connecté -->
                <div class="user-profile-dropdown">
                    <button class="profile-toggle" title="Mon profil">
                        <span class="profile-icon">👤</span>
                        <span class="profile-name"><?php echo htmlspecialchars($user['username']); ?></span>
                        <span class="dropdown-arrow">▼</span>
                    </button>
                    <div class="profile-dropdown-menu">
                        <div class="profile-header">
                            <div class="profile-avatar">👤</div>
                            <div class="profile-info">
                                <div class="profile-username"><?php echo htmlspecialchars($user['username']); ?></div>
                                <div class="profile-email"><?php echo htmlspecialchars($user['email']); ?></div>
                            </div>
                        </div>
                        <div class="dropdown-divider"></div>
                        <a href="panier.php" class="dropdown-item">
                            <span class="dropdown-icon">🛒</span>
                            Mon panier
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
                <a href="login.php" class="icon-user" title="Se connecter">👤</a>
                <a href="register.php" class="icon-register" title="S'inscrire">📝</a>
            <?php endif; ?>
        </div>
    </div>
</nav> 