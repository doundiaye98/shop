<?php
// Script pour examiner la structure réelle des tables
// Affiche les colonnes et la structure de chaque table

require_once 'backend/db.php';

echo "<h2>🔍 Structure de la base de données</h2>";

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

    // Examiner la structure de chaque table
    foreach ($tables as $table) {
        echo "<h3>🏗️ Structure de la table '{$table}' :</h3>";
        
        try {
            // Afficher la structure de la table
            $columns = $pdo->query("DESCRIBE {$table}")->fetchAll();
            if (count($columns) > 0) {
                echo "<table border='1' style='border-collapse: collapse; width: 100%; margin-bottom: 20px;'>";
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
            }
            
            // Afficher quelques exemples de données
            echo "<h4>📊 Exemples de données (5 premières lignes) :</h4>";
            $data = $pdo->query("SELECT * FROM {$table} LIMIT 5")->fetchAll();
            if (count($data) > 0) {
                echo "<table border='1' style='border-collapse: collapse; width: 100%; margin-bottom: 20px;'>";
                // En-têtes
                echo "<tr>";
                foreach (array_keys($data[0]) as $header) {
                    echo "<th>{$header}</th>";
                }
                echo "</tr>";
                // Données
                foreach ($data as $row) {
                    echo "<tr>";
                    foreach ($row as $value) {
                        echo "<td>" . htmlspecialchars($value ?? 'NULL') . "</td>";
                    }
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p>Aucune donnée dans cette table.</p>";
            }
            
        } catch (PDOException $e) {
            echo "<p>❌ Erreur avec la table '{$table}' : " . $e->getMessage() . "</p>";
        }
        
        echo "<hr>";
    }

    echo "<h3>📝 Actions recommandées :</h3>";
    echo "<ul>";
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
