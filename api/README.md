# API Directory

Ce dossier contient tous les endpoints API pour le déploiement.

## Structure

- `admin.php` - API d'administration (dashboard, gestion)
- `cart.php` - API du panier
- `favorites.php` - API des favoris
- `payment.php` - API de paiement (Stripe)
- `password_reset.php` - API de réinitialisation de mot de passe
- `direct_purchase.php` - API d'achat direct

## Utilisation

Les fichiers JavaScript doivent pointer vers `api/nom_fichier.php` au lieu des anciens chemins.

Exemple:
```javascript
// Avant
fetch('admin_api.php?action=get_stats')

// Après
fetch('api/admin.php?action=get_stats')
```

## Sécurité

- Tous les fichiers vérifient l'authentification
- Les sessions sont requises pour la plupart des endpoints
- Les erreurs ne sont pas affichées en production
