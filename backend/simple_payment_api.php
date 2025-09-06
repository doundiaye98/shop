<?php
// API de paiement simplifiée sans Composer
require_once "db.php";

// Démarrer la session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION["user_id"])) {
    http_response_code(401);
    echo json_encode([
        "success" => false,
        "error" => "Utilisateur non connecté"
    ]);
    exit;
}

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $input = json_decode(file_get_contents("php://input"), true);
    
    try {
        // Récupérer le panier de l'utilisateur
        $stmt = $pdo->prepare("
            SELECT c.*, p.name, p.price, p.image 
            FROM cart c 
            JOIN products p ON c.product_id = p.id 
            WHERE c.user_id = ?
        ");
        $stmt->execute([$_SESSION["user_id"]]);
        $cart_items = $stmt->fetchAll();
        
        if (empty($cart_items)) {
            throw new Exception("Panier vide");
        }
        
        // Calculer le total
        $total_amount = 0;
        foreach ($cart_items as $item) {
            $total_amount += $item["price"] * $item["quantity"];
        }
        
        // Créer la commande en base
        $stmt = $pdo->prepare("
            INSERT INTO orders (
                user_id, total_amount, status, shipping_address, 
                shipping_city, shipping_postal_code, shipping_country,
                customer_email, customer_phone, customer_name, payment_method
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        $shipping_address = $input["address"] ?? "";
        $shipping_city = $input["city"] ?? "";
        $shipping_postal_code = $input["postalCode"] ?? "";
        $shipping_country = $input["country"] ?? "";
        $customer_email = $input["email"] ?? "";
        $customer_phone = $input["phone"] ?? "";
        $customer_name = ($input["firstName"] ?? "") . " " . ($input["lastName"] ?? "");
        $payment_method = $input["paymentMethod"] ?? "card";
        
        $stmt->execute([
            $_SESSION["user_id"],
            $total_amount,
            "pending",
            $shipping_address,
            $shipping_city,
            $shipping_postal_code,
            $shipping_country,
            $customer_email,
            $customer_phone,
            $customer_name,
            $payment_method
        ]);
        
        $order_id = $pdo->lastInsertId();
        
        // Ajouter les articles de la commande
        foreach ($cart_items as $item) {
            $stmt = $pdo->prepare("
                INSERT INTO order_items (
                    order_id, product_id, product_name, quantity, 
                    unit_price, total_price
                ) VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $order_id,
                $item["product_id"],
                $item["name"],
                $item["quantity"],
                $item["price"],
                $item["price"] * $item["quantity"]
            ]);
        }
        
        // Simuler un client_secret pour le test
        $client_secret = "pi_" . uniqid() . "_secret_" . uniqid();
        
        echo json_encode([
            "success" => true,
            "client_secret" => $client_secret,
            "payment_intent_id" => "pi_" . uniqid(),
            "order_id" => $order_id,
            "amount" => $total_amount,
            "message" => "Mode test - Paiement simulé"
        ]);
        
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "error" => $e->getMessage()
        ]);
    }
} else {
    http_response_code(405);
    echo json_encode([
        "success" => false,
        "error" => "Méthode non autorisée"
    ]);
}
?>
