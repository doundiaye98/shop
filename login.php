<?php
// Page de connexion
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion - Ma Boutique</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .login-container {
            min-height: 100vh;
            background: linear-gradient(135deg, #1a1a1a 0%, #333 100%);
            padding: 2rem 0;
        }
        
        .login-wrapper {
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            padding: 3rem;
            max-width: 500px;
            margin: 0 auto;
            position: relative;
        }
        
        .login-wrapper::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #1a1a1a, #333);
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .login-header h1 {
            color: #1a1a1a;
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
            font-weight: 700;
        }
        
        .login-header h1 i {
            color: #1a1a1a;
            margin-right: 10px;
        }
        
        .login-header p {
            color: #666;
            font-size: 1.1rem;
            margin: 0;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #1a1a1a;
            font-weight: 600;
            font-size: 0.95rem;
        }
        
        .form-group label i {
            color: #1a1a1a;
            margin-right: 8px;
            width: 16px;
        }
        
        .form-group input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #1a1a1a;
            background: white;
            box-shadow: 0 0 0 3px rgba(26, 26, 26, 0.1);
        }
        
        .btn-login {
            background: linear-gradient(135deg, #1a1a1a 0%, #333 100%);
            border: none;
            color: white;
            padding: 0.75rem 2rem;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s ease;
        }
        
        .btn-login:hover {
            background: linear-gradient(135deg, #333 0%, #1a1a1a 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            color: white;
        }
        
        .register-link {
            text-align: center;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e9ecef;
        }
        
        .register-link p {
            color: #666;
            margin: 0;
        }
        
        .register-link a {
            color: #1a1a1a;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }
        
        .register-link a:hover {
            color: #333;
            text-decoration: underline;
        }
        
        @media (max-width: 768px) {
            .login-wrapper {
                margin: 1rem;
                padding: 2rem;
            }
            
            .login-header h1 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <?php include 'backend/navbar.php'; ?>

    <div class="login-container d-flex align-items-center justify-content-center">
        <div class="login-wrapper">
            <div class="login-header">
                <h1><i class="bi bi-person-circle"></i> Connexion</h1>
                <p>Connecte-toi pour accéder à ton compte</p>
            </div>
            
            <form class="login-form" action="backend/login.php" method="post">
                <div class="form-group">
                    <label for="email"><i class="bi bi-envelope"></i> Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="password"><i class="bi bi-lock"></i> Mot de passe</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <button type="submit" class="btn btn-login">Se connecter</button>
            </form>
            
            <div class="register-link">
                <p>Pas encore de compte ? <a href="register.php">Créer un compte</a></p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 