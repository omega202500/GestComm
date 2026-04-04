-- 1. TABLES DE BASE (sans dépendances externes)
CREATE TABLE zones (
  id INTEGER PRIMARY KEY AUTO_INCREMENT,
  nom VARCHAR(50) NOT NULL,
  description TEXT,
  region VARCHAR(50),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 2. TABLES QUI DÉPENDENT DE ZONES
CREATE TABLE users (
  id INTEGER PRIMARY KEY AUTO_INCREMENT,
  nom VARCHAR(150) NOT NULL,
  telephone VARCHAR(30),
  email VARCHAR(150) UNIQUE,
  mot_de_passe VARCHAR(255) NOT NULL,
  role VARCHAR(20) NOT NULL,
  zone_id INTEGER,
  statut BOOLEAN DEFAULT TRUE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (zone_id) REFERENCES zones(id) ON DELETE SET NULL
);

CREATE TABLE clients (
  id INTEGER PRIMARY KEY AUTO_INCREMENT,
  nom VARCHAR(150) NOT NULL,
  telephone VARCHAR(50),
  zone_id INTEGER,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (zone_id) REFERENCES zones(id)
);

-- 3. TABLES INDÉPENDANTES (produits)

CREATE TABLE produits (
  id INTEGER PRIMARY KEY AUTO_INCREMENT,
  nom VARCHAR(200) NOT NULL,
  categorie VARCHAR(50) NOT NULL,
  prix_unitaire DECIMAL(12,2) NOT NULL,
  stock INTEGER DEFAULT 0,
  unite VARCHAR(50),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY unique_id_nom (id, nom)
);
-- 4. TABLES QUI DÉPENDENT DE USERS, CLIENTS, PRODUITS
CREATE TABLE commandes (
  id INTEGER PRIMARY KEY AUTO_INCREMENT,
  commercial_id INTEGER,
  client_id INTEGER,
  date_commande TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  statut VARCHAR(30) DEFAULT 'en_attente', 
  total_quantite INTEGER DEFAULT 0, 
  montant_total DECIMAL(12,2) DEFAULT 0,
  notes TEXT,
  FOREIGN KEY (commercial_id) REFERENCES users(id),
  FOREIGN KEY (client_id) REFERENCES clients(id)
);

CREATE TABLE ventes (
  id INTEGER PRIMARY KEY AUTO_INCREMENT,
  commercial_id INTEGER,
  produit_id INTEGER,
  client_id INTEGER,
  date_vente TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  total_quantite INTEGER DEFAULT 0,
  montant_total DECIMAL(12,2) DEFAULT 0,
  facture_ref VARCHAR(100),
  prix_unitaire DECIMAL(12,2) NOT NULL,
  FOREIGN KEY (commercial_id) REFERENCES users(id),
  FOREIGN KEY (produit_id) REFERENCES produits(id),
  FOREIGN KEY (client_id) REFERENCES clients(id)
);

CREATE TABLE retours (
  id INTEGER PRIMARY KEY AUTO_INCREMENT,
  commercial_id INTEGER,
  produit_id INTEGER,
  type_retour VARCHAR(20) NOT NULL, 
  quantite INTEGER NOT NULL,
  date_retour TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  commentaire TEXT,
  photo_evidence VARCHAR(255),
  FOREIGN KEY (commercial_id) REFERENCES users(id),
  FOREIGN KEY (produit_id) REFERENCES produits(id)
);

CREATE TABLE chargements (
  id INTEGER PRIMARY KEY AUTO_INCREMENT,
  commercial_id INTEGER,
  date_chargement DATE,
  total_produits INTEGER,
  statut VARCHAR(20) DEFAULT 'en_cours', 
  FOREIGN KEY (commercial_id) REFERENCES users(id)
);

CREATE TABLE versements (
  id INTEGER PRIMARY KEY AUTO_INCREMENT,
  commercial_id INTEGER,
  date_versement TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  montant DECIMAL(12,2) NOT NULL,
  reference VARCHAR(100),
  justificatif VARCHAR(255), 
  valide BOOLEAN DEFAULT FALSE,
  validated_by INTEGER,
  validated_at DATETIME NULL,
  FOREIGN KEY (commercial_id) REFERENCES users(id),
  FOREIGN KEY (validated_by) REFERENCES users(id)
);

CREATE TABLE rapports_journaliers (
  id INTEGER PRIMARY KEY AUTO_INCREMENT,
  commercial_id INTEGER,
  date_rapport DATE,
  total_ventes DECIMAL(12,2) DEFAULT 0,
  total_versement DECIMAL(12,2) DEFAULT 0,
  observations TEXT,
  FOREIGN KEY (commercial_id) REFERENCES users(id)
);

CREATE TABLE stock (
  id INTEGER PRIMARY KEY AUTO_INCREMENT,
  commercial_id INTEGER,
  produit_id INTEGER,
  quantite INTEGER DEFAULT 0,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (commercial_id) REFERENCES users(id),
  FOREIGN KEY (produit_id) REFERENCES produits(id)
);

-- INSERTIONS DES DONNÉES
INSERT INTO `zones` (`id`, `nom`, `description`, `region`, `created_at`, `updated_at`) VALUES
(1, 'Zone Nord', 'Zone couvrant les régions du nord', 'Nord', '2025-12-18 13:28:27', '2025-12-18 13:28:27'),
(2, 'Zone Sud', 'Zone couvrant les régions du sud', 'Sud', '2025-12-18 13:28:27', '2025-12-18 13:28:27'),
(3, 'Zone Est', 'Zone couvrant les régions de l\'est', 'Est', '2025-12-18 13:28:27', '2025-12-18 13:28:27'),
(4, 'Zone Ouest', 'Zone couvrant les régions de l\'ouest', 'Ouest', '2025-12-18 13:28:27', '2025-12-18 13:28:27');

INSERT INTO `users` (`id`, `nom`, `telephone`, `email`, `mot_de_passe`, `role`, `zone_id`, `statut`, `created_at`) VALUES
(1, 'Admin System', '+237612345678', 'admin@gestcomm.com', '$2y$10$aPC41nJucI8vgJQQDoptf.R.sHV/L0E5rWqPP9oMt9lTo1YRup07y', 'admin', NULL, 1, '2025-12-18 14:28:28'),
(2, 'Gildas', '+237678563412', 'gildas@gmail.com', '$2y$10$5E/gmpMS.o4b/7QLKtnIEOGVE6qv/xB1LaQ4CrbpTw5Buyzg3FKJO', 'terrain', 1, 1, '2025-12-18 14:28:28'),
(3, 'Naelle', '+237652683801', 'nalle@gmail.com', '$2y$10$nd1qalZVkYvymaKZU9s2HOfdu.QbeOjnlRsN./YUvo7oXKAYPg/se', 'terrain', 2, 1, '2025-12-18 14:28:28'),
(4, 'Kevine', '+237612345679', 'kevine@gmail.com', '$2y$10$5QanyDibV5o/TbCV6jincO30QcY6ZR67Rz.Mk1pc.Ixhkp7FX1AxW', 'chauffeur', 1, 1, '2025-12-18 14:28:28'),
(5, 'Cedric', '+2376809101112', 'cedric@gmail.com', '$2y$10$G69SV3HjfRyGxXBJWAZPw.lAwfytvmRaR25g19a0UyPlym5m6Q7QS', 'chauffeur', 2, 1, '2025-12-18 14:28:28');

-- NOTE: Les commandes nécessitent des clients existants, j'ai modifié l'INSERT pour être cohérent
INSERT INTO `commandes` (`commercial_id`, `client_id`, `date_commande`, `statut`, `total_quantite`, `montant_total`, `notes`) VALUES
(2, NULL, '2025-12-18 14:28:28', 'livree', 0, 185000.00, 'Client fidèle'),
(2, NULL, '2025-12-18 14:28:28', 'validee', 0, 89200.00, 'Nouveau client'),
(3, NULL, '2025-12-18 14:28:28', 'en_attente', 0, 65400.00, 'Livraison avant 10h'),
(3, NULL, '2025-12-18 14:28:28', 'livree', 0, 234500.00, 'Commande urgente');

-- CRÉATION DES INDEX (après la création des tables)
CREATE INDEX idx_users_zone ON users(zone_id);
CREATE INDEX idx_clients_zone ON clients(zone_id);
CREATE INDEX idx_commandes_statut ON commandes(statut);
CREATE INDEX idx_commandes_commercial ON commandes(commercial_id);
CREATE INDEX idx_commandes_client ON commandes(client_id);
CREATE INDEX idx_ventes_date ON ventes(date_vente);
CREATE INDEX idx_ventes_commercial ON ventes(commercial_id);
CREATE INDEX idx_ventes_produit ON ventes(produit_id);
CREATE INDEX idx_ventes_client ON ventes(client_id);
CREATE INDEX idx_retours_commercial ON retours(commercial_id);
CREATE INDEX idx_chargements_commercial ON chargements(commercial_id);
CREATE INDEX idx_versements_commercial ON versements(commercial_id);
CREATE INDEX idx_rapports_commercial ON rapports_journaliers(commercial_id);
CREATE INDEX idx_stock_commercial_produit ON stock(commercial_id, produit_id);