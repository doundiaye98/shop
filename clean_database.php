<?php
// Script de nettoyage de la base de données
// Supprime les catégories "Jouets" et "Chambre" et leurs produits associés

require_once 'backend/db.php';

echo "<h2>🧹 Nettoyage de la base de données</h2>";

try {
    // 1. Afficher les catégories existantes
    echo "<h3>📋 Catégories existantes :</h3>";
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

    // 2. Afficher les produits des catégories à supprimer
    echo "<h3>🔍 Produits des catégories à supprimer :</h3>";
    $stmt = $pdo->prepare("
        SELECT p.*, c.name as category_name 
        FROM products p 
        JOIN categories c ON p.category_id = c.id 
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

    // 3. Supprimer les produits des catégories "Jouets" et "Chambre"
    echo "<h3>🗑️ Suppression des produits...</h3>";
    $stmt = $pdo->prepare("
        DELETE FROM products 
        WHERE category_id IN (
            SELECT id FROM categories 
            WHERE name IN ('Jouets', 'Chambre')
        )
    ");
    $stmt->execute();
    $deletedProducts = $stmt->rowCount();
    echo "<p>✅ {$deletedProducts} produit(s) supprimé(s).</p>";

    // 4. Supprimer les catégories "Jouets" et "Chambre"
    echo "<h3>🗑️ Suppression des catégories...</h3>";
    $stmt = $pdo->prepare("DELETE FROM categories WHERE name IN ('Jouets', 'Chambre')");
    $stmt->execute();
    $deletedCategories = $stmt->rowCount();
    echo "<p>✅ {$deletedCategories} catégorie(s) supprimée(s).</p>";

    // 5. Vérifier le résultat
    echo "<h3>✅ Résultat final :</h3>";
    
    echo "<h4>Catégories restantes :</h4>";
    $categories = $pdo->query("SELECT * FROM categories")->fetchAll();
    if (count($categories) > 0) {
        echo "<ul>";
        foreach ($categories as $cat) {
            echo "<li>ID: {$cat['id']} - Nom: {$cat['name']}</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>Aucune catégorie restante.</p>";
    }

    echo "<h4>Produits restants :</h4>";
    $stmt = $pdo->prepare("
        SELECT p.*, c.name as category_name 
        FROM products p 
        JOIN categories c ON p.category_id = c.id
    ");
    $stmt->execute();
    $remainingProducts = $stmt->fetchAll();
    
    if (count($remainingProducts) > 0) {
        echo "<ul>";
        foreach ($remainingProducts as $prod) {
            echo "<li>ID: {$prod['id']} - Nom: {$prod['name']} - Catégorie: {$prod['category_name']}</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>Aucun produit restant.</p>";
    }

    echo "<hr>";
    echo "<h3>🎉 Nettoyage terminé avec succès !</h3>";
    echo "<p>Votre base de données ne contient plus que les catégories de vêtements.</p>";
    echo "<p><a href='index.php'>← Retour à l'accueil</a></p>";

} catch (PDOException $e) {
    echo "<h3>❌ Erreur lors du nettoyage :</h3>";
    echo "<p>Erreur : " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p>Vérifiez que votre base de données est accessible et que les tables existent.</p>";
}
?>
