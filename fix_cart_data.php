<?php
// Script pour nettoyer les données corrompues du panier
session_start();
require_once 'backend/db.php';
require_once 'backend/auth_check.php';

echo "<h2>🧹 Nettoyage des données du panier</h2>";

// Vérifier si l'utilisateur est connecté
if (!isLoggedIn()) {
    echo "<h3>❌ Utilisateur non connecté</h3>";
    echo "<p>Vous devez être connecté pour nettoyer le panier.</p>";
    echo "<p><a href='login.php'>Se connecter</a></p>";
    exit;
}

$user_id = $_SESSION['user_id'];
echo "<h3>✅ Utilisateur connecté : " . htmlspecialchars($_SESSION['username'] ?? 'Inconnu') . " (ID: {$user_id})</h3>";

try {
    // Étape 1: Identifier les articles corrompus
    echo "<h3>🔍 Étape 1: Identification des articles corrompus</h3>";
    
    $stmt = $pdo->prepare("
        SELECT c.id, c.product_id, c.quantity, p.name, p.category
        FROM cart c
        LEFT JOIN products p ON c.product_id = p.id
        WHERE c.user_id = ?
        ORDER BY c.created_at DESC
    ");
    
    $stmt->execute([$user_id]);
    $cartItems = $stmt->fetchAll();
    
    if (count($cartItems) > 0) {
        echo "<table border='1' style='border-collapse: collapse; width: 100%; margin-bottom: 20px;'>";
        echo "<tr><th>ID Cart</th><th>Product ID</th><th>Quantité</th><th>Nom Produit</th><th>Catégorie</th><th>Statut</th></tr>";
        
        $corruptedItems = [];
        foreach ($cartItems as $item) {
            $status = $item['name'] ? "✅ Valide" : "❌ Corrompu";
            $statusColor = $item['name'] ? "green" : "red";
            
            echo "<tr>";
            echo "<td>{$item['id']}</td>";
            echo "<td>{$item['product_id']}</td>";
            echo "<td>{$item['quantity']}</td>";
            echo "<td>" . ($item['name'] ?: "PRODUIT SUPPRIMÉ") . "</td>";
            echo "<td>" . ($item['category'] ?: "N/A") . "</td>";
            echo "<td style='color: {$statusColor}; font-weight: bold;'>{$status}</td>";
            echo "</tr>";
            
            if (!$item['name']) {
                $corruptedItems[] = $item['id'];
            }
        }
        echo "</table>";
        
        // Étape 2: Supprimer les articles corrompus
        if (count($corruptedItems) > 0) {
            echo "<h3>🗑️ Étape 2: Suppression des articles corrompus</h3>";
            echo "<p>Articles corrompus détectés : " . count($corruptedItems) . "</p>";
            
            $placeholders = str_repeat('?,', count($corruptedItems) - 1) . '?';
            $stmt = $pdo->prepare("DELETE FROM cart WHERE id IN ($placeholders)");
            $result = $stmt->execute($corruptedItems);
            
            if ($result) {
                echo "<p style='color: green; font-weight: bold;'>✅ Articles corrompus supprimés avec succès !</p>";
            } else {
                echo "<p style='color: red; font-weight: bold;'>❌ Erreur lors de la suppression</p>";
            }
        } else {
            echo "<p style='color: green; font-weight: bold;'>✅ Aucun article corrompu détecté !</p>";
        }
        
        // Étape 3: Vérifier l'état final du panier
        echo "<h3>🔍 Étape 3: État final du panier</h3>";
        
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
        $finalCart = $stmt->fetchAll();
        
        if (count($finalCart) > 0) {
            echo "<h4>✅ Produits valides dans le panier :</h4>";
            
            // Calculer le total
            $total = 0;
            foreach ($finalCart as &$item) {
                $price = $item['promo_price'] && $item['promo_price'] < $item['price'] 
                        ? $item['promo_price'] 
                        : $item['price'];
                $item['total_price'] = $price * $item['quantity'];
                $total += $item['total_price'];
            }
            
            echo "<table border='1' style='border-collapse: collapse; width: 100%; margin-bottom: 20px;'>";
            echo "<tr><th>ID</th><th>Nom</th><th>Catégorie</th><th>Prix</th><th>Quantité</th><th>Total</th><th>Image</th></tr>";
            
            foreach ($finalCart as $item) {
                echo "<tr>";
                echo "<td>{$item['product_id']}</td>";
                echo "<td>{$item['name']}</td>";
                echo "<td>{$item['category']}</td>";
                echo "<td>{$item['price']} €</td>";
                echo "<td>{$item['quantity']}</td>";
                echo "<td>{$item['total_price']} €</td>";
                echo "<td>{$item['image']}</td>";
                echo "</tr>";
            }
            echo "</table>";
            
            echo "<h4>📊 Résumé final :</h4>";
            echo "<ul>";
            echo "<li><strong>Nombre d'articles :</strong> " . count($finalCart) . "</li>";
            echo "<li><strong>Total :</strong> " . number_format($total, 2) . " €</li>";
            echo "</ul>";
            
        } else {
            echo "<h4>❌ Panier vide après nettoyage</h4>";
            echo "<p>Votre panier a été vidé car il ne contenait que des articles corrompus.</p>";
        }
        
    } else {
        echo "<p>Aucun article dans le panier.</p>";
    }
    
    echo "<hr>";
    echo "<h3>🎯 Actions recommandées :</h3>";
    echo "<ul>";
    echo "<li><a href='index.php'>🏠 Retour à l'accueil</a> - Ajoutez des produits valides au panier</li>";
    echo "<li><a href='panier.php'>🛒 Testez la page panier</a> - Vérifiez l'affichage</li>";
    echo "<li><a href='test_cart_api.php'>🧪 Retestez l'API</a> - Vérifiez que tout fonctionne</li>";
    echo "</ul>";
    
} catch (Exception $e) {
    echo "<h3>❌ Erreur :</h3>";
    echo "<p>" . $e->getMessage() . "</p>";
}
?>
