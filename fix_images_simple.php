<?php
// Script simple pour corriger les images des produits Garçon
require_once 'backend/db.php';

echo "<h2>🖼️ Correction simple des images Garçon</h2>";

try {
    // Vérifier la connexion
    echo "<h3>✅ Connexion à la base de données</h3>";
    
    // Récupérer les produits Garçon
    $stmt = $pdo->prepare("SELECT id, name, category, image FROM products WHERE category = 'Garçon' ORDER BY id");
    $stmt->execute();
    $garconProducts = $stmt->fetchAll();
    
    if (count($garconProducts) > 0) {
        echo "<h3>🔍 Produits Garçon trouvés : " . count($garconProducts) . "</h3>";
        
        // Images disponibles avec chemins corrects
        $images = [
            'backend/chemise en coton.jpg',
            'backend/ensemble 3 pièces.jpg',
            'backend/madakids/IMG-20250830-WA0125.jpg',
            'backend/madakids/IMG-20250830-WA0127.jpg',
            'backend/madakids/IMG-20250830-WA0123.jpg',
            'backend/madakids/IMG-20250830-WA0124.jpg',
            'backend/madakids/IMG-20250830-WA0128.jpg'
        ];
        
        // Vérifier quelles images existent
        echo "<h3>📸 Vérification des images :</h3>";
        $existingImages = [];
        foreach ($images as $img) {
            if (file_exists($img)) {
                echo "<p>✅ {$img} - Existe</p>";
                $existingImages[] = $img;
            } else {
                echo "<p>❌ {$img} - Manquante</p>";
            }
        }
        
        if (count($existingImages) > 0) {
            echo "<h3>🔄 Mise à jour des images en cours...</h3>";
            
            $updatedCount = 0;
            $imageIndex = 0;
            
            foreach ($garconProducts as $product) {
                // Utiliser une image existante en rotation
                $newImage = $existingImages[$imageIndex % count($existingImages)];
                $imageIndex++;
                
                try {
                    $stmt = $pdo->prepare("UPDATE products SET image = ? WHERE id = ?");
                    $stmt->execute([$newImage, $product['id']]);
                    
                    echo "<p>✅ {$product['name']} - Image mise à jour : {$newImage}</p>";
                    $updatedCount++;
                    
                } catch (PDOException $e) {
                    echo "<p>❌ {$product['name']} - Erreur : " . $e->getMessage() . "</p>";
                }
            }
            
            echo "<hr>";
            echo "<h3>🎉 Mise à jour terminée !</h3>";
            echo "<p>✅ {$updatedCount} produit(s) mis à jour</p>";
            
            // Afficher le résultat
            echo "<h3>🔍 Produits après mise à jour :</h3>";
            $stmt = $pdo->prepare("SELECT id, name, category, image FROM products WHERE category = 'Garçon' ORDER BY id");
            $stmt->execute();
            $updatedProducts = $stmt->fetchAll();
            
            echo "<table border='1' style='border-collapse: collapse; width: 100%; margin-bottom: 20px;'>";
            echo "<tr><th>ID</th><th>Nom</th><th>Catégorie</th><th>Image</th></tr>";
            foreach ($updatedProducts as $prod) {
                echo "<tr>";
                echo "<td>{$prod['id']}</td>";
                echo "<td>{$prod['name']}</td>";
                echo "<td>{$prod['category']}</td>";
                echo "<td>{$prod['image']}</td>";
                echo "</tr>";
            }
            echo "</table>";
            
            // Test d'affichage
            echo "<h3>🖼️ Test d'affichage :</h3>";
            echo "<div style='display: flex; gap: 20px; flex-wrap: wrap; justify-content: center;'>";
            
            foreach ($updatedProducts as $prod) {
                echo "<div style='border: 1px solid #ddd; border-radius: 8px; padding: 15px; width: 250px; text-align: center;'>";
                echo "<img src='{$prod['image']}' alt='{$prod['name']}' style='width: 200px; height: 200px; object-fit: cover; border-radius: 4px; margin-bottom: 10px;'>";
                echo "<h5 style='color: #333; margin: 10px 0;'>{$prod['name']}</h5>";
                echo "<p style='color: #666; margin: 5px 0;'>{$prod['category']}</p>";
                echo "</div>";
            }
            
            echo "</div>";
            
            echo "<hr>";
            echo "<h3>🎯 Testez maintenant :</h3>";
            echo "<ul>";
            echo "<li><a href='index.php'>🏠 Page d'accueil</a></li>";
            echo "<li><a href='garcon.php'>👕 Page Garçon</a></li>";
            echo "<li><a href='nouveautes.php'>🆕 Page Nouveautés</a></li>";
            echo "</ul>";
            
        } else {
            echo "<h3>❌ Aucune image trouvée !</h3>";
            echo "<p>Vérifiez que les images sont bien dans les dossiers :</p>";
            echo "<ul>";
            echo "<li>backend/</li>";
            echo "<li>backend/madakids/</li>";
            echo "</ul>";
        }
        
    } else {
        echo "<p>❌ Aucun produit Garçon trouvé.</p>";
        echo "<p><a href='add_garcon_products_auto.php'>➕ Ajouter des produits d'abord</a></p>";
    }
    
} catch (PDOException $e) {
    echo "<h3>❌ Erreur :</h3>";
    echo "<p>" . $e->getMessage() . "</p>";
}
?>
