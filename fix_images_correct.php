<?php
// Script pour associer correctement chaque produit Garçon à sa vraie image
require_once 'backend/db.php';

echo "<h2>🎯 Association correcte des images Garçon</h2>";

try {
    // Vérifier la connexion
    echo "<h3>✅ Connexion à la base de données</h3>";
    
    // Récupérer les produits Garçon
    $stmt = $pdo->prepare("SELECT id, name, category, image FROM products WHERE category = 'Garçon' ORDER BY id");
    $stmt->execute();
    $garconProducts = $stmt->fetchAll();
    
    if (count($garconProducts) > 0) {
        echo "<h3>🔍 Produits Garçon trouvés : " . count($garconProducts) . "</h3>";
        
        // Association correcte produit → image
        $correctImageMapping = [
            'Chemise en coton' => 'backend/chemise en coton.jpg',
            'Ensemble 3 pièces - Basique' => 'backend/ensemble 3 pièces.jpg',
            'Ensemble 3 pièces - Sport' => 'backend/madakids/IMG-20250830-WA0125.jpg',
            'Ensemble 2 pièces - Casual' => 'backend/madakids/IMG-20250830-WA0127.jpg',
            'Ensemble 3 pièces - Classique' => 'backend/madakids/IMG-20250830-WA0123.jpg',
            'Chaussettes par lot de 6' => 'backend/madakids/IMG-20250830-WA0124.jpg',
            'Jean Cargo - Unisexe' => 'backend/madakids/IMG-20250830-WA0128.jpg'
        ];
        
        // Vérifier quelles images existent
        echo "<h3>📸 Vérification des images :</h3>";
        $existingImages = [];
        foreach ($correctImageMapping as $productName => $imagePath) {
            if (file_exists($imagePath)) {
                echo "<p>✅ {$productName} → {$imagePath} - Existe</p>";
                $existingImages[$productName] = $imagePath;
            } else {
                echo "<p>❌ {$productName} → {$imagePath} - Manquante</p>";
            }
        }
        
        if (count($existingImages) > 0) {
            echo "<h3>🔄 Association correcte des images en cours...</h3>";
            
            $updatedCount = 0;
            $errors = [];
            
            foreach ($garconProducts as $product) {
                $productName = $product['name'];
                
                if (isset($correctImageMapping[$productName])) {
                    $correctImage = $correctImageMapping[$productName];
                    
                    // Vérifier que l'image existe
                    if (file_exists($correctImage)) {
                        try {
                            $stmt = $pdo->prepare("UPDATE products SET image = ? WHERE id = ?");
                            $stmt->execute([$correctImage, $product['id']]);
                            
                            echo "<p>✅ {$productName} → Image correcte : {$correctImage}</p>";
                            $updatedCount++;
                            
                        } catch (PDOException $e) {
                            $errors[] = "Erreur pour {$productName}: " . $e->getMessage();
                            echo "<p>❌ {$productName} - Erreur lors de la mise à jour</p>";
                        }
                    } else {
                        echo "<p>⚠️ {$productName} - Image manquante : {$correctImage}</p>";
                    }
                } else {
                    echo "<p>❓ {$productName} - Pas de mapping d'image défini</p>";
                }
            }
            
            echo "<hr>";
            echo "<h3>🎉 Association terminée !</h3>";
            echo "<p>✅ {$updatedCount} produit(s) associé(s) à la bonne image</p>";
            
            if (count($errors) > 0) {
                echo "<h4>❌ Erreurs rencontrées :</h4>";
                echo "<ul>";
                foreach ($errors as $error) {
                    echo "<li>{$error}</li>";
                }
                echo "</ul>";
            }
            
            // Afficher le résultat final
            echo "<h3>🔍 Produits avec leurs images correctes :</h3>";
            $stmt = $pdo->prepare("SELECT id, name, category, image FROM products WHERE category = 'Garçon' ORDER BY id");
            $stmt->execute();
            $updatedProducts = $stmt->fetchAll();
            
            echo "<table border='1' style='border-collapse: collapse; width: 100%; margin-bottom: 20px;'>";
            echo "<tr><th>ID</th><th>Nom du produit</th><th>Catégorie</th><th>Image associée</th><th>Statut</th></tr>";
            foreach ($updatedProducts as $prod) {
                $imageExists = file_exists($prod['image']) ? "✅ Existe" : "❌ Manquante";
                $isCorrectImage = isset($correctImageMapping[$prod['name']]) && $correctImageMapping[$prod['name']] === $prod['image'];
                $status = $isCorrectImage ? "✅ Correcte" : "❌ Incorrecte";
                
                echo "<tr>";
                echo "<td>{$prod['id']}</td>";
                echo "<td>{$prod['name']}</td>";
                echo "<td>{$prod['category']}</td>";
                echo "<td>{$prod['image']}</td>";
                echo "<td>{$status} ({$imageExists})</td>";
                echo "</tr>";
            }
            echo "</table>";
            
            // Test d'affichage avec les bonnes images
            echo "<h3>🖼️ Test d'affichage avec les bonnes images :</h3>";
            echo "<div style='display: flex; gap: 20px; flex-wrap: wrap; justify-content: center;'>";
            
            foreach ($updatedProducts as $prod) {
                echo "<div style='border: 1px solid #ddd; border-radius: 8px; padding: 15px; width: 250px; text-align: center;'>";
                if (file_exists($prod['image'])) {
                    echo "<img src='{$prod['image']}' alt='{$prod['name']}' style='width: 200px; height: 200px; object-fit: cover; border-radius: 4px; margin-bottom: 10px;'>";
                } else {
                    echo "<div style='width: 200px; height: 200px; background: #f8f9fa; border: 2px dashed #ccc; border-radius: 4px; margin-bottom: 10px; display: flex; align-items: center; justify-content: center; color: #6c757d;'>Image manquante</div>";
                }
                echo "<h5 style='color: #333; margin: 10px 0;'>{$prod['name']}</h5>";
                echo "<p style='color: #666; margin: 5px 0;'>{$prod['category']}</p>";
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
            
            echo "<h3>📋 Résumé des associations :</h3>";
            echo "<ul>";
            foreach ($correctImageMapping as $product => $image) {
                $exists = file_exists($image) ? "✅" : "❌";
                echo "<li>{$exists} <strong>{$product}</strong> → {$image}</li>";
            }
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
