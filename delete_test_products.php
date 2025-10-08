<?php
// Script pour supprimer les produits test
require_once 'backend/db.php';

echo "<h2>Suppression des produits test</h2>";

try {
    // Identifier les produits test
    echo "<h3>Recherche des produits test :</h3>";
    
    $stmt = $pdo->query("
        SELECT id, name, created_at 
        FROM products 
        WHERE name LIKE '%Test%' OR name LIKE '%test%'
        ORDER BY id DESC
    ");
    
    $testProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($testProducts)) {
        echo "<p>Aucun produit test trouvé.</p>";
    } else {
        echo "<p>Produits test trouvés : " . count($testProducts) . "</p>";
        
        echo "<table border='1'><tr><th>ID</th><th>Nom</th><th>Date création</th><th>Action</th></tr>";
        foreach ($testProducts as $product) {
            echo "<tr>";
            echo "<td>" . $product['id'] . "</td>";
            echo "<td>" . htmlspecialchars($product['name']) . "</td>";
            echo "<td>" . $product['created_at'] . "</td>";
            echo "<td><button onclick='deleteProduct(" . $product['id'] . ")'>Supprimer</button></td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // Supprimer automatiquement les produits test
        echo "<h3>Suppression automatique :</h3>";
        
        $deletedCount = 0;
        foreach ($testProducts as $product) {
            echo "<p>Suppression du produit ID " . $product['id'] . " : " . htmlspecialchars($product['name']) . "</p>";
            
            // Supprimer les images associées
            $stmt = $pdo->prepare("SELECT image_path FROM product_images WHERE product_id = ?");
            $stmt->execute([$product['id']]);
            $images = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($images as $image) {
                if (file_exists($image['image_path'])) {
                    unlink($image['image_path']);
                    echo "<p style='color: blue;'>→ Fichier image supprimé : " . htmlspecialchars($image['image_path']) . "</p>";
                }
            }
            
            // Supprimer les entrées d'images en base
            $stmt = $pdo->prepare("DELETE FROM product_images WHERE product_id = ?");
            $stmt->execute([$product['id']]);
            
            // Supprimer le produit
            $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
            $result = $stmt->execute([$product['id']]);
            
            if ($result) {
                echo "<p style='color: green;'>✓ Produit supprimé avec succès</p>";
                $deletedCount++;
            } else {
                echo "<p style='color: red;'>✗ Erreur lors de la suppression</p>";
            }
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
        }
    }
    
    // Afficher le nombre total de produits
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM products");
    $totalProducts = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    echo "<p>Total de produits maintenant : $totalProducts</p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Erreur : " . $e->getMessage() . "</p>";
}
?>

<script>
function deleteProduct(productId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce produit ?')) {
        // Rediriger vers la page de suppression
        window.location.href = 'delete_product.php?id=' + productId;
    }
}
</script>
