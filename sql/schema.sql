DROP DATABASE IF EXISTS mydb;
CREATE DATABASE mydb
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE mydb;

-- =========================
-- UTILISATEUR
-- =========================
CREATE TABLE utilisateur (
    id INT AUTO_INCREMENT PRIMARY KEY,
    identifiant VARCHAR(45) NOT NULL UNIQUE,
    mdp VARCHAR(255) NOT NULL
) ENGINE=InnoDB;

-- =========================
-- ENTREPRISES (SIRENE)
-- =========================
CREATE TABLE entreprises (
    id INT AUTO_INCREMENT PRIMARY KEY,
    siret VARCHAR(14) NOT NULL UNIQUE,
    nom VARCHAR(255) NOT NULL,
    adresse VARCHAR(255),
    activite VARCHAR(255),
    codeNAF VARCHAR(10),
    description TEXT,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_entreprises_nom (nom),
    INDEX idx_entreprises_codeNAF (codeNAF)
) ENGINE=InnoDB;

-- =========================
-- ASSOCIATIONS (MAIRIE / LOCAL)
-- =========================
CREATE TABLE associations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    adresse VARCHAR(255),
    objet TEXT,
    telephone VARCHAR(20),
    email VARCHAR(255),
    site VARCHAR(255),
    codeNAF VARCHAR(10),
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_assos_nom (nom)
) ENGINE=InnoDB;

-- =========================
-- RESEAUX SOCIAUX
-- =========================
CREATE TABLE reseau (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reseau VARCHAR(45) NOT NULL UNIQUE
) ENGINE=InnoDB;

-- =========================
-- HORAIRE
-- =========================
CREATE TABLE horaire (
    id INT AUTO_INCREMENT PRIMARY KEY,
    jour ENUM(
        'Lundi','Mardi','Mercredi','Jeudi',
        'Vendredi','Samedi','Dimanche'
    ) NOT NULL UNIQUE
) ENGINE=InnoDB;

INSERT IGNORE INTO horaire (jour) VALUES
('Lundi'),('Mardi'),('Mercredi'),('Jeudi'),
('Vendredi'),('Samedi'),('Dimanche');

-- =========================
-- ENTREPRISE PHOTOS
-- =========================
CREATE TABLE photo (
    id INT AUTO_INCREMENT PRIMARY KEY,
    lien VARCHAR(255) NOT NULL,
    entreprise_id INT,
    FOREIGN KEY (entreprise_id) REFERENCES entreprises(id)
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- =========================
-- ENTREPRISE - RESEAU
-- =========================
CREATE TABLE entreprise_reseau (
    entreprise_id INT,
    reseau_id INT,
    url VARCHAR(255) NOT NULL,

    PRIMARY KEY (entreprise_id, reseau_id),

    FOREIGN KEY (entreprise_id) REFERENCES entreprises(id)
        ON DELETE CASCADE,
    FOREIGN KEY (reseau_id) REFERENCES reseau(id)
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- =========================
-- ASSO - RESEAU
-- =========================
CREATE TABLE association_reseau (
    association_id INT,
    reseau_id INT,
    url VARCHAR(255) NOT NULL,

    PRIMARY KEY (association_id, reseau_id),

    FOREIGN KEY (association_id) REFERENCES associations(id)
        ON DELETE CASCADE,
    FOREIGN KEY (reseau_id) REFERENCES reseau(id)
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- =========================
-- ENTREPRISE - HORAIRE
-- =========================
CREATE TABLE entreprise_horaire (
    entreprise_id INT,
    horaire_id INT,
    heure_debut TIME,
    heure_fin TIME,

    PRIMARY KEY (entreprise_id, horaire_id, heure_debut),

    FOREIGN KEY (entreprise_id) REFERENCES entreprises(id)
        ON DELETE CASCADE,
    FOREIGN KEY (horaire_id) REFERENCES horaire(id)
        ON DELETE CASCADE,

    CHECK (heure_fin > heure_debut)
) ENGINE=InnoDB;

-- =========================
-- ASSO - HORAIRE
-- =========================
CREATE TABLE association_horaire (
    association_id INT,
    horaire_id INT,
    heure_debut TIME,
    heure_fin TIME,

    PRIMARY KEY (association_id, horaire_id, heure_debut),

    FOREIGN KEY (association_id) REFERENCES associations(id)
        ON DELETE CASCADE,
    FOREIGN KEY (horaire_id) REFERENCES horaire(id)
        ON DELETE CASCADE,

    CHECK (heure_fin > heure_debut)
) ENGINE=InnoDB;

-- =========================
-- PROCES VERBAUX 
-- =========================
CREATE TABLE proces_verbaux (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    date_seance DATE NOT NULL,
    fichier VARCHAR(255) NOT NULL,  -- ex: "pv-2026-06-19.pdf"
    taille_ko INT,
    date_publication DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- =========================
-- ARRETES
-- =========================
CREATE TABLE arretes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    categorie ENUM('municipal', 'prefectoral', 'departemental') NOT NULL,
    date_arrete DATE NOT NULL,
    fichier VARCHAR(255) NOT NULL,
    date_publication DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- =========================
-- HORAIRES MAIRIE
-- =========================
CREATE TABLE horaires_mairie (
    id INT AUTO_INCREMENT PRIMARY KEY,
    jour VARCHAR(20) NOT NULL UNIQUE,
    horaires VARCHAR(100) NOT NULL,
    ordre TINYINT UNSIGNED NOT NULL UNIQUE
) ENGINE=InnoDB;

-- =========================
-- TARIFS PERISCOLAIRE
-- =========================
CREATE TABLE tarifs_periscolaire (
    id INT AUTO_INCREMENT PRIMARY KEY,
    groupe VARCHAR(100) NOT NULL,
    label VARCHAR(100) NOT NULL,
    tarif_commune DECIMAL(5,2) NOT NULL,
    tarif_hors_commune DECIMAL(5,2) NOT NULL,
    ordre INT NOT NULL DEFAULT 0
);

-- =========================
-- INSERT
-- =========================

INSERT INTO horaires_mairie (jour, horaires, ordre) VALUES
('Lundi','9h-12h / 13h45-17h30',1),
('Mardi','9h-12h',2),
('Mercredi','9h-12h',3),
('Jeudi','9h-12h',4),
('Vendredi','9h-12h / 13h45-17h30',5),
('Samedi','Fermé',6),
('Dimanche','Fermé',7);

INSERT INTO tarifs_periscolaire (groupe, label, tarif_commune, tarif_hors_commune, ordre) VALUES
('Accueil périscolaire garderie — au quart d\'heure', 'QF > 750 €', 0.28, 0.33, 1),
('Accueil périscolaire garderie — au quart d\'heure', '750 € < QF < 1 100 €', 0.29, 0.35, 2),
('Accueil périscolaire garderie — au quart d\'heure', 'QF > 1 100 €', 0.30, 0.36, 3),
('Accueil des mercredis et vacances scolaires — au quart d\'heure', 'QF > 750 €', 0.30, 0.36, 4),
('Accueil des mercredis et vacances scolaires — au quart d\'heure', '750 € < QF < 1 100 €', 0.32, 0.37, 5),
('Accueil des mercredis et vacances scolaires — au quart d\'heure', 'QF > 1 100 €', 0.33, 0.39, 6),
('Cantine — le repas', 'Maternel', 4.20, 4.95, 7),
('Cantine — le repas', 'Primaire', 4.20, 4.95, 8),
('Centre de loisirs — la demi-journée', 'QF > 750 €', 4.00, 4.80, 9),
('Centre de loisirs — la demi-journée', '750 € < QF < 1 100 €', 4.15, 4.98, 10),
('Centre de loisirs — la demi-journée', 'QF > 1 100 €', 4.25, 5.10, 11);


