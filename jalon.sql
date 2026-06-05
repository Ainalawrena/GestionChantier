CREATE TABLE jalon (
    id SERIAL PRIMARY KEY,
    ordre INT NOT NULL, -- Permet d'ordonner les jalons au sein d'une même tâche
    nom VARCHAR(150) NOT NULL, -- Description claire de l'étape métrique
    pourcentage INT NOT NULL, -- Le pourcentage d'avancement global que représente ce jalon
    id_tache INT NOT NULL, -- Clé étrangère vers votre table Tache
    FOREIGN KEY (id_tache) REFERENCES tache(id_tache) ON DELETE CASCADE
);
-- =========================================================================
-- TÂCHE 4 : Étude et permis de construire (id_tache = 4)
-- =========================================================================
INSERT INTO jalon (ordre, nom, pourcentage, id_tache) VALUES
(1, 'Plans d''architecture finalisés', 30, 4),
(2, 'Dépôt du dossier de permis en mairie', 60, 4),
(3, 'Permis de construire accordé et affiché', 100, 4);

-- =========================================================================
-- TÂCHE 5 : Terrassement (id_tache = 5)
-- =========================================================================
INSERT INTO jalon (ordre, nom, pourcentage, id_tache) VALUES
(1, 'Piquetage et délimitation du terrain', 20, 5),
(2, 'Fouilles et décaissement terminés', 70, 5),
(3, 'Évacuation des terres et remblayage', 100, 5);

-- =========================================================================
-- TÂCHE 6 : Fondations (id_tache = 6)
-- =========================================================================
INSERT INTO jalon (ordre, nom, pourcentage, id_tache) VALUES
(1, 'Frappage du ferraillage mis en place', 40, 6),
(2, 'Coulage du béton terminé', 90, 6),
(3, 'Séchage complet et validation des fondations', 100, 6);

-- =========================================================================
-- TÂCHE 7 : Gros œuvre - Murs (id_tache = 7)
-- =========================================================================
INSERT INTO jalon (ordre, nom, pourcentage, id_tache) VALUES
(1, 'Élévation des murs du rez-de-chaussée', 50, 7),
(2, 'Élévation des murs de l''étage', 90, 7),
(3, 'Chaînage haut et linteaux terminés', 100, 7);

-- =========================================================================
-- TÂCHE 8 : Gros œuvre - Dalle (id_tache = 8)
-- =========================================================================
INSERT INTO jalon (ordre, nom, pourcentage, id_tache) VALUES
(1, 'Pose des hourdis et du treillis soudé', 40, 8),
(2, 'Coulage de la dalle de béton', 80, 8),
(3, 'Séchage et décoffrage de la dalle', 100, 8);

-- =========================================================================
-- TÂCHE 9 : Charpente (id_tache = 9)
-- =========================================================================
INSERT INTO jalon (ordre, nom, pourcentage, id_tache) VALUES
(1, 'Livraison et levage de la structure', 40, 9),
(2, 'Assemblage et ancrage de la charpente', 90, 9),
(3, 'Pose des lattes et contre-lattes (littelage)', 100, 9);

-- =========================================================================
-- TÂCHE 10 : Toiture (id_tache = 10)
-- =========================================================================
INSERT INTO jalon (ordre, nom, pourcentage, id_tache) VALUES
(1, 'Pose de l''écran sous-toiture', 30, 10),
(2, 'Couverture en tuiles/tôles finalisée', 80, 10),
(3, 'Pose des gouttières et étanchéité des rives', 100, 10);

-- =========================================================================
-- TÂCHE 11 : Menuiseries extérieures (id_tache = 11)
-- =========================================================================
INSERT INTO jalon (ordre, nom, pourcentage, id_tache) VALUES
(1, 'Pose des pré-cadres', 20, 11),
(2, 'Installation des fenêtres et portes vitrées', 80, 11),
(3, 'Réglages, joints d''étanchéité et poignées', 100, 11);

-- =========================================================================
-- TÂCHE 12 : Plomberie (id_tache = 12)
-- =========================================================================
INSERT INTO jalon (ordre, nom, pourcentage, id_tache) VALUES
(1, 'Réseaux de tuyauterie encastrés (PCV/PER)', 40, 12),
(2, 'Raccordements aux collecteurs et évacuations', 70, 12),
(3, 'Pose des sanitaires et tests de mise en eau', 100, 12);

-- =========================================================================
-- TÂCHE 13 : Électricité (id_tache = 13)
-- =========================================================================
INSERT INTO jalon (ordre, nom, pourcentage, id_tache) VALUES
(1, 'Pose des gaines et boîtiers d''encastrement', 40, 13),
(2, 'Tirage des câbles et câblage du tableau', 75, 13),
(3, 'Pose des appareillages (prises/interrupteurs) et tests', 100, 13);
