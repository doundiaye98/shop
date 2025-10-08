li<?php
// Page de connexion moderne pour l'administration
session_start();

// Rediriger si déjà connecté
if (isset($_SESSION['user_id']) && isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    header('Location: admin_dashboard_modern.php');
    exit;
}

require_once 'backend/db.php';

$error = '';

if ($_POST) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = 'Veuillez remplir tous les champs';
    } else {
        try {
            // Essayer d'abord avec username, puis avec email
            $stmt = $pdo->prepare("SELECT * FROM users WHERE (username = ? OR email = ?) AND role = 'admin' AND is_active = 1");
            $stmt->execute([$username, $username]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['first_name'] = $user['first_name'] ?? '';
                $_SESSION['last_name'] = $user['last_name'] ?? '';
                
                // Mettre à jour la dernière connexion
                $updateStmt = $pdo->prepare('UPDATE users SET last_login = NOW() WHERE id = ?');
                $updateStmt->execute([$user['id']]);
                
                header('Location: admin_dashboard_modern.php');
                exit;
            } else {
                $error = 'Nom d\'utilisateur ou mot de passe incorrect';
            }
        } catch (PDOException $e) {
            $error = 'Erreur de connexion à la base de données';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Administration - Ma Boutique</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        :root {
            --admin-primary: #2563eb;
            --admin-primary-dark: #1d4ed8;
            --admin-secondary: #64748b;
            --admin-success: #10b981;
            --admin-warning: #f59e0b;
            --admin-danger: #ef4444;
            --admin-light: #f8fafc;
            --admin-dark: #1e293b;
            --admin-white: #ffffff;
            --admin-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
            --admin-shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --admin-border-radius: 0.5rem;
            --admin-transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .login-container {
            width: 100%;
            max-width: 400px;
        }

        .login-card {
            background: var(--admin-white);
            border-radius: 16px;
            box-shadow: var(--admin-shadow-lg);
            overflow: hidden;
            border: none;
        }

        .login-header {
            background: linear-gradient(135deg, var(--admin-primary), var(--admin-primary-dark));
            color: var(--admin-white);
            padding: 2rem;
            text-align: center;
        }

        .login-header h1 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .login-header p {
            margin: 0;
            opacity: 0.9;
            font-size: 0.875rem;
        }

        .login-body {
            padding: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 600;
            color: var(--admin-dark);
            margin-bottom: 0.5rem;
        }

        .form-control {
            border: 2px solid #e5e7eb;
            border-radius: var(--admin-border-radius);
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: var(--admin-transition);
        }

        .form-control:focus {
            border-color: var(--admin-primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .input-group {
            position: relative;
        }

        .input-group .form-control {
            padding-left: 3rem;
        }

        .input-group-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--admin-secondary);
            z-index: 10;
        }

        .btn-login {
            background: linear-gradient(135deg, var(--admin-primary), var(--admin-primary-dark));
            border: none;
            border-radius: var(--admin-border-radius);
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            font-size: 1rem;
            color: var(--admin-white);
            width: 100%;
            transition: var(--admin-transition);
            position: relative;
            overflow: hidden;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: var(--admin-shadow-lg);
            color: var(--admin-white);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn-login:hover::before {
            left: 100%;
        }

        .alert {
            border: none;
            border-radius: var(--admin-border-radius);
            padding: 1rem;
            margin-bottom: 1.5rem;
            font-weight: 500;
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.1);
            color: var(--admin-danger);
            border-left: 4px solid var(--admin-danger);
        }

        .login-footer {
            text-align: center;
            padding: 1rem 2rem 2rem;
            color: var(--admin-secondary);
            font-size: 0.875rem;
        }

        .login-footer a {
            color: var(--admin-primary);
            text-decoration: none;
            font-weight: 600;
        }

        .login-footer a:hover {
            text-decoration: underline;
        }

        .loading {
            display: none;
        }

        .loading.show {
            display: inline-block;
        }

        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-card {
            animation: fadeInUp 0.6s ease-out;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .login-header {
                padding: 1.5rem;
            }
            
            .login-body {
                padding: 1.5rem;
            }
            
            .login-header h1 {
                font-size: 1.25rem;
            }
        }

        /* Effet de focus pour l'accessibilité */
        .form-control:focus,
        .btn-login:focus {
            outline: 2px solid var(--admin-primary);
            outline-offset: 2px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h1><i class="bi bi-shield-check me-2"></i>Administration</h1>
                <p>Connectez-vous pour accéder au panneau d'administration</p>
            </div>
            
            <div class="login-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" id="loginForm">
                    <div class="form-group">
                        <label for="username" class="form-label">Nom d'utilisateur</label>
                        <div class="input-group">
                            <i class="bi bi-person input-group-icon"></i>
                            <input type="text" 
                                   class="form-control" 
                                   id="username" 
                                   name="username" 
                                   placeholder="Entrez votre nom d'utilisateur"
                                   value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>"
                                   required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="password" class="form-label">Mot de passe</label>
                        <div class="input-group">
                            <i class="bi bi-lock input-group-icon"></i>
                            <input type="password" 
                                   class="form-control" 
                                   id="password" 
                                   name="password" 
                                   placeholder="Entrez votre mot de passe"
                                   required>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-login" id="loginBtn">
                        <span class="btn-text">
                            <i class="bi bi-box-arrow-in-right me-2"></i>
                            Se connecter
                        </span>
                        <span class="loading">
                            <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                            Connexion...
                        </span>
                    </button>
                </form>
            </div>
            
            <div class="login-footer">
                <p>
                    <a href="../index.php">
                        <i class="bi bi-arrow-left me-1"></i>
                        Retour au site
                    </a>
                </p>
                <p class="mt-2">
                    <small>&copy; 2024 Ma Boutique. Tous droits réservés.</small>
                </p>
                <p class="mt-2">
                    <small style="color: var(--admin-secondary);">
                        <i class="bi bi-code-slash"></i> 
                        Créé par 
                        <span style="color: var(--admin-warning); font-weight: 600;">DOUCODER</span>
                    </small>
                </p>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const btn = document.getElementById('loginBtn');
            const btnText = btn.querySelector('.btn-text');
            const loading = btn.querySelector('.loading');
            
            // Afficher l'état de chargement
            btnText.style.display = 'none';
            loading.classList.add('show');
            btn.disabled = true;
            
            // Simuler un délai pour l'effet visuel
            setTimeout(() => {
                // Le formulaire sera soumis normalement
            }, 500);
        });

        // Animation d'entrée pour les champs
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('.form-control');
            inputs.forEach((input, index) => {
                input.style.opacity = '0';
                input.style.transform = 'translateY(20px)';
                
                setTimeout(() => {
                    input.style.transition = 'all 0.3s ease';
                    input.style.opacity = '1';
                    input.style.transform = 'translateY(0)';
                }, 200 + (index * 100));
            });
        });

        // Effet de focus sur les champs
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.02)';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });
    </script>
</body>
</html>
