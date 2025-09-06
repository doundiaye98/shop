<?php
// API d'administration pour le tableau de bord
session_start();
header('Content-Type: application/json');

// Vérifier si l'utilisateur est connecté et est admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Accès non autorisé']);
    exit;
}

require_once 'backend/db.php';

$action = $_GET['action'] ?? $_POST['action'] ?? '';

try {
    switch ($action) {
        case 'get_stats':
            echo json_encode(getDashboardStats());
            break;
            
        case 'get_users':
            echo json_encode(getUsers());
            break;
            
        case 'get_user':
            $id = $_GET['id'] ?? 0;
            echo json_encode(getUser($id));
            break;
            
        case 'get_user_details':
            $id = $_GET['id'] ?? 0;
            echo json_encode(getUserDetails($id));
            break;
            
        case 'get_products':
            echo json_encode(getProducts());
            break;
            
        case 'get_product':
            $id = $_GET['id'] ?? 0;
            echo json_encode(getProduct($id));
            break;
            
        case 'get_online_users':
            echo json_encode(getOnlineUsers());
            break;
            
        case 'add_user':
        case 'update_user':
            echo json_encode(handleUser($_POST));
            break;
            
        case 'add_product':
        case 'update_product':
            echo json_encode(handleProduct($_POST, $_FILES));
            break;
            
        case 'delete_user':
            $id = $_POST['id'] ?? 0;
            echo json_encode(deleteUser($id));
            break;
            
        case 'delete_product':
            $id = $_POST['id'] ?? 0;
            echo json_encode(deleteProduct($id));
            break;
            
        default:
            echo json_encode(['success' => false, 'message' => 'Action non reconnue']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Erreur: ' . $e->getMessage()]);
}

// Obtenir les statistiques du tableau de bord
function getDashboardStats() {
    global $pdo;
    
    try {
        // Statistiques de base
        $stats = [];
        
        // Total des utilisateurs
        $stmt = $pdo->query('SELECT COUNT(*) as total FROM users');
        $stats['total_users'] = $stmt->fetch()['total'];
        
        // Total des produits
        $stmt = $pdo->query('SELECT COUNT(*) as total FROM products');
        $stats['total_products'] = $stmt->fetch()['total'];
        
        // Total des commandes
        $stmt = $pdo->query('SELECT COUNT(*) as total FROM orders');
        $stats['total_orders'] = $stmt->fetch()['total'];
        
        // Total des revenus
        $stmt = $pdo->query('SELECT COALESCE(SUM(total_amount), 0) as total FROM orders WHERE status IN ("delivered", "shipped")');
        $stats['total_revenue'] = $stmt->fetch()['total'];
        
        // Calculer les pourcentages de changement (comparaison avec le mois précédent)
        $currentMonth = date('Y-m');
        $lastMonth = date('Y-m', strtotime('-1 month'));
        
        // Changement des utilisateurs
        $stmt = $pdo->prepare('SELECT COUNT(*) as count FROM users WHERE DATE_FORMAT(created_at, "%Y-%m") = ?');
        $stmt->execute([$currentMonth]);
        $currentUsers = $stmt->fetch()['count'];
        
        $stmt->execute([$lastMonth]);
        $lastUsers = $stmt->fetch()['count'];
        
        $stats['users_change'] = $lastUsers > 0 ? round((($currentUsers - $lastUsers) / $lastUsers) * 100, 1) : 0;
        
        // Changement des produits
        $stmt = $pdo->prepare('SELECT COUNT(*) as count FROM products WHERE DATE_FORMAT(created_at, "%Y-%m") = ?');
        $stmt->execute([$currentMonth]);
        $currentProducts = $stmt->fetch()['count'];
        
        $stmt->execute([$lastMonth]);
        $lastProducts = $stmt->fetch()['count'];
        
        $stats['products_change'] = $lastProducts > 0 ? round((($currentProducts - $lastProducts) / $lastProducts) * 100, 1) : 0;
        
        // Changement des commandes
        $stmt = $pdo->prepare('SELECT COUNT(*) as count FROM orders WHERE DATE_FORMAT(created_at, "%Y-%m") = ?');
        $stmt->execute([$currentMonth]);
        $currentOrders = $stmt->fetch()['count'];
        
        $stmt->execute([$lastMonth]);
        $lastOrders = $stmt->fetch()['count'];
        
        $stats['orders_change'] = $lastOrders > 0 ? round((($currentOrders - $lastOrders) / $lastOrders) * 100, 1) : 0;
        
        // Changement des revenus
        $stmt = $pdo->prepare('SELECT COALESCE(SUM(total_amount), 0) as total FROM orders WHERE DATE_FORMAT(created_at, "%Y-%m") = ? AND status IN ("delivered", "shipped")');
        $stmt->execute([$currentMonth]);
        $currentRevenue = $stmt->fetch()['total'];
        
        $stmt->execute([$lastMonth]);
        $lastRevenue = $stmt->fetch()['total'];
        
        $stats['revenue_change'] = $lastRevenue > 0 ? round((($currentRevenue - $lastRevenue) / $lastRevenue) * 100, 1) : 0;
        
        // Données pour les graphiques
        $stats['sales_data'] = getSalesData();
        $stats['category_data'] = getCategoryData();
        
        return ['success' => true, 'stats' => $stats];
        
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Erreur base de données: ' . $e->getMessage()];
    }
}

// Obtenir les données de vente pour le graphique
function getSalesData() {
    global $pdo;
    
    try {
        $stmt = $pdo->query('
            SELECT 
                DATE_FORMAT(created_at, "%Y-%m") as month,
                SUM(total_amount) as total
            FROM orders 
            WHERE status IN ("delivered", "shipped")
            GROUP BY DATE_FORMAT(created_at, "%Y-%m")
            ORDER BY month DESC
            LIMIT 12
        ');
        
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $labels = [];
        $values = [];
        
        foreach (array_reverse($data) as $row) {
            $labels[] = date('M Y', strtotime($row['month'] . '-01'));
            $values[] = $row['total'];
        }
        
        return ['labels' => $labels, 'values' => $values];
        
    } catch (PDOException $e) {
        return ['labels' => [], 'values' => []];
    }
}

// Obtenir les données des catégories pour le graphique
function getCategoryData() {
    global $pdo;
    
    try {
        $stmt = $pdo->query('
            SELECT 
                category,
                COUNT(*) as count
            FROM products 
            GROUP BY category
            ORDER BY count DESC
        ');
        
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $labels = [];
        $values = [];
        
        foreach ($data as $row) {
            $labels[] = ucfirst($row['category']);
            $values[] = $row['count'];
        }
        
        return ['labels' => $labels, 'values' => $values];
        
    } catch (PDOException $e) {
        return ['labels' => [], 'values' => []];
    }
}

// Obtenir la liste des utilisateurs
function getUsers() {
    global $pdo;
    
    try {
        $stmt = $pdo->query('
            SELECT 
                u.id, u.username, u.email, u.first_name, u.last_name, u.role, u.is_active, u.last_login, u.created_at,
                CASE WHEN us.id IS NOT NULL THEN 1 ELSE 0 END as is_online
            FROM users u
            LEFT JOIN user_sessions us ON u.id = us.user_id AND us.end_time IS NULL
            ORDER BY u.created_at DESC
        ');
        
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return ['success' => true, 'users' => $users];
        
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Erreur base de données: ' . $e->getMessage()];
    }
}

// Obtenir un utilisateur spécifique
function getUser($id) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->execute([$id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$user) {
            return ['success' => false, 'message' => 'Utilisateur non trouvé'];
        }
        
        return ['success' => true, 'user' => $user];
        
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Erreur base de données: ' . $e->getMessage()];
    }
}

// Obtenir les détails complets d'un utilisateur
function getUserDetails($id) {
    global $pdo;
    
    try {
        // Informations de base
        $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->execute([$id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$user) {
            return ['success' => false, 'message' => 'Utilisateur non trouvé'];
        }
        
        // Vérifier si en ligne
        $stmt = $pdo->prepare('SELECT COUNT(*) as count FROM user_sessions WHERE user_id = ? AND end_time IS NULL');
        $stmt->execute([$id]);
        $user['is_online'] = $stmt->fetch()['count'] > 0;
        
        // Statistiques des sessions
        $stmt = $pdo->prepare('
            SELECT 
                COUNT(*) as total_sessions,
                COALESCE(SUM(TIMESTAMPDIFF(MINUTE, start_time, COALESCE(end_time, NOW()))), 0) as total_time
            FROM user_sessions 
            WHERE user_id = ?
        ');
        $stmt->execute([$id]);
        $sessionStats = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $user['total_sessions'] = $sessionStats['total_sessions'];
        $user['total_time'] = $sessionStats['total_time'];
        
        // Sessions récentes
        $stmt = $pdo->prepare('
            SELECT 
                start_time, end_time, ip_address,
                TIMESTAMPDIFF(MINUTE, start_time, COALESCE(end_time, NOW())) as duration
            FROM user_sessions 
            WHERE user_id = ?
            ORDER BY start_time DESC
            LIMIT 10
        ');
        $stmt->execute([$id]);
        $user['sessions'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return ['success' => true, 'user' => $user];
        
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Erreur base de données: ' . $e->getMessage()];
    }
}

// Obtenir la liste des produits
function getProducts() {
    global $pdo;
    
    try {
        $stmt = $pdo->query('
            SELECT 
                id, name, description, price, promo_price, category, stock, image_url, created_at, updated_at
            FROM products 
            ORDER BY created_at DESC
        ');
        
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return ['success' => true, 'products' => $products];
        
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Erreur base de données: ' . $e->getMessage()];
    }
}

// Obtenir un produit spécifique
function getProduct($id) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
        $stmt->execute([$id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$product) {
            return ['success' => false, 'message' => 'Produit non trouvé'];
        }
        
        return ['success' => true, 'product' => $product];
        
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Erreur base de données: ' . $e->getMessage()];
    }
}

// Obtenir les utilisateurs en ligne
function getOnlineUsers() {
    global $pdo;
    
    try {
        $stmt = $pdo->query('
            SELECT 
                u.id, u.username, u.first_name, u.last_name,
                TIMESTAMPDIFF(MINUTE, us.start_time, NOW()) as session_duration
            FROM users u
            INNER JOIN user_sessions us ON u.id = us.user_id
            WHERE us.end_time IS NULL
            ORDER BY us.start_time ASC
        ');
        
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return ['success' => true, 'users' => $users];
        
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Erreur base de données: ' . $e->getMessage()];
    }
}

// Gérer l'ajout/modification d'un utilisateur
function handleUser($data) {
    global $pdo;
    
    try {
        $userId = $data['user_id'] ?? null;
        $username = $data['username'] ?? '';
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';
        $firstName = $data['first_name'] ?? '';
        $lastName = $data['last_name'] ?? '';
        $role = $data['role'] ?? 'user';
        $isActive = isset($data['is_active']) ? 1 : 0;
        
        // Validation
        if (empty($username) || empty($email)) {
            return ['success' => false, 'message' => 'Nom d\'utilisateur et email requis'];
        }
        
        if (empty($password) && !$userId) {
            return ['success' => false, 'message' => 'Mot de passe requis pour un nouvel utilisateur'];
        }
        
        // Vérifier si l'email existe déjà
        $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ? AND id != ?');
        $stmt->execute([$email, $userId ?? 0]);
        if ($stmt->fetch()) {
            return ['success' => false, 'message' => 'Cet email est déjà utilisé'];
        }
        
        // Vérifier si le nom d'utilisateur existe déjà
        $stmt = $pdo->prepare('SELECT id FROM users WHERE username = ? AND id != ?');
        $stmt->execute([$username, $userId ?? 0]);
        if ($stmt->fetch()) {
            return ['success' => false, 'message' => 'Ce nom d\'utilisateur est déjà utilisé'];
        }
        
        if ($userId) {
            // Modification
            if (!empty($password)) {
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare('
                    UPDATE users 
                    SET username = ?, email = ?, password = ?, first_name = ?, last_name = ?, role = ?, is_active = ?, updated_at = NOW()
                    WHERE id = ?
                ');
                $stmt->execute([$username, $email, $passwordHash, $firstName, $lastName, $role, $isActive, $userId]);
            } else {
                $stmt = $pdo->prepare('
                    UPDATE users 
                    SET username = ?, email = ?, first_name = ?, last_name = ?, role = ?, is_active = ?, updated_at = NOW()
                    WHERE id = ?
                ');
                $stmt->execute([$username, $email, $firstName, $lastName, $role, $isActive, $userId]);
            }
            
            $message = 'Utilisateur modifié avec succès';
        } else {
            // Ajout
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('
                INSERT INTO users (username, email, password, first_name, last_name, role, is_active, created_at, updated_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
            ');
            $stmt->execute([$username, $email, $passwordHash, $firstName, $lastName, $role, $isActive]);
            
            $message = 'Utilisateur ajouté avec succès';
        }
        
        return ['success' => true, 'message' => $message];
        
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Erreur base de données: ' . $e->getMessage()];
    }
}

// Gérer l'ajout/modification d'un produit
function handleProduct($data, $files) {
    global $pdo;
    
    try {
        $productId = $data['product_id'] ?? null;
        $name = $data['name'] ?? '';
        $description = $data['description'] ?? '';
        $category = $data['category'] ?? '';
        $price = $data['price'] ?? 0;
        $stock = $data['stock'] ?? 0;
        $promoPrice = $data['promo_price'] ?? null;
        
        // Validation
        if (empty($name) || empty($category) || $price <= 0) {
            return ['success' => false, 'message' => 'Nom, catégorie et prix requis'];
        }
        
        // Gestion de l'image
        $imageUrl = null;
        if (isset($files['image']) && $files['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'uploads/products/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $fileName = uniqid() . '_' . basename($files['image']['name']);
            $uploadPath = $uploadDir . $fileName;
            
            if (move_uploaded_file($files['image']['tmp_name'], $uploadPath)) {
                $imageUrl = $uploadPath;
            }
        }
        
        if ($productId) {
            // Modification
            if ($imageUrl) {
                $stmt = $pdo->prepare('
                    UPDATE products 
                    SET name = ?, description = ?, category = ?, price = ?, stock = ?, promo_price = ?, image_url = ?, updated_at = NOW()
                    WHERE id = ?
                ');
                $stmt->execute([$name, $description, $category, $price, $stock, $promoPrice, $imageUrl, $productId]);
            } else {
                $stmt = $pdo->prepare('
                    UPDATE products 
                    SET name = ?, description = ?, category = ?, price = ?, stock = ?, promo_price = ?, updated_at = NOW()
                    WHERE id = ?
                ');
                $stmt->execute([$name, $description, $category, $price, $stock, $promoPrice, $productId]);
            }
            
            $message = 'Produit modifié avec succès';
        } else {
            // Ajout
            $stmt = $pdo->prepare('
                INSERT INTO products (name, description, category, price, stock, promo_price, image_url, created_at, updated_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
            ');
            $stmt->execute([$name, $description, $category, $price, $stock, $promoPrice, $imageUrl]);
            
            $message = 'Produit ajouté avec succès';
        }
        
        return ['success' => true, 'message' => $message];
        
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Erreur base de données: ' . $e->getMessage()];
    }
}

// Supprimer un utilisateur
function deleteUser($id) {
    global $pdo;
    
    try {
        // Vérifier que l'utilisateur n'est pas l'admin principal
        $stmt = $pdo->prepare('SELECT role FROM users WHERE id = ?');
        $stmt->execute([$id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$user) {
            return ['success' => false, 'message' => 'Utilisateur non trouvé'];
        }
        
        if ($user['role'] === 'admin') {
            return ['success' => false, 'message' => 'Impossible de supprimer un administrateur'];
        }
        
        // Supprimer les sessions de l'utilisateur
        $stmt = $pdo->prepare('DELETE FROM user_sessions WHERE user_id = ?');
        $stmt->execute([$id]);
        
        // Supprimer l'utilisateur
        $stmt = $pdo->prepare('DELETE FROM users WHERE id = ?');
        $stmt->execute([$id]);
        
        return ['success' => true, 'message' => 'Utilisateur supprimé avec succès'];
        
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Erreur base de données: ' . $e->getMessage()];
    }
}

// Supprimer un produit
function deleteProduct($id) {
    global $pdo;
    
    try {
        // Récupérer l'image avant suppression
        $stmt = $pdo->prepare('SELECT image_url FROM products WHERE id = ?');
        $stmt->execute([$id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$product) {
            return ['success' => false, 'message' => 'Produit non trouvé'];
        }
        
        // Supprimer le produit
        $stmt = $pdo->prepare('DELETE FROM products WHERE id = ?');
        $stmt->execute([$id]);
        
        // Supprimer l'image si elle existe
        if ($product['image_url'] && file_exists($product['image_url'])) {
            unlink($product['image_url']);
        }
        
        return ['success' => true, 'message' => 'Produit supprimé avec succès'];
        
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Erreur base de données: ' . $e->getMessage()];
    }
}
?>
