# Guide de déploiement - Ma Boutique E-commerce

## 📋 Prérequis

- Un hébergeur web avec support PHP 8.0+ et MySQL 5.7+
- Un nom de domaine
- Un client FTP (FileZilla, WinSCP, etc.)

## 🚀 Étapes de déploiement

### 1. Choisir un hébergeur

**Recommandations :**
- **OVH** (France) - À partir de 2,99€/mois
- **Hostinger** - À partir de 2,99€/mois  
- **1&1 IONOS** - À partir de 1€/mois
- **O2switch** (France) - À partir de 3,50€/mois

### 2. Préparer les fichiers

1. **Nettoyer le projet :**
   - Supprimer les fichiers temporaires
   - Vérifier qu'il n'y a pas de données sensibles

2. **Modifier la configuration de base de données :**
   - Ouvrir `backend/db.php`
   - Remplacer les valeurs de production par celles de votre hébergeur :
     ```php
     $host = 'localhost'; // Ou l'adresse de votre serveur MySQL
     $db   = 'votre_nom_db'; // Nom de votre base de données
     $user = 'votre_utilisateur'; // Utilisateur de la base de données
     $pass = 'votre_mot_de_passe'; // Mot de passe de la base de données
     ```

### 3. Uploader les fichiers

1. **Se connecter via FTP** à votre hébergeur
2. **Uploader tous les fichiers** dans le dossier `public_html` ou `www`
3. **Vérifier les permissions :**
   - Dossiers : 755
   - Fichiers : 644
   - Fichiers PHP : 644

### 4. Créer la base de données

1. **Accéder au panneau d'administration** de votre hébergeur
2. **Créer une nouvelle base de données MySQL**
3. **Créer un utilisateur** pour cette base de données
4. **Importer le fichier `db.sql`** via phpMyAdmin ou l'outil d'import de votre hébergeur

### 5. Tester le site

1. **Accéder à votre site** via votre nom de domaine
2. **Vérifier que toutes les pages fonctionnent**
3. **Tester l'inscription/connexion**
4. **Vérifier l'affichage des produits**

## 🔧 Configuration spécifique par hébergeur

### OVH
- **Dossier d'upload :** `www`
- **Base de données :** Accessible via phpMyAdmin dans le panel
- **Configuration PHP :** Via le panel OVH

### Hostinger
- **Dossier d'upload :** `public_html`
- **Base de données :** Via hPanel > MySQL Databases
- **Configuration PHP :** Via hPanel > Advanced > PHP Configuration

### 1&1 IONOS
- **Dossier d'upload :** `htdocs`
- **Base de données :** Via 1&1 Control Panel
- **Configuration PHP :** Via le panel de contrôle

## 🔒 Sécurité

### Avant la mise en ligne
- [ ] Changer les mots de passe par défaut
- [ ] Vérifier que `db.sql` n'est pas accessible publiquement
- [ ] Tester les fonctionnalités d'inscription/connexion
- [ ] Vérifier que les erreurs PHP ne s'affichent pas

### Après la mise en ligne
- [ ] Installer un certificat SSL (HTTPS)
- [ ] Configurer des sauvegardes automatiques
- [ ] Surveiller les logs d'erreur
- [ ] Mettre en place un monitoring

## 📊 Optimisation

### Performance
- Le fichier `.htaccess` est déjà configuré pour :
  - Compression GZIP
  - Cache des navigateurs
  - Protection contre les injections

### SEO
- Ajouter des meta tags dans chaque page
- Créer un sitemap.xml
- Configurer Google Analytics

## 🆘 Dépannage

### Erreurs courantes

**Erreur de connexion à la base de données :**
- Vérifier les paramètres dans `backend/db.php`
- Vérifier que l'utilisateur a les droits sur la base de données

**Pages blanches :**
- Vérifier les logs d'erreur PHP
- Activer l'affichage des erreurs temporairement

**Images qui ne s'affichent pas :**
- Vérifier les chemins des images
- Vérifier les permissions des dossiers

## 📞 Support

En cas de problème :
1. Vérifier les logs d'erreur de votre hébergeur
2. Consulter la documentation de votre hébergeur
3. Contacter le support de votre hébergeur

## 🔄 Mise à jour

Pour mettre à jour votre site :
1. Sauvegarder la base de données
2. Uploader les nouveaux fichiers
3. Tester le site
4. Restaurer la base de données si nécessaire

---

**Note :** Ce guide est générique. Consultez la documentation spécifique de votre hébergeur pour des instructions détaillées. 