<?php
// Démarrer la session seulement si elle n'est pas déjà active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'db.php';

// Si l'utilisateur est déjà connecté, le rediriger
if (isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

// Traitement du formulaire de connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Validation des données
    $errors = [];
    
    if (empty($email)) {
        $errors[] = "L'email est requis";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Format d'email invalide";
    }
    
    if (empty($password)) {
        $errors[] = "Le mot de passe est requis";
    }
    
    // Si pas d'erreurs, tenter la connexion
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ? AND is_active = 1');
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password'])) {
                // Connexion réussie
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['first_name'] = $user['first_name'];
                $_SESSION['last_name'] = $user['last_name'];
                
                // Mettre à jour la dernière connexion
                $updateStmt = $pdo->prepare('UPDATE users SET last_login = NOW() WHERE id = ?');
                $updateStmt->execute([$user['id']]);
                
                // Rediriger selon le rôle
                if ($user['role'] === 'admin') {
                    header('Location: ../admin_dashboard_modern.php');
                } else {
                    // Rediriger vers la page demandée ou l'accueil
                    if (isset($_SESSION['redirect_after_login'])) {
                        $redirect = $_SESSION['redirect_after_login'];
                        unset($_SESSION['redirect_after_login']);
                        header('Location: ' . $redirect);
                    } else {
                        header('Location: ../index.php');
                    }
                }
                exit;
            } else {
                $errors[] = "Email ou mot de passe incorrect";
            }
        } catch (PDOException $e) {
            error_log("Erreur de connexion: " . $e->getMessage());
            $errors[] = "Erreur technique. Veuillez réessayer.";
        }
    }
    
    // Stocker les erreurs en session pour les afficher
    if (!empty($errors)) {
        $_SESSION['login_errors'] = $errors;
        $_SESSION['login_email'] = $email; // Garder l'email en cas d'erreur
        header('Location: ../login.php');
        exit;
    }
}

// Si on arrive ici, c'est un GET ou une redirection avec erreurs
// Rediriger vers la page de connexion principale
header('Location: ../login.php');
exit;
?> 