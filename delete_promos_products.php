<?php
// Script pour supprimer tous les produits de la catégorie "Promos"
require_once 'backend/db.php';

echo "<h2>🗑️ Suppression des produits Promos</h2>";

try {
    // Vérifier la connexion
    echo "<h3>✅ Connexion à la base de données</h3>";
    
    // Vérifier d'abord quels produits existent dans la catégorie Promos
    $stmt = $pdo->prepare("SELECT id, name, price, image FROM products WHERE category = 'Promos' ORDER BY name");
    $stmt->execute();
    $promosProducts = $stmt->fetchAll();
    
    if (count($promosProducts) > 0) {
        echo "<h3>🔍 Produits Promos trouvés :</h3>";
        echo "<table border='1' style='border-collapse: collapse; width: 100%; margin-bottom: 20px;'>";
        echo "<tr><th>ID</th><th>Nom</th><th>Prix</th><th>Image</th></tr>";
        
        foreach ($promosProducts as $prod) {
            echo "<tr>";
            echo "<td>{$prod['id']}</td>";
            echo "<td>{$prod['name']}</td>";
            echo "<td>{$prod['price']} €</td>";
            echo "<td>{$prod['image']}</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        echo "<h3>⚠️ ATTENTION : Vous êtes sur le point de supprimer " . count($promosProducts) . " produit(s) !</h3>";
        echo "<p>Cette action est irréversible.</p>";
        
        // Demander confirmation
        if (isset($_POST['confirm_delete'])) {
            // Supprimer tous les produits Promos
            $stmt = $pdo->prepare("DELETE FROM products WHERE category = 'Promos'");
            $stmt->execute();
            
            $deletedCount = $stmt->rowCount();
            
            echo "<hr>";
            echo "<h3>🎉 Suppression terminée !</h3>";
            echo "<p><strong>✅ {$deletedCount} produit(s) supprimé(s) avec succès !</strong></p>";
            
            // Vérification finale
            echo "<h3>🔍 Vérification finale :</h3>";
            $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM products WHERE category = 'Promos'");
            $stmt->execute();
            $finalCount = $stmt->fetch()['count'];
            
            if ($finalCount == 0) {
                echo "<p style='color: #28a745; font-weight: bold;'>✅ Aucun produit Promos restant dans la base de données</p>";
            } else {
                echo "<p style='color: #dc3545; font-weight: bold;'>❌ Il reste encore {$finalCount} produit(s) Promos</p>";
            }
            
            echo "<hr>";
            echo "<h3>🎯 Testez maintenant votre site :</h3>";
            echo "<ul>";
            echo "<li><a href='index.php'>🏠 Page d'accueil</a></li>";
            echo "<li><a href='promos.php'>🏷️ Page Promos</a></li>";
            echo "<li><a href='nouveautes.php'>🆕 Page Nouveautés</a></li>";
            echo "</ul>";
            
        } else {
            // Formulaire de confirmation
            echo "<form method='POST' style='margin: 20px 0;'>";
            echo "<button type='submit' name='confirm_delete' style='background: #dc3545; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; margin-right: 10px;'>";
            echo "🗑️ OUI, supprimer tous les produits Promos";
            echo "</button>";
            echo "<a href='index.php' style='background: #6c757d; color: white; text-decoration: none; padding: 10px 20px; border-radius: 5px;'>";
            echo "❌ Annuler et retourner à l'accueil";
            echo "</a>";
            echo "</form>";
        }
        
    } else {
        echo "<h3>ℹ️ Aucun produit Promos trouvé</h3>";
        echo "<p>Il n'y a actuellement aucun produit dans la catégorie 'Promos' à supprimer.</p>";
        
        // Afficher toutes les catégories existantes
        echo "<h3>🔍 Catégories disponibles dans la base de données :</h3>";
        $stmt = $pdo->prepare("SELECT DISTINCT category, COUNT(*) as count FROM products GROUP BY category ORDER BY category");
        $stmt->execute();
        $categories = $stmt->fetchAll();
        
        if (count($categories) > 0) {
            echo "<ul>";
            foreach ($categories as $cat) {
                echo "<li><strong>{$cat['category']}</strong> - {$cat['count']} produit(s)</li>";
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
