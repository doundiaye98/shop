<?php
require_once 'db.php';

header('Content-Type: application/json');

try {
    // Si un ID spécifique est demandé (pour product_detail.php)
    if (isset($_GET['id'])) {
        $stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
        $stmt->execute([$_GET['id']]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($product) {
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
    echo json_encode($products);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur serveur: ' . $e->getMessage()]);
} 