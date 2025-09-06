<?php
// API pour la récupération de mot de passe
header('Content-Type: application/json');
session_start();

require_once 'db.php';

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

try {
    switch ($method) {
        case 'POST':
            switch ($action) {
                case 'request_reset':
                    // Demander une récupération de mot de passe
                    requestPasswordReset($pdo);
                    break;
                    
                case 'verify_code':
                    // Vérifier le code de confirmation
                    verifyResetCode($pdo);
                    break;
                    
                case 'reset_password':
                    // Réinitialiser le mot de passe
                    resetPassword($pdo);
                    break;
                    
                default:
                    http_response_code(400);
                    echo json_encode(['success' => false, 'message' => 'Action non reconnue']);
                    break;
            }
            break;
            
        default:
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
            break;
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erreur serveur: ' . $e->getMessage()]);
}

// Demander une récupération de mot de passe
function requestPasswordReset($pdo) {
    $data = json_decode(file_get_contents('php://input'), true);
    $email = trim($data['email'] ?? '');
    
    if (empty($email)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Email requis']);
        return;
    }
    
    // Vérifier si l'email existe
    $stmt = $pdo->prepare('SELECT id, username, first_name, last_name FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        // Pour des raisons de sécurité, ne pas révéler si l'email existe ou non
        echo json_encode(['success' => true, 'message' => 'Si cet email existe dans notre base, un code de confirmation vous sera envoyé.']);
        return;
    }
    
    // Supprimer les anciens codes pour cet utilisateur
    $stmt = $pdo->prepare('DELETE FROM password_reset_codes WHERE user_id = ? OR email = ?');
    $stmt->execute([$user['id'], $email]);
    
    // Générer un code de 6 chiffres
    $code = sprintf('%06d', mt_rand(0, 999999));
    
    // Le code expire dans 15 minutes
    $expiresAt = date('Y-m-d H:i:s', strtotime('+15 minutes'));
    
    // Sauvegarder le code
    $stmt = $pdo->prepare('INSERT INTO password_reset_codes (user_id, email, code, expires_at) VALUES (?, ?, ?, ?)');
    $stmt->execute([$user['id'], $email, $code, $expiresAt]);
    
    // Envoyer l'email (simulation pour le moment)
    $subject = 'Récupération de mot de passe - Ma Boutique';
    $message = "
    Bonjour {$user['first_name']} {$user['last_name']},
    
    Vous avez demandé la récupération de votre mot de passe.
    
    Votre code de confirmation est : {$code}
    
    Ce code expire dans 15 minutes.
    
    Si vous n'avez pas demandé cette récupération, ignorez cet email.
    
    Cordialement,
    L'équipe Ma Boutique
    ";
    
    $headers = 'From: noreply@maboutique.com' . "\r\n" .
               'Reply-To: noreply@maboutique.com' . "\r\n" .
               'X-Mailer: PHP/' . phpversion();
    
    // Envoyer l'email (désactivé en développement)
    if (mail($email, $subject, $message, $headers)) {
        echo json_encode(['success' => true, 'message' => 'Code de confirmation envoyé à votre email.']);
    } else {
        // En développement, afficher le code dans la réponse
        echo json_encode([
            'success' => true, 
            'message' => 'Code de confirmation généré (développement)',
            'debug_code' => $code,
            'note' => 'En production, ce code serait envoyé par email'
        ]);
    }
}

// Vérifier le code de confirmation
function verifyResetCode($pdo) {
    $data = json_decode(file_get_contents('php://input'), true);
    $email = trim($data['email'] ?? '');
    $code = trim($data['code'] ?? '');
    
    if (empty($email) || empty($code)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Email et code requis']);
        return;
    }
    
    // Vérifier le code
    $stmt = $pdo->prepare('
        SELECT prc.*, u.username 
        FROM password_reset_codes prc
        JOIN users u ON prc.user_id = u.id
        WHERE prc.email = ? AND prc.code = ? AND prc.expires_at > NOW() AND prc.used = 0
    ');
    $stmt->execute([$email, $code]);
    $resetCode = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$resetCode) {
        echo json_encode(['success' => false, 'message' => 'Code invalide ou expiré']);
        return;
    }
    
    // Marquer le code comme utilisé
    $stmt = $pdo->prepare('UPDATE password_reset_codes SET used = 1 WHERE id = ?');
    $stmt->execute([$resetCode['id']]);
    
    // Créer un token temporaire pour la réinitialisation
    $token = bin2hex(random_bytes(32));
    $_SESSION['password_reset_token'] = $token;
    $_SESSION['password_reset_email'] = $email;
    $_SESSION['password_reset_user_id'] = $resetCode['user_id'];
    
    echo json_encode([
        'success' => true, 
        'message' => 'Code vérifié avec succès',
        'token' => $token
    ]);
}

// Réinitialiser le mot de passe
function resetPassword($pdo) {
    $data = json_decode(file_get_contents('php://input'), true);
    $token = $data['token'] ?? '';
    $newPassword = $data['new_password'] ?? '';
    
    // Vérifier le token
    if ($token !== ($_SESSION['password_reset_token'] ?? '') || 
        !isset($_SESSION['password_reset_email']) || 
        !isset($_SESSION['password_reset_user_id'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Token invalide ou expiré']);
        return;
    }
    
    if (strlen($newPassword) < 6) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Le mot de passe doit contenir au moins 6 caractères']);
        return;
    }
    
    // Hasher le nouveau mot de passe
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    
    // Mettre à jour le mot de passe
    $stmt = $pdo->prepare('UPDATE users SET password = ?, updated_at = NOW() WHERE id = ?');
    $stmt->execute([$hashedPassword, $_SESSION['password_reset_user_id']]);
    
    // Nettoyer les variables de session
    unset($_SESSION['password_reset_token']);
    unset($_SESSION['password_reset_email']);
    unset($_SESSION['password_reset_user_id']);
    
    echo json_encode(['success' => true, 'message' => 'Mot de passe mis à jour avec succès']);
}
?>
