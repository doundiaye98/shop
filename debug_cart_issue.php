<?php
// Script de diagnostic pour le problème du panier
session_start();
require_once 'backend/db.php';
require_once 'backend/auth_check.php';

echo "<h2>🔍 Diagnostic du problème du panier</h2>";

// Vérifier si l'utilisateur est connecté
if (!isLoggedIn()) {
    echo "<h3>❌ Utilisateur non connecté</h3>";
    echo "<p>Vous devez être connecté pour diagnostiquer le panier.</p>";
    echo "<p><a href='login.php'>Se connecter</a></p>";
    exit;
}

$user_id = $_SESSION['user_id'];
echo "<h3>✅ Utilisateur connecté : " . htmlspecialchars($_SESSION['username'] ?? 'Inconnu') . " (ID: {$user_id})</h3>";

try {
    // Test 1: Vérifier l'état actuel du panier
    echo "<h3>🔍 Test 1: État actuel du panier</h3>";
    
    $stmt = $pdo->prepare("SELECT * FROM cart WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $cartItems = $stmt->fetchAll();
    
    if (count($cartItems) > 0) {
        echo "<p>📊 Articles dans le panier : " . count($cartItems) . "</p>";
        echo "<table border='1' style='border-collapse: collapse; width: 100%; margin-bottom: 20px;'>";
        echo "<tr><th>ID Cart</th><th>Product ID</th><th>Quantité</th><th>Created At</th></tr>";
        
        foreach ($cartItems as $item) {
            echo "<tr>";
            echo "<td>{$item['id']}</td>";
            echo "<td>{$item['product_id']}</td>";
            echo "<td>{$item['quantity']}</td>";
            echo "<td>{$item['created_at']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>❌ Aucun article dans le panier</p>";
    }
    
    // Test 2: Vérifier les jointures avec products
    echo "<h3>🔍 Test 2: Test des jointures avec products</h3>";
    
    $stmt = $pdo->prepare("
        SELECT c.id, c.product_id, c.quantity, p.name, p.category, p.price
        FROM cart c
        LEFT JOIN products p ON c.product_id = p.id
        WHERE c.user_id = ?
    ");
    
    $stmt->execute([$user_id]);
    $joinedItems = $stmt->fetchAll();
    
    if (count($joinedItems) > 0) {
        echo "<table border='1' style='border-collapse: collapse; width: 100%; margin-bottom: 20px;'>";
        echo "<tr><th>ID Cart</th><th>Product ID</th><th>Quantité</th><th>Nom Produit</th><th>Catégorie</th><th>Prix</th><th>Statut</th></tr>";
        
        $validItems = 0;
        $corruptedItems = 0;
        
        foreach ($joinedItems as $item) {
            $status = $item['name'] ? "✅ Valide" : "❌ Corrompu";
            $statusColor = $item['name'] ? "green" : "red";
            
            if ($item['name']) {
                $validItems++;
            } else {
                $corruptedItems++;
            }
            
            echo "<tr>";
            echo "<td>{$item['id']}</td>";
            echo "<td>{$item['product_id']}</td>";
            echo "<td>{$item['quantity']}</td>";
            echo "<td>" . ($item['name'] ?: "PRODUIT SUPPRIMÉ") . "</td>";
            echo "<td>" . ($item['category'] ?: "N/A") . "</td>";
            echo "<td>" . ($item['price'] ?: "N/A") . "</td>";
            echo "<td style='color: {$statusColor}; font-weight: bold;'>{$status}</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        echo "<h4>📊 Résumé des jointures :</h4>";
        echo "<ul>";
        echo "<li><strong>Articles valides :</strong> {$validItems}</li>";
        echo "<li><strong>Articles corrompus :</strong> {$corruptedItems}</li>";
        echo "</ul>";
        
        if ($corruptedItems > 0) {
            echo "<p style='color: red; font-weight: bold;'>⚠️ ATTENTION : {$corruptedItems} article(s) corrompu(s) détecté(s) !</p>";
            echo "<p>Ces articles empêchent l'affichage correct du panier.</p>";
        }
        
    } else {
        echo "<p>Aucun article trouvé dans le panier</p>";
    }
    
    // Test 3: Simuler l'API getCart exactement comme dans cart_api.php
    echo "<h3>🔍 Test 3: Simulation exacte de l'API getCart</h3>";
    
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
    $apiResult = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($apiResult) > 0) {
        echo "<h4>✅ API getCart fonctionne - Produits trouvés :</h4>";
        
        // Calculer le total comme dans l'API
        $total = 0;
        foreach ($apiResult as &$item) {
            $price = $item['promo_price'] && $item['promo_price'] < $item['price'] 
                    ? $item['promo_price'] 
                    : $item['price'];
            $item['total_price'] = $price * $item['quantity'];
            $total += $item['total_price'];
        }
        
        echo "<table border='1' style='border-collapse: collapse; width: 100%; margin-bottom: 20px;'>";
        echo "<tr><th>ID</th><th>Nom</th><th>Catégorie</th><th>Prix</th><th>Quantité</th><th>Total</th><th>Image</th></tr>";
        
        foreach ($apiResult as $item) {
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
        
        echo "<h4>📊 Résumé API :</h4>";
        echo "<ul>";
        echo "<li><strong>Nombre d'articles :</strong> " . count($apiResult) . "</li>";
        echo "<li><strong>Total :</strong> " . number_format($total, 2) . " €</li>";
        echo "</ul>";
        
    } else {
        echo "<h4>❌ API getCart ne retourne aucun résultat</h4>";
        echo "<p>Problème : La jointure INNER JOIN échoue car il y a des articles corrompus.</p>";
    }
    
    // Test 4: Vérifier les erreurs JavaScript dans la console
    echo "<h3>🔍 Test 4: Vérification JavaScript</h3>";
    echo "<p>Ouvrez la console de votre navigateur (F12) et vérifiez s'il y a des erreurs JavaScript.</p>";
    echo "<p>Erreurs communes :</p>";
    echo "<ul>";
    echo "<li>❌ Erreurs CORS</li>";
    echo "<li>❌ Erreurs de parsing JSON</li>";
    echo "<li>❌ Erreurs de réseau</li>";
    echo "</ul>";
    
    // Test 5: Tester l'API directement
    echo "<h3>🔍 Test 5: Test direct de l'API</h3>";
    echo "<p><a href='backend/cart_api.php' target='_blank'>🔌 Cliquez ici pour tester l'API directement</a></p>";
    echo "<p>Vous devriez voir du JSON avec vos produits du panier.</p>";
    
    echo "<hr>";
    echo "<h3>🎯 Actions recommandées :</h3>";
    
    if ($corruptedItems > 0) {
        echo "<p style='color: red; font-weight: bold;'>🚨 PRIORITÉ 1 : Nettoyer les articles corrompus</p>";
        echo "<ul>";
        echo "<li><a href='fix_cart_data.php'>🧹 Lancer fix_cart_data.php</a> - Supprime les articles corrompus</li>";
        echo "</ul>";
    }
    
    echo "<p style='color: blue; font-weight: bold;'>📋 PRIORITÉ 2 : Tester après nettoyage</p>";
    echo "<ul>";
    echo "<li><a href='panier.php'>🛒 Tester panier.php</a> - Vérifier l'affichage</li>";
    echo "<li><a href='test_cart_api.php'>🧪 Retester l'API</a> - Confirmer le bon fonctionnement</li>";
    echo "</ul>";
    
} catch (Exception $e) {
    echo "<h3>❌ Erreur :</h3>";
    echo "<p>" . $e->getMessage() . "</p>";
}
?>
