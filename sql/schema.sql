DROP DATABASE IF EXISTS mydb;
CREATE DATABASE mydb
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE mydb;

-- =====================================================
-- TABLES DE REFERENCE
-- =====================================================

CREATE TABLE naf (
    code VARCHAR(10) PRIMARY KEY,
    libelle VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE naf_autorises (
    prefixe VARCHAR(10) PRIMARY KEY,
    libelle VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- UTILISATEUR
-- =====================================================

CREATE TABLE Utilisateur (
    id INT AUTO_INCREMENT PRIMARY KEY,
    identifiant VARCHAR(45) NOT NULL UNIQUE,
    mdp VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- ASSOCIATIONS
-- =====================================================

CREATE TABLE associations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    siret VARCHAR(14) UNIQUE,
    nom VARCHAR(255) NOT NULL,
    adresse VARCHAR(255),
    objet TEXT,
    telephone VARCHAR(20),
    email VARCHAR(255),
    site VARCHAR(255),
    codeNAF VARCHAR(10),
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_associations_codeNAF (codeNAF),
    INDEX idx_associations_nom (nom)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- ENTREPRISES
-- =====================================================

CREATE TABLE entreprises (
    id INT AUTO_INCREMENT PRIMARY KEY,
    siret VARCHAR(14) UNIQUE,
    nom VARCHAR(255) NOT NULL,
    adresse VARCHAR(255),
    activite VARCHAR(255),
    codeNAF VARCHAR(10),
    description TEXT,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_entreprises_codeNAF (codeNAF),
    INDEX idx_entreprises_nom (nom)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- RESEAUX SOCIAUX
-- =====================================================

CREATE TABLE Reseau (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reseau VARCHAR(45) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- HORAIRES
-- =====================================================

CREATE TABLE Horaire (
    id INT AUTO_INCREMENT PRIMARY KEY,
    jour ENUM(
        'Lundi',
        'Mardi',
        'Mercredi',
        'Jeudi',
        'Vendredi',
        'Samedi',
        'Dimanche'
    ) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO Horaire (jour) VALUES
('Lundi'),
('Mardi'),
('Mercredi'),
('Jeudi'),
('Vendredi'),
('Samedi'),
('Dimanche');

-- =====================================================
-- PHOTOS
-- =====================================================

CREATE TABLE Photo (
    id INT AUTO_INCREMENT PRIMARY KEY,
    lien VARCHAR(255) NOT NULL,
    entreprise_id INT,

    FOREIGN KEY (entreprise_id)
        REFERENCES entreprises(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- ENTREPRISE <-> RESEAU
-- =====================================================

CREATE TABLE Entreprise_Reseau (
    entreprise_id INT NOT NULL,
    reseau_id INT NOT NULL,
    url VARCHAR(255) NOT NULL,

    PRIMARY KEY (entreprise_id, reseau_id),

    FOREIGN KEY (entreprise_id)
        REFERENCES entreprises(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    FOREIGN KEY (reseau_id)
        REFERENCES Reseau(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- ASSOCIATION <-> RESEAU
-- =====================================================

CREATE TABLE Association_Reseau (
    association_id INT NOT NULL,
    reseau_id INT NOT NULL,
    url VARCHAR(255) NOT NULL,

    PRIMARY KEY (association_id, reseau_id),

    FOREIGN KEY (association_id)
        REFERENCES associations(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    FOREIGN KEY (reseau_id)
        REFERENCES Reseau(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- ENTREPRISE <-> HORAIRE
-- =====================================================

CREATE TABLE Entreprise_Horaire (
    entreprise_id INT NOT NULL,
    horaire_id INT NOT NULL,
    heure_debut TIME NOT NULL,
    heure_fin TIME NOT NULL,

    PRIMARY KEY (
        entreprise_id,
        horaire_id,
        heure_debut
    ),

    FOREIGN KEY (entreprise_id)
        REFERENCES entreprises(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    FOREIGN KEY (horaire_id)
        REFERENCES Horaire(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CHECK (heure_fin > heure_debut)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- ASSOCIATION <-> HORAIRE
-- =====================================================

CREATE TABLE Association_Horaire (
    association_id INT NOT NULL,
    horaire_id INT NOT NULL,
    heure_debut TIME NOT NULL,
    heure_fin TIME NOT NULL,

    PRIMARY KEY (
        association_id,
        horaire_id,
        heure_debut
    ),

    FOREIGN KEY (association_id)
        REFERENCES associations(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    FOREIGN KEY (horaire_id)
        REFERENCES Horaire(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CHECK (heure_fin > heure_debut)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- HORAIRES MAIRIE
-- =====================================================

CREATE TABLE horaires_mairie (
    id INT AUTO_INCREMENT PRIMARY KEY,
    jour VARCHAR(20) NOT NULL UNIQUE,
    horaires VARCHAR(100) NOT NULL,
    ordre TINYINT UNSIGNED NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO horaires_mairie (jour, horaires, ordre) VALUES
('Lundi',    '9h-12h / 13h45-17h30', 1),
('Mardi',    '9h-12h',               2),
('Mercredi', '9h-12h',               3),
('Jeudi',    '9h-12h',               4),
('Vendredi', '9h-12h / 13h45-17h30', 5),
('Samedi',   'Fermé',                6),
('Dimanche', 'Fermé',                7);

-- =====================================================
-- NAF - LIBELLES
-- =====================================================

INSERT IGNORE INTO naf (code, libelle) VALUES
('43.21A', 'Électricité'),
('43.22A', 'Plomberie'),
('43.22B', 'Chauffage'),
('43.31Z', 'Plâtrerie'),
('43.32A', 'Menuiserie'),
('43.34Z', 'Peinture'),
('43.91A', 'Travaux de charpente'),
('43.99B', 'Travaux d''étanchéification'),
('43.99C', 'Travaux de maçonnerie générale'),
('43.99D', 'Autres travaux spécialisés de construction'),
('43.12A', 'Travaux de terrassement'),
('45.11Z', 'Commerce de voitures et véhicules légers'),
('45.20A', 'Garage automobile'),
('47.11A', 'Commerce alimentaire'),
('47.11B', 'Commerce alimentaire généraliste'),
('56.10A', 'Restaurant'),
('75.00Z', 'Vétérinaire'),
('95.11Z', 'Informatique'),
('96.02A', 'Coiffure'),
('96.02B', 'Soins de beauté'),
('96.04Z', 'Bien-être'),
('94.99Z', 'Activités associatives diverses'),
('93.12Z', 'Activités de clubs de sports'),
('93.19Z', 'Autres activités liées au sport'),
('90.01Z', 'Arts du spectacle vivant'),
('85.20Z', 'Enseignement primaire'),
('88.91A', 'Accueil de jeunes enfants'),
('43.39Z', 'Autres travaux de finition');

-- =====================================================
-- NAF AUTORISES
-- =====================================================

INSERT IGNORE INTO naf_autorises (prefixe, libelle) VALUES
('41.', 'Construction de bâtiments'),
('42.', 'Génie civil'),
('43.', 'Travaux de construction spécialisés'),
('45.', 'Commerce et réparation automobile'),
('47.11', 'Commerce de détail alimentaire'),
('47.21', 'Commerce de détail alimentaire spécialisé'),
('56.10', 'Restauration'),
('75.00', 'Activités vétérinaires'),
('95.11', 'Réparation d''ordinateurs et de biens personnels'),
('96.02', 'Coiffure et soins de beauté'),
('96.04', 'Entretien corporel');