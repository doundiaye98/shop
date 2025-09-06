<?php
// Script pour supprimer des produits spécifiques
require_once 'backend/db.php';

echo "<h2>🗑️ Suppression des produits spécifiques</h2>";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Connexion à la base de données réussie<br><br>";
    
    // Produits à supprimer
    $productsToDelete = [
        'T-shirt Bébé Bio',
        'Robe Fille Élégante'
    ];
    
    echo "🎯 Produits à supprimer :<br>";
    foreach ($productsToDelete as $productName) {
        echo "- $productName<br>";
    }
    echo "<br>";
    
    // Vérifier l'existence des produits
    echo "🔍 Vérification de l'existence des produits :<br>";
    foreach ($productsToDelete as $productName) {
        $stmt = $pdo->prepare("SELECT id, name, category, price FROM products WHERE name = ?");
        $stmt->execute([$productName]);
        $product = $stmt->fetch();
        
        if ($product) {
            echo "✅ Trouvé : {$product['name']} (ID: {$product['id']}) - {$product['category']} - {$product['price']} €<br>";
        } else {
            echo "❌ Non trouvé : $productName<br>";
        }
    }
    echo "<br>";
    
    // Demander confirmation
    echo "<form method='post'>";
    echo "<input type='submit' name='confirm' value='🗑️ Confirmer la suppression' style='background: #dc3545; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;'>";
    echo "</form>";
    
    if (isset($_POST['confirm'])) {
        echo "<br>🔄 Suppression en cours...<br><br>";
        
        $deletedCount = 0;
        
        foreach ($productsToDelete as $productName) {
            $stmt = $pdo->prepare("DELETE FROM products WHERE name = ?");
            $result = $stmt->execute([$productName]);
            
            if ($result && $stmt->rowCount() > 0) {
                echo "✅ Supprimé : $productName<br>";
                $deletedCount++;
            } else {
                echo "❌ Erreur lors de la suppression de : $productName<br>";
            }
        }
        
        echo "<br>📊 Résumé de la suppression :<br>";
        echo "Produits supprimés : $deletedCount sur " . count($productsToDelete) . "<br><br>";
        
        // Vérifier l'état final
        echo "🔍 État final de la base de données :<br>";
        $stmt = $pdo->query("SELECT category, COUNT(*) as count FROM products GROUP BY category ORDER BY category");
        $categories = $stmt->fetchAll();
        
        foreach ($categories as $category) {
            echo "- {$category['category']} : {$category['count']} produit(s)<br>";
        }
        
        echo "<br>✅ Suppression terminée !";
    }
    
} catch (PDOException $e) {
    echo "❌ Erreur de base de données : " . $e->getMessage();
}
?>
