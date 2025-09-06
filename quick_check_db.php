<?php
// Vérification rapide de la structure de la base de données
require_once 'backend/db.php';

echo "<h2>🔍 Vérification rapide de la base de données</h2>";

try {
    // Vérifier la connexion
    echo "<h3>✅ Connexion à la base de données</h3>";
    echo "<p>Base de données : " . $pdo->query("SELECT DATABASE()")->fetchColumn() . "</p>";
    
    // Lister les tables
    echo "<h3>📋 Tables disponibles :</h3>";
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "<ul>";
    foreach ($tables as $table) {
        echo "<li>{$table}</li>";
    }
    echo "</ul>";
    
    // Vérifier la structure de la table products
    echo "<h3>📦 Structure de la table 'products' :</h3>";
    $columns = $pdo->query("DESCRIBE products")->fetchAll();
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>Champ</th><th>Type</th><th>Null</th><th>Clé</th><th>Défaut</th><th>Extra</th></tr>";
    foreach ($columns as $col) {
        echo "<tr>";
        echo "<td>{$col['Field']}</td>";
        echo "<td>{$col['Type']}</td>";
        echo "<td>{$col['Null']}</td>";
        echo "<td>{$col['Key']}</td>";
        echo "<td>" . ($col['Default'] ?? 'NULL') . "</td>";
        echo "<td>{$col['Extra']}</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Vérifier les produits existants
    echo "<h3>🔍 Produits existants :</h3>";
    $products = $pdo->query("SELECT id, name, category, price FROM products ORDER BY id")->fetchAll();
    if (count($products) > 0) {
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>ID</th><th>Nom</th><th>Catégorie</th><th>Prix</th></tr>";
        foreach ($products as $prod) {
            echo "<tr>";
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
    
    // Vérifier les catégories uniques
    echo "<h3>🏷️ Catégories uniques dans les produits :</h3>";
    $categories = $pdo->query("SELECT DISTINCT category FROM products WHERE category IS NOT NULL ORDER BY category")->fetchAll(PDO::FETCH_COLUMN);
    if (count($categories) > 0) {
        echo "<ul>";
        foreach ($categories as $cat) {
            echo "<li>{$cat}</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>Aucune catégorie trouvée.</p>";
    }
    
    echo "<hr>";
    echo "<h3>📝 Actions recommandées :</h3>";
    echo "<ul>";
    echo "<li><a href='clean_products_direct.php'>🧹 Nettoyer directement les produits</a> - Supprime les produits avec catégorie 'Jouets' ou 'Chambre'</li>";
    echo "<li><a href='index.php'>🏠 Retour à l'accueil</a></li>";
    echo "</ul>";
    
} catch (PDOException $e) {
    echo "<h3>❌ Erreur :</h3>";
    echo "<p>" . $e->getMessage() . "</p>";
}
?>
