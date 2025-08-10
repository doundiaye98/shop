<?php
// Démarrer la session seulement si elle n'est pas déjà active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Fonction pour vérifier si l'utilisateur est connecté
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

// Fonction pour rediriger vers la page de connexion si non connecté
function requireLogin() {
    if (!isLoggedIn()) {
        // Stocker l'URL actuelle pour rediriger après connexion
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
        
        // Rediriger vers la page de connexion
        header('Location: ../login.php');
        exit;
    }
}

// Fonction pour obtenir les informations de l'utilisateur connecté
function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }
    
    try {
        require_once 'db.php';
        $stmt = $pdo->prepare('SELECT id, username, email, first_name, last_name FROM users WHERE id = ?');
        $stmt->execute([$_SESSION['user_id']]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return null;
    }
}

// Fonction pour déconnecter l'utilisateur
function logout() {
    session_destroy();
    header('Location: ../index.php');
    exit;
}
?> 