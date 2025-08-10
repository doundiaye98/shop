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

    echo '<script>alert("Inscription réussie ! Connectez-vous.");window.location.href="../login.html";</script>';
    exit;
} 