<?php
// Script pour ajouter automatiquement des produits dans la catégorie Garçon
require_once 'backend/db.php';

echo "<h2>👕 Ajout automatique de produits dans la catégorie Garçon</h2>";

try {
    // Vérifier la connexion
    echo "<h3>✅ Connexion à la base de données</h3>";
    echo "<p>Base de données : " . $pdo->query("SELECT DATABASE()")->fetchColumn() . "</p>";
    
    // Produits à ajouter
    $products = [
        [
            'name' => 'Chemise en coton',
            'category' => 'Garçon',
            'description' => 'Chemise en coton de qualité, confortable et respirante. Parfaite pour toutes les occasions.',
            'price' => 12.50,
            'stock' => 50,
            'image' => 'https://via.placeholder.com/300x400?text=Chemise+Coton'
        ],
        [
            'name' => 'Ensemble 3 pièces - Basique',
            'category' => 'Garçon',
            'description' => 'Ensemble complet comprenant pantalon, t-shirt à manches longues et chemise. Idéal pour le quotidien et les occasions spéciales.',
            'price' => 25.00,
            'stock' => 30,
            'image' => 'https://via.placeholder.com/300x400?text=Ensemble+3+Pièces'
        ],
        [
            'name' => 'Ensemble 3 pièces - Sport',
            'category' => 'Garçon',
            'description' => 'Ensemble sportif avec short, t-shirt à manches courtes et short en jean. Parfait pour les activités en plein air.',
            'price' => 25.00,
            'stock' => 35,
            'image' => 'https://via.placeholder.com/300x400?text=Ensemble+Sport'
        ],
        [
            'name' => 'Ensemble 2 pièces - Casual',
            'category' => 'Garçon',
            'description' => 'Ensemble décontracté et élégant, parfait pour les sorties en famille et les activités quotidiennes. Design moderne et confortable.',
            'price' => 25.00,
            'stock' => 40,
            'image' => 'https://via.placeholder.com/300x400?text=Ensemble+2+Pièces'
        ],
        [
            'name' => 'Ensemble 3 pièces - Classique',
            'category' => 'Garçon',
            'description' => 'Ensemble élégant et stylé comprenant pantalon, t-shirt à manches longues et veste. Parfait pour les occasions formelles et les événements spéciaux.',
            'price' => 40.00,
            'stock' => 25,
            'image' => 'https://via.placeholder.com/300x400?text=Ensemble+Classique'
        ],
        [
            'name' => 'Chaussettes par lot de 6',
            'category' => 'Garçon',
            'description' => 'Lot de 6 paires de chaussettes de qualité, confortables et durables. Disponibles en plusieurs coloris et motifs. Parfaites pour tous les jours.',
            'price' => 10.00,
            'stock' => 100,
            'image' => 'https://via.placeholder.com/300x400?text=Chaussettes+Lot+6'
        ],
        [
            'name' => 'Jean Cargo - Unisexe',
            'category' => 'Garçon',
            'description' => 'Jean cargo polyvalent et confortable, adapté aux garçons et filles. Nombreuses poches pratiques, coupe moderne et durable. Idéal pour le quotidien.',
            'price' => 15.00,
            'stock' => 60,
            'image' => 'https://via.placeholder.com/300x400?text=Jean+Cargo'
        ]
    ];
    
    // Afficher les produits à ajouter
    echo "<h3>📦 Produits à ajouter :</h3>";
    echo "<table border='1' style='border-collapse: collapse; width: 100%; margin-bottom: 20px;'>";
    echo "<tr><th>Nom</th><th>Catégorie</th><th>Prix</th><th>Stock</th></tr>";
    foreach ($products as $prod) {
        echo "<tr>";
        echo "<td>{$prod['name']}</td>";
        echo "<td>{$prod['category']}</td>";
        echo "<td>{$prod['price']} €</td>";
        echo "<td>{$prod['stock']}</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Procéder directement à l'ajout
    echo "<h3>➕ Ajout des produits en cours...</h3>";
    
    $addedCount = 0;
    $errors = [];
    
    foreach ($products as $product) {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO products (name, category, description, price, stock, image, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, NOW())
            ");
            
            $stmt->execute([
                $product['name'],
                $product['category'],
                $product['description'],
                $product['price'],
                $product['stock'],
                $product['image']
            ]);
            
            $addedCount++;
            echo "<p>✅ {$product['name']} - Ajouté avec succès</p>";
            
        } catch (PDOException $e) {
            $errors[] = "Erreur pour {$product['name']}: " . $e->getMessage();
            echo "<p>❌ {$product['name']} - Erreur lors de l'ajout</p>";
        }
    }
    
    echo "<hr>";
    echo "<h3>🎉 Résultat de l'ajout :</h3>";
    echo "<p>✅ {$addedCount} produit(s) ajouté(s) avec succès</p>";
    
    if (count($errors) > 0) {
        echo "<h4>❌ Erreurs rencontrées :</h4>";
        echo "<ul>";
        foreach ($errors as $error) {
            echo "<li>{$error}</li>";
        }
        echo "</ul>";
    }
    
    // Vérifier les produits de la catégorie Garçon
    echo "<h3>🔍 Produits de la catégorie Garçon après ajout :</h3>";
    $stmt = $pdo->prepare("SELECT id, name, price, stock FROM products WHERE category = 'Garçon' ORDER BY id");
    $stmt->execute();
    $garconProducts = $stmt->fetchAll();
    
    if (count($garconProducts) > 0) {
        echo "<table border='1' style='border-collapse: collapse; width: 100%; margin-bottom: 20px;'>";
        echo "<tr><th>ID</th><th>Nom</th><th>Prix</th><th>Stock</th></tr>";
        foreach ($garconProducts as $prod) {
            echo "<tr>";
            echo "<td>{$prod['id']}</td>";
            echo "<td>{$prod['name']}</td>";
            echo "<td>{$prod['price']} €</td>";
            echo "<td>{$prod['stock']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>Aucun produit trouvé dans la catégorie Garçon.</p>";
    }
    
    echo "<hr>";
    echo "<h3>🎯 Prochaines étapes :</h3>";
    echo "<ul>";
    echo "<li>✅ Les produits ont été ajoutés à votre base de données</li>";
    echo "<li>🌐 Ils sont maintenant visibles sur votre site</li>";
    echo "<li>🛒 Testez l'ajout au panier pour ces nouveaux produits</li>";
    echo "<li>📱 Vérifiez qu'ils s'affichent sur les pages d'accueil et nouveautés</li>";
    echo "</ul>";
    echo "<p><a href='index.php'>← Retour à l'accueil pour voir les nouveaux produits</a></p>";
    echo "<p><a href='garcon.php'>👕 Voir la page Garçon</a></p>";
    
} catch (PDOException $e) {
    echo "<h3>❌ Erreur :</h3>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "<p>Vérifiez que votre base de données est accessible.</p>";
}
?>
