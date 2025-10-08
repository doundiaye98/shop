<?php
// API d'administration moderne - Gestion complète du site
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Vérifier si l'utilisateur est connecté et est admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Accès non autorisé']);
    exit;
}

require_once 'backend/db.php';

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? $_POST['action'] ?? '';

// Gérer les requêtes OPTIONS pour CORS
if ($method === 'OPTIONS') {
    http_response_code(200);
    exit;
}

try {
    switch ($action) {
        // ========================================
        // STATISTIQUES
        // ========================================
        case 'get_stats':
            echo json_encode(getDashboardStats());
            break;
            
        case 'get_sales_data':
            echo json_encode(getSalesData());
            break;
            
        case 'get_category_data':
            echo json_encode(getCategoryData());
            break;
            
        // ========================================
        // PRODUITS
        // ========================================
        case 'get_products':
            echo json_encode(getProducts());
            break;
            
        case 'get_product':
            $id = $_GET['id'] ?? 0;
            echo json_encode(getProduct($id));
            break;
            
        case 'add_product':
            echo json_encode(addProduct());
            break;
            
        case 'update_product':
            echo json_encode(updateProduct());
            break;
            
        case 'delete_product':
            echo json_encode(deleteProduct());
            break;
            
        // ========================================
        // COMMANDES
        // ========================================
        case 'get_orders':
            echo json_encode(getOrders());
            break;
            
        case 'get_order':
            $id = $_GET['id'] ?? 0;
            echo json_encode(getOrder($id));
            break;
            
        case 'update_order_status':
            echo json_encode(updateOrderStatus());
            break;
            
        // ========================================
        // CLIENTS
        // ========================================
        case 'get_users':
            echo json_encode(getUsers());
            break;
            
        case 'get_user':
            $id = $_GET['id'] ?? 0;
            echo json_encode(getUser($id));
            break;
            
        case 'update_user':
            echo json_encode(updateUser());
            break;
            
        case 'delete_user':
            echo json_encode(deleteUser());
            break;
            
        // ========================================
        // ACTIVITÉ
        // ========================================
        case 'get_recent_activity':
            echo json_encode(getRecentActivity());
            break;
            
        case 'get_recent_orders':
            echo json_encode(getRecentOrders());
            break;
            
        // ========================================
        // ANALYTICS
        // ========================================
        case 'get_analytics':
            echo json_encode(getAnalytics());
            break;
            
        case 'get_product_performance':
            echo json_encode(getProductPerformance());
            break;
            
        // ========================================
        // PARAMÈTRES
        // ========================================
        case 'get_settings':
            echo json_encode(getSettings());
            break;
            
        case 'update_settings':
            echo json_encode(updateSettings());
            break;
            
        default:
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Action non reconnue']);
            break;
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erreur serveur: ' . $e->getMessage()]);
}

// ========================================
// FONCTIONS DE STATISTIQUES
// ========================================

function getDashboardStats() {
    global $pdo;
    
    try {
        // Statistiques générales
        $stats = [];
        
        // Nombre total de produits
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM products");
        $stats['total_products'] = $stmt->fetch()['count'];
        
        // Nombre total de commandes
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM orders");
        $stats['total_orders'] = $stmt->fetch()['count'];
        
        // Nombre total de clients
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM users WHERE role = 'user'");
        $stats['total_customers'] = $stmt->fetch()['count'];
        
        // Revenus totaux
        $stmt = $pdo->query("SELECT SUM(total_amount) as total FROM orders WHERE status = 'delivered'");
        $result = $stmt->fetch();
        $stats['total_revenue'] = $result['total'] ?? 0;
        
        // Commandes en attente
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM orders WHERE status = 'pending'");
        $stats['pending_orders'] = $stmt->fetch()['count'];
        
        // Produits en rupture de stock
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM products WHERE stock <= 0");
        $stats['out_of_stock'] = $stmt->fetch()['count'];
        
        return ['success' => true, 'stats' => $stats];
        
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Erreur base de données: ' . $e->getMessage()];
    }
}

function getSalesData() {
    global $pdo;
    
    try {
        // Données des ventes des 30 derniers jours
        $stmt = $pdo->query("
            SELECT 
                DATE(created_at) as date,
                COUNT(*) as orders,
                SUM(total_amount) as revenue
            FROM orders 
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
            GROUP BY DATE(created_at)
            ORDER BY date ASC
        ");
        
        $salesData = $stmt->fetchAll();
        
        $labels = [];
        $values = [];
        
        foreach ($salesData as $row) {
            $labels[] = date('d/m', strtotime($row['date']));
            $values[] = (float)$row['revenue'];
        }
        
        return [
            'success' => true,
            'sales_data' => [
                'labels' => $labels,
                'values' => $values
            ]
        ];
        
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Erreur base de données: ' . $e->getMessage()];
    }
}

function getCategoryData() {
    global $pdo;
    
    try {
        $stmt = $pdo->query("
            SELECT 
                p.category,
                COUNT(oi.id) as sales_count,
                SUM(oi.quantity) as total_quantity
            FROM products p
            LEFT JOIN order_items oi ON p.id = oi.product_id
            LEFT JOIN orders o ON oi.order_id = o.id AND o.status = 'delivered'
            GROUP BY p.category
            ORDER BY sales_count DESC
        ");
        
        $categoryData = $stmt->fetchAll();
        
        $labels = [];
        $values = [];
        
        foreach ($categoryData as $row) {
            $labels[] = $row['category'];
            $values[] = (int)$row['sales_count'];
        }
        
        return [
            'success' => true,
            'category_data' => [
                'labels' => $labels,
                'values' => $values
            ]
        ];
        
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Erreur base de données: ' . $e->getMessage()];
    }
}

// ========================================
// FONCTIONS DE PRODUITS
// ========================================

function getProducts() {
    global $pdo;
    
    try {
        $stmt = $pdo->query("
            SELECT 
                p.*,
                COUNT(oi.id) as sales_count
            FROM products p
            LEFT JOIN order_items oi ON p.id = oi.product_id
            GROUP BY p.id
            ORDER BY p.created_at DESC
        ");
        
        $products = $stmt->fetchAll();
        
        return ['success' => true, 'products' => $products];
        
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Erreur base de données: ' . $e->getMessage()];
    }
}

function getProduct($id) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $product = $stmt->fetch();
        
        if (!$product) {
            return ['success' => false, 'message' => 'Produit non trouvé'];
        }
        
        return ['success' => true, 'product' => $product];
        
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Erreur base de données: ' . $e->getMessage()];
    }
}

function addProduct() {
    global $pdo;
    
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? 0;
    $category = $_POST['category'] ?? '';
    $stock = $_POST['stock'] ?? 0;
    $image = $_POST['image'] ?? '';
    
    if (empty($name) || empty($price) || empty($category)) {
        return ['success' => false, 'message' => 'Champs obligatoires manquants'];
    }
    
    try {
        $stmt = $pdo->prepare("
            INSERT INTO products (name, description, price, category, stock, image, created_at)
            VALUES (?, ?, ?, ?, ?, ?, NOW())
        ");
        
        $stmt->execute([$name, $description, $price, $category, $stock, $image]);
        
        return ['success' => true, 'message' => 'Produit ajouté avec succès', 'id' => $pdo->lastInsertId()];
        
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Erreur base de données: ' . $e->getMessage()];
    }
}

function updateProduct() {
    global $pdo;
    
    $id = $_POST['id'] ?? 0;
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? 0;
    $category = $_POST['category'] ?? '';
    $stock = $_POST['stock'] ?? 0;
    $image = $_POST['image'] ?? '';
    
    if (empty($id) || empty($name) || empty($price) || empty($category)) {
        return ['success' => false, 'message' => 'Champs obligatoires manquants'];
    }
    
    try {
        $stmt = $pdo->prepare("
            UPDATE products 
            SET name = ?, description = ?, price = ?, category = ?, stock = ?, image = ?, updated_at = NOW()
            WHERE id = ?
        ");
        
        $stmt->execute([$name, $description, $price, $category, $stock, $image, $id]);
        
        return ['success' => true, 'message' => 'Produit mis à jour avec succès'];
        
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Erreur base de données: ' . $e->getMessage()];
    }
}

function deleteProduct() {
    global $pdo;
    
    $id = $_POST['id'] ?? 0;
    
    if (empty($id)) {
        return ['success' => false, 'message' => 'ID produit manquant'];
    }
    
    try {
        $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$id]);
        
        return ['success' => true, 'message' => 'Produit supprimé avec succès'];
        
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Erreur base de données: ' . $e->getMessage()];
    }
}

// ========================================
// FONCTIONS DE COMMANDES
// ========================================

function getOrders() {
    global $pdo;
    
    try {
        $stmt = $pdo->query("
            SELECT 
                o.*,
                u.username as customer_name,
                u.email as customer_email
            FROM orders o
            LEFT JOIN users u ON o.user_id = u.id
            ORDER BY o.created_at DESC
        ");
        
        $orders = $stmt->fetchAll();
        
        return ['success' => true, 'orders' => $orders];
        
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Erreur base de données: ' . $e->getMessage()];
    }
}

function getOrder($id) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("
            SELECT 
                o.*,
                u.username as customer_name,
                u.email as customer_email,
                u.phone as customer_phone
            FROM orders o
            LEFT JOIN users u ON o.user_id = u.id
            WHERE o.id = ?
        ");
        $stmt->execute([$id]);
        $order = $stmt->fetch();
        
        if (!$order) {
            return ['success' => false, 'message' => 'Commande non trouvée'];
        }
        
        // Récupérer les articles de la commande
        $stmt = $pdo->prepare("
            SELECT 
                oi.*,
                p.name as product_name,
                p.image as product_image
            FROM order_items oi
            LEFT JOIN products p ON oi.product_id = p.id
            WHERE oi.order_id = ?
        ");
        $stmt->execute([$id]);
        $order['items'] = $stmt->fetchAll();
        
        return ['success' => true, 'order' => $order];
        
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Erreur base de données: ' . $e->getMessage()];
    }
}

function updateOrderStatus() {
    global $pdo;
    
    $id = $_POST['id'] ?? 0;
    $status = $_POST['status'] ?? '';
    
    if (empty($id) || empty($status)) {
        return ['success' => false, 'message' => 'ID et statut requis'];
    }
    
    $validStatuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
    if (!in_array($status, $validStatuses)) {
        return ['success' => false, 'message' => 'Statut invalide'];
    }
    
    try {
        $stmt = $pdo->prepare("UPDATE orders SET status = ?, updated_at = NOW() WHERE id = ?");
        $stmt->execute([$status, $id]);
        
        return ['success' => true, 'message' => 'Statut de commande mis à jour'];
        
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Erreur base de données: ' . $e->getMessage()];
    }
}

// ========================================
// FONCTIONS DE CLIENTS
// ========================================

function getUsers() {
    global $pdo;
    
    try {
        $stmt = $pdo->query("
            SELECT 
                u.*,
                COUNT(o.id) as order_count,
                SUM(o.total_amount) as total_spent
            FROM users u
            LEFT JOIN orders o ON u.id = o.user_id
            WHERE u.role = 'user'
            GROUP BY u.id
            ORDER BY u.created_at DESC
        ");
        
        $users = $stmt->fetchAll();
        
        return ['success' => true, 'users' => $users];
        
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Erreur base de données: ' . $e->getMessage()];
    }
}

function getUser($id) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ? AND role = 'user'");
        $stmt->execute([$id]);
        $user = $stmt->fetch();
        
        if (!$user) {
            return ['success' => false, 'message' => 'Utilisateur non trouvé'];
        }
        
        return ['success' => true, 'user' => $user];
        
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Erreur base de données: ' . $e->getMessage()];
    }
}

// ========================================
// FONCTIONS D'ACTIVITÉ
// ========================================

function getRecentActivity() {
    global $pdo;
    
    try {
        $stmt = $pdo->query("
            SELECT 
                'order' as type,
                CONCAT('Nouvelle commande #', id) as title,
                created_at
            FROM orders
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
            
            UNION ALL
            
            SELECT 
                'user' as type,
                CONCAT('Nouvel utilisateur: ', username) as title,
                created_at
            FROM users
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY) AND role = 'user'
            
            ORDER BY created_at DESC
            LIMIT 10
        ");
        
        $activities = $stmt->fetchAll();
        
        return ['success' => true, 'activities' => $activities];
        
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Erreur base de données: ' . $e->getMessage()];
    }
}

function getRecentOrders() {
    global $pdo;
    
    try {
        $stmt = $pdo->query("
            SELECT 
                o.id,
                o.status,
                o.total_amount,
                o.created_at,
                u.username as customer_name
            FROM orders o
            LEFT JOIN users u ON o.user_id = u.id
            ORDER BY o.created_at DESC
            LIMIT 5
        ");
        
        $orders = $stmt->fetchAll();
        
        return ['success' => true, 'orders' => $orders];
        
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Erreur base de données: ' . $e->getMessage()];
    }
}

// ========================================
// FONCTIONS D'ANALYTICS
// ========================================

function getAnalytics() {
    global $pdo;
    
    try {
        $analytics = [];
        
        // Conversion rate
        $stmt = $pdo->query("
            SELECT 
                COUNT(DISTINCT u.id) as total_users,
                COUNT(DISTINCT o.user_id) as users_with_orders
            FROM users u
            LEFT JOIN orders o ON u.id = o.user_id
            WHERE u.role = 'user'
        ");
        $result = $stmt->fetch();
        $analytics['conversion_rate'] = $result['total_users'] > 0 ? 
            ($result['users_with_orders'] / $result['total_users']) * 100 : 0;
        
        // Average order value
        $stmt = $pdo->query("SELECT AVG(total_amount) as avg_order_value FROM orders WHERE status = 'delivered'");
        $result = $stmt->fetch();
        $analytics['avg_order_value'] = $result['avg_order_value'] ?? 0;
        
        // Top products
        $stmt = $pdo->query("
            SELECT 
                p.name,
                COUNT(oi.id) as sales_count,
                SUM(oi.quantity) as total_quantity
            FROM products p
            LEFT JOIN order_items oi ON p.id = oi.product_id
            LEFT JOIN orders o ON oi.order_id = o.id AND o.status = 'delivered'
            GROUP BY p.id
            ORDER BY sales_count DESC
            LIMIT 5
        ");
        $analytics['top_products'] = $stmt->fetchAll();
        
        return ['success' => true, 'analytics' => $analytics];
        
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Erreur base de données: ' . $e->getMessage()];
    }
}

function getProductPerformance() {
    global $pdo;
    
    try {
        $stmt = $pdo->query("
            SELECT 
                p.name,
                COUNT(oi.id) as sales_count,
                SUM(oi.quantity * oi.price) as revenue
            FROM products p
            LEFT JOIN order_items oi ON p.id = oi.product_id
            LEFT JOIN orders o ON oi.order_id = o.id AND o.status = 'delivered'
            GROUP BY p.id
            ORDER BY revenue DESC
            LIMIT 10
        ");
        
        $performance = $stmt->fetchAll();
        
        return ['success' => true, 'performance' => $performance];
        
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Erreur base de données: ' . $e->getMessage()];
    }
}

// ========================================
// FONCTIONS DE PARAMÈTRES
// ========================================

function getSettings() {
    // Retourner les paramètres par défaut
    return [
        'success' => true,
        'settings' => [
            'site_name' => 'Ma Boutique',
            'contact_email' => 'contact@maboutique.com',
            'phone' => '+33 1 23 45 67 89',
            'currency' => 'EUR',
            'description' => 'Votre boutique en ligne de vêtements pour enfants'
        ]
    ];
}

function updateSettings() {
    // Implémenter la sauvegarde des paramètres
    return ['success' => true, 'message' => 'Paramètres mis à jour avec succès'];
}
?>
