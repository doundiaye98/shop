-- Script de nettoyage de la base de données
-- Supprime les catégories "Jouets" et "Chambre" et leurs produits associés

-- 1. Afficher les catégories existantes
SELECT 'Catégories existantes :' as info;
SELECT * FROM categories;

-- 2. Afficher les produits des catégories à supprimer
SELECT 'Produits des catégories à supprimer :' as info;
SELECT p.*, c.name as category_name 
FROM products p 
JOIN categories c ON p.category_id = c.id 
WHERE c.name IN ('Jouets', 'Chambre');

-- 3. Supprimer les produits des catégories "Jouets" et "Chambre"
DELETE FROM products 
WHERE category_id IN (
    SELECT id FROM categories 
    WHERE name IN ('Jouets', 'Chambre')
);

-- 4. Supprimer les catégories "Jouets" et "Chambre"
DELETE FROM categories 
WHERE name IN ('Jouets', 'Chambre');

-- 5. Vérifier le résultat
SELECT 'Catégories restantes :' as info;
SELECT * FROM categories;

SELECT 'Produits restants :' as info;
SELECT p.*, c.name as category_name 
FROM products p 
JOIN categories c ON p.category_id = c.id;

-- 6. Réorganiser les IDs des catégories si nécessaire (optionnel)
-- ALTER TABLE categories AUTO_INCREMENT = 1;
-- ALTER TABLE products AUTO_INCREMENT = 1;
