<?php
// Script de vérification de la base de données
// Affiche l'état actuel des tables et données

require_once 'backend/db.php';

echo "<h2>🔍 Vérification de la base de données</h2>";

try {
    // Vérifier la connexion
    echo "<h3>✅ Connexion à la base de données</h3>";
    echo "<p>Base de données : " . $pdo->query("SELECT DATABASE()")->fetchColumn() . "</p>";
    echo "<p>Version MySQL : " . $pdo->query("SELECT VERSION()")->fetchColumn() . "</p>";

    // Lister toutes les tables
    echo "<h3>📋 Tables disponibles :</h3>";
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    if (count($tables) > 0) {
        echo "<ul>";
        foreach ($tables as $table) {
            echo "<li>{$table}</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>Aucune table trouvée.</p>";
    }

    // Vérifier la table categories
    echo "<h3>🏷️ Table 'categories' :</h3>";
    try {
        $categories = $pdo->query("SELECT * FROM categories")->fetchAll();
        if (count($categories) > 0) {
            echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
            echo "<tr><th>ID</th><th>Nom</th><th>Description</th></tr>";
            foreach ($categories as $cat) {
                echo "<tr>";
                echo "<td>{$cat['id']}</td>";
                echo "<td>{$cat['name']}</td>";
                echo "<td>" . ($cat['description'] ?? '') . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>Aucune catégorie trouvée.</p>";
        }
    } catch (PDOException $e) {
        echo "<p>❌ Erreur avec la table 'categories' : " . $e->getMessage() . "</p>";
    }

    // Vérifier la table products
    echo "<h3>📦 Table 'products' :</h3>";
    try {
        $products = $pdo->query("SELECT * FROM products")->fetchAll();
        if (count($products) > 0) {
            echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
            echo "<tr><th>ID</th><th>Nom</th><th>Catégorie ID</th><th>Prix</th><th>Stock</th></tr>";
            foreach ($products as $prod) {
                echo "<tr>";
                echo "<td>{$prod['id']}</td>";
                echo "<td>{$prod['name']}</td>";
                echo "<td>{$prod['category_id']}</td>";
                echo "<td>{$prod['price']} €</td>";
                echo "<td>{$prod['stock']}</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>Aucun produit trouvé.</p>";
        }
    } catch (PDOException $e) {
        echo "<p>❌ Erreur avec la table 'products' : " . $e->getMessage() . "</p>";
    }

    // Vérifier la table users
    echo "<h3>👥 Table 'users' :</h3>";
    try {
        $users = $pdo->query("SELECT id, username, email, role, created_at FROM users")->fetchAll();
        if (count($users) > 0) {
            echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
            echo "<tr><th>ID</th><th>Nom d'utilisateur</th><th>Email</th><th>Rôle</th><th>Date création</th></tr>";
            foreach ($users as $user) {
                echo "<tr>";
                echo "<td>{$user['id']}</td>";
                echo "<td>{$user['username']}</td>";
                echo "<td>{$user['email']}</td>";
                echo "<td>{$user['role']}</td>";
                echo "<td>{$user['created_at']}</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>Aucun utilisateur trouvé.</p>";
        }
    } catch (PDOException $e) {
        echo "<p>❌ Erreur avec la table 'users' : " . $e->getMessage() . "</p>";
    }

    // Vérifier les relations entre produits et catégories
    echo "<h3>🔗 Relations produits-catégories :</h3>";
    try {
        $stmt = $pdo->prepare("
            SELECT p.id, p.name as product_name, p.category_id, c.name as category_name
            FROM products p 
            LEFT JOIN categories c ON p.category_id = c.id
            ORDER BY p.category_id, p.name
        ");
        $stmt->execute();
        $relations = $stmt->fetchAll();
        
        if (count($relations) > 0) {
            echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
            echo "<tr><th>Produit ID</th><th>Nom du produit</th><th>Catégorie ID</th><th>Nom de la catégorie</th></tr>";
            foreach ($relations as $rel) {
                $categoryName = $rel['category_name'] ?: '❌ Catégorie manquante';
                echo "<tr>";
                echo "<td>{$rel['id']}</td>";
                echo "<td>{$rel['product_name']}</td>";
                echo "<td>{$rel['category_id']}</td>";
                echo "<td>{$categoryName}</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>Aucune relation trouvée.</p>";
        }
    } catch (PDOException $e) {
        echo "<p>❌ Erreur lors de la vérification des relations : " . $e->getMessage() . "</p>";
    }

    echo "<hr>";
    echo "<h3>📝 Actions recommandées :</h3>";
    echo "<ul>";
    echo "<li><a href='clean_database.php'>🧹 Nettoyer la base de données</a> - Supprime les catégories 'Jouets' et 'Chambre'</li>";
    echo "<li><a href='index.php'>🏠 Retour à l'accueil</a></li>";
    echo "</ul>";

} catch (PDOException $e) {
    echo "<h3>❌ Erreur de connexion à la base de données :</h3>";
    echo "<p>Erreur : " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p>Vérifiez que :</p>";
    echo "<ul>";
    echo "<li>Votre serveur MySQL est démarré</li>";
    echo "<li>La base de données 'shop' existe</li>";
    echo "<li>Les identifiants dans 'backend/db.php' sont corrects</li>";
    echo "</ul>";
}
?>
