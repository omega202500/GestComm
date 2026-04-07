-- ======================
-- ACTIVITIES
-- ======================
CREATE TABLE "activities" (
  "id" SERIAL PRIMARY KEY,
  "user_id" INTEGER NOT NULL,
  "type" TEXT NOT NULL,
  "reference" INTEGER NOT NULL,
  "status" TEXT DEFAULT 'en_attente',
  "created_at" TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  "updated_at" TIMESTAMP NULL
);

INSERT INTO "activities" ("id", "user_id", "type", "reference", "status", "created_at", "updated_at") VALUES
(1, 2, 'vente', 8875, 'valide', '2025-12-23 22:38:48', '2025-12-28 20:32:56'),
(2, 5, 'retour', 9435, 'valide', '2025-11-30 18:45:31', '2025-12-28 20:32:56'),
(3, 3, 'retour', 8869, 'en_attente', '2025-11-30 09:58:07', '2025-12-28 20:32:56'),
(4, 4, 'commande', 4359, 'valide', '2025-12-25 05:06:27', '2025-12-28 20:32:56'),
(5, 5, 'vente', 3368, 'valide', '2025-12-07 00:38:42', '2025-12-28 20:32:56'),
(6, 4, 'commande', 3252, 'en_attente', '2025-12-02 17:14:25', '2025-12-28 20:32:56'),
(7, 2, 'vente', 5270, 'en_attente', '2025-12-01 21:56:11', '2025-12-28 20:32:56'),
(8, 5, 'retour', 9861, 'en_attente', '2025-12-22 21:40:31', '2025-12-28 20:32:56'),
(9, 4, 'rapport', 1719, 'en_attente', '2025-12-26 04:01:04', '2025-12-28 20:32:56'),
(10, 2, 'commande', 5640, 'valide', '2025-12-01 13:24:21', '2025-12-28 20:32:56');

-- ======================
-- ZONES
-- ======================
CREATE TABLE "zones" (
  "id" SERIAL PRIMARY KEY,
  "nom" VARCHAR(50) NOT NULL,
  "description" TEXT,
  "region" VARCHAR(50),
  "created_at" TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  "updated_at" TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO "zones" ("id", "nom", "description", "region", "created_at", "updated_at") VALUES
(1, 'Zone Nord', 'Zone couvrant les régions du nord', 'Nord', '2025-12-18 15:37:26', '2025-12-18 15:37:26'),
(2, 'Zone Sud', 'Zone couvrant les régions du sud', 'Sud', '2025-12-18 15:37:26', '2025-12-18 15:37:26'),
(3, 'Zone Est', 'Zone couvrant les régions de l''est', 'Est', '2025-12-18 15:37:26', '2025-12-18 15:37:26'),
(4, 'Zone Ouest', 'Zone couvrant les régions de l''ouest', 'Ouest', '2025-12-18 15:37:26', '2025-12-18 15:37:26');

-- ======================
-- CLIENTS
-- ======================
CREATE TABLE "clients" (
  "id" SERIAL PRIMARY KEY,
  "nom" VARCHAR(150) NOT NULL,
  "telephone" VARCHAR(50),
  "zone_id" INTEGER REFERENCES "zones"("id"),
  "created_at" TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  "updated_at" TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO "clients" ("id", "nom", "telephone", "zone_id", "created_at", "updated_at") VALUES
(1, 'Jean Pierre', '+237657343976', 1, '2026-02-05 12:47:23', '2026-02-05 12:47:23'),
(2, 'Alimentation Hilton', '697198070', 4, '2026-04-02 14:17:07', '2026-04-02 14:17:07');

-- ======================
-- USERS
-- ======================
CREATE TABLE "users" (
  "id" SERIAL PRIMARY KEY,
  "nom" VARCHAR(150) NOT NULL,
  "telephone" VARCHAR(30),
  "email" VARCHAR(150) UNIQUE,
  "photo" VARCHAR(255),
  "password" VARCHAR(200),
  "role" VARCHAR(20) NOT NULL,
  "zone_id" INTEGER REFERENCES "zones"("id") ON DELETE SET NULL,
  "statut" BOOLEAN DEFAULT TRUE,
  "created_at" TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  "updated_at" TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO "users" ("id", "nom", "telephone", "email", "photo", "password", "role", "zone_id", "statut", "created_at", "updated_at") VALUES
(1, 'Super Admin', '+237612345678', 'admin@gestcomm.com', 'avatars/6UHqnupgPbv0JM0MTVZJPBUU8VxcTimnp0DXqr7S.jpg', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', NULL, TRUE, '2025-12-18 16:37:26', '2026-04-02 07:00:59'),
(2, 'Gildas', '+237678563412', 'gildas@gmail.com', 'avatars/ehztj9bTsdl8adRtgfSocsI23JGRgjkT2afT6GqI.jpg', '$2y$10$295UR7VkXXUivD6bBwg03OPv8s97eErogXjYFKjcSq8Mxh7ogxQNy', 'terrain', 1, TRUE, '2025-12-18 16:37:26', '2026-04-01 13:57:09'),
(3, 'Naelle', '+237652683801', 'nalle@gmail.com', 'avatars/9EgqZf97UMUlowpaDTeJiNE3gr4J0ijkG8j7LOt3.jpg', '$2y$10$htNl4PkdCEh3vNz/NHnyyeOixu5WiP6fg17jEPGfJkn', 'terrain', 2, TRUE, '2025-12-18 16:37:26', '2026-04-02 07:28:12'),
(4, 'Kevine', '+237612345679', 'kevine@gmail.com', NULL, '$2y$10$.Ul/Mj.SHhnsmQaTeyeDmuZnQ4lnTUG25V5Nq7LUL8K', 'chauffeur', 1, TRUE, '2025-12-18 16:37:26', '2025-12-21 00:09:42'),
(5, 'Cedric', '+2376809101112', 'cedric@gmail.com', NULL, '$2y$10$07HIPYsvFEcxez3tFlGNvOUbT2mkN0e3Ghnb7Xvzaea', 'chauffeur', 2, TRUE, '2025-12-18 16:37:26', '2025-12-21 00:09:42'),
(6, 'Joel Omega', '652683801', 'omega@gmail.com', 'avatars/e8NIagt7Xbukh91KUXb8zpKVvJTOOpUjK54ifgGT.jpg', '$2y$10$JPDzIDMOQYcchr3O5P07du8zC78.0UMwG5ZxqE3QgYr3lNDfIodv6', 'admin', NULL, TRUE, '2026-04-01 15:39:02', '2026-04-01 15:39:53'),
(7, 'Tagne', '677556704', 'tagne@gmail.com', 'avatars/iwBVP1PaOfxe0fhf4iwy3AyISIEXDT1o6Nn7vBbw.jpg', '$2y$10$LJ80fJbd0BHB1GJ8YfeSfuz6LbTT6pCw9RRPOMocQ7hut2r2C55B.', 'terrain', NULL, TRUE, '2026-04-01 15:41:02', '2026-04-01 15:44:11'),
(8, 'Susaku', '655667709', 'susaku@gmail.com', 'avatars/B0BQ8MzH6uWthlngVE59mx1Q9vPtOHljEU4UJcWM.jpg', '$2y$10$KMPPDnGxAvYTKLrnPAiaHuk986SrDFmtfGYA4PJw2hKbUw75AnUh.', 'chauffeur', NULL, TRUE, '2026-04-02 07:14:12', '2026-04-02 07:14:12');

-- ======================
-- COMMANDES
-- ======================
CREATE TABLE "commandes" (
  "id" SERIAL PRIMARY KEY,
  "commercial_id" INTEGER REFERENCES "users"("id"),
  "client_id" INTEGER REFERENCES "clients"("id"),
  "date_commande" TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  "statut" VARCHAR(30) DEFAULT 'en_attente',
  "total_quantite" INTEGER DEFAULT 0,
  "montant_total" DECIMAL(12,2) DEFAULT 0.00,
  "notes" TEXT,
  "client_tel" VARCHAR(20) NOT NULL,
  "date_livraison" TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  "created_at" TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  "updated_at" TIMESTAMP,
  "zone_id" INTEGER REFERENCES "zones"("id") ON DELETE SET NULL
);

INSERT INTO "commandes" ("id", "commercial_id", "client_id", "date_commande", "statut", "total_quantite", "montant_total", "notes", "client_tel", "date_livraison", "created_at", "updated_at", "zone_id") VALUES
(1, 2, 1, '2026-02-05 12:50:27', 'en_attente', 59, 48500.00, 'Paye au content', '+237656962880', '2026-02-05 12:50:27', '2026-03-30 16:32:19', NULL, 1);

-- ======================
-- PRODUITS
-- ======================
CREATE TABLE "produits" (
  "id" SERIAL PRIMARY KEY,
  "nom" VARCHAR(200) NOT NULL,
  "categorie" VARCHAR(50) NOT NULL,
  "prix_unitaire" DECIMAL(12,2) NOT NULL,
  "stock" INTEGER DEFAULT 0,
  "unite" VARCHAR(50),
  "created_at" TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  "photo" VARCHAR(100),
  "updated_at" TIMESTAMP
);

INSERT INTO "produits" ("id", "nom", "categorie", "prix_unitaire", "stock", "unite", "created_at", "photo", "updated_at") VALUES
(1, 'savon Azur 60', 'Menage', 18000.00, 4, 'kg', '2026-03-31 15:24:22', 'produits/5RHfHSdKVFbbDLVJZNMFQaiCWC1WskcslV20k3qL.jpg', '2026-04-02 13:08:52'),
(2, 'Medicinal', 'Cosmetique', 12000.00, 3, 'pièce', '2026-03-31 15:32:27', 'produits/mfUf1uJ3v2dxHp1hZEw0p7gK5HGbEqGInmVB1cRm.jpg', '2026-03-31 15:32:27'),
(3, 'Lait Hydratant Palmia', 'Cosmetique', 16000.00, 2, 'boîte', '2026-03-31 15:34:51', 'produits/mlSAbYV1pY8ZhgwDlEZKaiMEGNHvkCoPbKrU4Hsx.jpg', '2026-03-31 15:34:51');

-- ======================
-- LIVRAISONS
-- ======================
CREATE TABLE "livraisons" (
  "id" SERIAL PRIMARY KEY,
  "terrain_id" INTEGER NOT NULL REFERENCES "users"("id") ON DELETE CASCADE,
  "chauffeur_id" INTEGER NOT NULL REFERENCES "users"("id") ON DELETE CASCADE,
  "commande_id" INTEGER NOT NULL REFERENCES "commandes"("id") ON DELETE CASCADE,
  "date_livraison" TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  "statut" VARCHAR(50) DEFAULT 'en_attente',
  "notes" TEXT,
  "created_at" TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  "updated_at" TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO "livraisons" ("id", "terrain_id", "chauffeur_id", "commande_id", "date_livraison", "statut", "notes", "created_at", "updated_at") VALUES
(1, 3, 4, 1, '2026-03-06 07:00:00', 'en_attente', NULL, '2026-02-05 12:51:40', '2026-03-31 12:09:52'),
(3, 2, 4, 1, '2026-04-02 14:18:00', 'livree', 'Client satisfait', '2026-04-02 13:19:29', '2026-04-02 13:19:29');

-- ======================
-- PERSONAL ACCESS TOKENS
-- ======================
CREATE TABLE "personal_access_tokens" (
  "id" BIGSERIAL PRIMARY KEY,
  "tokenable_type" VARCHAR(255) NOT NULL,
  "tokenable_id" BIGINT NOT NULL,
  "name" VARCHAR(255) NOT NULL,
  "token" VARCHAR(64) NOT NULL UNIQUE,
  "abilities" TEXT,
  "last_used_at" TIMESTAMP,
  "expires_at" TIMESTAMP,
  "created_at" TIMESTAMP,
  "updated_at" TIMESTAMP
);

CREATE INDEX "personal_access_tokens_tokenable_type_tokenable_id_index" ON "personal_access_tokens"("tokenable_type", "tokenable_id");

INSERT INTO "personal_access_tokens" ("id", "tokenable_type", "tokenable_id", "name", "token", "abilities", "last_used_at", "expires_at", "created_at", "updated_at") VALUES
(6, 'App\\Models\\User', 7, 'mobile-token', 'f2fcf5dd2eae587e17dbc700089f8f144dbc539ea5dab89fe84c733aa955b533', '["terrain"]', NULL, NULL, '2026-04-02 06:50:35', '2026-04-02 06:50:35'),
(13, 'App\\Models\\User', 8, 'mobile-token', 'fe15e39f2ffaa6ed8a8966004be03bba9677f4ddc14af1c51ca8755da0cc2702', '["chauffeur"]', NULL, NULL, '2026-04-03 11:33:53', '2026-04-03 11:33:53');

-- ======================
-- MIGRATIONS
-- ======================
CREATE TABLE "migrations" (
  "id" SERIAL PRIMARY KEY,
  "migration" VARCHAR(255),
  "batch" INTEGER
);

INSERT INTO "migrations" ("id", "migration", "batch") VALUES
(1, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(2, '2025_11_01_000001_create_zones_table', 1),
(3, '2025_11_01_000003_create_produits_table', 1),
(4, '2025_11_01_000004_create_users_table', 1),
(5, '2025_11_01_000005_create_commandes_table', 1),
(6, '2025_11_01_000007_create_ventes_table', 1),
(7, '2025_11_01_000009_create_retours_table', 1),
(8, '2025_11_01_000010_create_chargements_table', 1),
(9, '2025_11_01_000011_create_versements_table', 1),
(10, '2025_11_12_1409_create_livraison_table', 1),
(11, '2025_11_28_134856_add_produit_id_to_remise_categorie_table', 1),
(12, '2025_12_11_154310_create_nom_du_modeles_table', 1),
(13, '2025_12_26_215039_create_activities_table', 1),
(14, '2026_03_30_171547_add_avatar_to_users_table', 2);

-- ======================
-- CHARGEMENTS
-- ======================
CREATE TABLE "chargements" (
  "id" SERIAL PRIMARY KEY,
  "commercial_id" INTEGER REFERENCES "users"("id"),
  "date_chargement" DATE,
  "total_produits" INTEGER,
  "statut" VARCHAR(20) DEFAULT 'en_cours'
);

-- ======================
-- RAPPORTS
-- ======================
CREATE TABLE "rapports" (
  "id" SERIAL PRIMARY KEY,
  "commercial_id" INTEGER REFERENCES "users"("id"),
  "date_rapport" DATE,
  "total_ventes" DECIMAL(12,2) DEFAULT 0.00,
  "total_versement" DECIMAL(12,2) DEFAULT 0.00,
  "observations" TEXT
);

-- ======================
-- RETOURS
-- ======================
CREATE TABLE "retours" (
  "id" SERIAL PRIMARY KEY,
  "commercial_id" INTEGER REFERENCES "users"("id"),
  "produit_id" INTEGER REFERENCES "produits"("id"),
  "type_retour" VARCHAR(20) NOT NULL,
  "quantite" INTEGER NOT NULL,
  "date_retour" TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  "commentaire" TEXT,
  "photo_evidence" VARCHAR(255)
);

-- ======================
-- STOCK
-- ======================
CREATE TABLE "stock" (
  "id" SERIAL PRIMARY KEY,
  "commercial_id" INTEGER REFERENCES "users"("id"),
  "produit_id" INTEGER REFERENCES "produits"("id"),
  "quantite" INTEGER DEFAULT 0,
  "updated_at" TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ======================
-- VENTES
-- ======================
CREATE TABLE "ventes" (
  "id" SERIAL PRIMARY KEY,
  "commercial_id" INTEGER REFERENCES "users"("id"),
  "produit_id" INTEGER REFERENCES "produits"("id"),
  "client_id" INTEGER REFERENCES "clients"("id"),
  "date_vente" TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  "total_quantite" INTEGER DEFAULT 0,
  "montant_total" DECIMAL(12,2) DEFAULT 0.00,
  "facture_ref" VARCHAR(100),
  "prix_unitaire" DECIMAL(12,2) NOT NULL
);

-- ======================
-- VERSEMENTS
-- ======================
CREATE TABLE "versements" (
  "id" SERIAL PRIMARY KEY,
  "commercial_id" INTEGER REFERENCES "users"("id"),
  "date_versement" TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  "montant" DECIMAL(12,2) NOT NULL,
  "reference" VARCHAR(100),
  "justificatif" VARCHAR(255),
  "valide" BOOLEAN DEFAULT FALSE,
  "validated_by" INTEGER REFERENCES "users"("id"),
  "validated_at" TIMESTAMP
);
