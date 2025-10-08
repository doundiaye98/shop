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
        case 'get_notifications':
            echo json_encode(getNotifications());
            break;
            
        case 'mark_notification_read':
            echo json_encode(markNotificationAsRead());
            break;
            
        case 'mark_all_notifications_read':
            echo json_encode(markAllNotificationsAsRead());
            break;
            
        case 'add_notification':
            echo json_encode(addNotification());
            break;
            
        case 'get_order_details':
            $id = $_GET['id'] ?? 0;
            echo json_encode(getOrderDetails($id));
            break;
            
        case 'get_recent_orders':
            echo json_encode(getRecentOrders());
            break;
            
        case 'get_conversations':
            echo json_encode(getConversations());
            break;
            
        case 'get_conversation_messages':
            $id = $_GET['id'] ?? 0;
            echo json_encode(getConversationMessages($id));
            break;
            
        case 'send_message':
            echo json_encode(sendMessage());
            break;
            
        case 'mark_messages_read':
            echo json_encode(markMessagesAsRead());
            break;
            
        case 'delete_conversation':
            echo json_encode(deleteConversation());
            break;
            
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
            
        case 'get_product_images':
            $productId = $_GET['product_id'] ?? 0;
            echo json_encode(getProductImages($productId));
            break;
            
        case 'remove_product_image':
            $imageId = $_POST['image_id'] ?? 0;
            echo json_encode(removeProductImage($imageId));
            break;
            
        case 'get_online_users':
            echo json_encode(getOnlineUsers());
            break;
            
        case 'get_client_evolution':
            echo json_encode(getClientEvolution());
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
        $stmt = $pdo->query('SELECT COALESCE(SUM(total), 0) as total FROM orders');
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
        $stmt = $pdo->prepare('SELECT COALESCE(SUM(total), 0) as total FROM orders WHERE DATE_FORMAT(created_at, "%Y-%m") = ?');
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
                SUM(total) as total
            FROM orders 
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
                id, name, description, price, promo_price, category, stock, image, created_at, updated_at
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

// Gérer l'ajout/modification d'un produit avec images multiples
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
        
        if ($productId) {
            // Modification
            $stmt = $pdo->prepare('
                UPDATE products 
                SET name = ?, description = ?, category = ?, price = ?, stock = ?, promo_price = ?, updated_at = NOW()
                WHERE id = ?
            ');
            $stmt->execute([$name, $description, $category, $price, $stock, $promoPrice, $productId]);
            $message = 'Produit modifié avec succès';
            $currentProductId = $productId;
        } else {
            // Ajout
            $stmt = $pdo->prepare('
                INSERT INTO products (name, description, category, price, stock, promo_price, created_at, updated_at)
                VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())
            ');
            $stmt->execute([$name, $description, $category, $price, $stock, $promoPrice]);
            $currentProductId = $pdo->lastInsertId();
            $message = 'Produit ajouté avec succès';
        }
        
        // Gestion des images multiples
        $uploadedImages = [];
        $uploadErrors = [];
        
        if (isset($files['images']) && is_array($files['images']['name'])) {
            $uploadDir = 'uploads/products/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            // Vérifier le nombre d'images existantes
            $stmt = $pdo->prepare('SELECT COUNT(*) as count FROM product_images WHERE product_id = ?');
            $stmt->execute([$currentProductId]);
            $existingCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
            
            $imageCount = count(array_filter($files['images']['name']));
            
            if (($existingCount + $imageCount) > 3) {
                return ['success' => false, 'message' => 'Maximum 3 photos par produit. Vous avez déjà ' . $existingCount . ' image(s).'];
            }
            
            for ($i = 0; $i < count($files['images']['name']); $i++) {
                if ($files['images']['error'][$i] === UPLOAD_ERR_OK) {
                    $fileName = uniqid() . '_' . basename($files['images']['name'][$i]);
                    $uploadPath = $uploadDir . $fileName;
                    
                    // Validation du fichier
                    $fileType = mime_content_type($files['images']['tmp_name'][$i]);
                    $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                    
                    if (!in_array($fileType, $allowedTypes)) {
                        $uploadErrors[] = "Format non supporté pour l'image " . ($i + 1);
                        continue;
                    }
                    
                    if ($files['images']['size'][$i] > 2 * 1024 * 1024) { // 2MB max
                        $uploadErrors[] = "Image " . ($i + 1) . " trop volumineuse (max 2MB)";
                        continue;
                    }
                    
                    if (move_uploaded_file($files['images']['tmp_name'][$i], $uploadPath)) {
                        // Ajouter l'image à la table product_images
                        $imageResult = addProductImage($currentProductId, $uploadPath);
                        if ($imageResult['success']) {
                            $uploadedImages[] = $uploadPath;
                        } else {
                            $uploadErrors[] = "Erreur lors de l'ajout de l'image " . ($i + 1) . ": " . $imageResult['message'];
                        }
                    } else {
                        $uploadErrors[] = "Erreur lors de l'upload de l'image " . ($i + 1);
                    }
                }
            }
        }
        
        // Construire le message de retour
        if (!empty($uploadedImages)) {
            $message .= '. ' . count($uploadedImages) . ' image(s) ajoutée(s)';
        }
        
        if (!empty($uploadErrors)) {
            $message .= '. Erreurs: ' . implode(', ', $uploadErrors);
        }
        
        return ['success' => true, 'message' => $message, 'product_id' => $currentProductId];
        
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
        $stmt = $pdo->prepare('SELECT image FROM products WHERE id = ?');
        $stmt->execute([$id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$product) {
            return ['success' => false, 'message' => 'Produit non trouvé'];
        }
        
        // Supprimer le produit
        $stmt = $pdo->prepare('DELETE FROM products WHERE id = ?');
        $stmt->execute([$id]);
        
        // Supprimer l'image si elle existe
        if ($product['image'] && file_exists($product['image'])) {
            unlink($product['image']);
        }
        
        return ['success' => true, 'message' => 'Produit supprimé avec succès'];
        
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Erreur base de données: ' . $e->getMessage()];
    }
}

// Obtenir les images d'un produit
function getProductImages($productId) {
    global $pdo;
    
    try {
        if (!$productId) {
            return ['success' => false, 'message' => 'ID produit requis'];
        }
        
        $stmt = $pdo->prepare('
            SELECT id, image_path, image_order  
            FROM product_images 
            WHERE product_id = ? 
            ORDER BY image_order ASC
        ');
        $stmt->execute([$productId]);
        $images = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return ['success' => true, 'images' => $images];
        
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Erreur base de données: ' . $e->getMessage()];
    }
}

// Supprimer une image de produit
function removeProductImage($imageId) {
    global $pdo;
    
    try {
        if (!$imageId) {
            return ['success' => false, 'message' => 'ID image requis'];
        }
        
        // Récupérer les infos de l'image avant suppression
        $stmt = $pdo->prepare('SELECT image_path FROM product_images WHERE id = ?');
        $stmt->execute([$imageId]);
        $image = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$image) {
            return ['success' => false, 'message' => 'Image non trouvée'];
        }
        
        // Supprimer de la base de données
        $stmt = $pdo->prepare('DELETE FROM product_images WHERE id = ?');
        $stmt->execute([$imageId]);
        
        // Supprimer le fichier physique si il existe
        if ($image['image_path'] && file_exists($image['image_path'])) {
            unlink($image['image_path']);
        }
        
        return ['success' => true, 'message' => 'Image supprimée avec succès'];
        
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Erreur base de données: ' . $e->getMessage()];
    }
}

// Ajouter une image à un produit
function addProductImage($productId, $imageUrl, $imageOrder = null) {
    global $pdo;
    
    try {
        // Vérifier le nombre d'images existantes
        $stmt = $pdo->prepare('SELECT COUNT(*) as count FROM product_images WHERE product_id = ?');
        $stmt->execute([$productId]);
        $currentCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        if ($currentCount >= 3) {
            return ['success' => false, 'message' => 'Maximum 3 photos par produit'];
        }
        
        // Déterminer l'ordre si non spécifié
        if ($imageOrder === null) {
            $stmt = $pdo->prepare('SELECT COALESCE(MAX(image_order), 0) + 1 as next_order FROM product_images WHERE product_id = ?');
            $stmt->execute([$productId]);
            $imageOrder = $stmt->fetch(PDO::FETCH_ASSOC)['next_order'];
        }
        
        // Insérer la nouvelle image
        $stmt = $pdo->prepare('
            INSERT INTO product_images (product_id, image_path, image_order) 
            VALUES (?, ?, ?)
        ');
        $stmt->execute([$productId, $imageUrl, $imageOrder]);
        
        return ['success' => true, 'message' => 'Image ajoutée avec succès', 'image_id' => $pdo->lastInsertId()];
        
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Erreur base de données: ' . $e->getMessage()];
    }
}

// Obtenir l'évolution des clients par mois
function getClientEvolution() {
    global $pdo;
    
    try {
        // Requête simple compatible avec toutes les versions de MySQL
        $stmt = $pdo->query('
            SELECT 
                DATE_FORMAT(created_at, "%Y-%m") as month,
                COUNT(*) as new_clients
            FROM users 
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
            GROUP BY DATE_FORMAT(created_at, "%Y-%m")
            ORDER BY month ASC
        ');
        
        $monthlyData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Créer un tableau associatif pour faciliter la recherche
        $monthlyDataMap = [];
        foreach ($monthlyData as $item) {
            $monthlyDataMap[$item['month']] = (int)$item['new_clients'];
        }
        
        // Générer les données pour les 12 derniers mois
        $labels = [];
        $newClientsData = [];
        $totalClientsData = [];
        $runningTotal = 0;
        
        for ($i = 11; $i >= 0; $i--) {
            $month = date('Y-m', strtotime("-$i month"));
            $monthName = date('M Y', strtotime("-$i month"));
            
            $labels[] = $monthName;
            
            // Récupérer le nombre de nouveaux clients pour ce mois
            $newClients = isset($monthlyDataMap[$month]) ? $monthlyDataMap[$month] : 0;
            $newClientsData[] = $newClients;
            
            // Calculer le total cumulé
            $runningTotal += $newClients;
            $totalClientsData[] = $runningTotal;
        }
        
        return [
            'success' => true,
            'data' => [
                'labels' => $labels,
                'datasets' => [
                    [
                        'label' => 'Nouveaux clients',
                        'data' => $newClientsData,
                        'borderColor' => '#007bff',
                        'backgroundColor' => 'rgba(0, 123, 255, 0.1)',
                        'fill' => true,
                        'tension' => 0.4
                    ],
                    [
                        'label' => 'Total clients',
                        'data' => $totalClientsData,
                        'borderColor' => '#28a745',
                        'backgroundColor' => 'rgba(40, 167, 69, 0.1)',
                        'fill' => false,
                        'tension' => 0.4
                    ]
                ]
            ]
        ];
        
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Erreur base de données: ' . $e->getMessage()];
    }
}

// ========================================
// FONCTIONS DE GESTION DES NOTIFICATIONS
// ========================================

// Obtenir les notifications
function getNotifications() {
    global $pdo;
    
    try {
        // Créer la table notifications si elle n'existe pas
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS notifications (
                id INT PRIMARY KEY AUTO_INCREMENT,
                title VARCHAR(255) NOT NULL,
                message TEXT NOT NULL,
                type ENUM('info', 'success', 'warning', 'error') DEFAULT 'info',
                read_status TINYINT(1) DEFAULT 0,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_read_status (read_status),
                INDEX idx_created_at (created_at)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
        
        $stmt = $pdo->query("
            SELECT 
                id,
                title,
                message,
                type,
                read_status as read,
                created_at,
                CASE 
                    WHEN created_at >= DATE_SUB(NOW(), INTERVAL 1 MINUTE) THEN 'Il y a moins d\'une minute'
                    WHEN created_at >= DATE_SUB(NOW(), INTERVAL 1 HOUR) THEN CONCAT('Il y a ', TIMESTAMPDIFF(MINUTE, created_at, NOW()), ' minutes')
                    WHEN created_at >= DATE_SUB(NOW(), INTERVAL 1 DAY) THEN CONCAT('Il y a ', TIMESTAMPDIFF(HOUR, created_at, NOW()), ' heures')
                    ELSE CONCAT('Il y a ', TIMESTAMPDIFF(DAY, created_at, NOW()), ' jours')
                END as time
            FROM notifications 
            ORDER BY created_at DESC 
            LIMIT 50
        ");
        
        $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return [
            'success' => true,
            'notifications' => $notifications
        ];
        
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Erreur base de données: ' . $e->getMessage()];
    }
}

// Marquer une notification comme lue
function markNotificationAsRead() {
    global $pdo;
    
    try {
        $input = json_decode(file_get_contents('php://input'), true);
        $notificationId = $input['notification_id'] ?? 0;
        
        if (!$notificationId) {
            return ['success' => false, 'message' => 'ID de notification manquant'];
        }
        
        $stmt = $pdo->prepare("UPDATE notifications SET read_status = 1 WHERE id = ?");
        $stmt->execute([$notificationId]);
        
        return ['success' => true, 'message' => 'Notification marquée comme lue'];
        
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Erreur base de données: ' . $e->getMessage()];
    }
}

// Marquer toutes les notifications comme lues
function markAllNotificationsAsRead() {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("UPDATE notifications SET read_status = 1 WHERE read_status = 0");
        $stmt->execute();
        
        return ['success' => true, 'message' => 'Toutes les notifications marquées comme lues'];
        
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Erreur base de données: ' . $e->getMessage()];
    }
}

// Ajouter une notification
function addNotification() {
    global $pdo;
    
    try {
        $input = json_decode(file_get_contents('php://input'), true);
        $title = $input['title'] ?? '';
        $message = $input['message'] ?? '';
        $type = $input['type'] ?? 'info';
        
        if (!$title || !$message) {
            return ['success' => false, 'message' => 'Titre et message requis'];
        }
        
        $stmt = $pdo->prepare("
            INSERT INTO notifications (title, message, type) 
            VALUES (?, ?, ?)
        ");
        $stmt->execute([$title, $message, $type]);
        
        return ['success' => true, 'message' => 'Notification ajoutée', 'id' => $pdo->lastInsertId()];
        
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Erreur base de données: ' . $e->getMessage()];
    }
}

// Fonction utilitaire pour ajouter une notification depuis d'autres parties du système
function createNotification($title, $message, $type = 'info') {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("
            INSERT INTO notifications (title, message, type) 
            VALUES (?, ?, ?)
        ");
        $stmt->execute([$title, $message, $type]);
        return $pdo->lastInsertId();
    } catch (PDOException $e) {
        error_log("Erreur création notification: " . $e->getMessage());
        return false;
    }
}

// ========================================
// FONCTIONS DE GESTION DES COMMANDES
// ========================================

// Obtenir les détails d'une commande
function getOrderDetails($orderId) {
    global $pdo;
    
    try {
        if (!$orderId) {
            return ['success' => false, 'message' => 'ID de commande manquant'];
        }
        
        // Récupérer les détails de la commande
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
        $stmt->execute([$orderId]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$order) {
            return ['success' => false, 'message' => 'Commande non trouvée'];
        }
        
        // Récupérer les articles de la commande
        $stmt = $pdo->prepare("
            SELECT 
                oi.*,
                p.name as product_name,
                p.image_url
            FROM order_items oi
            LEFT JOIN products p ON oi.product_id = p.id
            WHERE oi.order_id = ?
            ORDER BY oi.id
        ");
        $stmt->execute([$orderId]);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $order['items'] = $items;
        
        return [
            'success' => true,
            'order' => $order
        ];
        
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Erreur base de données: ' . $e->getMessage()];
    }
}

// Obtenir les commandes récentes
function getRecentOrders() {
    global $pdo;
    
    try {
        $stmt = $pdo->query("
            SELECT 
                o.*,
                u.username as customer_name
            FROM orders o
            LEFT JOIN users u ON o.user_id = u.id
            ORDER BY o.created_at DESC
            LIMIT 10
        ");
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return [
            'success' => true,
            'orders' => $orders
        ];
        
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Erreur base de données: ' . $e->getMessage()];
    }
}

// ========================================
// FONCTIONS DE GESTION DE LA MESSAGERIE
// ========================================

// Obtenir toutes les conversations
function getConversations() {
    global $pdo;
    
    try {
        $stmt = $pdo->query("
            SELECT 
                c.*,
                u.username as customer_name,
                u.email as customer_email,
                (SELECT COUNT(*) FROM messages WHERE conversation_id = c.id AND is_read = 0 AND sender_id != {$_SESSION['user_id']}) as unread_count,
                (SELECT message FROM messages WHERE conversation_id = c.id ORDER BY created_at DESC LIMIT 1) as last_message
            FROM conversations c
            LEFT JOIN users u ON c.user_id = u.id
            ORDER BY c.last_message_at DESC
        ");
        $conversations = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return [
            'success' => true,
            'conversations' => $conversations
        ];
        
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Erreur base de données: ' . $e->getMessage()];
    }
}

// Obtenir les messages d'une conversation
function getConversationMessages($conversationId) {
    global $pdo;
    
    try {
        if (!$conversationId) {
            return ['success' => false, 'message' => 'ID de conversation manquant'];
        }
        
        // Récupérer les détails de la conversation
        $stmt = $pdo->prepare("
            SELECT 
                c.*,
                u.username as customer_name,
                u.email as customer_email
            FROM conversations c
            LEFT JOIN users u ON c.user_id = u.id
            WHERE c.id = ?
        ");
        $stmt->execute([$conversationId]);
        $conversation = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$conversation) {
            return ['success' => false, 'message' => 'Conversation non trouvée'];
        }
        
        // Récupérer les messages
        $stmt = $pdo->prepare("
            SELECT 
                m.*,
                u.username as sender_name,
                u.role as sender_role
            FROM messages m
            LEFT JOIN users u ON m.sender_id = u.id
            WHERE m.conversation_id = ?
            ORDER BY m.created_at ASC
        ");
        $stmt->execute([$conversationId]);
        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return [
            'success' => true,
            'conversation' => $conversation,
            'messages' => $messages
        ];
        
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Erreur base de données: ' . $e->getMessage()];
    }
}

// Envoyer un message
function sendMessage() {
    global $pdo;
    
    try {
        $input = json_decode(file_get_contents('php://input'), true);
        $conversationId = $input['conversation_id'] ?? 0;
        $message = $input['message'] ?? '';
        
        if (!$conversationId || empty($message)) {
            return ['success' => false, 'message' => 'Données manquantes'];
        }
        
        // Vérifier que la conversation existe
        $stmt = $pdo->prepare("SELECT id FROM conversations WHERE id = ?");
        $stmt->execute([$conversationId]);
        if (!$stmt->fetch()) {
            return ['success' => false, 'message' => 'Conversation non trouvée'];
        }
        
        // Insérer le message
        $stmt = $pdo->prepare("
            INSERT INTO messages (conversation_id, sender_id, message) 
            VALUES (?, ?, ?)
        ");
        $stmt->execute([$conversationId, $_SESSION['user_id'], $message]);
        
        // Mettre à jour la date du dernier message
        $stmt = $pdo->prepare("UPDATE conversations SET last_message_at = NOW() WHERE id = ?");
        $stmt->execute([$conversationId]);
        
        return ['success' => true, 'message' => 'Message envoyé'];
        
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Erreur base de données: ' . $e->getMessage()];
    }
}

// Marquer les messages comme lus
function markMessagesAsRead() {
    global $pdo;
    
    try {
        $input = json_decode(file_get_contents('php://input'), true);
        $conversationId = $input['conversation_id'] ?? 0;
        
        if (!$conversationId) {
            return ['success' => false, 'message' => 'ID de conversation manquant'];
        }
        
        // Marquer tous les messages non lus de la conversation comme lus (sauf ceux de l'admin)
        $stmt = $pdo->prepare("
            UPDATE messages 
            SET is_read = 1 
            WHERE conversation_id = ? 
            AND sender_id != ? 
            AND is_read = 0
        ");
        $stmt->execute([$conversationId, $_SESSION['user_id']]);
        
        return ['success' => true, 'message' => 'Messages marqués comme lus'];
        
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Erreur base de données: ' . $e->getMessage()];
    }
}

// Supprimer une conversation
function deleteConversation() {
    global $pdo;
    
    try {
        $input = json_decode(file_get_contents('php://input'), true);
        $conversationId = $input['conversation_id'] ?? 0;
        
        if (!$conversationId) {
            return ['success' => false, 'message' => 'ID de conversation manquant'];
        }
        
        // Supprimer la conversation (les messages seront supprimés en cascade)
        $stmt = $pdo->prepare("DELETE FROM conversations WHERE id = ?");
        $stmt->execute([$conversationId]);
        
        return ['success' => true, 'message' => 'Conversation supprimée'];
        
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Erreur base de données: ' . $e->getMessage()];
    }
}
?>
