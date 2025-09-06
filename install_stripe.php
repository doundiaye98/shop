<?php
// Guide d'installation Stripe
echo "<h2>💳 Installation de Stripe</h2>";
echo "<p>Suivez ces étapes pour intégrer Stripe à votre boutique :</p>";
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Installation Stripe - Ma Boutique</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .step-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }
        .step-number {
            background: linear-gradient(135deg, #6772e5 0%, #6772e5 100%);
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 1rem;
        }
        .code-block {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 1rem;
            font-family: 'Courier New', monospace;
            margin: 1rem 0;
        }
        .warning {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 1rem;
            margin: 1rem 0;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="step-card">
            <h3><span class="step-number">1</span>Créer un compte Stripe</h3>
            <p>Allez sur <a href="https://dashboard.stripe.com/register" target="_blank">https://dashboard.stripe.com/register</a></p>
            <ul>
                <li>Créez votre compte Stripe</li>
                <li>Complétez la vérification de votre identité</li>
                <li>Activez votre compte</li>
            </ul>
        </div>

        <div class="step-card">
            <h3><span class="step-number">2</span>Récupérer vos clés API</h3>
            <p>Dans votre dashboard Stripe :</p>
            <ul>
                <li>Allez dans <strong>Developers > API keys</strong></li>
                <li>Copiez votre <strong>Publishable key</strong> (pk_test_...)</li>
                <li>Copiez votre <strong>Secret key</strong> (sk_test_...)</li>
            </ul>
            <div class="warning">
                <strong>⚠️ Important :</strong> Utilisez les clés de test (test mode) pour le développement, 
                et les clés de production (live mode) pour votre site en ligne.
            </div>
        </div>

        <div class="step-card">
            <h3><span class="step-number">3</span>Installer la bibliothèque Stripe</h3>
            <p>Créez un fichier <code>composer.json</code> à la racine de votre projet :</p>
            <div class="code-block">
{
    "require": {
        "stripe/stripe-php": "^10.0"
    }
}
            </div>
            <p>Puis exécutez :</p>
            <div class="code-block">
composer install
            </div>
        </div>

        <div class="step-card">
            <h3><span class="step-number">4</span>Configurer les clés API</h3>
            <p>Créez un fichier <code>backend/stripe_config.php</code> :</p>
            <div class="code-block">
&lt;?php
// Configuration Stripe
define('STRIPE_PUBLISHABLE_KEY', 'pk_test_votre_cle_publique');
define('STRIPE_SECRET_KEY', 'sk_test_votre_cle_secrete');

// Mode de test ou production
define('STRIPE_MODE', 'test'); // Changez à 'live' pour la production

// Devise
define('STRIPE_CURRENCY', 'eur');

// Webhook secret (à configurer plus tard)
define('STRIPE_WEBHOOK_SECRET', 'whsec_votre_webhook_secret');
?&gt;
            </div>
        </div>

        <div class="step-card">
            <h3><span class="step-number">5</span>Créer l'API de paiement</h3>
            <p>Créez un fichier <code>backend/payment_api.php</code> :</p>
            <div class="code-block">
&lt;?php
require_once 'stripe_config.php';
require_once 'vendor/autoload.php';
require_once 'db.php';

\Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    try {
        // Créer l'intention de paiement
        $payment_intent = \Stripe\PaymentIntent::create([
            'amount' => $input['amount'] * 100, // Stripe utilise les centimes
            'currency' => STRIPE_CURRENCY,
            'payment_method_types' => ['card'],
            'metadata' => [
                'user_id' => $input['user_id'],
                'order_id' => $input['order_id']
            ]
        ]);
        
        echo json_encode([
            'success' => true,
            'client_secret' => $payment_intent->client_secret,
            'payment_intent_id' => $payment_intent->id
        ]);
        
    } catch (\Stripe\Exception\ApiErrorException $e) {
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
}
?&gt;
            </div>
        </div>

        <div class="step-card">
            <h3><span class="step-number">6</span>Mettre à jour la page de commande</h3>
            <p>Modifiez <code>commande.php</code> pour intégrer Stripe :</p>
            <div class="code-block">
&lt;script src="https://js.stripe.com/v3/"&gt;&lt;/script&gt;
&lt;script&gt;
const stripe = Stripe('pk_test_votre_cle_publique');
const elements = stripe.elements();

// Créer l'élément de carte
const card = elements.create('card');
card.mount('#card-element');

// Gérer la soumission
document.getElementById('orderForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const {paymentMethod, error} = await stripe.createPaymentMethod({
        type: 'card',
        card: card,
    });
    
    if (error) {
        console.error(error);
        return;
    }
    
    // Confirmer le paiement
    const {paymentIntent, error: confirmError} = await stripe.confirmCardPayment(
        clientSecret,
        {
            payment_method: paymentMethod.id,
        }
    );
    
    if (confirmError) {
        console.error(confirmError);
    } else {
        // Paiement réussi
        window.location.href = 'order_confirmation.php?order_id=' + orderId;
    }
});
&lt;/script&gt;
            </div>
        </div>

        <div class="step-card">
            <h3><span class="step-number">7</span>Configuration pour la production</h3>
            <ul>
                <li>Changez les clés de test vers les clés de production</li>
                <li>Configurez un certificat SSL</li>
                <li>Configurez les webhooks Stripe</li>
                <li>Testez tous les scénarios de paiement</li>
            </ul>
        </div>

        <div class="text-center mt-4">
            <a href="create_payment_system.php" class="btn btn-primary btn-lg">
                🚀 Créer le système de paiement complet
            </a>
        </div>
    </div>
</body>
</html>
