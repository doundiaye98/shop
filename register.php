<?php
// Page d'inscription
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription - Ma Boutique</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .register-container {
            min-height: 100vh;
            background: linear-gradient(135deg, #1a1a1a 0%, #333 100%);
            padding: 2rem 0;
        }
        
        .register-wrapper {
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            padding: 3rem;
            max-width: 500px;
            margin: 0 auto;
            position: relative;
        }
        
        .register-wrapper::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #1a1a1a, #333);
        }
        
        .register-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .register-header h1 {
            color: #1a1a1a;
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
            font-weight: 700;
        }
        
        .register-header h1 i {
            color: #1a1a1a;
            margin-right: 10px;
        }
        
        .register-header p {
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
        
        .btn-register {
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
        
        .btn-register:hover {
            background: linear-gradient(135deg, #333 0%, #1a1a1a 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            color: white;
        }
        
        .login-link {
            text-align: center;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e9ecef;
        }
        
        .login-link p {
            color: #666;
            margin: 0;
        }
        
        .login-link a {
            color: #1a1a1a;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }
        
        .login-link a:hover {
            color: #333;
            text-decoration: underline;
        }
        
        .benefits-section {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            margin-top: 2rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .benefits-title {
            color: #1a1a1a;
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        
        .benefits-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .benefits-list li {
            padding: 0.5rem 0;
            color: #666;
            display: flex;
            align-items: center;
        }
        
        .benefits-list li i {
            color: #1a1a1a;
            margin-right: 0.75rem;
            font-size: 1.1rem;
        }
        
        @media (max-width: 768px) {
            .register-wrapper {
                margin: 1rem;
                padding: 2rem;
            }
            
            .register-header h1 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <?php include 'backend/navbar.php'; ?>

    <div class="register-container d-flex align-items-center justify-content-center">
        <div class="register-wrapper">
            <div class="register-header">
                <h1><i class="bi bi-person-plus"></i> Inscription</h1>
                <p>Crée ton compte pour profiter de tous les avantages</p>
            </div>
            
            <form class="register-form" action="backend/register.php" method="post">
                <div class="form-group">
                    <label for="name"><i class="bi bi-person"></i> Nom complet</label>
                    <input type="text" id="name" name="name" required>
                </div>
                
                <div class="form-group">
                    <label for="email"><i class="bi bi-envelope"></i> Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="password"><i class="bi bi-lock"></i> Mot de passe</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <div class="form-group">
                    <label for="confirmPassword"><i class="bi bi-shield-check"></i> Confirmer le mot de passe</label>
                    <input type="password" id="confirmPassword" name="confirmPassword" required>
                </div>
                
                <button type="submit" class="btn btn-register">
                    <i class="bi bi-person-plus me-2"></i>
                    Créer mon compte
                </button>
            </form>
            
            <div class="login-link">
                <p>Déjà un compte ? <a href="login.php">Se connecter</a></p>
            </div>
            
            <!-- Avantages de l'inscription -->
           
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Validation du formulaire
        document.querySelector('.register-form').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Les mots de passe ne correspondent pas.');
                return false;
            }
            
            if (password.length < 6) {
                e.preventDefault();
                alert('Le mot de passe doit contenir au moins 6 caractères.');
                return false;
            }
            
            // Simulation d'envoi
            alert('Compte créé avec succès ! Vous allez recevoir un email de confirmation.');
        });
    </script>
</body>
</html> 