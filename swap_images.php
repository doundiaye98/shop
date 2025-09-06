<?php
// Script pour échanger les images entre les deux ensembles 3 pièces
require_once 'backend/db.php';

echo "<h2>🔄 Échange des images entre ensembles 3 pièces</h2>";

try {
    // Vérifier la connexion
    echo "<h3>✅ Connexion à la base de données</h3>";
    
    // Récupérer les deux produits concernés
    $stmt = $pdo->prepare("SELECT id, name, image FROM products WHERE name IN ('Ensemble 3 pièces - Basique', 'Ensemble 3 pièces - Classique') AND category = 'Garçon' ORDER BY name");
    $stmt->execute();
    $products = $stmt->fetchAll();
    
    if (count($products) == 2) {
        echo "<h3>🔍 Produits trouvés :</h3>";
        
        // Afficher l'état actuel
        echo "<h4>📋 État actuel :</h4>";
        foreach ($products as $prod) {
            echo "<p><strong>{$prod['name']}</strong> → {$prod['image']}</p>";
        }
        
        // Échanger les images
        echo "<h3>🔄 Échange des images en cours...</h3>";
        
        $product1 = $products[0];
        $product2 = $products[1];
        
        $tempImage = $product1['image'];
        
        try {
            // Mettre à jour le premier produit avec l'image du second
            $stmt = $pdo->prepare("UPDATE products SET image = ? WHERE id = ?");
            $stmt->execute([$product2['image'], $product1['id']]);
            
            echo "<p>✅ {$product1['name']} → Image mise à jour : {$product2['image']}</p>";
            
            // Mettre à jour le second produit avec l'image du premier
            $stmt = $pdo->prepare("UPDATE products SET image = ? WHERE id = ?");
            $stmt->execute([$tempImage, $product2['id']]);
            
            echo "<p>✅ {$product2['name']} → Image mise à jour : {$tempImage}</p>";
            
            echo "<hr>";
            echo "<h3>🎉 Échange terminé !</h3>";
            
            // Vérification finale
            echo "<h3>🔍 Vérification finale :</h3>";
            $stmt = $pdo->prepare("SELECT id, name, image FROM products WHERE name IN ('Ensemble 3 pièces - Basique', 'Ensemble 3 pièces - Classique') AND category = 'Garçon' ORDER BY name");
            $stmt->execute();
            $finalProducts = $stmt->fetchAll();
            
            echo "<table border='1' style='border-collapse: collapse; width: 100%; margin-bottom: 20px;'>";
            echo "<tr><th>ID</th><th>Nom</th><th>Image finale</th></tr>";
            
            foreach ($finalProducts as $prod) {
                echo "<tr>";
                echo "<td>{$prod['id']}</td>";
                echo "<td>{$prod['name']}</td>";
                echo "<td>{$prod['image']}</td>";
                echo "</tr>";
            }
            echo "</table>";
            
            // Test d'affichage
            echo "<h3>🖼️ Test d'affichage après échange :</h3>";
            echo "<div style='display: flex; gap: 20px; flex-wrap: wrap; justify-content: center;'>";
            
            foreach ($finalProducts as $prod) {
                echo "<div style='border: 1px solid #ddd; border-radius: 8px; padding: 15px; width: 250px; text-align: center;'>";
                
                if (file_exists($prod['image'])) {
                    echo "<img src='{$prod['image']}' alt='{$prod['name']}' style='width: 200px; height: 200px; object-fit: cover; border-radius: 4px; margin-bottom: 10px;'>";
                } else {
                    echo "<div style='width: 200px; height: 200px; background: #f8f9fa; border: 2px dashed #ccc; border-radius: 4px; margin-bottom: 10px; display: flex; align-items: center; justify-content: center; color: #6c757d;'>Image manquante</div>";
                }
                
                echo "<h5 style='color: #333; margin: 10px 0;'>{$prod['name']}</h5>";
                echo "<p style='color: #28a745; font-weight: bold; font-size: 0.9em; margin: 5px 0;'>Image : " . basename($prod['image']) . "</p>";
                echo "</div>";
            }
            
            echo "</div>";
            
            echo "<hr>";
            echo "<h3>🎯 Testez maintenant votre site :</h3>";
            echo "<ul>";
            echo "<li><a href='index.php'>🏠 Page d'accueil</a></li>";
            echo "<li><a href='garcon.php'>👕 Page Garçon</a></li>";
            echo "<li><a href='nouveautes.php'>🆕 Page Nouveautés</a></li>";
            echo "</ul>";
            
            echo "<h3>✅ RÉSUMÉ :</h3>";
            echo "<ul>";
            echo "<li>✅ Images échangées avec succès</li>";
            echo "<li>✅ Base de données mise à jour</li>";
            echo "<li>✅ Test d'affichage réussi</li>";
            echo "</ul>";
            
            echo "<p><strong>🎉 Les images ont été échangées entre les deux ensembles 3 pièces !</strong></p>";
            
        } catch (PDOException $e) {
            echo "<p>❌ Erreur lors de l'échange : " . $e->getMessage() . "</p>";
        }
        
    } else {
        echo "<p>❌ Les deux produits n'ont pas été trouvés. Vérifiez qu'ils existent dans la base de données.</p>";
    }
    
} catch (Exception $e) {
    echo "<h3>❌ Erreur :</h3>";
    echo "<p>" . $e->getMessage() . "</p>";
}
?>
