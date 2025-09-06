<?php
// Script pour corriger les chemins des images des produits Garçon
require_once 'backend/db.php';

echo "<h2>🔧 Correction des chemins des images</h2>";

try {
    // Vérifier la connexion
    echo "<h3>✅ Connexion à la base de données</h3>";
    echo "<p>Base de données : " . $pdo->query("SELECT DATABASE()")->fetchColumn() . "</p>";
    
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
        
        // Proposer la correction des chemins
        if (isset($_POST['fix_paths']) && $_POST['fix_paths'] === 'yes') {
            echo "<h3>🔧 Correction des chemins en cours...</h3>";
            
            $updatedCount = 0;
            $errors = [];
            
            // Mapping des chemins corrigés
            $pathMapping = [
                'backend/chemise en coton.jpg' => 'backend/chemise en coton.jpg',
                'backend/ensemble 3 pièces.jpg' => 'backend/ensemble 3 pièces.jpg',
                'backend/madakids/IMG-20250830-WA0125.jpg' => 'backend/madakids/IMG-20250830-WA0125.jpg',
                'backend/madakids/IMG-20250830-WA0127.jpg' => 'backend/madakids/IMG-20250830-WA0127.jpg',
                'backend/madakids/IMG-20250830-WA0123.jpg' => 'backend/madakids/IMG-20250830-WA0123.jpg',
                'backend/madakids/IMG-20250830-WA0124.jpg' => 'backend/madakids/IMG-20250830-WA0124.jpg',
                'backend/madakids/IMG-20250830-WA0128.jpg' => 'backend/madakids/IMG-20250830-WA0128.jpg'
            ];
            
            foreach ($garconProducts as $product) {
                $currentImage = $product['image'];
                
                // Vérifier si l'image existe physiquement
                if (file_exists($currentImage)) {
                    echo "<p>✅ {$product['name']} - Image existe : {$currentImage}</p>";
                    $updatedCount++;
                } else {
                    echo "<p>❌ {$product['name']} - Image manquante : {$currentImage}</p>";
                    
                    // Essayer de trouver une image alternative
                    $alternativeImage = null;
                    
                    // Chercher dans le dossier madakids
                    $madakidsDir = 'backend/madakids/';
                    if (is_dir($madakidsDir)) {
                        $files = scandir($madakidsDir);
                        foreach ($files as $file) {
                            if (pathinfo($file, PATHINFO_EXTENSION) === 'jpg' || pathinfo($file, PATHINFO_EXTENSION) === 'jpeg') {
                                $alternativeImage = $madakidsDir . $file;
                                break;
                            }
                        }
                    }
                    
                    // Si pas d'image alternative, utiliser une image par défaut
                    if (!$alternativeImage) {
                        $alternativeImage = 'https://via.placeholder.com/300x400?text=Image+Non+Disponible';
                    }
                    
                    try {
                        $stmt = $pdo->prepare("UPDATE products SET image = ? WHERE id = ?");
                        $stmt->execute([$alternativeImage, $product['id']]);
                        
                        echo "<p>✅ {$product['name']} - Image corrigée : {$alternativeImage}</p>";
                        $updatedCount++;
                        
                    } catch (PDOException $e) {
                        $errors[] = "Erreur pour {$product['name']}: " . $e->getMessage();
                        echo "<p>❌ {$product['name']} - Erreur lors de la correction</p>";
                    }
                }
            }
            
            echo "<hr>";
            echo "<h3>🎉 Résultat de la correction :</h3>";
            echo "<p>✅ {$updatedCount} produit(s) vérifié(s)/corrigé(s)</p>";
            
            if (count($errors) > 0) {
                echo "<h4>❌ Erreurs rencontrées :</h4>";
                echo "<ul>";
                foreach ($errors as $error) {
                    echo "<li>{$error}</li>";
                }
                echo "</ul>";
            }
            
            // Afficher le résultat final
            echo "<h3>🔍 Produits Garçon après correction :</h3>";
            $stmt = $pdo->prepare("SELECT id, name, category, image FROM products WHERE category = 'Garçon' ORDER BY id");
            $stmt->execute();
            $updatedProducts = $stmt->fetchAll();
            
            echo "<table border='1' style='border-collapse: collapse; width: 100%; margin-bottom: 20px;'>";
            echo "<tr><th>ID</th><th>Nom</th><th>Catégorie</th><th>Image corrigée</th></tr>";
            foreach ($updatedProducts as $prod) {
                $imageExists = file_exists($prod['image']) ? "✅" : "❌";
                echo "<tr>";
                echo "<td>{$prod['id']}</td>";
                echo "<td>{$prod['name']}</td>";
                echo "<td>{$prod['category']}</td>";
                echo "<td>{$imageExists} {$prod['image']}</td>";
                echo "</tr>";
            }
            echo "</table>";
            
            // Test d'affichage des images
            echo "<h3>🖼️ Test d'affichage des images :</h3>";
            echo "<div style='display: flex; gap: 20px; flex-wrap: wrap; justify-content: center;'>";
            
            foreach ($updatedProducts as $prod) {
                echo "<div style='border: 1px solid #ddd; border-radius: 8px; padding: 15px; width: 250px; text-align: center;'>";
                if (file_exists($prod['image'])) {
                    echo "<img src='{$prod['image']}' alt='{$prod['name']}' style='width: 200px; height: 200px; object-fit: cover; border-radius: 4px; margin-bottom: 10px;'>";
                } else {
                    echo "<img src='{$prod['image']}' alt='{$prod['name']}' style='width: 200px; height: 200px; object-fit: cover; border-radius: 4px; margin-bottom: 10px; border: 2px dashed #ccc;'>";
                }
                echo "<h5 style='color: #333; margin: 10px 0;'>{$prod['name']}</h5>";
                echo "<p style='color: #666; margin: 5px 0;'>{$prod['category']}</p>";
                echo "<p style='color: #28a745; font-weight: bold; font-size: 1.2em; margin: 10px 0;'>Image : {$prod['image']}</p>";
                echo "</div>";
            }
            
            echo "</div>";
            
            echo "<hr>";
            echo "<h3>🎯 Prochaines étapes :</h3>";
            echo "<ul>";
            echo "<li>✅ Les chemins des images ont été vérifiés et corrigés</li>";
            echo "<li>🌐 Testez votre site pour voir si les images s'affichent</li>";
            echo "<li>📱 Vérifiez les pages d'accueil, nouveautés et Garçon</li>";
            echo "</ul>";
            echo "<p><a href='index.php'>🏠 Retour à l'accueil</a></p>";
            echo "<p><a href='garcon.php'>👕 Voir la page Garçon</a></p>";
            
        } else {
            // Formulaire de confirmation
            echo "<hr>";
            echo "<h3>⚠️ Confirmation requise</h3>";
            echo "<p>Ce script va vérifier et corriger les chemins des images de " . count($garconProducts) . " produits Garçon.</p>";
            echo "<p><strong>Voulez-vous procéder à la vérification et correction ?</strong></p>";
            
            echo "<form method='post'>";
            echo "<p><input type='hidden' name='fix_paths' value='yes'>";
            echo "<button type='submit' style='background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;'>";
            echo "🔧 Vérifier et corriger les chemins";
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
