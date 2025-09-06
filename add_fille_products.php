<?php
// Script pour ajouter les produits Fille
require_once 'backend/db.php';

echo "<h2>👗 Ajout des produits Fille</h2>";

try {
    echo "✅ Connexion à la base de données réussie<br><br>";
    
    // Produits à ajouter
    $products = [
        [
            'name' => 'Ensemble 3 pièces (jupe, chemise, gilet)',
            'description' => 'Très stylé disponible en deux couleurs (rouge et marron) 4-7 ans',
            'price' => 30.00,
            'stock' => 10,
            'image' => 'backend/madakids-fille/ensemble 3 pièces (jupe, chemise, gilet.jpg'
        ],
        [
            'name' => 'Ensemble 2 pièces (pull et pantalon)',
            'description' => 'Disponible en deux couleurs (marron et rose) 4-7 ans',
            'price' => 30.00,
            'stock' => 10,
            'image' => 'backend/madakids-fille/Ensemble 2 pièces (pull et pantalon).jpg'
        ],
        [
            'name' => 'Robe de sortie',
            'description' => 'Élégante et confortable pour toutes occasions 2-7 ans',
            'price' => 25.00,
            'stock' => 15,
            'image' => 'backend/madakids-fille/robe de sortie.jpg'
        ],
        [
            'name' => 'Ensemble 2 pièces très stylé',
            'description' => 'Look moderne et tendance pour les petites filles 2-5 ans',
            'price' => 25.00,
            'stock' => 12,
            'image' => 'backend/madakids-fille/Ensemble 2 pièces très stylé.jpg'
        ],
        [
            'name' => 'Ensemble 3 pièces (pantalon, t-shirt longue manche, veste)',
            'description' => 'Complet et pratique pour les tout-petits 9-24 mois',
            'price' => 25.00,
            'stock' => 8,
            'image' => 'backend/madakids-fille/Ensemble 3 pèces ( patalon, t-shirt longue manche, veste.jpg'
        ],
        [
            'name' => 'Ensemble 2 pièces + sacoche',
            'description' => 'Look complet avec accessoire pratique 3-6 ans',
            'price' => 25.00,
            'stock' => 10,
            'image' => 'backend/madakids-fille/Ensemble 2 pièces  + sacoche.jpg'
        ],
        [
            'name' => 'Robe avec sacoche',
            'description' => 'Élégante robe accompagnée d\'une sacoche assortie 4-7 ans',
            'price' => 25.00,
            'stock' => 12,
            'image' => 'backend/madakids-fille/Robe avec sacoche.jpg'
        ],
        [
            'name' => 'Robe avec pull + sacoche',
            'description' => 'Ensemble complet avec pull et sacoche pour un look sophistiqué 4-7 ans',
            'price' => 25.00,
            'stock' => 10,
            'image' => 'backend/madakids-fille/Robe avec pull + sacoche.jpg'
        ],
        [
            'name' => 'Robe + poupée Barbie',
            'description' => 'Robe élégante accompagnée d\'une poupée Barbie pour le plaisir des petites filles 2-5 ans',
            'price' => 25.00,
            'stock' => 8,
            'image' => 'backend/madakids-fille/Robe + poupée barbie.jpg'
        ],
        [
            'name' => 'Ensemble 2 pièces rose (pull et pantalon)',
            'description' => 'Ensemble rose tendance pour un look girly et moderne 4-7 ans',
            'price' => 30.00,
            'stock' => 10,
            'image' => 'backend/madakids-fille/Ensemble 2 pièces rose (pull et pantalon).jpg'
        ],
        [
            'name' => 'Ensemble 3 pièces (jupe, chemise, gilet) Rouge',
            'description' => 'Très stylé en couleur rouge disponible 4-7 ans',
            'price' => 30.00,
            'stock' => 8,
            'image' => 'backend/madakids-fille/ensemble 3 pièces rouge.jpg'
        ],
        [
            'name' => 'Robe avec pull',
            'description' => 'Robe élégante avec pull assorti pour un look chaleureux et stylé 4-7 ans',
            'price' => 25.00,
            'stock' => 12,
            'image' => 'backend/madakids-fille/Robe avec pull.jpg'
        ],
        [
            'name' => 'Robe de fête ou pour grande occasion',
            'description' => 'Robe de soirée élégante et raffinée pour les grandes occasions 4-7 ans',
            'price' => 30.00,
            'stock' => 6,
            'image' => 'backend/madakids-fille/Robe de fête.jpg'
        ],
        [
            'name' => 'Attache cheveux',
            'description' => 'Accessoire indispensable pour coiffer les cheveux des petites filles avec style',
            'price' => 5.00,
            'stock' => 20,
            'image' => 'backend/madakids-fille/Attache cheveux.jpg'
        ],
        [
            'name' => 'Collant et attache cheveux par lot de 2',
            'description' => 'Lot de 2 collants assortis avec attache cheveux pour un look coordonné',
            'price' => 5.00,
            'stock' => 15,
            'image' => 'backend/madakids-fille/Collant et attache cheveux.jpg'
        ],
        [
            'name' => 'Ensemble 3 pièces (pantalon, pull, gilet)',
            'description' => 'Ensemble complet et élégant pour toutes les occasions 4-7 ans',
            'price' => 30.00,
            'stock' => 10,
            'image' => 'backend/madakids-fille/Ensembles 3pièces (pantalon, pull, gilet).jpg'
        ],
        [
            'name' => 'Robe de fête ou pour grande occasion',
            'description' => 'Robe de soirée sophistiquée pour les événements spéciaux 4-7 ans',
            'price' => 25.00,
            'stock' => 8,
            'image' => 'backend/madakids-fille/Robe de fête occasion.jpg'
        ],
        [
            'name' => 'Jean fille',
            'description' => 'Jean confortable et stylé pour les petites filles actives. Taille disponible de 3-16 ans',
            'price' => 15.00,
            'stock' => 20,
            'image' => 'backend/madakids-fille/Jean fille.jpg'
        ],
        [
            'name' => 'Jean',
            'description' => 'Jean classique et intemporel pour toutes les occasions. Taille disponible de 3-16 ans',
            'price' => 15.00,
            'stock' => 18,
            'image' => 'backend/madakids-fille/Jean.jpg'
        ],
        [
            'name' => 'Robe de sortie rose',
            'description' => 'Robe rose élégante et romantique pour les petites princesses 2-7 ans',
            'price' => 25.00,
            'stock' => 12,
            'image' => 'backend/madakids-fille/Robe de sortie rose.jpg'
        ]
    ];
    
    echo "🎯 Produits à ajouter : " . count($products) . "<br><br>";
    
    // Demander confirmation
    echo "<form method='post'>";
    echo "<input type='submit' name='confirm' value='👗 Confirmer l\'ajout des produits Fille' style='background: #e91e63; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;'>";
    echo "</form>";
    
    if (isset($_POST['confirm'])) {
        echo "<br>🔄 Ajout des produits en cours...<br><br>";
        
        $addedCount = 0;
        
        foreach ($products as $product) {
            try {
                $stmt = $pdo->prepare("
                    INSERT INTO products (name, category, description, price, stock, image, created_at) 
                    VALUES (?, ?, ?, ?, ?, ?, NOW())
                ");
                
                $result = $stmt->execute([
                    $product['name'],
                    'Fille',
                    $product['description'],
                    $product['price'],
                    $product['stock'],
                    $product['image']
                ]);
                
                if ($result) {
                    echo "✅ Ajouté : {$product['name']} - {$product['price']} €<br>";
                    $addedCount++;
                } else {
                    echo "❌ Erreur lors de l'ajout de : {$product['name']}<br>";
                }
                
            } catch (PDOException $e) {
                echo "❌ Erreur pour {$product['name']} : " . $e->getMessage() . "<br>";
            }
        }
        
        echo "<br>📊 Résumé de l'ajout :<br>";
        echo "Produits ajoutés : $addedCount sur " . count($products) . "<br><br>";
        
        // Vérifier l'état final
        echo "🔍 État final de la base de données :<br>";
        $stmt = $pdo->query("SELECT category, COUNT(*) as count FROM products GROUP BY category ORDER BY category");
        $categories = $stmt->fetchAll();
        
        foreach ($categories as $category) {
            echo "- {$category['category']} : {$category['count']} produit(s)<br>";
        }
        
        echo "<br>✅ Ajout des produits Fille terminé !";
    }
    
} catch (PDOException $e) {
    echo "❌ Erreur de base de données : " . $e->getMessage();
}
?>
