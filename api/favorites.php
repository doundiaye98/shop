<?php
// API pour la gestion des favoris/wishlist
session_start();
require_once 'db.php';

header('Content-Type: application/json');

// Vérifier que l'utilisateur est connecté - Version sans erreur 401
if (!isset($_SESSION['user_id'])) {
    // Retourner des favoris vides au lieu d'une erreur
    echo json_encode(['success' => true, 'favorites' => [], 'count' => 0, 'message' => 'Favoris vides (non connecté)']);
    exit;
}

$user_id = $_SESSION['user_id'];
$method = $_SERVER['REQUEST_METHOD'];

try {
    switch ($method) {
        case 'GET':
            // Récupérer les favoris de l'utilisateur
            $stmt = $pdo->prepare("
                SELECT f.*, p.name, p.price, p.image, p.category, p.description
                FROM favorites f
                JOIN products p ON f.product_id = p.id
                WHERE f.user_id = ?
                ORDER BY f.created_at DESC
            ");
            $stmt->execute([$user_id]);
            $favorites = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo json_encode([
                'success' => true,
                'favorites' => $favorites,
                'count' => count($favorites)
            ]);
            break;
            
        case 'POST':
            // Ajouter un produit aux favoris
            $input = json_decode(file_get_contents('php://input'), true);
            $product_id = $input['product_id'] ?? 0;
            
            if (!$product_id) {
                throw new Exception('ID produit requis');
            }
            
            // Vérifier si le produit existe
            $stmt = $pdo->prepare('SELECT id FROM products WHERE id = ?');
            $stmt->execute([$product_id]);
            if (!$stmt->fetch()) {
                throw new Exception('Produit non trouvé');
            }
            
            // Vérifier si déjà en favoris
            $stmt = $pdo->prepare('SELECT id FROM favorites WHERE user_id = ? AND product_id = ?');
            $stmt->execute([$user_id, $product_id]);
            if ($stmt->fetch()) {
                throw new Exception('Produit déjà en favoris');
            }
            
            // Ajouter aux favoris
            $stmt = $pdo->prepare('INSERT INTO favorites (user_id, product_id, created_at) VALUES (?, ?, NOW())');
            $stmt->execute([$user_id, $product_id]);
            
            echo json_encode([
                'success' => true,
                'message' => 'Produit ajouté aux favoris'
            ]);
            break;
            
        case 'DELETE':
            // Supprimer un produit des favoris
            $input = json_decode(file_get_contents('php://input'), true);
            $product_id = $input['product_id'] ?? 0;
            
            if (!$product_id) {
                throw new Exception('ID produit requis');
            }
            
            $stmt = $pdo->prepare('DELETE FROM favorites WHERE user_id = ? AND product_id = ?');
            $stmt->execute([$user_id, $product_id]);
            
            if ($stmt->rowCount() > 0) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Produit supprimé des favoris'
                ]);
            } else {
                throw new Exception('Produit non trouvé dans les favoris');
            }
            break;
            
        default:
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
    }
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
