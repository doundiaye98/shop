<?php
// Script pour supprimer tous les produits des catégories non-vêtements
require_once 'backend/db.php';

echo "<h2>🗑️ Suppression des produits non-vêtements</h2>";
echo "<p style='color: #dc3545; font-weight: bold;'>Suppression des catégories : Chambre, Jouets et Chaussures</p>";

try {
    // Vérifier la connexion
    echo "<h3>✅ Connexion à la base de données</h3>";
    
    // Vérifier d'abord quels produits existent dans les catégories non-vêtements
    $stmt = $pdo->prepare("SELECT id, name, price, image, category FROM products WHERE category IN ('Chambre', 'Jouets', 'Chaussures') ORDER BY category, name");
    $stmt->execute();
    $nonClothingProducts = $stmt->fetchAll();
    
    if (count($nonClothingProducts) > 0) {
        echo "<h3>🔍 Produits non-vêtements trouvés :</h3>";
        echo "<table border='1' style='border-collapse: collapse; width: 100%; margin-bottom: 20px;'>";
        echo "<tr><th>ID</th><th>Nom</th><th>Prix</th><th>Catégorie</th><th>Image</th></tr>";
        
        foreach ($nonClothingProducts as $prod) {
            echo "<tr>";
            echo "<td>{$prod['id']}</td>";
            echo "<td>{$prod['name']}</td>";
            echo "<td>{$prod['price']} €</td>";
            echo "<td style='color: #dc3545; font-weight: bold;'>{$prod['category']}</td>";
            echo "<td>{$prod['image']}</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // Compter par catégorie
        $chambreCount = 0;
        $jouetsCount = 0;
        $chaussuresCount = 0;
        foreach ($nonClothingProducts as $prod) {
            if ($prod['category'] == 'Chambre') $chambreCount++;
            if ($prod['category'] == 'Jouets') $jouetsCount++;
            if ($prod['category'] == 'Chaussures') $chaussuresCount++;
        }
        
        echo "<h3>📊 Résumé par catégorie :</h3>";
        echo "<ul>";
        echo "<li><strong>Chambre</strong> : {$chambreCount} produit(s)</li>";
        echo "<li><strong>Jouets</strong> : {$jouetsCount} produit(s)</li>";
        echo "<li><strong>Chaussures</strong> : {$chaussuresCount} produit(s)</li>";
        echo "</ul>";
        
        echo "<h3>⚠️ ATTENTION : Vous êtes sur le point de supprimer " . count($nonClothingProducts) . " produit(s) non-vêtements !</h3>";
        echo "<p>Cette action est irréversible et finalisera la transformation de votre boutique en boutique de vêtements uniquement.</p>";
        
        // Demander confirmation
        if (isset($_POST['confirm_delete'])) {
            // Supprimer tous les produits non-vêtements
            $stmt = $pdo->prepare("DELETE FROM products WHERE category IN ('Chambre', 'Jouets', 'Chaussures')");
            $stmt->execute();
            
            $deletedCount = $stmt->rowCount();
            
            echo "<hr>";
            echo "<h3>🎉 Suppression terminée !</h3>";
            echo "<p><strong>✅ {$deletedCount} produit(s) supprimé(s) avec succès !</strong></p>";
            
            // Vérification finale
            echo "<h3>🔍 Vérification finale :</h3>";
            $stmt = $pdo->prepare("SELECT category, COUNT(*) as count FROM products GROUP BY category ORDER BY category");
            $stmt->execute();
            $finalCategories = $stmt->fetchAll();
            
            echo "<h4>📋 Catégories restantes (100% vêtements enfants) :</h4>";
            if (count($finalCategories) > 0) {
                echo "<ul>";
                foreach ($finalCategories as $cat) {
                    $icon = '';
                    switch($cat['category']) {
                        case 'Bébé': $icon = '👶'; break;
                        case 'Fille': $icon = '👗'; break;
                        case 'Garçon': $icon = '👕'; break;

                        case 'Promos': $icon = '🏷️'; break;
                        default: $icon = '👕';
                    }
                    echo "<li>{$icon} <strong>{$cat['category']}</strong> - {$cat['count']} produit(s)</li>";
                }
                echo "</ul>";
            } else {
                echo "<p>Aucune catégorie trouvée.</p>";
            }
            
            // Vérifier qu'il ne reste plus de produits non-vêtements
            $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM products WHERE category IN ('Chambre', 'Jouets', 'Chaussures')");
            $stmt->execute();
            $remainingNonClothing = $stmt->fetch()['count'];
            
            if ($remainingNonClothing == 0) {
                echo "<p style='color: #28a745; font-weight: bold; font-size: 1.1em;'>🎉 FÉLICITATIONS ! Votre boutique est maintenant 100% vêtements enfants !</p>";
            } else {
                echo "<p style='color: #dc3545; font-weight: bold;'>❌ Il reste encore {$remainingNonClothing} produit(s) non-vêtements</p>";
            }
            
            echo "<hr>";
            echo "<h3>🎯 Testez maintenant votre site :</h3>";
            echo "<ul>";
            echo "<li><a href='index.php'>🏠 Page d'accueil</a></li>";
            echo "<li><a href='bebe.php'>👶 Page Bébé</a></li>";
            echo "<li><a href='fille.php'>👗 Page Fille</a></li>";
            echo "<li><a href='garcon.php'>👕 Page Garçon</a></li>";

            echo "<li><a href='nouveautes.php'>🆕 Page Nouveautés</a></li>";
            echo "</ul>";
            
            echo "<h3>✅ RÉSUMÉ DE LA TRANSFORMATION :</h3>";
            echo "<ul>";
            echo "<li>✅ Boutique transformée en boutique de vêtements enfants uniquement</li>";
            echo "<li>✅ Produits non-vêtements supprimés</li>";
            echo "<li>✅ Base de données nettoyée</li>";
            echo "<li>✅ Navigation cohérente avec l'offre</li>";
            echo "</ul>";
            
        } else {
            // Formulaire de confirmation
            echo "<form method='POST' style='margin: 20px 0;'>";
            echo "<button type='submit' name='confirm_delete' style='background: #dc3545; color: white; border: none; padding: 15px 25px; border-radius: 8px; cursor: pointer; margin-right: 15px; font-size: 1.1em; font-weight: bold;'>";
            echo "🗑️ OUI, supprimer tous les produits non-vêtements";
            echo "</button>";
            echo "<a href='index.php' style='background: #6c757d; color: white; text-decoration: none; padding: 15px 25px; border-radius: 8px; font-size: 1.1em;'>";
            echo "❌ Annuler et retourner à l'accueil";
            echo "</a>";
            echo "</form>";
            
            echo "<div style='background: #fff3cd; border: 1px solid #ffeaa7; border-radius: 8px; padding: 15px; margin: 20px 0;'>";
            echo "<h4>💡 Information :</h4>";
            echo "<p>Cette suppression finalisera la transformation de votre boutique en boutique de vêtements enfants uniquement, conformément à votre demande initiale.</p>";
            echo "</div>";
        }
        
    } else {
        echo "<h3>ℹ️ Aucun produit non-vêtements trouvé</h3>";
        echo "<p>Il n'y a actuellement aucun produit dans les catégories 'Chambre', 'Jouets' et 'Chaussures' à supprimer.</p>";
        
        // Afficher toutes les catégories existantes
        echo "<h3>🔍 Catégories disponibles dans la base de données :</h3>";
        $stmt = $pdo->prepare("SELECT DISTINCT category, COUNT(*) as count FROM products GROUP BY category ORDER BY category");
        $stmt->execute();
        $categories = $stmt->fetchAll();
        
        if (count($categories) > 0) {
            echo "<ul>";
            foreach ($categories as $cat) {
                $icon = '';
                switch($cat['category']) {
                    case 'Bébé': $icon = '👶'; break;
                    case 'Fille': $icon = '👗'; break;
                    case 'Garçon': $icon = '👕'; break;
                    case 'Promos': $icon = '🏷️'; break;
                    default: $icon = '👕';
                }
                echo "<li>{$icon} <strong>{$cat['category']}</strong> - {$cat['count']} produit(s)</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>Aucune catégorie trouvée.</p>";
        }
        
        echo "<hr>";
        echo "<p><a href='index.php'>🏠 Retour à l'accueil</a></p>";
    }
    
} catch (Exception $e) {
    echo "<h3>❌ Erreur :</h3>";
    echo "<p>" . $e->getMessage() . "</p>";
}
?>
