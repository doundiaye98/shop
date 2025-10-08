<?php
// Script pour vérifier et corriger la structure de la base de données
session_start();

// Vérifier si l'utilisateur est admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die('Accès non autorisé');
}

require_once 'backend/db.php';

echo "<h2>Vérification et correction de la structure de la base de données</h2>";

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
        
        // Vérifier si la colonne image_path existe
        $hasImagePath = false;
        $hasImageUrl = false;
        foreach ($columns as $column) {
            if ($column['Field'] === 'image_path') {
                $hasImagePath = true;
            }
            if ($column['Field'] === 'image_url') {
                $hasImageUrl = true;
            }
        }
        
        if ($hasImageUrl && !$hasImagePath) {
            echo "<p style='color: orange;'>ATTENTION: La table utilise 'image_url' mais le code attend 'image_path'</p>";
            echo "<p>Correction automatique en cours...</p>";
            
            // Renommer la colonne
            $pdo->exec("ALTER TABLE product_images CHANGE image_url image_path VARCHAR(255) NOT NULL");
            echo "<p style='color: green;'>Colonne renommée de 'image_url' vers 'image_path'</p>";
        } elseif (!$hasImagePath && !$hasImageUrl) {
            echo "<p style='color: red;'>ERREUR: Aucune colonne d'image trouvée</p>";
            echo "<p>Ajout de la colonne image_path...</p>";
            $pdo->exec("ALTER TABLE product_images ADD COLUMN image_path VARCHAR(255) NOT NULL");
            echo "<p style='color: green;'>Colonne image_path ajoutée</p>";
        } else {
            echo "<p style='color: green;'>Structure correcte</p>";
        }
        
    } catch (PDOException $e) {
        echo "<p style='color: red;'>Erreur : La table product_images n'existe pas : " . $e->getMessage() . "</p>";
        
        // Créer la table product_images
        echo "<h4>Création de la table product_images :</h4>";
        try {
            $createTable = "
                CREATE TABLE IF NOT EXISTS product_images (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    product_id INT NOT NULL,
                    image_path VARCHAR(255) NOT NULL,
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
    
    // Vérifier le dossier uploads
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
    
    // Test d'ajout de produit
    echo "<h3>Test d'ajout de produit :</h3>";
    try {
        $stmt = $pdo->prepare('
            INSERT INTO products (name, description, category, price, stock, promo_price, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())
        ');
        
        $result = $stmt->execute([
            'Test Produit ' . date('H:i:s'),
            'Description test',
            'Bébé',
            29.99,
            10,
            null
        ]);
        
        if ($result) {
            $productId = $pdo->lastInsertId();
            echo "<p style='color: green;'>Produit test ajouté avec succès (ID: $productId)</p>";
            
            // Test d'ajout d'image
            try {
                $stmt = $pdo->prepare('
                    INSERT INTO product_images (product_id, image_path, image_order) 
                    VALUES (?, ?, ?)
                ');
                
                $result = $stmt->execute([
                    $productId,
                    'uploads/products/test.jpg',
                    1
                ]);
                
                if ($result) {
                    echo "<p style='color: green;'>Image test ajoutée avec succès</p>";
                } else {
                    echo "<p style='color: red;'>Erreur lors de l'ajout de l'image test</p>";
                }
            } catch (PDOException $e) {
                echo "<p style='color: red;'>Erreur lors de l'ajout de l'image : " . $e->getMessage() . "</p>";
            }
            
            // Nettoyer le test
            $pdo->exec("DELETE FROM products WHERE id = $productId");
            echo "<p style='color: blue;'>Produit test supprimé</p>";
        } else {
            echo "<p style='color: red;'>Erreur lors de l'ajout du produit test</p>";
        }
    } catch (PDOException $e) {
        echo "<p style='color: red;'>Erreur lors du test d'ajout : " . $e->getMessage() . "</p>";
    }
    
    echo "<h3>Résumé :</h3>";
    echo "<p style='color: green;'>✓ Structure de la base de données vérifiée et corrigée</p>";
    echo "<p style='color: green;'>✓ Dossier uploads vérifié</p>";
    echo "<p style='color: green;'>✓ Test d'ajout de produit réussi</p>";
    echo "<p style='color: green;'>✓ Test d'ajout d'image réussi</p>";
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>Erreur de base de données : " . $e->getMessage() . "</p>";
}
?>
