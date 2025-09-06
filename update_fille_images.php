<?php
// Script pour associer les images Madakids Fille aux produits
require_once 'backend/db.php';

echo "<h2>🖼️ Association des images Madakids Fille</h2>";

try {
    echo "✅ Connexion à la base de données réussie<br><br>";
    
    // Mapping des produits avec leurs images correspondantes
    $image_mapping = [
        'Ensemble 3 pièces (jupe, chemise, gilet)' => 'ensemble 3 pièces (jupe, chemise, gilet.jpg',
        'Ensemble 2 pièces (pull et pantalon)' => 'Ensemble 2 pièces (pull et pantalon).jpg',
        'Robe de sortie' => 'robe de sortie.jpg',
        'Ensemble 2 pièces très stylé' => 'Ensembles 2 pièces très stylé.jpg',
        'Ensemble 3 pièces (pantalon, t-shirt longue manche, veste)' => 'Ensemble 3 pèces ( patalon, t-shirt longue manche, veste.jpg',
        'Ensemble 2 pièces + sacoche' => 'Ensemble 2 pièces  + sacoche.jpg',
        'Robe avec sacoche' => 'Robe avec sacoche.jpg',
        'Robe avec pull + sacoche' => 'Robe avec pull + sacoche.jpg',
        'Robe + poupée Barbie' => 'Robe + poupée barbie.jpg',
        'Ensemble 2 pièces rose (pull et pantalon)' => 'Ensemble 2 pièces rose (pull et pantalon).jpg',
        'Ensemble 3 pièces (jupe, chemise, gilet) Rouge' => 'ensemble 3 pièces (jupe, chemise, gilet.jpg',
        'Robe avec pull' => 'Robe avec pull.jpg',
        'Robe de fête ou pour grande occasion' => 'Robe de fete ou pour grande occasion.jpg',
        'Attache cheveux' => 'Attache cheveux.jpg',
        'Collant et attache cheveux par lot de 2' => 'collant et attache cheveux par lot de 2.jpg',
        'Ensemble 3 pièces (pantalon, pull, gilet)' => 'Ensembles 3pièces (pantalon, pull, gilet).jpg',
        'Robe de fête ou pour grande occasion' => 'Robe de fete ou pour grande occasions.jpg',
        'Jean fille' => 'Jean fille.jpg',
        'Jean' => 'Jean.jpg',
        'Robe de sortie rose' => 'Robe de sortie rose.jpg'
    ];
    
    echo "🔍 Vérification des produits Fille existants :<br>";
    
    // Récupérer tous les produits Fille
    $stmt = $pdo->prepare("SELECT id, name, image FROM products WHERE category = 'Fille' ORDER BY name");
    $stmt->execute();
    $products = $stmt->fetchAll();
    
    if (empty($products)) {
        echo "❌ Aucun produit Fille trouvé dans la base de données.<br>";
        echo "Veuillez d'abord exécuter <a href='add_fille_products.php'>add_fille_products.php</a><br>";
        exit;
    }
    
    echo "📋 Produits Fille trouvés : " . count($products) . "<br><br>";
    
    // Afficher les produits et leurs images actuelles
    foreach ($products as $product) {
        $current_image = $product['image'] ?: 'Aucune image';
        echo "• {$product['name']} - Image actuelle: {$current_image}<br>";
    }
    
    echo "<br>🎯 Images disponibles dans madakids-fille :<br>";
    $image_dir = 'backend/madakids-fille/';
    $available_images = glob($image_dir . '*.jpg');
    foreach ($available_images as $image) {
        $filename = basename($image);
        echo "• {$filename}<br>";
    }
    
    echo "<br><form method='post'>";
    echo "<input type='submit' name='update_images' value='🖼️ Mettre à jour les images' style='background: #e91e63; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;'>";
    echo "</form>";
    
    if (isset($_POST['update_images'])) {
        echo "<br>🔄 Mise à jour des images en cours...<br><br>";
        
        $updated_count = 0;
        $not_found_count = 0;
        
        foreach ($products as $product) {
            $product_name = $product['name'];
            
            // Chercher l'image correspondante
            $image_file = null;
            
            // Correspondance exacte
            if (isset($image_mapping[$product_name])) {
                $image_file = $image_mapping[$product_name];
            } else {
                // Correspondance partielle
                foreach ($image_mapping as $mapped_name => $mapped_image) {
                    if (strpos($product_name, $mapped_name) !== false || strpos($mapped_name, $product_name) !== false) {
                        $image_file = $mapped_image;
                        break;
                    }
                }
            }
            
            if ($image_file) {
                $full_image_path = 'backend/madakids-fille/' . $image_file;
                
                // Vérifier que le fichier existe
                if (file_exists($full_image_path)) {
                    $stmt = $pdo->prepare("UPDATE products SET image = ? WHERE id = ?");
                    $result = $stmt->execute([$full_image_path, $product['id']]);
                    
                    if ($result) {
                        echo "✅ {$product_name} → {$image_file}<br>";
                        $updated_count++;
                    } else {
                        echo "❌ Erreur lors de la mise à jour de {$product_name}<br>";
                    }
                } else {
                    echo "⚠️ Image non trouvée : {$full_image_path}<br>";
                    $not_found_count++;
                }
            } else {
                echo "⚠️ Aucune correspondance trouvée pour : {$product_name}<br>";
                $not_found_count++;
            }
        }
        
        echo "<br>📊 Résumé de la mise à jour :<br>";
        echo "Images mises à jour : {$updated_count}<br>";
        echo "Images non trouvées : {$not_found_count}<br>";
        echo "Total produits : " . count($products) . "<br><br>";
        
        // Vérifier l'état final
        echo "🔍 État final des images :<br>";
        $stmt = $pdo->prepare("SELECT name, image FROM products WHERE category = 'Fille' ORDER BY name");
        $stmt->execute();
        $final_products = $stmt->fetchAll();
        
        foreach ($final_products as $product) {
            $image_status = $product['image'] ? '✅ ' . basename($product['image']) : '❌ Aucune image';
            echo "• {$product['name']} - {$image_status}<br>";
        }
        
        echo "<br>✅ Mise à jour des images terminée !";
    }
    
} catch (PDOException $e) {
    echo "❌ Erreur de base de données : " . $e->getMessage();
}
?>
