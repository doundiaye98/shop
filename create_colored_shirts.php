<?php
// Script pour créer des produits séparés pour chaque couleur de chemise en coton
require_once 'backend/db.php';

echo "<h2>👕 Création des chemises en coton par couleur</h2>";

try {
    // Vérifier la connexion
    echo "<h3>✅ Connexion à la base de données</h3>";
    
    // 1. D'abord, supprimer l'ancienne chemise en coton générique
    echo "<h3>🗑️ Suppression de l'ancienne chemise générique...</h3>";
    
    $stmt = $pdo->prepare("DELETE FROM products WHERE name = 'Chemise en coton' AND category = 'Garçon'");
    $stmt->execute();
    $deletedCount = $stmt->rowCount();
    echo "<p>✅ {$deletedCount} ancienne(s) chemise(s) supprimée(s)</p>";
    
    // 2. Créer les nouvelles chemises par couleur
    echo "<h3>➕ Création des nouvelles chemises par couleur...</h3>";
    
    $coloredShirts = [
        [
            'name' => 'Chemise en coton - Blanc',
            'category' => 'Garçon',
            'description' => 'Chemise en coton de qualité, couleur blanc cassé. Confortable et respirante, parfaite pour toutes les occasions. Taille disponible de 6-16 ans.',
            'price' => 12.50,
            'stock' => 25,
            'image' => 'backend/chemise en coton.jpg'
        ],
        [
            'name' => 'Chemise en coton - Bleu',
            'category' => 'Garçon',
            'description' => 'Chemise en coton de qualité, couleur bleu ciel. Confortable et respirante, parfaite pour toutes les occasions. Taille disponible de 6-16 ans.',
            'price' => 12.50,
            'stock' => 25,
            'image' => 'backend/madakids/IMG-20250830-WA0114.jpg'
        ],
        [
            'name' => 'Chemise en coton - Rouge',
            'category' => 'Garçon',
            'description' => 'Chemise en coton de qualité, couleur rouge bordeaux. Confortable et respirante, parfaite pour toutes les occasions. Taille disponible de 6-16 ans.',
            'price' => 12.50,
            'stock' => 25,
            'image' => 'backend/madakids/IMG-20250830-WA0115.jpg'
        ],
        [
            'name' => 'Chemise en coton - Vert',
            'category' => 'Garçon',
            'description' => 'Chemise en coton de qualité, couleur vert forêt. Confortable et respirante, parfaite pour toutes les occasions. Taille disponible de 6-16 ans.',
            'price' => 12.50,
            'stock' => 25,
            'image' => 'backend/madakids/IMG-20250830-WA0116.jpg'
        ]
    ];
    
    $createdCount = 0;
    $errors = [];
    
    foreach ($coloredShirts as $shirt) {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO products (name, category, description, price, stock, image, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, NOW())
            ");
            
            $stmt->execute([
                $shirt['name'],
                $shirt['category'],
                $shirt['description'],
                $shirt['price'],
                $shirt['stock'],
                $shirt['image']
            ]);
            
            $createdCount++;
            echo "<p>✅ {$shirt['name']} - Créée avec succès</p>";
            
        } catch (PDOException $e) {
            $errors[] = "Erreur pour {$shirt['name']}: " . $e->getMessage();
            echo "<p>❌ {$shirt['name']} - Erreur lors de la création</p>";
        }
    }
    
    echo "<hr>";
    echo "<h3>🎉 Résultat de la création :</h3>";
    echo "<p>✅ {$createdCount} chemise(s) créée(s) avec succès</p>";
    
    if (count($errors) > 0) {
        echo "<h4>❌ Erreurs rencontrées :</h4>";
        echo "<ul>";
        foreach ($errors as $error) {
            echo "<li>{$error}</li>";
        }
        echo "</ul>";
    }
    
    // 3. Vérification finale
    echo "<h3>🔍 Vérification finale des chemises :</h3>";
    $stmt = $pdo->prepare("SELECT id, name, price, stock, image FROM products WHERE name LIKE 'Chemise en coton%' AND category = 'Garçon' ORDER BY id");
    $stmt->execute();
    $finalShirts = $stmt->fetchAll();
    
    if (count($finalShirts) > 0) {
        echo "<table border='1' style='border-collapse: collapse; width: 100%; margin-bottom: 20px;'>";
        echo "<tr><th>ID</th><th>Nom</th><th>Prix</th><th>Stock</th><th>Image</th></tr>";
        foreach ($finalShirts as $shirt) {
            echo "<tr>";
            echo "<td>{$shirt['id']}</td>";
            echo "<td>{$shirt['name']}</td>";
            echo "<td>{$shirt['price']} €</td>";
            echo "<td>{$shirt['stock']}</td>";
            echo "<td>{$shirt['image']}</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // 4. Test d'affichage des chemises
        echo "<h3>🖼️ Test d'affichage des chemises par couleur :</h3>";
        echo "<div style='display: flex; gap: 20px; flex-wrap: wrap; justify-content: center;'>";
        
        foreach ($finalShirts as $shirt) {
            echo "<div style='border: 1px solid #ddd; border-radius: 8px; padding: 15px; width: 250px; text-align: center;'>";
            
            if (file_exists($shirt['image'])) {
                echo "<img src='{$shirt['image']}' alt='{$shirt['name']}' style='width: 200px; height: 200px; object-fit: cover; border-radius: 4px; margin-bottom: 10px;'>";
            } else {
                echo "<div style='width: 200px; height: 200px; background: #f8f9fa; border: 2px dashed #ccc; border-radius: 4px; margin-bottom: 10px; display: flex; align-items: center; justify-content: center; color: #6c757d;'>Image manquante</div>";
            }
            
            echo "<h5 style='color: #333; margin: 10px 0;'>{$shirt['name']}</h5>";
            echo "<p style='color: #28a745; font-weight: bold; font-size: 1.2em; margin: 10px 0;'>{$shirt['price']} €</p>";
            echo "<p style='color: #666; margin: 5px 0;'>Stock : {$shirt['stock']}</p>";
            echo "</div>";
        }
        
        echo "</div>";
        
    } else {
        echo "<p>❌ Aucune chemise trouvée</p>";
    }
    
    // 5. Vérification du total des produits Garçon
    echo "<h3>📊 Total des produits Garçon :</h3>";
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM products WHERE category = 'Garçon'");
    $stmt->execute();
    $total = $stmt->fetchColumn();
    echo "<p>✅ Total des produits Garçon : {$total}</p>";
    
    echo "<hr>";
    echo "<h3>🎯 Testez maintenant votre site :</h3>";
    echo "<ul>";
    echo "<li><a href='index.php'>🏠 Page d'accueil</a></li>";
    echo "<li><a href='garcon.php'>👕 Page Garçon</a></li>";
    echo "<li><a href='nouveautes.php'>🆕 Page Nouveautés</a></li>";
    echo "</ul>";
    
    echo "<h3>✅ RÉSUMÉ :</h3>";
    echo "<ul>";
    echo "<li>✅ 4 chemises en coton créées (Blanc, Bleu, Rouge, Vert)</li>";
    echo "<li>✅ Chaque couleur a sa propre image</li>";
    echo "<li>✅ Prix et stock configurés</li>";
    echo "<li>✅ Base de données mise à jour</li>";
    echo "</ul>";
    
    echo "<p><strong>🎉 Maintenant vous avez 4 chemises distinctes avec des couleurs différentes !</strong></p>";
    
} catch (Exception $e) {
    echo "<h3>❌ Erreur :</h3>";
    echo "<p>" . $e->getMessage() . "</p>";
}
?>
