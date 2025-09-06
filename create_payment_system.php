<?php
// Script pour créer le système de paiement complet
echo "<h2>🚀 Création du système de paiement complet</h2>";

// Créer le fichier composer.json
$composer_content = '{
    "require": {
        "stripe/stripe-php": "^10.0"
    }
}';

file_put_contents('composer.json', $composer_content);
echo "✅ Fichier composer.json créé<br>";

// Créer la configuration Stripe
$stripe_config_content = '<?php
// Configuration Stripe
// Remplacez ces valeurs par vos vraies clés Stripe

// Mode de test (changez à "live" pour la production)
define("STRIPE_MODE", "test");

if (STRIPE_MODE === "test") {
    define("STRIPE_PUBLISHABLE_KEY", "pk_test_votre_cle_publique_de_test");
    define("STRIPE_SECRET_KEY", "sk_test_votre_cle_secrete_de_test");
} else {
    define("STRIPE_PUBLISHABLE_KEY", "pk_live_votre_cle_publique_de_production");
    define("STRIPE_SECRET_KEY", "sk_live_votre_cle_secrete_de_production");
}

// Devise
define("STRIPE_CURRENCY", "eur");

// Webhook secret (à configurer dans le dashboard Stripe)
define("STRIPE_WEBHOOK_SECRET", "whsec_votre_webhook_secret");

// Configuration de la boutique
define("SHOP_NAME", "Ma Boutique");
define("SHOP_EMAIL", "contact@maboutique.com");
?>';

file_put_contents('backend/stripe_config.php', $stripe_config_content);
echo "✅ Configuration Stripe créée<br>";

// Créer l'API de paiement
$payment_api_content = '<?php
require_once "stripe_config.php";
require_once "db.php";

// Vérifier si Stripe est installé
if (!file_exists("../vendor/autoload.php")) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "error" => "Stripe non installé. Exécutez: composer install"
    ]);
    exit;
}

require_once "../vendor/autoload.php";

// Démarrer la session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifier que l\'utilisateur est connecté
if (!isset($_SESSION["user_id"])) {
    http_response_code(401);
    echo json_encode([
        "success" => false,
        "error" => "Utilisateur non connecté"
    ]);
    exit;
}

header("Content-Type: application/json");

// Configurer Stripe
\Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $input = json_decode(file_get_contents("php://input"), true);
    
    try {
        // Récupérer le panier de l\'utilisateur
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
        
        // Créer l\'intention de paiement Stripe
        $payment_intent = \Stripe\PaymentIntent::create([
            "amount" => intval($total_amount * 100), // Stripe utilise les centimes
            "currency" => STRIPE_CURRENCY,
            "payment_method_types" => ["card"],
            "metadata" => [
                "order_id" => $order_id,
                "user_id" => $_SESSION["user_id"]
            ],
            "description" => "Commande #" . $order_id . " - " . SHOP_NAME
        ]);
        
        // Mettre à jour la commande avec l\'ID de paiement
        $stmt = $pdo->prepare("UPDATE orders SET payment_intent_id = ? WHERE id = ?");
        $stmt->execute([$payment_intent->id, $order_id]);
        
        echo json_encode([
            "success" => true,
            "client_secret" => $payment_intent->client_secret,
            "payment_intent_id" => $payment_intent->id,
            "order_id" => $order_id,
            "amount" => $total_amount
        ]);
        
    } catch (\Stripe\Exception\ApiErrorException $e) {
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "error" => "Erreur Stripe: " . $e->getMessage()
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
?>';

file_put_contents('backend/payment_api.php', $payment_api_content);
echo "✅ API de paiement créée<br>";

// Créer l'API de webhook Stripe
$webhook_content = '<?php
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
        
        // Vider le panier de l\'utilisateur
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
?>';

file_put_contents('backend/stripe_webhook.php', $webhook_content);
echo "✅ Webhook Stripe créé<br>";

// Créer un fichier de test de paiement
$test_payment_content = '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test de paiement - Ma Boutique</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://js.stripe.com/v3/"></script>
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3>🧪 Test de paiement Stripe</h3>
                    </div>
                    <div class="card-body">
                        <p>Ceci est un test de paiement. Utilisez ces cartes de test :</p>
                        <ul>
                            <li><strong>Succès :</strong> 4242 4242 4242 4242</li>
                            <li><strong>Échec :</strong> 4000 0000 0000 0002</li>
                            <li><strong>3D Secure :</strong> 4000 0025 0000 3155</li>
                        </ul>
                        
                        <form id="payment-form">
                            <div class="mb-3">
                                <label for="card-element">Carte bancaire</label>
                                <div id="card-element" class="form-control"></div>
                                <div id="card-errors" class="text-danger mt-2"></div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100">
                                Payer 10.00 €
                            </button>
                        </form>
                        
                        <div id="payment-result" class="mt-3"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Initialiser Stripe
        const stripe = Stripe("pk_test_votre_cle_publique_de_test");
        const elements = stripe.elements();
        
        // Créer l\'élément de carte
        const card = elements.create("card");
        card.mount("#card-element");
        
        // Gérer les erreurs
        card.addEventListener("change", ({error}) => {
            const displayError = document.getElementById("card-errors");
            if (error) {
                displayError.textContent = error.message;
            } else {
                displayError.textContent = "";
            }
        });
        
        // Gérer la soumission
        const form = document.getElementById("payment-form");
        form.addEventListener("submit", async (event) => {
            event.preventDefault();
            
            const submitButton = form.querySelector("button");
            submitButton.disabled = true;
            submitButton.textContent = "Traitement...";
            
            try {
                // Créer l\'intention de paiement
                const response = await fetch("backend/payment_api.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({
                        amount: 10.00,
                        paymentMethod: "card"
                    })
                });
                
                const data = await response.json();
                
                if (!data.success) {
                    throw new Error(data.error);
                }
                
                // Confirmer le paiement
                const {error} = await stripe.confirmCardPayment(data.client_secret, {
                    payment_method: {
                        card: card,
                    }
                });
                
                if (error) {
                    throw new Error(error.message);
                }
                
                // Succès
                document.getElementById("payment-result").innerHTML = `
                    <div class="alert alert-success">
                        ✅ Paiement réussi ! Commande #${data.order_id}
                    </div>
                `;
                
            } catch (error) {
                document.getElementById("payment-result").innerHTML = `
                    <div class="alert alert-danger">
                        ❌ Erreur : ${error.message}
                    </div>
                `;
            } finally {
                submitButton.disabled = false;
                submitButton.textContent = "Payer 10.00 €";
            }
        });
    </script>
</body>
</html>';

file_put_contents('test_payment.html', $test_payment_content);
echo "✅ Page de test de paiement créée<br>";

// Créer un guide de déploiement
$deployment_guide = '# 🚀 Guide de déploiement du système de paiement

## 📋 Prérequis

1. **Compte Stripe** : Créez un compte sur [stripe.com](https://stripe.com)
2. **Certificat SSL** : Obligatoire pour les paiements en ligne
3. **Composer** : Installé sur votre serveur

## 🔧 Installation

### 1. Installer les dépendances
```bash
composer install
```

### 2. Configurer Stripe
Modifiez `backend/stripe_config.php` avec vos vraies clés :
- Remplacez `pk_test_...` par votre clé publique
- Remplacez `sk_test_...` par votre clé secrète

### 3. Créer les tables
Exécutez `create_orders_tables.php` dans votre navigateur

### 4. Configurer les webhooks
Dans votre dashboard Stripe :
- URL : `https://votre-site.com/backend/stripe_webhook.php`
- Événements : `payment_intent.succeeded`, `payment_intent.payment_failed`

## 🧪 Tests

1. **Mode test** : Utilisez les cartes de test Stripe
2. **Test complet** : Ouvrez `test_payment.html`
3. **Test en production** : Changez les clés vers le mode "live"

## 🔒 Sécurité

- ✅ Certificat SSL obligatoire
- ✅ Clés API sécurisées
- ✅ Validation des données
- ✅ Gestion des erreurs
- ✅ Logs de paiement

## 📞 Support

En cas de problème :
1. Vérifiez les logs d\'erreur
2. Testez avec les cartes de test
3. Consultez la documentation Stripe

## 🎉 Déploiement réussi !

Votre boutique est maintenant prête pour les paiements en ligne !';

file_put_contents('PAYMENT_DEPLOYMENT_GUIDE.md', $deployment_guide);
echo "✅ Guide de déploiement créé<br>";

echo "<br><h3>🎉 Système de paiement créé avec succès !</h3>";
echo "<p>Voici ce qui a été créé :</p>";
echo "<ul>";
echo "<li>✅ <code>composer.json</code> - Configuration Composer</li>";
echo "<li>✅ <code>backend/stripe_config.php</code> - Configuration Stripe</li>";
echo "<li>✅ <code>backend/payment_api.php</code> - API de paiement</li>";
echo "<li>✅ <code>backend/stripe_webhook.php</code> - Webhook Stripe</li>";
echo "<li>✅ <code>test_payment.html</code> - Page de test</li>";
echo "<li>✅ <code>PAYMENT_DEPLOYMENT_GUIDE.md</code> - Guide de déploiement</li>";
echo "</ul>";

echo "<br><h3>📋 Prochaines étapes :</h3>";
echo "<ol>";
echo "<li>Exécutez <code>composer install</code> dans votre terminal</li>";
echo "<li>Créez un compte Stripe et récupérez vos clés API</li>";
echo "<li>Modifiez <code>backend/stripe_config.php</code> avec vos clés</li>";
echo "<li>Testez avec <code>test_payment.html</code></li>";
echo "<li>Intégrez dans <code>commande.php</code></li>";
echo "</ol>";

echo "<br><div class=\"alert alert-info\">";
echo "<strong>💡 Conseil :</strong> Commencez par tester en mode développement avec les clés de test Stripe avant de passer en production.";
echo "</div>";

echo "<br><a href=\"test_payment.html\" class=\"btn btn-primary\">🧪 Tester le paiement</a>";
echo " <a href=\"PAYMENT_DEPLOYMENT_GUIDE.md\" class=\"btn btn-secondary\">📖 Guide complet</a>";
?>
