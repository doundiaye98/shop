<?php
// Script pour créer la table cart si elle n'existe pas
require_once 'backend/db.php';

echo "<h2>🛒 Création de la table Cart</h2>";

try {
    // Vérifier la connexion
    echo "<h3>✅ Connexion à la base de données</h3>";
    
    // Vérifier si la table cart existe
    $stmt = $pdo->query("SHOW TABLES LIKE 'cart'");
    $tableExists = $stmt->rowCount() > 0;
    
    if ($tableExists) {
        echo "<h3>ℹ️ Table Cart existe déjà</h3>";
        echo "<p>La table 'cart' existe déjà dans votre base de données.</p>";
        
        // Afficher la structure de la table
        echo "<h3>🔍 Structure de la table Cart :</h3>";
        $stmt = $pdo->query("DESCRIBE cart");
        $columns = $stmt->fetchAll();
        
        echo "<table border='1' style='border-collapse: collapse; width: 100%; margin-bottom: 20px;'>";
        echo "<tr><th>Champ</th><th>Type</th><th>Null</th><th>Clé</th><th>Défaut</th><th>Extra</th></tr>";
        
        foreach ($columns as $column) {
            echo "<tr>";
            echo "<td>{$column['Field']}</td>";
            echo "<td>{$column['Type']}</td>";
            echo "<td>{$column['Null']}</td>";
            echo "<td>{$column['Key']}</td>";
            echo "<td>{$column['Default']}</td>";
            echo "<td>{$column['Extra']}</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // Afficher le nombre d'articles dans le panier
        echo "<h3>📊 Contenu actuel du panier :</h3>";
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM cart");
        $cartCount = $stmt->fetch()['count'];
        echo "<p>Nombre d'articles dans le panier : <strong>{$cartCount}</strong></p>";
        
    } else {
        echo "<h3>🔨 Création de la table Cart</h3>";
        
        // Créer la table cart
        $sql = "CREATE TABLE cart (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            product_id INT NOT NULL,
            quantity INT NOT NULL DEFAULT 1,
            added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
            UNIQUE KEY unique_user_product (user_id, product_id)
        )";
        
        $pdo->exec($sql);
        
        echo "<p>✅ Table 'cart' créée avec succès !</p>";
        echo "<p>La table contient les colonnes suivantes :</p>";
        echo "<ul>";
        echo "<li><strong>id</strong> - Identifiant unique de l'article</li>";
        echo "<li><strong>user_id</strong> - ID de l'utilisateur (lié à la table users)</li>";
        echo "<li><strong>product_id</strong> - ID du produit (lié à la table products)</li>";
        echo "<li><strong>quantity</strong> - Quantité du produit</li>";
        echo "<li><strong>added_at</strong> - Date d'ajout au panier</li>";
        echo "</ul>";
        
        echo "<p>✅ Contraintes ajoutées :</p>";
        echo "<ul>";
        echo "<li>Clé étrangère vers la table <strong>users</strong></li>";
        echo "<li>Clé étrangère vers la table <strong>products</strong></li>";
        echo "<li>Contrainte unique sur user_id + product_id (un seul article par produit par utilisateur)</li>";
        echo "<li>Suppression en cascade si l'utilisateur ou le produit est supprimé</li>";
        echo "</ul>";
    }
    
    echo "<hr>";
    echo "<h3>🎯 Test du panier :</h3>";
    echo "<ul>";
    echo "<li><a href='index.php'>🏠 Page d'accueil</a> - Ajoutez des produits au panier</li>";
    echo "<li><a href='panier.php'>🛒 Page Panier</a> - Vérifiez que les produits s'affichent</li>";
    echo "<li><a href='backend/cart_api.php'>🔌 API Panier</a> - Testez l'API directement</li>";
    echo "</ul>";
    
    if (!$tableExists) {
        echo "<div style='background: #d4edda; border: 1px solid #c3e6cb; border-radius: 8px; padding: 15px; margin: 20px 0;'>";
        echo "<h4>💡 Prochaines étapes :</h4>";
        echo "<ol>";
        echo "<li>Connectez-vous à votre compte</li>";
        echo "<li>Ajoutez des produits au panier depuis la page d'accueil</li>";
        echo "<li>Vérifiez que les produits apparaissent dans votre panier</li>";
        echo "<li>Testez la modification des quantités et la suppression</li>";
        echo "</ol>";
        echo "</div>";
    }
    
} catch (Exception $e) {
    echo "<h3>❌ Erreur :</h3>";
    echo "<p>" . $e->getMessage() . "</p>";
}
?>
