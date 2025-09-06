<?php
// Page de confirmation de commande
session_start();
require_once 'backend/db.php';
require_once 'backend/auth_check.php';

// Vérifier que l'utilisateur est connecté
if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

if (!$order_id) {
    header('Location: index.php');
    exit;
}

// Récupérer les informations de la commande
try {
    $stmt = $pdo->prepare("
        SELECT o.*, oi.*, p.name as product_name, p.image as product_image
        FROM orders o
        JOIN order_items oi ON o.id = oi.order_id
        JOIN products p ON oi.product_id = p.id
        WHERE o.id = ? AND o.user_id = ?
    ");
    $stmt->execute([$order_id, $_SESSION['user_id']]);
    $order_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($order_items)) {
        header('Location: index.php');
        exit;
    }
    
    $order = [
        'id' => $order_items[0]['order_id'],
        'total_amount' => $order_items[0]['total_amount'],
        'status' => $order_items[0]['status'],
        'created_at' => $order_items[0]['created_at']
    ];
    
} catch (Exception $e) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de commande #<?php echo $order_id; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="cart-styles.css">
    <style>
        .confirmation-container {
            min-height: 100vh;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 2rem 0;
        }
        
        .confirmation-card {
            background: white;
            border-radius: 20px;
            padding: 3rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        
        .success-icon {
            font-size: 5rem;
            color: #28a745;
            margin-bottom: 2rem;
        }
        
        .order-number {
            background: linear-gradient(135deg, #1a1a1a 0%, #333 100%);
            color: white;
            padding: 1rem 2rem;
            border-radius: 50px;
            font-size: 1.5rem;
            font-weight: 700;
            display: inline-block;
            margin-bottom: 2rem;
        }
        
        .order-details {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 2rem;
            margin: 2rem 0;
        }
        
        .product-item {
            display: flex;
            align-items: center;
            padding: 1rem;
            border-bottom: 1px solid #e9ecef;
            margin-bottom: 1rem;
        }
        
        .product-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }
        
        .product-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 10px;
            margin-right: 1rem;
        }
        
        .product-info {
            flex: 1;
            text-align: left;
        }
        
        .product-price {
            font-weight: 600;
            color: #28a745;
        }
        
        .total-section {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            border-radius: 15px;
            padding: 2rem;
            margin: 2rem 0;
        }
        
        .total-amount {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .action-buttons {
            margin-top: 3rem;
        }
        
        .btn-action {
            margin: 0.5rem;
            padding: 0.75rem 2rem;
            border-radius: 25px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
        }
        
        .status-badge {
            background: #ffc107;
            color: #1a1a1a;
            padding: 0.5rem 1.5rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <?php include 'backend/navbar.php'; ?>
    
    <div class="confirmation-container">
        <div class="container">
            <div class="confirmation-card">
                <!-- Icône de succès -->
                <div class="success-icon">
                    <i class="bi bi-check-circle"></i>
                </div>
                
                <!-- Titre -->
                <h1 class="mb-4">Commande confirmée !</h1>
                <p class="lead mb-4">Votre commande a été créée avec succès et est en cours de traitement.</p>
                
                <!-- Numéro de commande -->
                <div class="order-number">
                    Commande #<?php echo $order_id; ?>
                </div>
                
                <!-- Statut -->
                <div class="status-badge">
                    <i class="bi bi-clock me-2"></i>En attente de confirmation
                </div>
                
                <!-- Détails de la commande -->
                <div class="order-details">
                    <h4 class="mb-3">Récapitulatif de votre commande</h4>
                    
                    <?php foreach ($order_items as $item): ?>
                    <div class="product-item">
                        <img src="<?php echo htmlspecialchars($item['product_image'] ?: 'https://via.placeholder.com/80x80?text=Produit'); ?>" 
                             alt="<?php echo htmlspecialchars($item['product_name']); ?>" 
                             class="product-image">
                        <div class="product-info">
                            <h6 class="mb-1"><?php echo htmlspecialchars($item['product_name']); ?></h6>
                            <p class="mb-0 text-muted">Quantité : <?php echo $item['quantity']; ?></p>
                        </div>
                        <div class="product-price">
                            <?php echo number_format($item['total_price'], 2); ?> €
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Total -->
                <div class="total-section">
                    <div class="total-amount">Total : <?php echo number_format($order['total_amount'], 2); ?> €</div>
                    <small>Livraison incluse</small>
                </div>
                
                <!-- Informations supplémentaires -->
                <div class="row text-start">
                    <div class="col-md-6">
                        <h6><i class="bi bi-calendar me-2"></i>Date de commande</h6>
                        <p><?php echo date('d/m/Y à H:i', strtotime($order['created_at'])); ?></p>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="bi bi-truck me-2"></i>Livraison estimée</h6>
                        <p>3-5 jours ouvrables</p>
                    </div>
                </div>
                
                <!-- Actions -->
                <div class="action-buttons">
                    <a href="index.php" class="btn btn-outline-primary btn-action">
                        <i class="bi bi-house me-2"></i>Retour à l'accueil
                    </a>
                    <a href="products.php" class="btn btn-primary btn-action">
                        <i class="bi bi-bag me-2"></i>Continuer les achats
                    </a>
                    <a href="commande.php" class="btn btn-success btn-action">
                        <i class="bi bi-list-ul me-2"></i>Voir mes commandes
                    </a>
                </div>
                
                <!-- Message d'information -->
                <div class="alert alert-info mt-4" role="alert">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Important :</strong> Vous recevrez un email de confirmation avec tous les détails de votre commande. 
                    Notre équipe vous contactera bientôt pour confirmer les détails de livraison.
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
