<?php
// Script pour créer la table des favoris
require_once 'backend/db.php';

echo "<h2>❤️ Création de la table des favoris</h2>";

try {
    echo "✅ Connexion à la base de données réussie<br><br>";
    
    // Créer la table favorites
    $sql_favorites = "
    CREATE TABLE IF NOT EXISTS favorites (
        id INT PRIMARY KEY AUTO_INCREMENT,
        user_id INT NOT NULL,
        product_id INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
        UNIQUE KEY unique_user_product (user_id, product_id),
        INDEX idx_user_id (user_id),
        INDEX idx_product_id (product_id),
        INDEX idx_created_at (created_at)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    
    $pdo->exec($sql_favorites);
    echo "✅ Table 'favorites' créée avec succès<br><br>";
    
    // Vérifier que la table existe
    $stmt = $pdo->query("SHOW TABLES LIKE 'favorites'");
    if ($stmt->rowCount() > 0) {
        echo "✅ Table 'favorites' existe<br>";
        
        // Afficher la structure
        echo "<br>🔍 Structure de la table 'favorites' :<br>";
        $stmt = $pdo->query("DESCRIBE favorites");
        $columns = $stmt->fetchAll();
        
        echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
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
        
    } else {
        echo "❌ Table 'favorites' n'existe pas<br>";
    }
    
    echo "<br>🎉 Table des favoris créée avec succès !<br>";
    echo "Vous pouvez maintenant utiliser le système de favoris.<br><br>";
    
    echo "<a href='favorites.php' class='btn btn-primary'>❤️ Voir la page des favoris</a>";
    
} catch (PDOException $e) {
    echo "❌ Erreur lors de la création de la table : " . $e->getMessage();
}
?>
