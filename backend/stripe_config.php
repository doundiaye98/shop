<?php
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
?>