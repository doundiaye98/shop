<?php
require_once 'db.php';

header('Content-Type: application/json');

// Fonction pour récupérer les images d'un produit
function getProductImages($pdo, $productId) {
    $stmt = $pdo->prepare('
        SELECT image_path, image_order 
        FROM product_images 
        WHERE product_id = ? 
        ORDER BY image_order ASC
    ');
    $stmt->execute([$productId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

try {
    // Si un ID spécifique est demandé (pour product_detail.php)
    if (isset($_GET['id'])) {
        $stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
        $stmt->execute([$_GET['id']]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($product) {
            // Ajouter les images multiples
            $product['images'] = getProductImages($pdo, $product['id']);
            
            // Maintenir la compatibilité avec l'ancien champ 'image'
            if (!empty($product['images'])) {
                $product['image'] = $product['images'][0]['image_path'];
            } elseif (empty($product['image']) && !empty($product['image'])) {
                $product['image'] = $product['image'];
            }
            
            echo json_encode($product);
        } else {
            echo json_encode(['error' => 'Produit non trouvé']);
        }
        exit;
    }
    
    // Si une catégorie est demandée
    $category = isset($_GET['category']) ? $_GET['category'] : null;
    
    if ($category) {
        $stmt = $pdo->prepare('SELECT * FROM products WHERE category = ? ORDER BY created_at DESC');
        $stmt->execute([$category]);
    } else {
        $stmt = $pdo->query('SELECT * FROM products ORDER BY created_at DESC');
    }
    
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Ajouter les images pour chaque produit
    foreach ($products as &$product) {
        $product['images'] = getProductImages($pdo, $product['id']);
        
        // Maintenir la compatibilité avec l'ancien champ 'image'
        if (!empty($product['images'])) {
            $product['image'] = $product['images'][0]['image_path'];
        } elseif (empty($product['image']) && !empty($product['image'])) {
            $product['image'] = $product['image'];
        }
    }
    
    echo json_encode($products);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur serveur: ' . $e->getMessage()]);
} 