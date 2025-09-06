<?php
// Script de debug pour le panier
session_start();
require_once 'backend/db.php';
require_once 'backend/auth_check.php';

echo "<h2>🔍 Debug du Panier</h2>";

// Vérifier si l'utilisateur est connecté
if (!isLoggedIn()) {
    echo "<h3>❌ Utilisateur non connecté</h3>";
    echo "<p>Vous devez être connecté pour déboguer le panier.</p>";
    echo "<p><a href='login.php'>Se connecter</a></p>";
    exit;
}

$user_id = $_SESSION['user_id'];
echo "<h3>✅ Utilisateur connecté : " . htmlspecialchars($_SESSION['username'] ?? 'Inconnu') . " (ID: {$user_id})</h3>";

try {
    // Test 1: Vérifier le contenu du panier via l'API
    echo "<h3>🔍 Test 1: Contenu du panier via l'API</h3>";
    
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
    
    if (count($cart_items) > 0) {
        echo "<h4>✅ Produits trouvés dans le panier :</h4>";
        
        // Calculer le total
        $total = 0;
        foreach ($cart_items as &$item) {
            $price = $item['promo_price'] && $item['promo_price'] < $item['price'] 
                    ? $item['promo_price'] 
                    : $item['price'];
            $item['total_price'] = $price * $item['quantity'];
            $total += $item['total_price'];
        }
        
        echo "<table border='1' style='border-collapse: collapse; width: 100%; margin-bottom: 20px;'>";
        echo "<tr><th>ID</th><th>Nom</th><th>Catégorie</th><th>Prix</th><th>Quantité</th><th>Total</th><th>Image</th></tr>";
        
        foreach ($cart_items as $item) {
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
        
        echo "<h4>📊 Résumé :</h4>";
        echo "<ul>";
        echo "<li><strong>Nombre d'articles :</strong> " . count($cart_items) . "</li>";
        echo "<li><strong>Total :</strong> " . number_format($total, 2) . " €</li>";
        echo "</ul>";
        
    } else {
        echo "<h4>❌ Aucun produit trouvé dans le panier</h4>";
    }
    
    // Test 2: Vérifier les fichiers JavaScript
    echo "<h3>🔍 Test 2: Vérification des fichiers JavaScript</h3>";
    
    $files_to_check = [
        'cart-manager.js' => 'Gestionnaire du panier',
        'panier.php' => 'Page du panier',
        'backend/cart_api.php' => 'API du panier'
    ];
    
    foreach ($files_to_check as $file => $description) {
        if (file_exists($file)) {
            $size = filesize($file);
            echo "<p>✅ <strong>{$description}</strong> : {$file} ({$size} octets)</p>";
        } else {
            echo "<p>❌ <strong>{$description}</strong> : {$file} - FICHIER MANQUANT !</p>";
        }
    }
    
    // Test 3: Simuler l'appel JavaScript
    echo "<h3>🔍 Test 3: Simulation de l'appel JavaScript</h3>";
    
    echo "<p>Le JavaScript de panier.php devrait faire cet appel :</p>";
    echo "<pre style='background: #f8f9fa; padding: 10px; border-radius: 3px;'>";
    echo "fetch('backend/cart_api.php', {
    method: 'GET',
    credentials: 'same-origin'
})</pre>";
    
    echo "<p><strong>Résultat attendu :</strong></p>";
    echo "<pre style='background: #f8f9fa; padding: 10px; border-radius: 3px;'>";
    echo json_encode([
        'cart_items' => $cart_items,
        'total' => $total ?? 0,
        'item_count' => count($cart_items)
    ], JSON_PRETTY_PRINT);
    echo "</pre>";
    
    // Test 4: Vérifier les erreurs potentielles
    echo "<h3>🔍 Test 4: Erreurs potentielles</h3>";
    
    echo "<h4>🚨 Problèmes courants :</h4>";
    echo "<ul>";
    echo "<li><strong>CartManager non défini :</strong> cart-manager.js ne se charge pas</li>";
    echo "<li><strong>Erreur de timing :</strong> JavaScript exécuté avant chargement complet</li>";
    echo "<li><strong>Erreur de console :</strong> Vérifiez F12 → Console</li>";
    echo "<li><strong>Conflit de code :</strong> Ancien code JavaScript encore présent</li>";
    echo "</ul>";
    
    // Test 5: Actions de débogage
    echo "<h3>🔍 Test 5: Actions de débogage</h3>";
    
    echo "<h4>🎯 Étapes à suivre :</h4>";
    echo "<ol>";
    echo "<li><strong>Ouvrir la console :</strong> Allez sur panier.php et appuyez sur F12</li>";
    echo "<li><strong>Vérifier les erreurs :</strong> Regardez les messages rouges dans Console</li>";
    echo "<li><strong>Tester l'API :</strong> <a href='backend/cart_api.php' target='_blank'>Cliquez ici</a></li>";
    echo "<li><strong>Vérifier CartManager :</strong> <a href='test_cart_javascript.html'>Test JavaScript</a></li>";
    echo "</ol>";
    
    echo "<hr>";
    echo "<h3>🎯 Actions recommandées :</h3>";
    echo "<ul>";
    echo "<li><a href='panier.php'>🛒 Tester panier.php avec F12 ouvert</a></li>";
    echo "<li><a href='test_cart_javascript.html'>🧪 Test complet du JavaScript</a></li>";
    echo "<li><a href='backend/cart_api.php'>🔌 Vérifier l'API directement</a></li>";
    echo "</ul>";
    
} catch (Exception $e) {
    echo "<h3>❌ Erreur :</h3>";
    echo "<p>" . $e->getMessage() . "</p>";
}
?>
