<?php
// Script pour corriger les anciennes images
session_start();

// Vérifier si l'utilisateur est admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die('Accès non autorisé');
}

require_once 'backend/db.php';

echo "<h2>Correction des anciennes images</h2>";

try {
    // Vérifier s'il y a des images avec des chemins incorrects
    echo "<h3>Vérification des chemins d'images :</h3>";
    
    $stmt = $pdo->query("SELECT * FROM product_images ORDER BY id DESC LIMIT 20");
    $images = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $fixedCount = 0;
    $errorCount = 0;
    
    foreach ($images as $image) {
        $currentPath = $image['image_path'];
        $fileExists = file_exists($currentPath);
        
        echo "<p>Image ID {$image['id']}: <strong>$currentPath</strong> - ";
        
        if ($fileExists) {
            echo "<span style='color: green;'>Fichier existe</span></p>";
        } else {
            echo "<span style='color: red;'>Fichier manquant</span></p>";
            
            // Essayer de trouver le fichier dans d'autres emplacements
            $possiblePaths = [
                $currentPath,
                str_replace('uploads/products/', 'uploads/', $currentPath),
                str_replace('uploads/products/', '', $currentPath),
                'uploads/' . basename($currentPath),
                'uploads/products/' . basename($currentPath)
            ];
            
            $found = false;
            foreach ($possiblePaths as $path) {
                if (file_exists($path)) {
                    echo "<p style='color: blue;'>→ Fichier trouvé à: $path</p>";
                    
                    // Mettre à jour le chemin en base
                    try {
                        $updateStmt = $pdo->prepare("UPDATE product_images SET image_path = ? WHERE id = ?");
                        $updateStmt->execute([$path, $image['id']]);
                        echo "<p style='color: green;'>→ Chemin corrigé en base de données</p>";
                        $fixedCount++;
                        $found = true;
                        break;
                    } catch (PDOException $e) {
                        echo "<p style='color: red;'>→ Erreur lors de la mise à jour: " . $e->getMessage() . "</p>";
                        $errorCount++;
                    }
                }
            }
            
            if (!$found) {
                echo "<p style='color: orange;'>→ Fichier introuvable, suppression de l'entrée en base</p>";
                
                // Supprimer l'entrée en base si le fichier n'existe pas
                try {
                    $deleteStmt = $pdo->prepare("DELETE FROM product_images WHERE id = ?");
                    $deleteStmt->execute([$image['id']]);
                    echo "<p style='color: green;'>→ Entrée supprimée de la base de données</p>";
                } catch (PDOException $e) {
                    echo "<p style='color: red;'>→ Erreur lors de la suppression: " . $e->getMessage() . "</p>";
                    $errorCount++;
                }
            }
        }
    }
    
    echo "<h3>Résumé des corrections :</h3>";
    echo "<p style='color: green;'>✓ Images corrigées : $fixedCount</p>";
    echo "<p style='color: red;'>✗ Erreurs : $errorCount</p>";
    
    // Vérifier les images après correction
    echo "<h3>État après correction :</h3>";
    
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM product_images");
    $totalImages = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    $stmt = $pdo->query("
        SELECT COUNT(*) as existing 
        FROM product_images pi 
        WHERE EXISTS (
            SELECT 1 FROM products p WHERE p.id = pi.product_id
        )
    ");
    $existingProducts = $stmt->fetch(PDO::FETCH_ASSOC)['existing'];
    
    echo "<p>Total d'images en base : $totalImages</p>";
    echo "<p>Images avec produits existants : $existingProducts</p>";
    
    // Lister les produits avec leurs images
    echo "<h3>Produits avec images :</h3>";
    
    $stmt = $pdo->query("
        SELECT p.id, p.name, COUNT(pi.id) as image_count,
               GROUP_CONCAT(pi.image_path ORDER BY pi.image_order) as image_paths
        FROM products p 
        LEFT JOIN product_images pi ON p.id = pi.product_id 
        GROUP BY p.id, p.name 
        HAVING image_count > 0 
        ORDER BY p.id DESC 
        LIMIT 10
    ");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($products)) {
        echo "<p>Aucun produit avec images trouvé.</p>";
    } else {
        echo "<table border='1'><tr><th>ID</th><th>Nom</th><th>Images</th><th>Chemins</th></tr>";
        foreach ($products as $product) {
            echo "<tr>";
            echo "<td>" . $product['id'] . "</td>";
            echo "<td>" . htmlspecialchars($product['name']) . "</td>";
            echo "<td>" . $product['image_count'] . "</td>";
            echo "<td>" . htmlspecialchars($product['image_paths']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
    // Vérifier les fichiers dans le dossier uploads
    echo "<h3>Fichiers dans uploads/products/ :</h3>";
    
    $uploadDir = 'uploads/products/';
    if (is_dir($uploadDir)) {
        $files = scandir($uploadDir);
        $imageFiles = array_filter($files, function($file) {
            return in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif']);
        });
        
        if (empty($imageFiles)) {
            echo "<p>Aucun fichier image trouvé dans le dossier uploads/products/</p>";
        } else {
            echo "<p>Fichiers trouvés : " . count($imageFiles) . "</p>";
            echo "<ul>";
            foreach ($imageFiles as $file) {
                $filePath = $uploadDir . $file;
                $fileSize = filesize($filePath);
                $fileDate = date('Y-m-d H:i:s', filemtime($filePath));
                echo "<li><strong>$file</strong> - " . number_format($fileSize / 1024, 2) . " KB - $fileDate</li>";
            }
            echo "</ul>";
        }
    } else {
        echo "<p>Le dossier uploads/products/ n'existe pas.</p>";
    }
    
    echo "<h3>Actions recommandées :</h3>";
    echo "<p>1. Vérifiez que les images s'affichent maintenant dans le dashboard</p>";
    echo "<p>2. Si des images sont encore manquantes, vérifiez qu'elles sont dans le bon dossier</p>";
    echo "<p>3. Vous pouvez maintenant ajouter de nouveaux produits avec images</p>";
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>Erreur de base de données : " . $e->getMessage() . "</p>";
}
?>
