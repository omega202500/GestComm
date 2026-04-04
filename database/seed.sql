-- Seed SQL complet pour la base de données gestcommdb
-- Ce fichier peut être importé directement dans MySQL

SET FOREIGN_KEY_CHECKS=0;

-- Vider les tables
TRUNCATE TABLE users;
TRUNCATE TABLE zones;
TRUNCATE TABLE produits;
TRUNCATE TABLE commandes;
TRUNCATE TABLE commande_items;
TRUNCATE TABLE ventes;
TRUNCATE TABLE versements;
TRUNCATE TABLE chargements;
TRUNCATE TABLE retours;
TRUNCATE TABLE avaries;

SET FOREIGN_KEY_CHECKS=1;

-- Insertion des zones   
INSERT INTO zones (nom, description, created_at, updated_at) VALUES
('Zone Nord', 'Zone couvrant les régions du nord', NOW(), NOW()),
('Zone Sud', 'Zone couvrant les régions du sud', NOW(), NOW()),
('Zone Est', 'Zone couvrant les régions de l''est', NOW(), NOW()),
('Zone Ouest', 'Zone couvrant les régions de l''ouest', NOW(), NOW());

-- Insertion des utilisateurs
INSERT INTO users (name, email, password, role, zone_id, telephone, adresse, email_verified_at, created_at, updated_at) VALUES
('Admin System', 'admin@gestcomm.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', NULL, '+2250102030405', 'Siège Abidjan', NOW(), NOW(), NOW()),
('Jean Tra Bi', 'jean.tra@gestcomm.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'commercial_terrain', 1, '+2250506070809', 'Abidjan Plateau', NOW(), NOW(), NOW()),
('Marie Koné', 'marie.kone@gestcomm.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'commercial_terrain', 2, '+2250708091011', 'Yopougon', NOW(), NOW(), NOW()),
('Paul Kouadio', 'paul.kouadio@gestcomm.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'chauffeur', 1, '+2250607080910', 'Cocody', NOW(), NOW(), NOW()),
('Lucie Gbêhi', 'lucie.gbehi@gestcomm.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'chauffeur', 2, '+2250809101112', 'Treichville', NOW(), NOW(), NOW());

-- Insertion des produits
INSERT INTO produits (nom, description, prix_unitaire, stock, type, remise_menage, remise_liquide, remise_cosmetic, created_at, updated_at) VALUES
('Détergent Liquide 5L', 'Détergent liquide pour surfaces - Bidon 5L', 8500.00, 150, 'menage', 10.00, 8.00, 5.00, NOW(), NOW()),
('Nettoyant Multi-usage', 'Nettoyant pour toutes surfaces - 2L', 4500.00, 200, 'menage', 12.00, 10.00, 6.00, NOW(), NOW()),
('Javel Concentrée', 'Eau de javel concentrée - 1L', 3200.00, 180, 'menage', 8.00, 7.00, 4.00, NOW(), NOW()),
('Huile Végétale 2L', 'Huile de cuisine raffinée - Bouteille 2L', 5200.00, 120, 'liquide', 5.00, 15.00, 3.00, NOW(), NOW()),
('Sauce Tomate 1kg', 'Sauce tomate concentrée - Pot 1kg', 2800.00, 160, 'liquide', 4.00, 12.00, 2.00, NOW(), NOW()),
('Lait Concentré 400g', 'Lait concentré sucré - Boîte 400g', 1800.00, 220, 'liquide', 3.00, 10.00, 2.00, NOW(), NOW()),
('Shampoing Revitalisant', 'Shampoing soin capillaire - 400ml', 3800.00, 90, 'cosmetic', 6.00, 5.00, 20.00, NOW(), NOW()),
('Gel Douche Hydratant', 'Gel douche parfumé - 500ml', 2900.00, 130, 'cosmetic', 5.00, 4.00, 18.00, NOW(), NOW()),
('Crème Corps Nourrissante', 'Crème hydratante pour le corps - 250ml', 4200.00, 80, 'cosmetic', 7.00, 6.00, 22.00, NOW(), NOW()),
('Déodorant Spray', 'Déodorant protection 48h - 150ml', 2500.00, 110, 'cosmetic', 4.00, 3.00, 15.00, NOW(), NOW());

-- Insertion des commandes
INSERT INTO commandes (numero, commercial_terrain_id, zone_id, client_nom, client_type, client_telephone, client_adresse, statut, date_livraison, montant_total, notes, created_at, updated_at) VALUES
('CMD-2024-001', 2, 1, 'Supermarket ABC', 'liquide', '+2250102030406', 'Rue du Commerce, Plateau', 'livree', '2024-01-15', 185000.00, 'Client fidèle - Paiement à la livraison', NOW(), NOW()),
('CMD-2024-002', 2, 1, 'Boutique Familiale', 'menage', '+2250506070801', 'Marcory Zone 3', 'validee', '2024-01-18', 89200.00, 'Nouveau client - Demande facture', NOW(), NOW()),
('CMD-2024-003', 3, 2, 'Salon de Beauté Élégance', 'cosmetic', '+2250708091012', 'Yopougon Toit Rouge', 'en_attente', '2024-01-20', 65400.00, 'Livraison avant 10h', NOW(), NOW()),
('CMD-2024-004', 3, 2, 'Restaurant Le Délicieux', 'liquide', '+2250901011121', 'Koumassi SICOGI', 'livree', '2024-01-12', 234500.00, 'Commande urgente - Bien emballer', NOW(), NOW());

-- Insertion des items de commande
INSERT INTO commande_items (commande_id, produit_id, quantite, prix_unitaire, remise, created_at, updated_at) VALUES
(1, 4, 20, 5200.00, 15.00, NOW(), NOW()),
(1, 5, 15, 2800.00, 12.00, NOW(), NOW()),
(1, 6, 30, 1800.00, 10.00, NOW(), NOW()),
(2, 1, 8, 8500.00, 10.00, NOW(), NOW()),
(2, 2, 12, 4500.00, 12.00, NOW(), NOW()),
(3, 7, 10, 3800.00, 20.00, NOW(), NOW()),
(3, 8, 15, 2900.00, 18.00, NOW(), NOW()),
(3, 10, 8, 2500.00, 15.00, NOW(), NOW()),
(4, 4, 35, 5200.00, 15.00, NOW(), NOW()),
(4, 5, 25, 2800.00, 12.00, NOW(), NOW()),
(4, 6, 40, 1800.00, 10.00, NOW(), NOW());

-- Insertion des ventes
INSERT INTO ventes (commande_id, produit_id, commercial_terrain_id, quantite, prix_vente, date_vente, created_at, updated_at) VALUES
(1, 4, 2, 20, 4420.00, '2024-01-15', NOW(), NOW()),
(1, 5, 2, 15, 2464.00, '2024-01-15', NOW(), NOW()),
(1, 6, 2, 30, 1620.00, '2024-01-15', NOW(), NOW()),
(4, 4, 3, 35, 4420.00, '2024-01-12', NOW(), NOW()),
(4, 5, 3, 25, 2464.00, '2024-01-12', NOW(), NOW()),
(4, 6, 3, 40, 1620.00, '2024-01-12', NOW(), NOW());

-- Insertion des versements
INSERT INTO versements (commande_id, commercial_terrain_id, montant, date_versement, mode_paiement, reference, created_at, updated_at) VALUES
(1, 2, 100000.00, '2024-01-15', 'mobile_money', 'MM20240115123456', NOW(), NOW()),
(1, 2, 85000.00, '2024-01-16', 'espece', NULL, NOW(), NOW()),
(4, 3, 234500.00, '2024-01-12', 'carte', 'CARTE20240112987654', NOW(), NOW());

-- Insertion des chargements
INSERT INTO chargements (chauffeur_id, produit_id, quantite, date_chargement, statut, created_at, updated_at) VALUES
(4, 1, 50, '2024-01-14', 'livre', NOW(), NOW()),
(4, 4, 60, '2024-01-14', 'livre', NOW(), NOW()),
(5, 7, 30, '2024-01-11', 'livre', NOW(), NOW()),
(5, 4, 40, '2024-01-11', 'livre', NOW(), NOW());

-- Insertion des retours
INSERT INTO retours (commande_id, produit_id, quantite, raison, date_retour, created_at, updated_at) VALUES
(1, 6, 2, 'Emballage endommagé pendant le transport', '2024-01-16', NOW(), NOW()),
(4, 5, 1, 'Produit périmé - Retour accepté', '2024-01-13', NOW(), NOW());

-- Insertion des avaries
INSERT INTO avaries (produit_id, quantite, raison, date_avarie, created_at, updated_at) VALUES
(1, 3, 'Fuite dans le bidon - Stock endommagé', '2024-01-10', NOW(), NOW()),
(7, 2, 'Bouteille cassée pendant le chargement', '2024-01-08', NOW(), NOW());