-- ============================================
-- Table dependance_modele
-- ============================================
CREATE TABLE dependance_modele (
    id_dependance_modele SERIAL PRIMARY KEY,
    id_tache_modele INTEGER NOT NULL,
    id_tache_modele_precedente INTEGER NOT NULL,
    FOREIGN KEY (id_tache_modele) REFERENCES tache_modele(id_tache_modele) ON DELETE CASCADE,
    FOREIGN KEY (id_tache_modele_precedente) REFERENCES tache_modele(id_tache_modele) ON DELETE CASCADE
);

-- ============================================
-- MODELE 1 — Résidentiel
-- ============================================
INSERT INTO dependance_modele (id_tache_modele, id_tache_modele_precedente) VALUES
(5, 4),    -- Terrassement <- Étude et permis
(6, 5),    -- Fondations <- Terrassement
(7, 6),    -- Gros œuvre Murs <- Fondations
(8, 6),    -- Gros œuvre Dalle <- Fondations
(9, 7),    -- Charpente <- Gros œuvre Murs
(9, 8),    -- Charpente <- Gros œuvre Dalle
(10, 9),   -- Toiture <- Charpente
(11, 10),  -- Menuiseries extérieures <- Toiture
(12, 7),   -- Plomberie <- Gros œuvre Murs
(13, 7);   -- Électricité <- Gros œuvre Murs

-- ============================================
-- MODELE 2 — Commercial
-- ============================================
INSERT INTO dependance_modele (id_tache_modele, id_tache_modele_precedente) VALUES
(15, 14),  -- Terrassement <- Étude et permis
(16, 15),  -- Fondations <- Terrassement
(17, 16),  -- Structure béton <- Fondations
(18, 17),  -- Façade et vitrage <- Structure béton
(19, 17),  -- Toiture <- Structure béton
(20, 17),  -- Plomberie <- Structure béton
(21, 17),  -- Électricité <- Structure béton
(22, 17),  -- CVC <- Structure béton
(23, 18),  -- Aménagement intérieur <- Façade et vitrage
(23, 20),  -- Aménagement intérieur <- Plomberie
(23, 21),  -- Aménagement intérieur <- Électricité
(23, 22),  -- Aménagement intérieur <- CVC
(24, 23),  -- Signalétique <- Aménagement intérieur
(25, 24);  -- Nettoyage et réception <- Signalétique

-- ============================================
-- MODELE 3 — Industriel
-- ============================================
INSERT INTO dependance_modele (id_tache_modele, id_tache_modele_precedente) VALUES
(27, 26),  -- Préparation du terrain <- Étude technique
(28, 27),  -- Fondations industrielles <- Préparation du terrain
(29, 28),  -- Structure métallique <- Fondations industrielles
(30, 29),  -- Couverture et bardage <- Structure métallique
(31, 29),  -- Dalle béton intérieure <- Structure métallique
(32, 29),  -- Réseaux électriques HT/BT <- Structure métallique
(33, 31),  -- Plomberie industrielle <- Dalle béton intérieure
(34, 30),  -- Ventilation industrielle <- Couverture et bardage
(35, 31),  -- Équipements spécifiques <- Dalle béton intérieure
(35, 32),  -- Équipements spécifiques <- Réseaux électriques HT/BT
(36, 28),  -- Voirie et parking <- Fondations industrielles
(37, 35),  -- Contrôle et réception <- Équipements spécifiques
(37, 36);  -- Contrôle et réception <- Voirie et parking

-- ============================================
-- MODELE 4 — Infrastructure
-- ============================================
INSERT INTO dependance_modele (id_tache_modele, id_tache_modele_precedente) VALUES
(39, 38),  -- Déblaiement et terrassement <- Étude topographique
(40, 39),  -- Fondations et semelles <- Déblaiement et terrassement
(41, 40),  -- Coffrages et ferraillages <- Fondations et semelles
(42, 41),  -- Coulage béton <- Coffrages et ferraillages
(43, 42),  -- Assainissement <- Coulage béton
(44, 42),  -- Réseaux souterrains <- Coulage béton
(45, 43),  -- Revêtement de surface <- Assainissement
(45, 44),  -- Revêtement de surface <- Réseaux souterrains
(46, 45),  -- Signalisation <- Revêtement de surface
(47, 44),  -- Éclairage public <- Réseaux souterrains
(48, 45),  -- Espaces verts <- Revêtement de surface
(49, 46),  -- Contrôle qualité et réception <- Signalisation
(49, 47),  -- Contrôle qualité et réception <- Éclairage public
(49, 48);  -- Contrôle qualité et réception <- Espaces verts

-- ============================================
-- MODELE 5 — Rénovation
-- ============================================
INSERT INTO dependance_modele (id_tache_modele, id_tache_modele_precedente) VALUES
(51, 50),  -- Dépose et démolition <- Diagnostic et état des lieux
(52, 51),  -- Traitement de structure <- Dépose et démolition
(53, 52),  -- Mise aux normes électrique <- Traitement de structure
(54, 52),  -- Mise aux normes plomberie <- Traitement de structure
(55, 52),  -- Isolation thermique <- Traitement de structure
(56, 53),  -- Plâtrerie et cloisons <- Mise aux normes électrique
(56, 54),  -- Plâtrerie et cloisons <- Mise aux normes plomberie
(56, 55),  -- Plâtrerie et cloisons <- Isolation thermique
(57, 56),  -- Menuiseries <- Plâtrerie et cloisons
(58, 56),  -- Carrelage et parquet <- Plâtrerie et cloisons
(59, 57),  -- Peinture <- Menuiseries
(59, 58),  -- Peinture <- Carrelage et parquet
(60, 54),  -- Cuisine et sanitaires <- Mise aux normes plomberie
(60, 58),  -- Cuisine et sanitaires <- Carrelage et parquet
(61, 59),  -- Nettoyage et livraison <- Peinture
(61, 60);  -- Nettoyage et livraison <- Cuisine et sanitaires