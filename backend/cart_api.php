<?php
// API pour la gestion du panier
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
    switch ($method) {
        case 'GET':
            // Récupérer le contenu du panier
            getCart($pdo, $user_id);
            break;
            
        case 'POST':
            // Ajouter un produit au panier
            $data = json_decode(file_get_contents('php://input'), true);
            if (isset($data['product_id']) && isset($data['quantity'])) {
                addToCart($pdo, $user_id, $data['product_id'], $data['quantity']);
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'Données manquantes']);
            }
            break;
            
        case 'PUT':
            // Modifier la quantité d'un produit
            $data = json_decode(file_get_contents('php://input'), true);
            if (isset($data['product_id']) && isset($data['quantity'])) {
                updateCartItem($pdo, $user_id, $data['product_id'], $data['quantity']);
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'Données manquantes']);
            }
            break;
            
        case 'DELETE':
            // Supprimer un produit du panier
            $data = json_decode(file_get_contents('php://input'), true);
            if (isset($data['product_id'])) {
                removeFromCart($pdo, $user_id, $data['product_id']);
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'ID du produit manquant']);
            }
            break;
            
        default:
            http_response_code(405);
            echo json_encode(['error' => 'Méthode non autorisée']);
            break;
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur serveur: ' . $e->getMessage()]);
}

// Fonction pour récupérer le contenu du panier
function getCart($pdo, $user_id) {
    $stmt = $pdo->prepare("
        SELECT c.id, c.quantity, c.created_at,
               p.id as product_id, p.name, p.price, p.image, p.stock,
               p.promo_price, p.category
        FROM cart c
        JOIN products p ON c.product_id = p.id
        WHERE c.user_id = ?
        ORDER BY c.created_at DESC
    ");
    
    $stmt->execute([$user_id]);
    $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Calculer le total
    $total = 0;
    foreach ($cart_items as &$item) {
        $price = $item['promo_price'] && $item['promo_price'] < $item['price'] 
                ? $item['promo_price'] 
                : $item['price'];
        $item['total_price'] = $price * $item['quantity'];
        $total += $item['total_price'];
    }
    
    echo json_encode([
        'cart_items' => $cart_items,
        'total' => $total,
        'item_count' => count($cart_items)
    ]);
}

// Fonction pour ajouter un produit au panier
function addToCart($pdo, $user_id, $product_id, $quantity) {
    // Vérifier que le produit existe et a du stock
    $stmt = $pdo->prepare("SELECT id, name, stock FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();
    
    if (!$product) {
        http_response_code(404);
        echo json_encode(['error' => 'Produit non trouvé']);
        return;
    }
    
    if ($product['stock'] < $quantity) {
        http_response_code(400);
        echo json_encode(['error' => 'Stock insuffisant']);
        return;
    }
    
    // Vérifier si le produit est déjà dans le panier
    $stmt = $pdo->prepare("SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$user_id, $product_id]);
    $existing_item = $stmt->fetch();
    
    if ($existing_item) {
        // Mettre à jour la quantité
        $new_quantity = $existing_item['quantity'] + $quantity;
        if ($new_quantity > $product['stock']) {
            http_response_code(400);
            echo json_encode(['error' => 'Quantité totale dépasse le stock disponible']);
            return;
        }
        
        $stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
        $stmt->execute([$new_quantity, $existing_item['id']]);
        
        echo json_encode([
            'message' => 'Quantité mise à jour dans le panier',
            'quantity' => $new_quantity
        ]);
    } else {
        // Ajouter le nouveau produit
        $stmt = $pdo->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $product_id, $quantity]);
        
        echo json_encode([
            'message' => 'Produit ajouté au panier',
            'quantity' => $quantity
        ]);
    }
}

// Fonction pour modifier la quantité d'un produit
function updateCartItem($pdo, $user_id, $product_id, $quantity) {
    if ($quantity <= 0) {
        // Si quantité <= 0, supprimer l'article
        removeFromCart($pdo, $user_id, $product_id);
        return;
    }
    
    // Vérifier le stock
    $stmt = $pdo->prepare("SELECT stock FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();
    
    if (!$product) {
        http_response_code(404);
        echo json_encode(['error' => 'Produit non trouvé']);
        return;
    }
    
    if ($product['stock'] < $quantity) {
        http_response_code(400);
        echo json_encode(['error' => 'Stock insuffisant']);
        return;
    }
    
    // Mettre à jour la quantité
    $stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
    $result = $stmt->execute([$quantity, $user_id, $product_id]);
    
    if ($result && $stmt->rowCount() > 0) {
        echo json_encode([
            'message' => 'Quantité mise à jour',
            'quantity' => $quantity
        ]);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Article non trouvé dans le panier']);
    }
}

// Fonction pour supprimer un produit du panier
function removeFromCart($pdo, $user_id, $product_id) {
    $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
    $result = $stmt->execute([$user_id, $product_id]);
    
    if ($result && $stmt->rowCount() > 0) {
        echo json_encode(['message' => 'Produit supprimé du panier']);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Article non trouvé dans le panier']);
    }
}
?>
