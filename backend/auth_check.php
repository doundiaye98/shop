<?php
// Démarrer la session seulement si elle n'est pas déjà active et si les headers ne sont pas encore envoyés
if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_start();
}

// Fonction pour vérifier si l'utilisateur est connecté
function isLoggedIn() {
    return session_status() === PHP_SESSION_ACTIVE && isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

// Fonction pour rediriger vers la page de connexion si non connecté
function requireLogin() {
    if (!isLoggedIn()) {
        // Démarrer la session si possible pour stocker l'URL de redirection
        if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
            session_start();
        }
        
        // Stocker l'URL actuelle pour rediriger après connexion
        if (session_status() === PHP_SESSION_ACTIVE) {
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
        }
        
        // Rediriger vers la page de connexion (chemin absolu)
        header('Location: /shop/login.php');
        exit;
    }
}

// Fonction pour obtenir les informations de l'utilisateur connecté
function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }
    
    try {
        global $pdo;
        if (!$pdo) {
            return null;
        }
        $stmt = $pdo->prepare('SELECT id, username, email, first_name, last_name, role FROM users WHERE id = ?');
        $stmt->execute([$_SESSION['user_id']]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return null;
    }
}

// Fonction pour déconnecter l'utilisateur
function logout() {
    // Démarrer la session si elle n'est pas active
    if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
        session_start();
    }
    
    // Détruire toutes les variables de session
    if (session_status() === PHP_SESSION_ACTIVE) {
        $_SESSION = array();
        session_destroy();
    }
    
    // Rediriger vers la page d'accueil
    header('Location: ../index.php');
    exit;
}
?> 