<?php
// Page de déconnexion avec confirmation
session_start();
require_once 'backend/auth_check.php';

// Vérifier si l'utilisateur est connecté
$wasLoggedIn = isLoggedIn();
$username = '';

if ($wasLoggedIn) {
    $user = getCurrentUser();
    $username = $user['username'] ?? '';
    
    // Détruire la session
    session_destroy();
    
    // Nettoyer les cookies de session
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Déconnexion - Ma Boutique</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <style>
        .logout-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 2rem;
        }
        
        .logout-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            padding: 3rem;
            text-align: center;
            max-width: 500px;
            width: 100%;
            animation: slideIn 0.6s ease-out;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .logout-icon {
            font-size: 4rem;
            margin-bottom: 1.5rem;
            animation: bounce 2s infinite;
        }
        
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-10px);
            }
            60% {
                transform: translateY(-5px);
            }
        }
        
        .logout-title {
            color: var(--dark-blue);
            font-size: 2rem;
            margin-bottom: 1rem;
            font-weight: 700;
        }
        
        .logout-message {
            color: #666;
            font-size: 1.1rem;
            margin-bottom: 2rem;
            line-height: 1.6;
        }
        
        .logout-username {
            background: var(--light-blue);
            color: var(--dark-blue);
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            font-weight: 600;
            margin-bottom: 2rem;
            display: inline-block;
        }
        
        .logout-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .btn {
            padding: 0.75rem 2rem;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            font-size: 1rem;
        }
        
        .btn-primary {
            background: var(--primary-color);
            color: white;
        }
        
        .btn-primary:hover {
            background: var(--dark-blue);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        
        .btn-secondary {
            background: var(--light-blue);
            color: var(--dark-blue);
        }
        
        .btn-secondary:hover {
            background: var(--success-color);
            color: white;
            transform: translateY(-2px);
        }
        
        .logout-timer {
            margin-top: 2rem;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 10px;
            color: #666;
        }
        
        .countdown {
            font-weight: 700;
            color: var(--primary-color);
            font-size: 1.2rem;
        }
        
        .logout-info {
            margin-top: 2rem;
            padding: 1rem;
            background: #e3f2fd;
            border-radius: 10px;
            color: #1976d2;
            font-size: 0.9rem;
        }
        
        @media (max-width: 768px) {
            .logout-card {
                padding: 2rem;
                margin: 1rem;
            }
            
            .logout-actions {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="logout-container">
        <div class="logout-card">
            <?php if ($wasLoggedIn): ?>
                <!-- Utilisateur était connecté -->
                <div class="logout-icon">👋</div>
                <h1 class="logout-title">Au revoir !</h1>
                <p class="logout-message">
                    Vous avez été déconnecté avec succès de votre compte.
                </p>
                
                <div class="logout-username">
                    👤 <?php echo htmlspecialchars($username); ?>
                </div>
                
                <div class="logout-actions">
                    <a href="login.php" class="btn btn-primary">
                        🔐 Se reconnecter
                    </a>
                    <a href="index.php" class="btn btn-secondary">
                        🏠 Retour à l'accueil
                    </a>
                </div>
                
                <div class="logout-timer">
                    <p>Redirection automatique vers l'accueil dans <span class="countdown" id="countdown">5</span> secondes</p>
                </div>
                
                <div class="logout-info">
                    <strong>💡 Conseil :</strong> Votre session a été fermée en toute sécurité. 
                    Toutes vos données sont sauvegardées.
                </div>
                
            <?php else: ?>
                <!-- Utilisateur n'était pas connecté -->
                <div class="logout-icon">❓</div>
                <h1 class="logout-title">Aucune session active</h1>
                <p class="logout-message">
                    Vous n'étiez pas connecté à un compte.
                </p>
                
                <div class="logout-actions">
                    <a href="login.php" class="btn btn-primary">
                        🔐 Se connecter
                    </a>
                    <a href="register.php" class="btn btn-secondary">
                        📝 Créer un compte
                    </a>
                </div>
                
                <div class="logout-info">
                    <strong>💡 Conseil :</strong> Connectez-vous pour accéder à votre espace personnel.
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <script>
        // Compteur de redirection automatique
        <?php if ($wasLoggedIn): ?>
        let countdown = 5;
        const countdownElement = document.getElementById('countdown');
        
        const timer = setInterval(() => {
            countdown--;
            countdownElement.textContent = countdown;
            
            if (countdown <= 0) {
                clearInterval(timer);
                window.location.href = 'index.php';
            }
        }, 1000);
        <?php endif; ?>
        
        // Animation d'entrée pour les boutons
        document.addEventListener('DOMContentLoaded', function() {
            const buttons = document.querySelectorAll('.btn');
            buttons.forEach((button, index) => {
                button.style.opacity = '0';
                button.style.transform = 'translateY(20px)';
                
                setTimeout(() => {
                    button.style.transition = 'all 0.5s ease';
                    button.style.opacity = '1';
                    button.style.transform = 'translateY(0)';
                }, 300 + (index * 100));
            });
        });
    </script>
</body>
</html> 