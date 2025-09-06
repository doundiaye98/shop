<?php
// API pour l'achat direct d'un produit
header('Content-Type: application/json');
session_start();

require_once 'db.php';
require_once 'auth_check.php';

// Vérifier que l'utilisateur est connecté
if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['error' => 'Utilisateur non connecté']);
    exit;
}

$user_id = $_SESSION['user_id'];
$method = $_SERVER['REQUEST_METHOD'];

try {
    if ($method === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($data['product_id']) || !isset($data['quantity'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Données manquantes']);
            exit;
        }
        
        $product_id = $data['product_id'];
        $quantity = intval($data['quantity']);
        
        // Vérifier que le produit existe et a du stock
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$product_id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$product) {
            http_response_code(404);
            echo json_encode(['error' => 'Produit non trouvé']);
            exit;
        }
        
        if ($product['stock'] < $quantity) {
            http_response_code(400);
            echo json_encode(['error' => 'Stock insuffisant']);
            exit;
        }
        
        // Calculer le prix final (promo ou normal)
        $final_price = $product['promo_price'] && $product['promo_price'] < $product['price'] 
                      ? $product['promo_price'] 
                      : $product['price'];
        
        $total_amount = $final_price * $quantity;
        
        // Créer la commande
        $stmt = $pdo->prepare("
            INSERT INTO orders (user_id, total_amount, status, shipping_address, payment_method) 
            VALUES (?, ?, 'pending', ?, ?)
        ");
        
        $shipping_address = $data['shipping_address'] ?? 'Adresse à confirmer';
        $payment_method = $data['payment_method'] ?? 'À définir';
        
        $stmt->execute([$user_id, $total_amount, $shipping_address, $payment_method]);
        $order_id = $pdo->lastInsertId();
        
        // Ajouter l'article à la commande
        $stmt = $pdo->prepare("
            INSERT INTO order_items (order_id, product_id, quantity, unit_price, total_price) 
            VALUES (?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([$order_id, $product_id, $quantity, $final_price, $total_amount]);
        
        // Mettre à jour le stock
        $new_stock = $product['stock'] - $quantity;
        $stmt = $pdo->prepare("UPDATE products SET stock = ? WHERE id = ?");
        $stmt->execute([$new_stock, $product_id]);
        
        // Retourner les informations de la commande
        echo json_encode([
            'success' => true,
            'message' => 'Commande créée avec succès',
            'order_id' => $order_id,
            'total_amount' => $total_amount,
            'product_name' => $product['name'],
            'quantity' => $quantity,
            'unit_price' => $final_price
        ]);
        
    } else {
        http_response_code(405);
        echo json_encode(['error' => 'Méthode non autorisée']);
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur serveur: ' . $e->getMessage()]);
}
?>
