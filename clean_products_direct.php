<?php
// Script de nettoyage direct des produits
// Supprime les produits avec catégorie 'Jouets' ou 'Chambre' de la table products

require_once 'backend/db.php';

echo "<h2>🧹 Nettoyage direct des produits</h2>";

try {
    // 1. Afficher les produits existants avant nettoyage
    echo "<h3>📋 Produits existants avant nettoyage :</h3>";
    $products = $pdo->query("SELECT id, name, category, price FROM products ORDER BY id")->fetchAll();
    if (count($products) > 0) {
        echo "<table border='1' style='border-collapse: collapse; width: 100%; margin-bottom: 20px;'>";
        echo "<tr><th>ID</th><th>Nom</th><th>Catégorie</th><th>Prix</th></tr>";
        foreach ($products as $prod) {
            $rowClass = (in_array($prod['category'], ['Jouets', 'Chambre'])) ? 'style="background-color: #ffe6e6;"' : '';
            echo "<tr {$rowClass}>";
            echo "<td>{$prod['id']}</td>";
            echo "<td>{$prod['name']}</td>";
            echo "<td>{$prod['category']}</td>";
            echo "<td>{$prod['price']} €</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>Aucun produit trouvé.</p>";
    }
    
    // 2. Identifier les produits à supprimer
    echo "<h3>🔍 Produits à supprimer :</h3>";
    $stmt = $pdo->prepare("SELECT id, name, category, price FROM products WHERE category IN ('Jouets', 'Chambre') ORDER BY id");
    $stmt->execute();
    $productsToDelete = $stmt->fetchAll();
    
    if (count($productsToDelete) > 0) {
        echo "<table border='1' style='border-collapse: collapse; width: 100%; margin-bottom: 20px;'>";
        echo "<tr><th>ID</th><th>Nom</th><th>Catégorie</th><th>Prix</th></tr>";
        foreach ($productsToDelete as $prod) {
            echo "<tr style='background-color: #ffe6e6;'>";
            echo "<td>{$prod['id']}</td>";
            echo "<td>{$prod['name']}</td>";
            echo "<td>{$prod['category']}</td>";
            echo "<td>{$prod['price']} €</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        echo "<p><strong>⚠️ Attention : " . count($productsToDelete) . " produit(s) vont être supprimé(s) définitivement !</strong></p>";
        
        // 3. Demander confirmation
        if (isset($_POST['confirm']) && $_POST['confirm'] === 'yes') {
            // 4. Procéder à la suppression
            echo "<h3>🗑️ Suppression en cours...</h3>";
            
            $stmt = $pdo->prepare("DELETE FROM products WHERE category IN ('Jouets', 'Chambre')");
            $stmt->execute();
            $deletedCount = $stmt->rowCount();
            
            echo "<p>✅ {$deletedCount} produit(s) supprimé(s) avec succès !</p>";
            
            // 5. Afficher le résultat final
            echo "<h3>✅ Résultat final :</h3>";
            $remainingProducts = $pdo->query("SELECT id, name, category, price FROM products ORDER BY id")->fetchAll();
            
            if (count($remainingProducts) > 0) {
                echo "<table border='1' style='border-collapse: collapse; width: 100%; margin-bottom: 20px;'>";
                echo "<tr><th>ID</th><th>Nom</th><th>Catégorie</th><th>Prix</th></tr>";
                foreach ($remainingProducts as $prod) {
                    echo "<tr>";
                    echo "<td>{$prod['id']}</td>";
                    echo "<td>{$prod['name']}</td>";
                    echo "<td>{$prod['category']}</td>";
                    echo "<td>{$prod['price']} €</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p>Aucun produit restant.</p>";
            }
            
            // 6. Vérifier les catégories restantes
            echo "<h3>🏷️ Catégories restantes :</h3>";
            $remainingCategories = $pdo->query("SELECT DISTINCT category FROM products WHERE category IS NOT NULL ORDER BY category")->fetchAll(PDO::FETCH_COLUMN);
            if (count($remainingCategories) > 0) {
                echo "<ul>";
                foreach ($remainingCategories as $cat) {
                    echo "<li>{$cat}</li>";
                }
                echo "</ul>";
            } else {
                echo "<p>Aucune catégorie restante.</p>";
            }
            
            echo "<hr>";
            echo "<h3>🎉 Nettoyage terminé avec succès !</h3>";
            echo "<p>Votre base de données ne contient plus que les produits de vêtements.</p>";
            echo "<p>Les pages d'accueil et nouveautés ne devraient plus afficher les produits supprimés.</p>";
            echo "<p><a href='index.php'>← Retour à l'accueil</a></p>";
            
        } else {
            // Formulaire de confirmation
            echo "<hr>";
            echo "<h3>⚠️ Confirmation requise</h3>";
            echo "<p>Ce script va supprimer définitivement tous les produits des catégories 'Jouets' et 'Chambre'.</p>";
            echo "<p><strong>Cette action est irréversible !</strong></p>";
            
            echo "<form method='post'>";
            echo "<p><input type='hidden' name='confirm' value='yes'>";
            echo "<button type='submit' style='background: #dc3545; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;'>";
            echo "🚨 Confirmer la suppression de " . count($productsToDelete) . " produit(s)";
            echo "</button></p>";
            echo "</form>";
            
            echo "<p><a href='index.php'>← Annuler et retourner à l'accueil</a></p>";
        }
        
    } else {
        echo "<p>✅ Aucun produit des catégories 'Jouets' ou 'Chambre' trouvé.</p>";
        echo "<p>Votre base de données est déjà propre !</p>";
        echo "<p><a href='index.php'>← Retour à l'accueil</a></p>";
    }
    
} catch (PDOException $e) {
    echo "<h3>❌ Erreur lors du nettoyage :</h3>";
    echo "<p>Erreur : " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p>Vérifiez que votre base de données est accessible.</p>";
}
?>
