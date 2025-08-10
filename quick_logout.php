<?php
// Déconnexion rapide - redirection immédiate
session_start();

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

// Rediriger vers l'accueil avec message de succès
header('Location: index.php?logout=success');
exit;
?> 