<?php
// Script pour modifier l'image du produit Jean Garçon existant
require_once 'backend/db.php';

echo "<h2>👖 Modification de l'image du Jean Garçon</h2>";

try {
    // Vérifier la connexion
    echo "<h3>✅ Connexion à la base de données</h3>";
    
    // Récupérer le produit Jean Garçon existant
    $stmt = $pdo->prepare("SELECT id, name, category, description, price, stock, image FROM products WHERE name = 'Jean Garçon' AND category = 'Garçon'");
    $stmt->execute();
    $product = $stmt->fetch();
    
    if ($product) {
        echo "<h3>🔍 Produit trouvé :</h3>";
        echo "<div style='border: 1px solid #ddd; border-radius: 8px; padding: 15px; margin: 20px 0;'>";
        echo "<h4>📋 État actuel :</h4>";
        echo "<p><strong>ID :</strong> {$product['id']}</p>";
        echo "<p><strong>Nom :</strong> {$product['name']}</p>";
        echo "<p><strong>Catégorie :</strong> {$product['category']}</p>";
        echo "<p><strong>Description :</strong> {$product['description']}</p>";
        echo "<p><strong>Prix :</strong> {$product['price']} €</p>";
        echo "<p><strong>Stock :</strong> {$product['stock']}</p>";
        echo "<p><strong>Image actuelle :</strong> {$product['image']}</p>";
        echo "</div>";
        
        // Mettre à jour l'image
        echo "<h3>🔄 Mise à jour de l'image en cours...</h3>";
        
        try {
            $newImage = "backend/madakids/jean.jpg";
            
            $stmt = $pdo->prepare("UPDATE products SET image = ? WHERE id = ?");
            $stmt->execute([$newImage, $product['id']]);
            
            echo "<p>✅ Image mise à jour : <strong>{$newImage}</strong></p>";
            
            echo "<hr>";
            echo "<h3>🎉 Modification terminée !</h3>";
            
            // Vérification finale
            echo "<h3>🔍 Vérification finale :</h3>";
            $stmt = $pdo->prepare("SELECT id, name, category, description, price, stock, image FROM products WHERE id = ?");
            $stmt->execute([$product['id']]);
            $updatedProduct = $stmt->fetch();
            
            echo "<div style='border: 2px solid #28a745; border-radius: 8px; padding: 15px; margin: 20px 0; background-color: #f8fff8;'>";
            echo "<h4>✅ Produit mis à jour :</h4>";
            echo "<p><strong>ID :</strong> {$updatedProduct['id']}</p>";
            echo "<p><strong>Nom :</strong> {$updatedProduct['name']}</p>";
            echo "<p><strong>Catégorie :</strong> {$updatedProduct['category']}</p>";
            echo "<p><strong>Description :</strong> {$updatedProduct['description']}</p>";
            echo "<p><strong>Prix :</strong> {$updatedProduct['price']} €</p>";
            echo "<p><strong>Stock :</strong> {$updatedProduct['stock']}</p>";
            echo "<p><strong>Nouvelle image :</strong> {$updatedProduct['image']}</p>";
            echo "</div>";
            
            // Test d'affichage
            echo "<h3>🖼️ Test d'affichage avec la nouvelle image :</h3>";
            echo "<div style='display: flex; justify-content: center;'>";
            echo "<div style='border: 1px solid #ddd; border-radius: 8px; padding: 15px; width: 300px; text-align: center;'>";
            
            if (file_exists($updatedProduct['image'])) {
                echo "<img src='{$updatedProduct['image']}' alt='{$updatedProduct['name']}' style='width: 250px; height: 250px; object-fit: cover; border-radius: 4px; margin-bottom: 15px;'>";
                echo "<p style='color: #28a745; font-weight: bold;'>✅ Image trouvée et affichée</p>";
            } else {
                echo "<div style='width: 250px; height: 250px; background: #f8f9fa; border: 2px dashed #ccc; border-radius: 4px; margin-bottom: 15px; display: flex; align-items: center; justify-content: center; color: #6c757d;'>Image manquante</div>";
                echo "<p style='color: #dc3545; font-weight: bold;'>❌ Image non trouvée : {$updatedProduct['image']}</p>";
            }
            
            echo "<h4 style='color: #333; margin: 15px 0;'>{$updatedProduct['name']}</h4>";
            echo "<p style='color: #666; margin: 10px 0; font-size: 0.9em;'>{$updatedProduct['description']}</p>";
            echo "<p style='color: #28a745; font-weight: bold; font-size: 1.5em; margin: 15px 0;'>{$updatedProduct['price']} €</p>";
            echo "<p style='color: #666; margin: 5px 0;'>Stock : {$updatedProduct['stock']}</p>";
            echo "</div>";
            echo "</div>";
            
            echo "<hr>";
            echo "<h3>🎯 Testez maintenant votre site :</h3>";
            echo "<ul>";
            echo "<li><a href='index.php'>🏠 Page d'accueil</a></li>";
            echo "<li><a href='garcon.php'>👕 Page Garçon</a></li>";
            echo "<li><a href='nouveautes.php'>🆕 Page Nouveautés</a></li>";
            echo "</ul>";
            
            echo "<h3>✅ RÉSUMÉ DE LA MODIFICATION :</h3>";
            echo "<ul>";
            echo "<li>✅ <strong>Image :</strong> {$product['image']} → {$newImage}</li>";
            echo "<li>✅ <strong>Base de données :</strong> Mise à jour avec succès</li>";
            echo "</ul>";
            
            echo "<p><strong>🎉 Votre Jean Garçon a maintenant l'image madakids/jean.jpg !</strong></p>";
            
        } catch (PDOException $e) {
            echo "<p>❌ Erreur lors de la mise à jour : " . $e->getMessage() . "</p>";
        }
        
    } else {
        echo "<p>❌ Le produit 'Jean Garçon' n'a pas été trouvé dans la base de données.</p>";
        
        // Afficher tous les produits Garçon pour aider
        echo "<h3>🔍 Produits Garçon disponibles :</h3>";
        $stmt = $pdo->prepare("SELECT id, name, price FROM products WHERE category = 'Garçon' ORDER BY name");
        $stmt->execute();
        $allProducts = $stmt->fetchAll();
        
        if (count($allProducts) > 0) {
            echo "<ul>";
            foreach ($allProducts as $prod) {
                echo "<li><strong>{$prod['name']}</strong> - {$prod['price']} €</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>Aucun produit Garçon trouvé.</p>";
        }
    }
    
} catch (Exception $e) {
    echo "<h3>❌ Erreur :</h3>";
    echo "<p>" . $e->getMessage() . "</p>";
}
?>
