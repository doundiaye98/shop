<?php
require_once "stripe_config.php";
require_once "db.php";

if (!file_exists("../vendor/autoload.php")) {
    http_response_code(500);
    echo "Stripe non installé";
    exit;
}

require_once "../vendor/autoload.php";

\Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);

$payload = @file_get_contents("php://input");
$sig_header = $_SERVER["HTTP_STRIPE_SIGNATURE"];
$event = null;

try {
    $event = \Stripe\Webhook::constructEvent(
        $payload, $sig_header, STRIPE_WEBHOOK_SECRET
    );
} catch(\UnexpectedValueException $e) {
    http_response_code(400);
    exit();
} catch(\Stripe\Exception\SignatureVerificationException $e) {
    http_response_code(400);
    exit();
}

// Gérer les événements
switch ($event->type) {
    case "payment_intent.succeeded":
        $payment_intent = $event->data->object;
        
        // Mettre à jour le statut de la commande
        $stmt = $pdo->prepare("
            UPDATE orders 
            SET status = "paid" 
            WHERE payment_intent_id = ?
        ");
        $stmt->execute([$payment_intent->id]);
        
        // Vider le panier de l'utilisateur
        $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ?");
        $stmt->execute([$payment_intent->metadata->user_id]);
        
        break;
        
    case "payment_intent.payment_failed":
        $payment_intent = $event->data->object;
        
        // Mettre à jour le statut de la commande
        $stmt = $pdo->prepare("
            UPDATE orders 
            SET status = "cancelled" 
            WHERE payment_intent_id = ?
        ");
        $stmt->execute([$payment_intent->id]);
        
        break;
}

http_response_code(200);
echo "Webhook traité avec succès";
?>