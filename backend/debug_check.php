<?php
require_once 'db.php';

// Afficher les catégories
echo "<h2>Catégories</h2><ul>";
$stmt = $pdo->query('SELECT * FROM categories');
while ($row = $stmt->fetch()) {
    echo "<li>ID: {$row['id']} — Nom: {$row['name']}</li>";
}
echo "</ul>";

// Afficher les produits et leur catégorie
echo "<h2>Produits</h2><ul>";
$stmt = $pdo->query('SELECT products.id, products.name, products.category_id, categories.name AS category_name FROM products LEFT JOIN categories ON products.category_id = categories.id');
while ($row = $stmt->fetch()) {
    echo "<li>ID: {$row['id']} — {$row['name']} — category_id: {$row['category_id']} — Catégorie: {$row['category_name']}</li>";
}
echo "</ul>";
?> 