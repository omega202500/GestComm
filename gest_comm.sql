BEGIN;

-- ================================
-- TYPES ENUM (PostgreSQL)
-- ================================
CREATE TYPE activity_type AS ENUM ('commande','vente','rapport','retour');
CREATE TYPE activity_status AS ENUM ('en_attente','valide');

-- ================================
-- TABLE: zones
-- ================================
CREATE TABLE zones (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,
    description TEXT,
    region VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ================================
-- TABLE: users
-- ================================
CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(150) NOT NULL,
    telephone VARCHAR(30),
    email VARCHAR(150) UNIQUE,
    photo VARCHAR(255),
    password VARCHAR(200),
    role VARCHAR(20) NOT NULL,
    zone_id INT,
    statut BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (zone_id) REFERENCES zones(id) ON DELETE SET NULL
);

-- ================================
-- TABLE: clients
-- ================================
CREATE TABLE clients (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(150) NOT NULL,
    telephone VARCHAR(50),
    zone_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (zone_id) REFERENCES zones(id)
);

-- ================================
-- TABLE: produits
-- ================================
CREATE TABLE produits (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(200) NOT NULL,
    categorie VARCHAR(50) NOT NULL,
    prix_unitaire NUMERIC(12,2) NOT NULL,
    stock INT DEFAULT 0,
    unite VARCHAR(50),
    photo VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP
);

-- ================================
-- TABLE: commandes
-- ================================
CREATE TABLE commandes (
    id SERIAL PRIMARY KEY,
    commercial_id INT,
    client_id INT,
    date_commande TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    statut VARCHAR(30) DEFAULT 'en_attente',
    total_quantite INT DEFAULT 0,
    montant_total NUMERIC(12,2) DEFAULT 0,
    notes TEXT,
    client_tel VARCHAR(20),
    date_livraison TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP,
    zone_id INT,
    FOREIGN KEY (commercial_id) REFERENCES users(id),
    FOREIGN KEY (client_id) REFERENCES clients(id),
    FOREIGN KEY (zone_id) REFERENCES zones(id) ON DELETE SET NULL
);

-- ================================
-- TABLE: livraisons
-- ================================
CREATE TABLE livraisons (
    id SERIAL PRIMARY KEY,
    terrain_id INT NOT NULL,
    chauffeur_id INT NOT NULL,
    commande_id INT NOT NULL,
    date_livraison TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    statut VARCHAR(50) DEFAULT 'en_attente',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (terrain_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (chauffeur_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (commande_id) REFERENCES commandes(id) ON DELETE CASCADE
);

-- ================================
-- TABLE: activités
-- ================================
CREATE TABLE activities (
    id SERIAL PRIMARY KEY,
    user_id INT NOT NULL,
    type activity_type NOT NULL,
    reference INT NOT NULL,
    status activity_status DEFAULT 'en_attente',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ================================
-- TABLE: ventes
-- ================================
CREATE TABLE ventes (
    id SERIAL PRIMARY KEY,
    commercial_id INT,
    produit_id INT,
    client_id INT,
    date_vente TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    total_quantite INT DEFAULT 0,
    montant_total NUMERIC(12,2) DEFAULT 0,
    facture_ref VARCHAR(100),
    prix_unitaire NUMERIC(12,2) NOT NULL,
    FOREIGN KEY (commercial_id) REFERENCES users(id),
    FOREIGN KEY (produit_id) REFERENCES produits(id),
    FOREIGN KEY (client_id) REFERENCES clients(id)
);

-- ================================
-- TABLE: retours
-- ================================
CREATE TABLE retours (
    id SERIAL PRIMARY KEY,
    commercial_id INT,
    produit_id INT,
    type_retour VARCHAR(20),
    quantite INT,
    date_retour TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    commentaire TEXT,
    photo_evidence VARCHAR(255),
    FOREIGN KEY (commercial_id) REFERENCES users(id),
    FOREIGN KEY (produit_id) REFERENCES produits(id)
);

-- ================================
-- TABLE: stock
-- ================================
CREATE TABLE stock (
    id SERIAL PRIMARY KEY,
    commercial_id INT,
    produit_id INT,
    quantite INT DEFAULT 0,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (commercial_id) REFERENCES users(id),
    FOREIGN KEY (produit_id) REFERENCES produits(id)
);

-- ================================
-- TABLE: versements
-- ================================
CREATE TABLE versements (
    id SERIAL PRIMARY KEY,
    commercial_id INT,
    date_versement TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    montant NUMERIC(12,2),
    reference VARCHAR(100),
    justificatif VARCHAR(255),
    valide BOOLEAN DEFAULT FALSE,
    validated_by INT,
    validated_at TIMESTAMP,
    FOREIGN KEY (commercial_id) REFERENCES users(id),
    FOREIGN KEY (validated_by) REFERENCES users(id)
);

-- ================================
-- TABLE: chargements
-- ================================
CREATE TABLE chargements (
    id SERIAL PRIMARY KEY,
    commercial_id INT,
    date_chargement DATE,
    total_produits INT,
    statut VARCHAR(20) DEFAULT 'en_cours',
    FOREIGN KEY (commercial_id) REFERENCES users(id)
);

-- ================================
-- TABLE: rapports
-- ================================
CREATE TABLE rapports (
    id SERIAL PRIMARY KEY,
    commercial_id INT,
    date_rapport DATE,
    total_ventes NUMERIC(12,2),
    total_versement NUMERIC(12,2),
    observations TEXT,
    FOREIGN KEY (commercial_id) REFERENCES users(id)
);

COMMIT;
