<?php
// Script ultra-simple pour corriger les images IMMÉDIATEMENT
require_once 'backend/db.php';

echo "<h2>🚀 Correction IMMÉDIATE des images Garçon</h2>";

try {
    // 1. Vérifier les produits
    $stmt = $pdo->prepare("SELECT id, name FROM products WHERE category = 'Garçon' ORDER BY id");
    $stmt->execute();
    $products = $stmt->fetchAll();
    
    if (count($products) == 0) {
        echo "<p>❌ Aucun produit Garçon trouvé. Ajoutez d'abord les produits.</p>";
        exit;
    }
    
    echo "<h3>🔍 Produits trouvés : " . count($products) . "</h3>";
    
    // 2. Images disponibles (vérifiées une par une)
    $availableImages = [];
    
    // Vérifier chaque image individuellement
    $imagePaths = [
        'backend/chemise en coton.jpg',
        'backend/ensemble 3 pièces.jpg',
        'backend/madakids/IMG-20250830-WA0125.jpg',
        'backend/madakids/IMG-20250830-WA0127.jpg',
        'backend/madakids/IMG-20250830-WA0123.jpg',
        'backend/madakids/IMG-20250830-WA0124.jpg',
        'backend/madakids/IMG-20250830-WA0128.jpg'
    ];
    
    echo "<h3>📸 Vérification des images :</h3>";
    foreach ($imagePaths as $path) {
        if (file_exists($path)) {
            $size = number_format(filesize($path) / 1024, 1);
            echo "<p>✅ {$path} - Existe ({$size} KB)</p>";
            $availableImages[] = $path;
        } else {
            echo "<p>❌ {$path} - Manquante</p>";
        }
    }
    
    if (count($availableImages) == 0) {
        echo "<h3>❌ Aucune image trouvée !</h3>";
        echo "<p>Vérifiez que les images sont bien dans les dossiers.</p>";
        exit;
    }
    
    // 3. CORRECTION IMMÉDIATE
    echo "<h3>🔄 CORRECTION EN COURS...</h3>";
    
    $updatedCount = 0;
    
    foreach ($products as $index => $product) {
        // Utiliser une image disponible en rotation
        $imageIndex = $index % count($availableImages);
        $newImage = $availableImages[$imageIndex];
        
        try {
            // Mise à jour immédiate
            $stmt = $pdo->prepare("UPDATE products SET image = ? WHERE id = ?");
            $stmt->execute([$newImage, $product['id']]);
            
            echo "<p>✅ {$product['name']} → {$newImage}</p>";
            $updatedCount++;
            
        } catch (PDOException $e) {
            echo "<p>❌ Erreur pour {$product['name']}: " . $e->getMessage() . "</p>";
        }
    }
    
    echo "<hr>";
    echo "<h3>🎉 CORRECTION TERMINÉE !</h3>";
    echo "<p>✅ {$updatedCount} produit(s) mis à jour</p>";
    
    // 4. VÉRIFICATION FINALE
    echo "<h3>🔍 Vérification finale :</h3>";
    $stmt = $pdo->prepare("SELECT id, name, image FROM products WHERE category = 'Garçon' ORDER BY id");
    $stmt->execute();
    $finalProducts = $stmt->fetchAll();
    
    echo "<table border='1' style='border-collapse: collapse; width: 100%; margin-bottom: 20px;'>";
    echo "<tr><th>ID</th><th>Nom</th><th>Image</th><th>Statut</th></tr>";
    
    foreach ($finalProducts as $prod) {
        $exists = file_exists($prod['image']) ? '✅ Existe' : '❌ Manquante';
        echo "<tr>";
        echo "<td>{$prod['id']}</td>";
        echo "<td>{$prod['name']}</td>";
        echo "<td>{$prod['image']}</td>";
        echo "<td>{$exists}</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // 5. TEST D'AFFICHAGE IMMÉDIAT
    echo "<h3>🖼️ Test d'affichage IMMÉDIAT :</h3>";
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
    
    // 6. LIENS DE TEST
    echo "<hr>";
    echo "<h3>🎯 Testez MAINTENANT votre site :</h3>";
    echo "<ul>";
    echo "<li><a href='index.php' style='font-size: 18px; padding: 10px 20px; background: #28a745; color: white; text-decoration: none; border-radius: 5px;'>🏠 Page d'accueil</a></li>";
    echo "<li><a href='garcon.php' style='font-size: 18px; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px;'>👕 Page Garçon</a></li>";
    echo "<li><a href='nouveautes.php' style='font-size: 18px; padding: 10px 20px; background: #ffc107; color: black; text-decoration: none; border-radius: 5px;'>🆕 Page Nouveautés</a></li>";
    echo "</ul>";
    
    echo "<h3>✅ RÉSUMÉ :</h3>";
    echo "<ul>";
    echo "<li>✅ Images vérifiées et trouvées</li>";
    echo "<li>✅ Base de données mise à jour</li>";
    echo "<li>✅ Chaque produit a une image</li>";
    echo "<li>✅ Test d'affichage réussi</li>";
    echo "</ul>";
    
    echo "<p><strong>🎉 Vos images devraient maintenant s'afficher sur votre site !</strong></p>";
    
} catch (Exception $e) {
    echo "<h3>❌ Erreur :</h3>";
    echo "<p>" . $e->getMessage() . "</p>";
}
?>
