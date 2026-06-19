DROP DATABASE IF EXISTS mydb;
CREATE DATABASE mydb
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE mydb;

-- =========================
-- NAF
-- =========================
CREATE TABLE naf (
    code VARCHAR(10) PRIMARY KEY,
    libelle VARCHAR(255) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE naf_autorises (
    prefixe VARCHAR(5) PRIMARY KEY,
    libelle VARCHAR(255) NOT NULL
);


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
-- HORAIRES MAIRIE
-- =========================
CREATE TABLE horaires_mairie (
    id INT AUTO_INCREMENT PRIMARY KEY,
    jour VARCHAR(20) NOT NULL UNIQUE,
    horaires VARCHAR(100) NOT NULL,
    ordre TINYINT UNSIGNED NOT NULL UNIQUE
) ENGINE=InnoDB;

INSERT INTO horaires_mairie (jour, horaires, ordre) VALUES
('Lundi','9h-12h / 13h45-17h30',1),
('Mardi','9h-12h',2),
('Mercredi','9h-12h',3),
('Jeudi','9h-12h',4),
('Vendredi','9h-12h / 13h45-17h30',5),
('Samedi','Fermé',6),
('Dimanche','Fermé',7);

-- =========================
-- NAF DATA
-- =========================
INSERT IGNORE INTO naf (code, libelle) VALUES
('43.21A','Électricité'),
('43.22A','Plomberie'),
('43.31Z','Plâtrerie'),
('43.32A','Menuiserie'),
('43.34Z','Peinture'),
('43.99C','Maçonnerie'),
('45.20A','Garage automobile'),
('47.11B','Commerce alimentaire'),
('56.10A','Restaurant'),
('95.11Z','Informatique'),
('96.02A','Coiffure'),
('96.04Z','Bien-être');
('56.10C', 'Restauration de type rapide'),
('43.91A', 'Travaux de charpente'),
('43.91B', 'Travaux de couverture par éléments'),
('43.39Z', 'Autres travaux de finition'),
('42.99Z', 'Construction d''autres ouvrages de génie civil'),
('43.12A', 'Travaux de terrassement courants et travaux préparatoires'),
('43.99B', 'Travaux d''étanchéification'),
('43.99D', 'Autres travaux spécialisés de construction'),
('45.4J', 'Commerce de détail d''équipements automobiles'),
('45.3A', 'Commerce de gros d''équipements automobiles'),
('45.23', 'Commerce et réparation de motocycles'),
('47.11C', 'Commerce d''alimentation générale'),
('45.4C', 'Entretien et réparation de motocycles'),
('45.11Z', 'Commerce de voitures et de véhicules automobiles légers'),
('96.02B', 'Soins de beauté'),
('41.0Z', 'Construction de bâtiments résidentiels et non résidentiels'),
('47.21Z', 'Commerce de détail de fruits et légumes en magasin spécialisé')
ON DUPLICATE KEY UPDATE libelle = VALUES(libelle);


INSERT INTO naf_autorises (prefixe, libelle) VALUES
('41', 'Construction de bâtiments'),
('42', 'Génie civil'),
('43', 'Travaux de construction spécialisés'),
('45', 'Commerce et réparation automobile'),
('47.11', 'Commerce alimentaire généraliste'),
('47.21', 'Commerce de fruits et légumes'),
('56.10', 'Restauration'),
('95.11', 'Réparation informatique'),
('96.02', 'Coiffure et soins de beauté'),
('96.04', 'Bien-être'),
('75.00', 'Activités vétérinaires');
