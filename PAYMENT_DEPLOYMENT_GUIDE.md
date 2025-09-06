# 🚀 Guide de déploiement du système de paiement

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
1. Vérifiez les logs d'erreur
2. Testez avec les cartes de test
3. Consultez la documentation Stripe

## 🎉 Déploiement réussi !

Votre boutique est maintenant prête pour les paiements en ligne !