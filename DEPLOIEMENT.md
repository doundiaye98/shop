# 🚀 Guide de Déploiement

## ✅ Ce qui est prêt

Votre application est maintenant configurée pour fonctionner automatiquement en **local** et en **production**.

### 📁 Structure organisée

```
shop/
├── api/                    ← Dossier API pour la production
│   ├── admin.php
│   ├── cart.php
│   ├── favorites.php
│   ├── payment.php
│   ├── password_reset.php
│   └── direct_purchase.php
├── backend/                ← Fichiers backend (requis)
│   ├── db.php
│   ├── login.php
│   ├── register.php
│   └── ...
├── config.js              ← Configuration JavaScript
├── config.php             ← Configuration PHP
├── admin_dashboard_modern.php
├── admin_messaging.js
└── ...
```

## 🔧 Configuration automatique

### JavaScript (config.js)
- ✅ Détecte automatiquement local/production
- ✅ Change les chemins API selon l'environnement
- ✅ Mode debug en local uniquement

### PHP (config.php)
- ✅ Détecte automatiquement local/production
- ✅ Gère les chemins backend
- ✅ Configuration centralisée

## 📦 Fichiers à déployer sur le serveur

### 1. Dossiers obligatoires
```
✅ api/              (tous les fichiers)
✅ backend/          (tous les fichiers)
✅ assets/           (CSS, images)
✅ uploads/          (si vous avez des uploads)
```

### 2. Fichiers racine importants
```
✅ config.js
✅ config.php
✅ index.php
✅ login.php
✅ admin_dashboard_modern.php
✅ admin_messaging.js
✅ admin-modern.css
✅ navbar-ecommerce.js
✅ products.php
✅ panier.php
✅ favorites.php
✅ contact.php
✅ ... (tous vos fichiers .php principaux)
```

### 3. Fichiers à NE PAS déployer
```
❌ test_*.php          (fichiers de test)
❌ create_*.php        (scripts de création)
❌ check_*.php         (scripts de vérification)
❌ clear_*.php         (scripts de nettoyage)
❌ execute_*.php       (scripts d'exécution)
❌ debug_*.php         (fichiers de debug)
❌ .git/               (si vous utilisez Git)
```

## 🗄️ Configuration de la base de données

### Sur votre serveur de production :

1. **Créer la base de données**
2. **Importer les tables** :
   - users
   - products
   - orders
   - order_items
   - conversations
   - messages
   - notifications
   - favorites
   - cart
   - etc.

3. **Mettre à jour backend/db.php** avec les identifiants du serveur

## 📝 Checklist de déploiement

### Avant le déploiement
- [ ] Sauvegarder la base de données locale
- [ ] Exporter toutes les tables
- [ ] Tester toutes les fonctionnalités en local
- [ ] Vérifier que config.js et config.php sont inclus

### Pendant le déploiement
- [ ] Uploader les dossiers api/ et backend/
- [ ] Uploader tous les fichiers .php principaux
- [ ] Uploader config.js et config.php
- [ ] Uploader les assets (CSS, JS, images)
- [ ] Créer la base de données sur le serveur
- [ ] Importer les tables
- [ ] Mettre à jour backend/db.php avec les infos du serveur

### Après le déploiement
- [ ] Tester la connexion à la base de données
- [ ] Tester la page de login
- [ ] Tester le dashboard admin
- [ ] Tester la messagerie
- [ ] Tester les notifications
- [ ] Tester le panier
- [ ] Tester le paiement (mode test Stripe)
- [ ] Vérifier les logs d'erreur

## 🔐 Sécurité

### Points de sécurité à vérifier :

1. **Backend/db.php** :
   - Ne pas afficher les erreurs en production
   - Utiliser des identifiants sécurisés
   - Activer les connexions SSL si disponibles

2. **Fichiers API** :
   - Vérifier que les sessions sont bien requises
   - Valider toutes les entrées utilisateur
   - Protéger contre les injections SQL (déjà fait avec PDO)

3. **.htaccess** :
   - Désactiver l'affichage des erreurs PHP
   - Bloquer l'accès aux fichiers sensibles

## 🌐 Environnements

### Local (développement)
- Utilise : `admin_api.php`, `backend/cart_api.php`, etc.
- Debug activé
- Erreurs affichées

### Production (serveur)
- Utilise : `api/admin.php`, `api/cart.php`, etc.
- Debug désactivé
- Erreurs dans les logs uniquement

## 📞 Support

Si vous rencontrez des problèmes :

1. Vérifier les logs d'erreur du serveur
2. Vérifier la console du navigateur (F12)
3. Tester les chemins API manuellement
4. Vérifier la connexion à la base de données

## ✨ Fonctionnalités déployées

- ✅ Dashboard admin moderne et responsive
- ✅ Système de messagerie complet
- ✅ Système de notifications
- ✅ Gestion des produits
- ✅ Gestion des commandes avec visualisation détaillée
- ✅ Gestion des utilisateurs
- ✅ Panier d'achat
- ✅ Favoris
- ✅ Paiement Stripe
- ✅ Réinitialisation mot de passe
- ✅ Analytics et graphiques

---

**🎉 Votre application est prête pour le déploiement !**

Le système détecte automatiquement l'environnement et ajuste les chemins API en conséquence.
Vous n'avez rien à modifier dans le code JavaScript pour passer de local à production.
