<?php
// Script pour mettre à jour les images des produits Garçon avec les vraies images
require_once 'backend/db.php';

echo "<h2>🖼️ Mise à jour des images des produits Garçon</h2>";

try {
    // Vérifier la connexion
    echo "<h3>✅ Connexion à la base de données</h3>";
    echo "<p>Base de données : " . $pdo->query("SELECT DATABASE()")->fetchColumn() . "</p>";
    
    // Images disponibles dans le dossier madakids
    $availableImages = [
        'IMG-20250830-WA0125.jpg',
        'IMG-20250830-WA0127.jpg', 
        'IMG-20250830-WA0123.jpg',
        'IMG-20250830-WA0124.jpg',
        'IMG-20250830-WA0128.jpg',
        'IMG-20250830-WA0121.jpg',
        'IMG-20250830-WA0120.jpg',
        'IMG-20250830-WA0122.jpg',
        'IMG-20250830-WA0119.jpg',
        'IMG-20250830-WA0118.jpg',
        'IMG-20250830-WA0117.jpg',
        'IMG-20250830-WA0116.jpg',
        'IMG-20250830-WA0114.jpg',
        'IMG-20250830-WA0115.jpg',
        'IMG-20250830-WA0113.jpg'
    ];
    
    // Images disponibles directement dans backend
    $backendImages = [
        'chemise en coton.jpg',
        'ensemble 3 pièces.jpg'
    ];
    
    echo "<h3>📸 Images disponibles :</h3>";
    echo "<h4>Dans le dossier madakids :</h4>";
    echo "<ul>";
    foreach ($availableImages as $img) {
        echo "<li>{$img}</li>";
    }
    echo "</ul>";
    
    echo "<h4>Dans le dossier backend :</h4>";
    echo "<ul>";
    foreach ($backendImages as $img) {
        echo "<li>{$img}</li>";
    }
    echo "</ul>";
    
    // Récupérer les produits Garçon actuels
    echo "<h3>🔍 Produits Garçon actuels :</h3>";
    $stmt = $pdo->prepare("SELECT id, name, category, image FROM products WHERE category = 'Garçon' ORDER BY id");
    $stmt->execute();
    $garconProducts = $stmt->fetchAll();
    
    if (count($garconProducts) > 0) {
        echo "<table border='1' style='border-collapse: collapse; width: 100%; margin-bottom: 20px;'>";
        echo "<tr><th>ID</th><th>Nom</th><th>Catégorie</th><th>Image actuelle</th></tr>";
        foreach ($garconProducts as $prod) {
            echo "<tr>";
            echo "<td>{$prod['id']}</td>";
            echo "<td>{$prod['name']}</td>";
            echo "<td>{$prod['category']}</td>";
            echo "<td>{$prod['image']}</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // Proposer la mise à jour des images
        if (isset($_POST['update_images']) && $_POST['update_images'] === 'yes') {
            echo "<h3>🔄 Mise à jour des images en cours...</h3>";
            
            $updatedCount = 0;
            $errors = [];
            
            // Associer des images spécifiques aux produits
            $productImageMapping = [
                'Chemise en coton' => 'backend/chemise en coton.jpg',
                'Ensemble 3 pièces - Basique' => 'backend/ensemble 3 pièces.jpg',
                'Ensemble 3 pièces - Sport' => 'backend/madakids/IMG-20250830-WA0125.jpg',
                'Ensemble 2 pièces - Casual' => 'backend/madakids/IMG-20250830-WA0127.jpg',
                'Ensemble 3 pièces - Classique' => 'backend/madakids/IMG-20250830-WA0123.jpg',
                'Chaussettes par lot de 6' => 'backend/madakids/IMG-20250830-WA0124.jpg',
                'Jean Cargo - Unisexe' => 'backend/madakids/IMG-20250830-WA0128.jpg'
            ];
            
            foreach ($garconProducts as $product) {
                if (isset($productImageMapping[$product['name']])) {
                    $newImage = $productImageMapping[$product['name']];
                    
                    try {
                        $stmt = $pdo->prepare("UPDATE products SET image = ? WHERE id = ?");
                        $stmt->execute([$newImage, $product['id']]);
                        
                        $updatedCount++;
                        echo "<p>✅ {$product['name']} - Image mise à jour : {$newImage}</p>";
                        
                    } catch (PDOException $e) {
                        $errors[] = "Erreur pour {$product['name']}: " . $e->getMessage();
                        echo "<p>❌ {$product['name']} - Erreur lors de la mise à jour</p>";
                    }
                } else {
                    // Pour les produits sans mapping spécifique, utiliser une image aléatoire
                    $randomImage = 'backend/madakids/' . $availableImages[array_rand($availableImages)];
                    
                    try {
                        $stmt = $pdo->prepare("UPDATE products SET image = ? WHERE id = ?");
                        $stmt->execute([$randomImage, $product['id']]);
                        
                        $updatedCount++;
                        echo "<p>✅ {$product['name']} - Image aléatoire assignée : {$randomImage}</p>";
                        
                    } catch (PDOException $e) {
                        $errors[] = "Erreur pour {$product['name']}: " . $e->getMessage();
                        echo "<p>❌ {$product['name']} - Erreur lors de la mise à jour</p>";
                    }
                }
            }
            
            echo "<hr>";
            echo "<h3>🎉 Résultat de la mise à jour :</h3>";
            echo "<p>✅ {$updatedCount} produit(s) mis à jour avec succès</p>";
            
            if (count($errors) > 0) {
                echo "<h4>❌ Erreurs rencontrées :</h4>";
                echo "<ul>";
                foreach ($errors as $error) {
                    echo "<li>{$error}</li>";
                }
                echo "</ul>";
            }
            
            // Afficher le résultat final
            echo "<h3>🔍 Produits Garçon après mise à jour :</h3>";
            $stmt = $pdo->prepare("SELECT id, name, category, image FROM products WHERE category = 'Garçon' ORDER BY id");
            $stmt->execute();
            $updatedProducts = $stmt->fetchAll();
            
            echo "<table border='1' style='border-collapse: collapse; width: 100%; margin-bottom: 20px;'>";
            echo "<tr><th>ID</th><th>Nom</th><th>Catégorie</th><th>Nouvelle image</th></tr>";
            foreach ($updatedProducts as $prod) {
                echo "<tr>";
                echo "<td>{$prod['id']}</td>";
                echo "<td>{$prod['name']}</td>";
                echo "<td>{$prod['category']}</td>";
                echo "<td>{$prod['image']}</td>";
                echo "</tr>";
            }
            echo "</table>";
            
            echo "<hr>";
            echo "<h3>🎯 Prochaines étapes :</h3>";
            echo "<ul>";
            echo "<li>✅ Les images ont été mises à jour dans la base de données</li>";
            echo "<li>🌐 Testez votre site pour voir les nouvelles images</li>";
            echo "<li>📱 Vérifiez que les images s'affichent correctement</li>";
            echo "</ul>";
            echo "<p><a href='index.php'>🏠 Retour à l'accueil</a></p>";
            echo "<p><a href='garcon.php'>👕 Voir la page Garçon</a></p>";
            
        } else {
            // Formulaire de confirmation
            echo "<hr>";
            echo "<h3>⚠️ Confirmation requise</h3>";
            echo "<p>Ce script va mettre à jour les images de " . count($garconProducts) . " produits Garçon avec les vraies images du dossier madakids.</p>";
            echo "<p><strong>Voulez-vous procéder à la mise à jour ?</strong></p>";
            
            echo "<form method='post'>";
            echo "<p><input type='hidden' name='update_images' value='yes'>";
            echo "<button type='submit' style='background: #28a745; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;'>";
            echo "✅ Confirmer la mise à jour des images";
            echo "</button></p>";
            echo "</form>";
            
            echo "<p><a href='index.php'>← Annuler et retourner à l'accueil</a></p>";
        }
        
    } else {
        echo "<p>❌ Aucun produit Garçon trouvé dans la base de données.</p>";
        echo "<p><a href='add_garcon_products_auto.php'>➕ Ajouter des produits Garçon d'abord</a></p>";
    }
    
} catch (PDOException $e) {
    echo "<h3>❌ Erreur :</h3>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "<p>Vérifiez que votre base de données est accessible.</p>";
}
?>
