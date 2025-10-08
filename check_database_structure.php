<?php
// Vérification de la structure de la base de données
session_start();

// Vérifier si l'utilisateur est admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die('Accès non autorisé');
}

require_once 'backend/db.php';

echo "<h2>Vérification de la structure de la base de données</h2>";

try {
    // Vérifier la table products
    echo "<h3>Table products :</h3>";
    $stmt = $pdo->query("DESCRIBE products");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<table border='1'><tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    foreach ($columns as $column) {
        echo "<tr>";
        foreach ($column as $value) {
            echo "<td>" . htmlspecialchars($value) . "</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
    
    // Vérifier la table product_images
    echo "<h3>Table product_images :</h3>";
    try {
        $stmt = $pdo->query("DESCRIBE product_images");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "<table border='1'><tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
        foreach ($columns as $column) {
            echo "<tr>";
            foreach ($column as $value) {
                echo "<td>" . htmlspecialchars($value) . "</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    } catch (PDOException $e) {
        echo "<p style='color: red;'>Erreur : La table product_images n'existe pas ou a un problème : " . $e->getMessage() . "</p>";
        
        // Créer la table product_images
        echo "<h4>Création de la table product_images :</h4>";
        try {
            $createTable = "
                CREATE TABLE IF NOT EXISTS product_images (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    product_id INT NOT NULL,
                    image_url VARCHAR(255) NOT NULL,
                    image_order INT DEFAULT 1,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
                )
            ";
            $pdo->exec($createTable);
            echo "<p style='color: green;'>Table product_images créée avec succès !</p>";
        } catch (PDOException $e2) {
            echo "<p style='color: red;'>Erreur lors de la création : " . $e2->getMessage() . "</p>";
        }
    }
    
    // Vérifier les permissions du dossier uploads
    echo "<h3>Dossier uploads :</h3>";
    $uploadDir = 'uploads/products/';
    echo "<p>Chemin : " . $uploadDir . "</p>";
    echo "<p>Existe : " . (is_dir($uploadDir) ? 'OUI' : 'NON') . "</p>";
    echo "<p>Lisible : " . (is_readable($uploadDir) ? 'OUI' : 'NON') . "</p>";
    echo "<p>Écriture : " . (is_writable($uploadDir) ? 'OUI' : 'NON') . "</p>";
    
    // Créer le dossier s'il n'existe pas
    if (!is_dir($uploadDir)) {
        if (mkdir($uploadDir, 0755, true)) {
            echo "<p style='color: green;'>Dossier créé avec succès !</p>";
        } else {
            echo "<p style='color: red;'>Erreur lors de la création du dossier</p>";
        }
    }
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>Erreur de base de données : " . $e->getMessage() . "</p>";
}
?>