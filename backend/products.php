<?php
require_once 'db.php';

$category = isset($_GET['category']) ? $_GET['category'] : null;

if ($category) {
    $stmt = $pdo->prepare('SELECT products.*, categories.name AS category FROM products LEFT JOIN categories ON products.category_id = categories.id WHERE categories.name = ? ORDER BY products.created_at DESC');
    $stmt->execute([$category]);
} else {
    $stmt = $pdo->query('SELECT products.*, categories.name AS category FROM products LEFT JOIN categories ON products.category_id = categories.id ORDER BY products.created_at DESC');
}
$products = $stmt->fetchAll();

header('Content-Type: application/json');
echo json_encode($products); 