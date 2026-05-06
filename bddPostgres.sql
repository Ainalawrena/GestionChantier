CREATE TABLE utilisateurs (
    id_user SERIAL PRIMARY KEY
    nom VARCHAR(10),
    login VARCHAR(10),
    adresse VARCHAR(20),
    email VARCHAR(20),
    password VARCHAR(255),
    id_role INTEGER,
    FOREIGN KEY (roleid_role) REFERENCES role(id_role)
);

CREATE TABLE role (
    id_role SERIAL PRIMARY KEY,
    libelle VARCHAR(20),
);

CREATE TABLE modele (
    id_modele SERIAL PRIMARY KEY,
    nom VARCHAR(15) NOT NULL
);

CREATE TABLE chantier (
    id_chantier SERIAL PRIMARY KEY,
    nom VARCHAR(20) NOT NULL,
    date_debut_prevu DATE,
    date_fin_prevu DATE,
    statut VARCHAR(20),
    modeleid_modele INTEGER,
    FOREIGN KEY (modeleid_modele) REFERENCES modele(id_modele)
);

CREATE TABLE tache_modele (
    id_tache_modele SERIAL PRIMARY KEY,
    nom VARCHAR(15) NOT NULL,
    ordre INTEGER,
    modeleid_modele INTEGER NOT NULL,
    FOREIGN KEY (modeleid_modele) REFERENCES modele(id_modele)
);

CREATE TABLE tache (
    id_tache SERIAL PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    ordre INTEGER,
    statut VARCHAR(10),
    pourcentage DECIMAL(19,0),
    date_debut_prevue DATE,
    date_fin_prevue DATE,
    date_debut_reelle DATE,
    date_fin_reelle DATE,
    tache_modeleid_tache_modele INTEGER,
    chantierid_chantier INTEGER NOT NULL,
    utilisateursid_utilisateur INTEGER,
    FOREIGN KEY (tache_modeleid_tache_modele) REFERENCES 						tache_modele(id_tache_modele),
    FOREIGN KEY (chantierid_chantier) REFERENCES chantier(id_chantier),
    FOREIGN KEY (utilisateursid_utilisateur) REFERENCES utilisateurs(id_user)
);

CREATE TABLE affectation_chantier (
    utilisateursid_utilisateur INTEGER NOT NULL,
    chantierid_chantier INTEGER NOT NULL,
    roleid_role INTEGER NOT NULL,
    PRIMARY KEY (utilisateursid_utilisateur, chantierid_chantier),
    FOREIGN KEY (utilisateursid_utilisateur) REFERENCES utilisateurs(id_user),
    FOREIGN KEY (chantierid_chantier) REFERENCES chantier(id_chantier),
    FOREIGN KEY (roleid_role) REFERENCES role(id_role)
)


INSERT INTO role (libelle) VALUES
('Administrateur'),
('Chef chantier'),
('Ouvrier'),
('Architecte');

INSERT INTO modele (nom) VALUES
('Résidentiel'),
('Commercial'),
('Industriel'),
('Infrastructure'),
('Rénovation');

INSERT INTO tache_modele (nom, ordre, Modeleid_modele) VALUES
('Fondations', 1, 1),
('Gros oeuvre', 2, 1),
('Toiture', 3, 1),
('Plomberie', 4, 1),
('Électricité', 5, 1),
('Finitions', 6, 1);


