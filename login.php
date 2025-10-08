<?php
// Démarrer la session seulement si elle n'est pas déjà active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Si l'utilisateur est déjà connecté, le rediriger
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'admin') {
        header('Location: admin_dashboard.php');
    } else {
        header('Location: index.php');
    }
    exit;
}

// Récupérer les erreurs et l'email de la session
$errors = $_SESSION['login_errors'] ?? [];
$saved_email = $_SESSION['login_email'] ?? '';

// Nettoyer les variables de session
unset($_SESSION['login_errors'], $_SESSION['login_email']);

// Récupérer le message de succès après inscription
$success_message = $_SESSION['success_message'] ?? '';
unset($_SESSION['success_message']);

// Inclure la base de données et la navbar
try {
    require_once 'backend/db.php';
    echo "<!-- Base de données chargée avec succès -->";
} catch (Exception $e) {
    echo "<!-- Erreur base de données : " . $e->getMessage() . " -->";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Ma Boutique</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .login-container {
            min-height: calc(100vh - 80px);
            background: linear-gradient(135deg, #1a1a1a 0%, #333 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            margin-top: 80px;
        }
        
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            padding: 40px;
            width: 100%;
            max-width: 450px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .login-header h1 {
            color: #333;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .login-header p {
            color: #666;
            margin: 0;
        }
        
        .form-floating {
            margin-bottom: 20px;
        }
        
        .form-control {
            border-radius: 10px;
            border: 2px solid #e1e5e9;
            padding: 15px 20px;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #1a1a1a;
            box-shadow: 0 0 0 0.2rem rgba(26, 26, 26, 0.25);
        }
        
        .btn-login {
            background: linear-gradient(135deg, #1a1a1a 0%, #333 100%);
            border: none;
            border-radius: 10px;
            padding: 15px;
            font-size: 16px;
            font-weight: 600;
            color: white;
            width: 100%;
            transition: all 0.3s ease;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(26, 26, 26, 0.3);
        }
        
        .alert {
            border-radius: 10px;
            border: none;
            margin-bottom: 20px;
        }
        
        .alert-success {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
            border-left: 4px solid #28a745;
        }
        
        .alert-danger {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            color: #721c24;
            border-left: 4px solid #dc3545;
        }
        
        .login-footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e1e5e9;
        }
        
        .login-footer a {
            color: #1a1a1a;
            text-decoration: none;
            font-weight: 500;
        }
        
        .login-footer a:hover {
            text-decoration: underline;
        }
        
        .brand-logo {
            font-size: 3rem;
            color: #1a1a1a;
            margin-bottom: 20px;
        }
        
        /* Styles pour la navbar */
        .navbar {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .navbar .logo img {
            height: 60px !important;
            width: auto;
        }
        
        .nav-links {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
            gap: 2rem;
        }
        
        .nav-links a {
            color: #333;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        
        .nav-links a:hover {
            color: #1a1a1a;
        }
        
        .icons {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .icon-user, .icon-register {
            color: #1a1a1a;
            text-decoration: none;
            font-size: 1.2rem;
            padding: 0.5rem;
            border-radius: 50%;
            transition: all 0.3s ease;
        }
        
        .icon-user:hover, .icon-register:hover {
            background: rgba(26, 26, 26, 0.1);
            transform: scale(1.1);
        }
        
        /* Styles responsives */
        @media (max-width: 768px) {
            .login-card {
                padding: 2rem;
            }
            
            .nav-links {
                gap: 1rem;
            }
            
            .navbar .logo img {
                height: 50px !important;
            }
            
            .login-container {
                margin-top: 60px;
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <?php 
    try {
        include 'backend/navbar.php';
        echo "<!-- Navbar chargée avec succès -->";
    } catch (Exception $e) {
        echo "<!-- Erreur navbar : " . $e->getMessage() . " -->";
    }
    ?>
    
    <div class="login-container">
        <div class="login-card">
            <!-- Logo et titre -->
            <div class="login-header">
                <div class="brand-logo">
                    <i class="bi bi-shop"></i>
                </div>
                <h1>Connexion</h1>
                <p>Accédez à votre compte</p>
            </div>
            
            <!-- Message de succès après inscription -->
            <?php if (!empty($success_message)): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>
                    <?php echo htmlspecialchars($success_message); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            
            <!-- Messages d'erreur -->
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <?php foreach ($errors as $error): ?>
                        <div><?php echo htmlspecialchars($error); ?></div>
                    <?php endforeach; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            
            <!-- Formulaire de connexion -->
            <form action="backend/login.php" method="POST" id="loginForm">
                <div class="form-floating">
                    <input type="email" 
                           class="form-control" 
                           id="email" 
                           name="email" 
                           placeholder="votre@email.com"
                           value="<?php echo htmlspecialchars($saved_email); ?>"
                           required>
                    <label for="email">
                        <i class="bi bi-envelope me-2"></i>Adresse email
                    </label>
                </div>
                
                <div class="form-floating">
                    <input type="password" 
                           class="form-control" 
                           id="password" 
                           name="password" 
                           placeholder="Mot de passe"
                           required>
                    <label for="password">
                        <i class="bi bi-lock me-2"></i>Mot de passe
                    </label>
                </div>
                
                <button type="submit" class="btn btn-login">
                    <i class="bi bi-box-arrow-in-right me-2"></i>
                    Se connecter
                </button>
            </form>
            
            <!-- Liens utiles -->
            <div class="login-footer">
                <p>Pas encore de compte ? 
                    <a href="register.php">S'inscrire</a>
                </p>
                <p>
                    <a href="forgot_password.php">
                        <i class="bi bi-question-circle me-1"></i>
                        Mot de passe oublié ?
                    </a>
                </p>
                <p>
                    <a href="index.php">
                        <i class="bi bi-arrow-left me-1"></i>
                        Retour à l'accueil
                    </a>
                </p>
                <p style="margin-top: 15px; font-size: 0.9em; color: #666;">
                    <i class="bi bi-code-slash"></i> 
                    Créé par 
                    <span style="color: var(--secondary-color); font-weight: 600;">DOUCODER</span>
                </p>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-focus sur le premier champ
        document.addEventListener('DOMContentLoaded', function() {
            const emailField = document.getElementById('email');
            if (emailField && !emailField.value) {
                emailField.focus();
            }
        });
        
        // Validation en temps réel
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
            
            if (!email || !password) {
                e.preventDefault();
                alert('Veuillez remplir tous les champs');
                return false;
            }
            
            if (!email.includes('@')) {
                e.preventDefault();
                alert('Veuillez entrer une adresse email valide');
                return false;
            }
        });
    </script>
</body>
</html> 