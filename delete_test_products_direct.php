<?php
// Script pour supprimer directement les 2 produits test
require_once 'backend/db.php';

echo "<h2>Suppression des 2 produits test</h2>";

try {
    // Supprimer directement les produits test (IDs 62 et 63 d'après les tests précédents)
    $testProductIds = [62, 63];
    
    $deletedCount = 0;
    
    foreach ($testProductIds as $productId) {
        echo "<h3>Suppression du produit ID $productId :</h3>";
        
        // Vérifier si le produit existe
        $stmt = $pdo->prepare("SELECT name FROM products WHERE id = ?");
        $stmt->execute([$productId]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$product) {
            echo "<p style='color: orange;'>⚠ Produit ID $productId non trouvé</p>";
            continue;
        }
        
        echo "<p>Nom du produit : " . htmlspecialchars($product['name']) . "</p>";
        
        // Supprimer les images associées
        $stmt = $pdo->prepare("SELECT image_path FROM product_images WHERE product_id = ?");
        $stmt->execute([$productId]);
        $images = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (!empty($images)) {
            echo "<p>Images associées : " . count($images) . "</p>";
            foreach ($images as $image) {
                if (file_exists($image['image_path'])) {
                    unlink($image['image_path']);
                    echo "<p style='color: blue;'>→ Fichier image supprimé : " . htmlspecialchars($image['image_path']) . "</p>";
                }
            }
        } else {
            echo "<p>Aucune image associée</p>";
        }
        
        // Supprimer les entrées d'images en base
        $stmt = $pdo->prepare("DELETE FROM product_images WHERE product_id = ?");
        $stmt->execute([$productId]);
        
        // Supprimer le produit
        $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
        $result = $stmt->execute([$productId]);
        
        if ($result) {
            echo "<p style='color: green;'>✓ Produit supprimé avec succès</p>";
            $deletedCount++;
        } else {
            echo "<p style='color: red;'>✗ Erreur lors de la suppression</p>";
        }
        
        echo "<hr>";
    }
    
    echo "<h3>Résumé :</h3>";
    echo "<p style='color: green;'>✓ Produits supprimés : $deletedCount</p>";
    
    // Vérifier qu'ils sont bien supprimés
    echo "<h3>Vérification :</h3>";
    $stmt = $pdo->query("
        SELECT COUNT(*) as count 
        FROM products 
        WHERE name LIKE '%Test%' OR name LIKE '%test%'
    ");
    $remainingCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    if ($remainingCount == 0) {
        echo "<p style='color: green;'>✓ Tous les produits test ont été supprimés</p>";
    } else {
        echo "<p style='color: orange;'>⚠ Il reste $remainingCount produit(s) test</p>";
        
        // Lister les produits test restants
        $stmt = $pdo->query("
            SELECT id, name 
            FROM products 
            WHERE name LIKE '%Test%' OR name LIKE '%test%'
        ");
        $remainingProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<p>Produits test restants :</p>";
        echo "<ul>";
        foreach ($remainingProducts as $product) {
            echo "<li>ID " . $product['id'] . " : " . htmlspecialchars($product['name']) . "</li>";
        }
        echo "</ul>";
    }
    
    // Afficher le nombre total de produits
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM products");
    $totalProducts = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    echo "<p>Total de produits maintenant : $totalProducts</p>";
    
    echo "<h3>Actions :</h3>";
    echo "<p>1. Les produits test ont été supprimés</p>";
    echo "<p>2. Vous pouvez maintenant utiliser le site normalement</p>";
    echo "<p>3. Les images des produits test ont été nettoyées</p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Erreur : " . $e->getMessage() . "</p>";
}
?>
