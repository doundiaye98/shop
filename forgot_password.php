<?php
session_start();

// Rediriger si déjà connecté
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

require_once 'backend/db.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe oublié - Ma Boutique</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background: linear-gradient(135deg, #1a1a1a 0%, #333 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        
        .forgot-password-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            max-width: 500px;
            width: 100%;
        }
        
        .forgot-password-header {
            background: linear-gradient(135deg, #1a1a1a 0%, #333 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        
        .forgot-password-header h1 {
            margin: 0;
            font-size: 1.8rem;
            font-weight: 600;
        }
        
        .forgot-password-header p {
            margin: 0.5rem 0 0 0;
            opacity: 0.9;
        }
        
        .forgot-password-body {
            padding: 2rem;
        }
        
        .step {
            display: none;
        }
        
        .step.active {
            display: block;
        }
        
        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #1a1a1a;
            box-shadow: 0 0 0 0.2rem rgba(26, 26, 26, 0.25);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #1a1a1a 0%, #333 100%);
            border: none;
            border-radius: 10px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }
        
        .btn-secondary {
            background: #6c757d;
            border: none;
            border-radius: 10px;
            padding: 0.75rem 2rem;
            font-weight: 600;
        }
        
        .code-input {
            display: flex;
            gap: 0.5rem;
            justify-content: center;
            margin: 1rem 0;
        }
        
        .code-input input {
            width: 50px;
            height: 60px;
            text-align: center;
            font-size: 1.5rem;
            font-weight: bold;
            border: 2px solid #e9ecef;
            border-radius: 10px;
        }
        
        .code-input input:focus {
            border-color: #1a1a1a;
            box-shadow: 0 0 0 0.2rem rgba(26, 26, 26, 0.25);
        }
        
        .alert {
            border-radius: 10px;
            border: none;
            margin-bottom: 1rem;
        }
        
        .progress-bar {
            height: 4px;
            background: #e9ecef;
            border-radius: 2px;
            margin-bottom: 2rem;
            overflow: hidden;
        }
        
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #1a1a1a, #333);
            width: 0%;
            transition: width 0.5s ease;
        }
        
        .back-link {
            text-align: center;
            margin-top: 1.5rem;
        }
        
        .back-link a {
            color: #6c757d;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .back-link a:hover {
            color: #1a1a1a;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="forgot-password-container">
                    <!-- Header -->
                    <div class="forgot-password-header">
                        <h1><i class="bi bi-shield-lock me-2"></i>Mot de passe oublié</h1>
                        <p>Récupérez votre accès en toute sécurité</p>
                    </div>
                    
                    <!-- Body -->
                    <div class="forgot-password-body">
                        <!-- Barre de progression -->
                        <div class="progress-bar">
                            <div class="progress-fill" id="progressBar"></div>
                        </div>
                        
                        <!-- Étape 1: Demande de récupération -->
                        <div class="step active" id="step1">
                            <h4 class="text-center mb-4">Étape 1: Vérification de l'email</h4>
                            <p class="text-muted text-center mb-4">
                                Entrez votre adresse email pour recevoir un code de confirmation
                            </p>
                            
                            <form id="emailForm">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Adresse email</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                                
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-send me-2"></i>Envoyer le code
                                </button>
                            </form>
                        </div>
                        
                        <!-- Étape 2: Vérification du code -->
                        <div class="step" id="step2">
                            <h4 class="text-center mb-4">Étape 2: Code de confirmation</h4>
                            <p class="text-muted text-center mb-4">
                                Entrez le code à 6 chiffres envoyé à votre email
                            </p>
                            
                            <form id="codeForm">
                                <div class="code-input">
                                    <input type="text" maxlength="1" class="form-control code-digit" data-index="0">
                                    <input type="text" maxlength="1" class="form-control code-digit" data-index="1">
                                    <input type="text" maxlength="1" class="form-control code-digit" data-index="2">
                                    <input type="text" maxlength="1" class="form-control code-digit" data-index="3">
                                    <input type="text" maxlength="1" class="form-control code-digit" data-index="4">
                                    <input type="text" maxlength="1" class="form-control code-digit" data-index="5">
                                </div>
                                
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-secondary flex-fill" onclick="previousStep()">
                                        <i class="bi bi-arrow-left me-2"></i>Retour
                                    </button>
                                    <button type="submit" class="btn btn-primary flex-fill">
                                        <i class="bi bi-check-circle me-2"></i>Vérifier
                                    </button>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Étape 3: Nouveau mot de passe -->
                        <div class="step" id="step3">
                            <h4 class="text-center mb-4">Étape 3: Nouveau mot de passe</h4>
                            <p class="text-muted text-center mb-4">
                                Choisissez votre nouveau mot de passe
                            </p>
                            
                            <form id="passwordForm">
                                <div class="mb-3">
                                    <label for="newPassword" class="form-label">Nouveau mot de passe</label>
                                    <input type="password" class="form-control" id="newPassword" name="newPassword" required minlength="6">
                                    <div class="form-text">Le mot de passe doit contenir au moins 6 caractères</div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="confirmPassword" class="form-label">Confirmer le mot de passe</label>
                                    <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required minlength="6">
                                </div>
                                
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-secondary flex-fill" onclick="previousStep()">
                                        <i class="bi bi-arrow-left me-2"></i>Retour
                                    </button>
                                    <button type="submit" class="btn btn-primary flex-fill">
                                        <i class="bi bi-check-circle me-2"></i>Réinitialiser
                                    </button>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Étape 4: Succès -->
                        <div class="step" id="step4">
                            <div class="text-center">
                                <i class="bi bi-check-circle text-success" style="font-size: 4rem;"></i>
                                <h4 class="text-success mt-3">Mot de passe mis à jour !</h4>
                                <p class="text-muted">Votre mot de passe a été réinitialisé avec succès.</p>
                                
                                <a href="login.php" class="btn btn-primary">
                                    <i class="bi bi-box-arrow-in-right me-2"></i>Se connecter
                                </a>
                            </div>
                        </div>
                        
                        <!-- Messages d'alerte -->
                        <div id="alertContainer"></div>
                        
                        <!-- Lien de retour -->
                        <div class="back-link">
                            <a href="login.php">
                                <i class="bi bi-arrow-left me-1"></i>Retour à la connexion
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let currentStep = 1;
        let resetToken = '';
        let userEmail = '';
        
        // Gestion des étapes
        function showStep(step) {
            document.querySelectorAll('.step').forEach(s => s.classList.remove('active'));
            document.getElementById(`step${step}`).classList.add('active');
            
            // Mettre à jour la barre de progression
            const progress = ((step - 1) / 3) * 100;
            document.getElementById('progressBar').style.width = progress + '%';
            
            currentStep = step;
        }
        
        function nextStep() {
            if (currentStep < 4) {
                showStep(currentStep + 1);
            }
        }
        
        function previousStep() {
            if (currentStep > 1) {
                showStep(currentStep - 1);
            }
        }
        
        // Gestion des inputs de code
        document.querySelectorAll('.code-digit').forEach(input => {
            input.addEventListener('input', function() {
                const value = this.value;
                const index = parseInt(this.dataset.index);
                
                if (value.length === 1 && index < 5) {
                    document.querySelector(`[data-index="${index + 1}"]`).focus();
                }
            });
            
            input.addEventListener('keydown', function(e) {
                if (e.key === 'Backspace' && this.value === '' && this.dataset.index > 0) {
                    document.querySelector(`[data-index="${parseInt(this.dataset.index) - 1}"]`).focus();
                }
            });
        });
        
        // Affichage des alertes
        function showAlert(message, type = 'info') {
            const alertContainer = document.getElementById('alertContainer');
            const alert = document.createElement('div');
            alert.className = `alert alert-${type} alert-dismissible fade show`;
            alert.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            alertContainer.innerHTML = '';
            alertContainer.appendChild(alert);
        }
        
        // Formulaire email
        document.getElementById('emailForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const email = document.getElementById('email').value;
            userEmail = email;
            
            try {
                const response = await fetch('backend/password_reset_api.php?action=request_reset', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ email: email })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showAlert(data.message, 'success');
                    
                    // En développement, afficher le code
                    if (data.debug_code) {
                        showAlert(`Code de développement: ${data.debug_code}`, 'info');
                    }
                    
                    setTimeout(() => {
                        nextStep();
                    }, 2000);
                } else {
                    showAlert(data.message, 'danger');
                }
            } catch (error) {
                showAlert('Erreur lors de la requête', 'danger');
            }
        });
        
        // Formulaire code
        document.getElementById('codeForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const code = Array.from(document.querySelectorAll('.code-digit'))
                .map(input => input.value)
                .join('');
            
            if (code.length !== 6) {
                showAlert('Veuillez entrer le code complet', 'warning');
                return;
            }
            
            try {
                const response = await fetch('backend/password_reset_api.php?action=verify_code', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ 
                        email: userEmail, 
                        code: code 
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    resetToken = data.token;
                    showAlert(data.message, 'success');
                    
                    setTimeout(() => {
                        nextStep();
                    }, 1500);
                } else {
                    showAlert(data.message, 'danger');
                }
            } catch (error) {
                showAlert('Erreur lors de la vérification', 'danger');
            }
        });
        
        // Formulaire mot de passe
        document.getElementById('passwordForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const newPassword = document.getElementById('newPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            
            if (newPassword !== confirmPassword) {
                showAlert('Les mots de passe ne correspondent pas', 'warning');
                return;
            }
            
            if (newPassword.length < 6) {
                showAlert('Le mot de passe doit contenir au moins 6 caractères', 'warning');
                return;
            }
            
            try {
                const response = await fetch('backend/password_reset_api.php?action=reset_password', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ 
                        token: resetToken, 
                        new_password: newPassword 
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showAlert(data.message, 'success');
                    
                    setTimeout(() => {
                        nextStep();
                    }, 1500);
                } else {
                    showAlert(data.message, 'danger');
                }
            } catch (error) {
                showAlert('Erreur lors de la réinitialisation', 'danger');
            }
        });
    </script>
</body>
</html>
