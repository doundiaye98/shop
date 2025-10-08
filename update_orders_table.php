<?php
require_once 'backend/db.php';

echo "<h2>Mise à jour de la table orders</h2>";

try {
    // Ajouter les colonnes manquantes
    $columns_to_add = [
        "status VARCHAR(50) DEFAULT 'pending'",
        "shipping_address TEXT",
        "shipping_city VARCHAR(100)",
        "shipping_postal_code VARCHAR(20)",
        "shipping_country VARCHAR(100)",
        "payment_method VARCHAR(50)",
        "payment_intent_id VARCHAR(255)",
        "notes TEXT"
    ];
    
    echo "<h3>Ajout des colonnes manquantes</h3>";
    
    foreach ($columns_to_add as $column) {
        try {
            $sql = "ALTER TABLE orders ADD COLUMN $column";
            $pdo->exec($sql);
            echo "✅ Colonne ajoutée: $column<br>";
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
                echo "⚠️ Colonne déjà existante: $column<br>";
            } else {
                echo "❌ Erreur pour $column: " . $e->getMessage() . "<br>";
            }
        }
    }
    
    // Vérifier la nouvelle structure
    echo "<h3>Nouvelle structure de la table orders</h3>";
    $stmt = $pdo->query('DESCRIBE orders');
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>Champ</th><th>Type</th><th>Null</th><th>Clé</th><th>Défaut</th><th>Extra</th></tr>";
    while($row = $stmt->fetch()) {
        echo "<tr>";
        echo "<td>" . $row['Field'] . "</td>";
        echo "<td>" . $row['Type'] . "</td>";
        echo "<td>" . $row['Null'] . "</td>";
        echo "<td>" . $row['Key'] . "</td>";
        echo "<td>" . $row['Default'] . "</td>";
        echo "<td>" . $row['Extra'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    echo "<br><a href='test_order_creation.php' class='btn btn-primary'>Créer une commande de test</a>";
    echo " <a href='admin_dashboard_modern.php' class='btn btn-secondary'>Voir le Dashboard</a>";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage();
}
?>
