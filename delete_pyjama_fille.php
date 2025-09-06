<?php
// Script pour supprimer le produit "Ensemble Pyjama Fille"
require_once 'backend/db.php';

echo "<h2>🗑️ Suppression du produit Ensemble Pyjama Fille</h2>";

try {
    echo "✅ Connexion à la base de données réussie<br><br>";
    
    // Nom du produit à supprimer
    $productToDelete = 'Ensemble Pyjama Fille';
    
    echo "🎯 Produit à supprimer : $productToDelete<br><br>";
    
    // Vérifier l'existence du produit
    echo "🔍 Vérification de l'existence du produit :<br>";
    $stmt = $pdo->prepare("SELECT id, name, category, price FROM products WHERE name = ?");
    $stmt->execute([$productToDelete]);
    $product = $stmt->fetch();
    
    if ($product) {
        echo "✅ Trouvé : {$product['name']} (ID: {$product['id']}) - {$product['category']} - {$product['price']} €<br>";
    } else {
        echo "❌ Le produit '$productToDelete' n'a pas été trouvé dans la base de données.<br>";
        
        // Chercher des produits similaires
        echo "<br>🔍 Recherche de produits similaires :<br>";
        $stmt = $pdo->prepare("SELECT id, name, category, price FROM products WHERE name LIKE ?");
        $stmt->execute(['%Pyjama%']);
        $similar_products = $stmt->fetchAll();
        
        if (!empty($similar_products)) {
            echo "Produits contenant 'Pyjama' :<br>";
            foreach ($similar_products as $similar) {
                echo "- {$similar['name']} (ID: {$similar['id']}) - {$similar['category']} - {$similar['price']} €<br>";
            }
        } else {
            echo "Aucun produit contenant 'Pyjama' trouvé.<br>";
        }
        
        // Afficher tous les produits Fille
        echo "<br>🔍 Tous les produits Fille :<br>";
        $stmt = $pdo->prepare("SELECT id, name, price FROM products WHERE category = 'Fille' ORDER BY name");
        $stmt->execute();
        $fille_products = $stmt->fetchAll();
        
        foreach ($fille_products as $fille_product) {
            echo "- {$fille_product['name']} (ID: {$fille_product['id']}) - {$fille_product['price']} €<br>";
        }
        
        exit;
    }
    
    echo "<br>";
    
    // Demander confirmation
    echo "<form method='post'>";
    echo "<input type='submit' name='confirm' value='🗑️ Confirmer la suppression' style='background: #dc3545; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;'>";
    echo "</form>";
    
    if (isset($_POST['confirm'])) {
        echo "<br>🔄 Suppression en cours...<br><br>";
        
        // Supprimer le produit
        $stmt = $pdo->prepare("DELETE FROM products WHERE name = ?");
        $result = $stmt->execute([$productToDelete]);
        
        if ($result && $stmt->rowCount() > 0) {
            echo "✅ Supprimé avec succès : $productToDelete<br>";
            
            // Vérifier s'il y a des articles dans le panier pour ce produit
            $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM cart WHERE product_id = ?");
            $stmt->execute([$product['id']]);
            $cart_count = $stmt->fetch()['count'];
            
            if ($cart_count > 0) {
                echo "⚠️ Attention : $cart_count article(s) de ce produit dans le panier<br>";
                echo "Ces articles seront supprimés automatiquement.<br>";
                
                // Supprimer les articles du panier
                $stmt = $pdo->prepare("DELETE FROM cart WHERE product_id = ?");
                $stmt->execute([$product['id']]);
                echo "✅ Articles supprimés du panier<br>";
            }
            
        } else {
            echo "❌ Erreur lors de la suppression de : $productToDelete<br>";
        }
        
        echo "<br>📊 Résumé de la suppression :<br>";
        echo "Produit supprimé : $productToDelete<br><br>";
        
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
