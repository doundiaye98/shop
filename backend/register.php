<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Vérifier si l'email existe déjà
    $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        echo '<script>alert("Cet email est déjà utilisé.");window.location.href="../register.html";</script>';
        exit;
    }

    // Vérifier si le nom d'utilisateur existe déjà
    $stmt = $pdo->prepare('SELECT id FROM users WHERE username = ?');
    $stmt->execute([$username]);
    if ($stmt->fetch()) {
        echo '<script>alert("Ce nom d\'utilisateur est déjà utilisé.");window.location.href="../register.html";</script>';
        exit;
    }

    // Hash du mot de passe
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Insertion
    $stmt = $pdo->prepare('INSERT INTO users (username, email, password) VALUES (?, ?, ?)');
    $stmt->execute([$username, $email, $passwordHash]);

    // Redirection automatique vers la page de connexion avec un message de succès
    $_SESSION['success_message'] = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
    header('Location: ../login.php');
    exit;
} 