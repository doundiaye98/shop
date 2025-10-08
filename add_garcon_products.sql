-- Script SQL pour ajouter des produits dans la catégorie Garçon
-- Exécutez ce script dans phpMyAdmin ou votre gestionnaire de base de données

-- Ajout des produits Garçon
INSERT INTO products (name, category, description, price, stock, image, created_at) VALUES
('Chemise en coton', 'Garçon', 'Chemise en coton de qualité, confortable et respirante. Parfaite pour toutes les occasions.', 12.50, 50, 'https://via.placeholder.com/300x400?text=Chemise+Coton', NOW()),

('Ensemble 3 pièces - Basique', 'Garçon', 'Ensemble complet comprenant pantalon, t-shirt à manches longues et chemise. Idéal pour le quotidien et les occasions spéciales.', 25.00, 30, 'https://via.placeholder.com/300x400?text=Ensemble+3+Pièces', NOW()),

('Ensemble 3 pièces - Sport', 'Garçon', 'Ensemble sportif avec short, t-shirt à manches courtes et short en jean. Parfait pour les activités en plein air.', 25.00, 35, 'https://via.placeholder.com/300x400?text=Ensemble+Sport', NOW()),

('Ensemble 2 pièces - Casual', 'Garçon', 'Ensemble décontracté et élégant, parfait pour les sorties en famille et les activités quotidiennes. Design moderne et confortable.', 25.00, 40, 'https://via.placeholder.com/300x400?text=Ensemble+2+Pièces', NOW()),

('Ensemble 3 pièces - Classique', 'Garçon', 'Ensemble élégant et stylé comprenant pantalon, t-shirt à manches longues et veste. Parfait pour les occasions formelles et les événements spéciaux.', 40.00, 25, 'https://via.placeholder.com/300x400?text=Ensemble+Classique', NOW()),

('Chaussettes par lot de 6', 'Garçon', 'Lot de 6 paires de chaussettes de qualité, confortables et durables. Disponibles en plusieurs coloris et motifs. Parfaites pour tous les jours.', 10.00, 100, 'https://via.placeholder.com/300x400?text=Chaussettes+Lot+6', NOW()),

('Jean Cargo - Unisexe', 'Garçon', 'Jean cargo polyvalent et confortable, adapté aux garçons et filles. Nombreuses poches pratiques, coupe moderne et durable. Idéal pour le quotidien.', 15.00, 60, 'https://via.placeholder.com/300x400?text=Jean+Cargo', NOW());

-- Vérification des produits ajoutés
SELECT 'Produits ajoutés dans la catégorie Garçon :' as info;
SELECT id, name, price, stock, created_at FROM products WHERE category = 'Garçon' ORDER BY id;

-- Vérification du total des produits par catégorie
SELECT 'Total des produits par catégorie :' as info;
SELECT category, COUNT(*) as total_produits FROM products GROUP BY category ORDER BY category;
