<?php
// Script de débogage simple pour les images
echo "<h2>🔍 Débogage des images - Diagnostic complet</h2>";

// 1. Vérifier la structure des dossiers
echo "<h3>📁 Structure des dossiers :</h3>";
echo "<p>Dossier actuel : " . getcwd() . "</p>";

// 2. Vérifier le dossier backend
echo "<h3>📂 Contenu du dossier backend :</h3>";
if (is_dir('backend')) {
    $files = scandir('backend');
    foreach ($files as $file) {
        if ($file != '.' && $file != '..') {
            $path = 'backend/' . $file;
            $type = is_dir($path) ? '📁 Dossier' : '📄 Fichier';
            $size = is_file($path) ? ' (' . number_format(filesize($path) / 1024, 1) . ' KB)' : '';
            echo "<p>{$type} : {$file}{$size}</p>";
        }
    }
} else {
    echo "<p>❌ Le dossier backend n'existe pas</p>";
}

// 3. Vérifier le dossier madakids
echo "<h3>📂 Contenu du dossier backend/madakids :</h3>";
if (is_dir('backend/madakids')) {
    $files = scandir('backend/madakids');
    $imageCount = 0;
    foreach ($files as $file) {
        if ($file != '.' && $file != '..') {
            $path = 'backend/madakids/' . $file;
            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) {
                $imageCount++;
                $size = number_format(filesize($path) / 1024, 1);
                echo "<p>🖼️ Image : {$file} ({$size} KB)</p>";
            }
        }
    }
    echo "<p><strong>Total images trouvées : {$imageCount}</strong></p>";
} else {
    echo "<p>❌ Le dossier backend/madakids n'existe pas</p>";
}

// 4. Tester l'accès direct aux images
echo "<h3>🖼️ Test d'accès direct aux images :</h3>";

$testImages = [
    'backend/chemise en coton.jpg',
    'backend/ensemble 3 pièces.jpg',
    'backend/madakids/IMG-20250830-WA0125.jpg',
    'backend/madakids/IMG-20250830-WA0127.jpg'
];

foreach ($testImages as $imagePath) {
    if (file_exists($imagePath)) {
        $size = number_format(filesize($imagePath) / 1024, 1);
        echo "<p>✅ {$imagePath} - Existe ({$size} KB)</p>";
        
        // Tester l'affichage
        echo "<div style='margin: 10px 0; padding: 10px; border: 1px solid #ddd;'>";
        echo "<p><strong>Test d'affichage :</strong></p>";
        echo "<img src='{$imagePath}' alt='Test' style='max-width: 200px; max-height: 200px; border: 1px solid #ccc;'>";
        echo "<p>Chemin : {$imagePath}</p>";
        echo "</div>";
    } else {
        echo "<p>❌ {$imagePath} - N'existe pas</p>";
    }
}

// 5. Vérifier les permissions
echo "<h3>🔐 Permissions des fichiers :</h3>";
foreach ($testImages as $imagePath) {
    if (file_exists($imagePath)) {
        $perms = fileperms($imagePath);
        $permsStr = substr(sprintf('%o', $perms), -4);
        $readable = is_readable($imagePath) ? '✅' : '❌';
        echo "<p>{$readable} {$imagePath} - Permissions : {$permsStr} - Lisible : " . (is_readable($imagePath) ? 'Oui' : 'Non') . "</p>";
    }
}

// 6. Vérifier la base de données
echo "<h3>🗄️ Vérification de la base de données :</h3>";
try {
    require_once 'backend/db.php';
    
    $stmt = $pdo->prepare("SELECT id, name, category, image FROM products WHERE category = 'Garçon' ORDER BY id");
    $stmt->execute();
    $products = $stmt->fetchAll();
    
    if (count($products) > 0) {
        echo "<p>✅ " . count($products) . " produits Garçon trouvés</p>";
        
        echo "<table border='1' style='border-collapse: collapse; width: 100%; margin-bottom: 20px;'>";
        echo "<tr><th>ID</th><th>Nom</th><th>Catégorie</th><th>Image dans DB</th><th>Fichier existe</th></tr>";
        
        foreach ($products as $prod) {
            $imageExists = file_exists($prod['image']) ? '✅ Oui' : '❌ Non';
            echo "<tr>";
            echo "<td>{$prod['id']}</td>";
            echo "<td>{$prod['name']}</td>";
            echo "<td>{$prod['category']}</td>";
            echo "<td>{$prod['image']}</td>";
            echo "<td>{$imageExists}</td>";
            echo "</tr>";
        }
        echo "</table>";
        
    } else {
        echo "<p>❌ Aucun produit Garçon trouvé dans la base de données</p>";
    }
    
} catch (Exception $e) {
    echo "<p>❌ Erreur base de données : " . $e->getMessage() . "</p>";
}

// 7. Test d'URL
echo "<h3>🌐 Test des URLs :</h3>";
$baseUrl = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']);
echo "<p>URL de base : {$baseUrl}</p>";

foreach ($testImages as $imagePath) {
    if (file_exists($imagePath)) {
        $fullUrl = $baseUrl . '/' . $imagePath;
        echo "<p>🔗 <a href='{$fullUrl}' target='_blank'>{$fullUrl}</a></p>";
    }
}

echo "<hr>";
echo "<h3>🎯 Actions recommandées :</h3>";
echo "<ul>";
echo "<li>1. Vérifiez que toutes les images existent physiquement</li>";
echo "<li>2. Vérifiez les permissions des fichiers</li>";
echo "<li>3. Testez les URLs directes des images</li>";
echo "<li>4. Vérifiez que la base de données contient les bons chemins</li>";
echo "</ul>";

echo "<p><a href='index.php'>🏠 Retour à l'accueil</a></p>";
?>
