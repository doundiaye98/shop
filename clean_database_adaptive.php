<?php
// Script de nettoyage adaptatif de la base de données
// S'adapte à la structure réelle des tables

require_once 'backend/db.php';

echo "<h2>🧹 Nettoyage adaptatif de la base de données</h2>";

try {
    // 1. Examiner la structure des tables
    echo "<h3>🔍 Analyse de la structure des tables...</h3>";
    
    // Vérifier si la table categories existe
    $categoriesExist = false;
    $productsExist = false;
    $categoryField = '';
    
    try {
        $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
        $categoriesExist = in_array('categories', $tables);
        $productsExist = in_array('products', $tables);
        
        echo "<p>✅ Tables trouvées : " . implode(', ', $tables) . "</p>";
        echo "<p>📋 Table 'categories' : " . ($categoriesExist ? 'Existe' : 'N\'existe pas') . "</p>";
        echo "<p>📦 Table 'products' : " . ($productsExist ? 'Existe' : 'N\'existe pas') . "</p>";
        
    } catch (PDOException $e) {
        echo "<p>❌ Erreur lors de la vérification des tables : " . $e->getMessage() . "</p>";
        exit;
    }
    
    if (!$categoriesExist) {
        echo "<h3>⚠️ Aucune table 'categories' trouvée</h3>";
        echo "<p>Votre base de données n'a pas de table de catégories séparée.</p>";
        echo "<p>Les produits sont probablement organisés différemment.</p>";
        
        // Vérifier la structure de la table products
        if ($productsExist) {
            echo "<h4>🔍 Structure de la table 'products' :</h4>";
            try {
                $columns = $pdo->query("DESCRIBE products")->fetchAll();
                echo "<ul>";
                foreach ($columns as $col) {
                    echo "<li><strong>{$col['Field']}</strong> : {$col['Type']}</li>";
                }
                echo "</ul>";
                
                // Chercher un champ qui pourrait contenir la catégorie
                $categoryFields = ['category', 'categorie', 'type', 'genre', 'section'];
                foreach ($columns as $col) {
                    if (in_array(strtolower($col['Field']), $categoryFields)) {
                        $categoryField = $col['Field'];
                        break;
                    }
                }
                
                if ($categoryField) {
                    echo "<p>✅ Champ de catégorie trouvé : <strong>{$categoryField}</strong></p>";
                } else {
                    echo "<p>❌ Aucun champ de catégorie évident trouvé.</p>";
                }
                
            } catch (PDOException $e) {
                echo "<p>❌ Erreur lors de l'examen de la structure : " . $e->getMessage() . "</p>";
            }
        }
        
        echo "<hr>";
        echo "<h3>📝 Actions recommandées :</h3>";
        echo "<ul>";
        echo "<li>Examinez la structure de vos tables avec <a href='check_database_structure.php'>check_database_structure.php</a></li>";
        echo "<li>Identifiez comment les catégories sont stockées dans votre base de données</li>";
        echo "<li>Adaptez le script de nettoyage en conséquence</li>";
        echo "</ul>";
        echo "<p><a href='index.php'>← Retour à l'accueil</a></p>";
        exit;
    }
    
    // 2. Examiner la structure de la table categories
    echo "<h3>🏷️ Structure de la table 'categories' :</h3>";
    try {
        $columns = $pdo->query("DESCRIBE categories")->fetchAll();
        echo "<ul>";
        foreach ($columns as $col) {
            echo "<li><strong>{$col['Field']}</strong> : {$col['Type']}</li>";
        }
        echo "</ul>";
    } catch (PDOException $e) {
        echo "<p>❌ Erreur lors de l'examen de la table 'categories' : " . $e->getMessage() . "</p>";
        exit;
    }
    
    // 3. Examiner la structure de la table products
    echo "<h3>📦 Structure de la table 'products' :</h3>";
    try {
        $columns = $pdo->query("DESCRIBE products")->fetchAll();
        echo "<ul>";
        foreach ($columns as $col) {
            echo "<li><strong>{$col['Field']}</strong> : {$col['Type']}</li>";
        }
        echo "</ul>";
        
        // Chercher le champ de liaison avec les catégories
        $categoryFields = ['category_id', 'category', 'categorie', 'type', 'genre', 'section'];
        foreach ($columns as $col) {
            if (in_array(strtolower($col['Field']), $categoryFields)) {
                $categoryField = $col['Field'];
                break;
            }
        }
        
        if ($categoryField) {
            echo "<p>✅ Champ de catégorie trouvé : <strong>{$categoryField}</strong></p>";
        } else {
            echo "<p>❌ Aucun champ de catégorie trouvé dans la table 'products'</p>";
            echo "<p>Les produits et catégories ne semblent pas liés.</p>";
            exit;
        }
        
    } catch (PDOException $e) {
        echo "<p>❌ Erreur lors de l'examen de la table 'products' : " . $e->getMessage() . "</p>";
        exit;
    }
    
    // 4. Afficher les catégories existantes
    echo "<h3>📋 Catégories existantes :</h3>";
    try {
        $categories = $pdo->query("SELECT * FROM categories")->fetchAll();
        if (count($categories) > 0) {
            echo "<ul>";
            foreach ($categories as $cat) {
                echo "<li>ID: {$cat['id']} - Nom: {$cat['name']}</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>Aucune catégorie trouvée.</p>";
        }
    } catch (PDOException $e) {
        echo "<p>❌ Erreur lors de la récupération des catégories : " . $e->getMessage() . "</p>";
        exit;
    }
    
    // 5. Afficher les produits des catégories à supprimer
    echo "<h3>🔍 Produits des catégories à supprimer :</h3>";
    try {
        $stmt = $pdo->prepare("
            SELECT p.*, c.name as category_name 
            FROM products p 
            JOIN categories c ON p.{$categoryField} = c.id 
            WHERE c.name IN ('Jouets', 'Chambre')
        ");
        $stmt->execute();
        $productsToDelete = $stmt->fetchAll();
        
        if (count($productsToDelete) > 0) {
            echo "<ul>";
            foreach ($productsToDelete as $prod) {
                echo "<li>ID: {$prod['id']} - Nom: {$prod['name']} - Catégorie: {$prod['category_name']}</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>Aucun produit des catégories 'Jouets' ou 'Chambre' trouvé.</p>";
        }
    } catch (PDOException $e) {
        echo "<p>❌ Erreur lors de la recherche des produits : " . $e->getMessage() . "</p>";
        echo "<p>Vérifiez que le champ '{$categoryField}' est bien le bon champ de liaison.</p>";
        exit;
    }
    
    // 6. Demander confirmation
    echo "<hr>";
    echo "<h3>⚠️ Confirmation requise</h3>";
    echo "<p>Ce script va supprimer :</p>";
    echo "<ul>";
    echo "<li>Les catégories 'Jouets' et 'Chambre'</li>";
    echo "<li>Tous les produits associés à ces catégories</li>";
    echo "</ul>";
    echo "<p><strong>Cette action est irréversible !</strong></p>";
    
    if (isset($_POST['confirm']) && $_POST['confirm'] === 'yes') {
        // 7. Procéder au nettoyage
        echo "<h3>🗑️ Suppression en cours...</h3>";
        
        try {
            // Supprimer les produits
            $stmt = $pdo->prepare("
                DELETE FROM products 
                WHERE {$categoryField} IN (
                    SELECT id FROM categories 
                    WHERE name IN ('Jouets', 'Chambre')
                )
            ");
            $stmt->execute();
            $deletedProducts = $stmt->rowCount();
            echo "<p>✅ {$deletedProducts} produit(s) supprimé(s).</p>";
            
            // Supprimer les catégories
            $stmt = $pdo->prepare("DELETE FROM categories WHERE name IN ('Jouets', 'Chambre')");
            $stmt->execute();
            $deletedCategories = $stmt->rowCount();
            echo "<p>✅ {$deletedCategories} catégorie(s) supprimée(s).</p>";
            
            echo "<hr>";
            echo "<h3>🎉 Nettoyage terminé avec succès !</h3>";
            echo "<p>Votre base de données ne contient plus que les catégories de vêtements.</p>";
            echo "<p><a href='index.php'>← Retour à l'accueil</a></p>";
            
        } catch (PDOException $e) {
            echo "<p>❌ Erreur lors du nettoyage : " . $e->getMessage() . "</p>";
        }
        
    } else {
        // Formulaire de confirmation
        echo "<form method='post'>";
        echo "<p><input type='hidden' name='confirm' value='yes'>";
        echo "<button type='submit' style='background: #dc3545; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;'>";
        echo "🚨 Confirmer la suppression";
        echo "</button></p>";
        echo "</form>";
        
        echo "<p><a href='index.php'>← Annuler et retourner à l'accueil</a></p>";
    }

} catch (PDOException $e) {
    echo "<h3>❌ Erreur lors de l'analyse :</h3>";
    echo "<p>Erreur : " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p>Vérifiez que votre base de données est accessible et que les tables existent.</p>";
}
?>
