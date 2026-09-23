-- ============================================================
--  Mairie de Montjean (53320) — Schéma base de données
--  Encodage : utf8mb4 / Moteur : InnoDB
-- ============================================================

SET NAMES utf8mb4;
SET time_zone = '+01:00';
SET foreign_key_checks = 0;

-- ------------------------------------------------------------
--  1. UTILISATEURS ADMIN
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `admin_users` (
  `id`              INT UNSIGNED     NOT NULL AUTO_INCREMENT,
  `nom`             VARCHAR(100)     NOT NULL,
  `prenom`          VARCHAR(100)     NOT NULL,
  `email`           VARCHAR(180)     NOT NULL UNIQUE,
  `password_hash`   VARCHAR(255)     NOT NULL,          -- password_hash() PHP
  `remember_token`  VARCHAR(255)         NULL DEFAULT NULL,
  `derniere_connexion` DATETIME          NULL DEFAULT NULL,
  `actif`           TINYINT(1)       NOT NULL DEFAULT 1,
  `created_at`      DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`      DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
--  2. HORAIRES
--  Regroupe mairie, bibliothèque, périscolaire, etc.
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `horaires` (
  `id`          INT UNSIGNED    NOT NULL AUTO_INCREMENT,
  `service`     VARCHAR(60)     NOT NULL,   -- 'mairie' | 'bibliotheque' | 'periscolaire' …
  `label`       VARCHAR(120)    NOT NULL,   -- ex: 'Lundi – Vendredi'
  `heure_debut` TIME                NULL,   -- NULL si fermé
  `heure_fin`   TIME                NULL,
  `periode`     VARCHAR(60)         NULL,   -- ex: 'hors_vacances' | 'vacances' | NULL
  `ferme`       TINYINT(1)      NOT NULL DEFAULT 0,
  `ordre`       TINYINT UNSIGNED NOT NULL DEFAULT 0,
  `updated_at`  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_service` (`service`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
--  3. TARIFS
--  Regroupe cimetière, périscolaire, salle des fêtes, etc.
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `tarifs` (
  `id`                  INT UNSIGNED     NOT NULL AUTO_INCREMENT,
  `service`             VARCHAR(60)      NOT NULL,   -- 'cimetiere' | 'periscolaire' | 'salle_fetes'
  `groupe`              VARCHAR(120)         NULL,   -- ex: 'Columbarium' (ligne de groupe dans le tableau)
  `label`               VARCHAR(180)     NOT NULL,   -- ex: 'Concession 30 ans'
  `tarif_base`          DECIMAL(8,2)         NULL,   -- tarif unique OU tarif commune
  `tarif_hors_commune`  DECIMAL(8,2)         NULL,   -- null si pas de distinction
  `unite`               VARCHAR(40)          NULL,   -- ex: '/ demi-journée' | '/ an' | NULL
  `ordre`               SMALLINT UNSIGNED NOT NULL DEFAULT 0,
  `actif`               TINYINT(1)       NOT NULL DEFAULT 1,
  `updated_at`          DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_service` (`service`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
--  4. ASSOCIATIONS
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `associations` (
  `id`          INT UNSIGNED    NOT NULL AUTO_INCREMENT,
  `nom`         VARCHAR(180)    NOT NULL,
  `objet`       TEXT                NULL,
  `adresse`     VARCHAR(255)        NULL,
  `telephone`   VARCHAR(20)         NULL,
  `email`       VARCHAR(180)        NULL,
  `site`        VARCHAR(255)        NULL,
  `logo`        VARCHAR(255)        NULL,
  `actif`       TINYINT(1)      NOT NULL DEFAULT 1,
  `ordre`       SMALLINT UNSIGNED NOT NULL DEFAULT 0,
  `created_at`  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
--  5. ARTISANS & ENTREPRISES
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `entreprises` (
  `id`          INT UNSIGNED    NOT NULL AUTO_INCREMENT,
  `nom`         VARCHAR(180)    NOT NULL,
  `activite`    VARCHAR(180)        NULL,
  `adresse`     VARCHAR(255)        NULL,
  `telephone`   VARCHAR(20)         NULL,
  `email`       VARCHAR(180)        NULL,
  `site`        VARCHAR(255)        NULL,
  `actif`       TINYINT(1)      NOT NULL DEFAULT 1,
  `ordre`       SMALLINT UNSIGNED NOT NULL DEFAULT 0,
  `created_at`  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
--  6. DOCUMENTS (procès-verbaux, arrêtés, …)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `documents` (
  `id`            INT UNSIGNED     NOT NULL AUTO_INCREMENT,
  `categorie`     VARCHAR(60)      NOT NULL,   -- 'pv' | 'arrete_municipal' | 'arrete_prefectoral' | 'arrete_departemental'
                                                        --  ↑ préfixe 'arrete_' ajouté automatiquement par getArretesParCategorie()
  `titre`         VARCHAR(255)     NOT NULL,
  `fichier`       VARCHAR(255)     NOT NULL,   -- chemin relatif depuis /uploads/documents/
  `date_document` DATE                 NULL,   -- date de séance (PV) ou date de l'arrêté
  `visible`       TINYINT(1)       NOT NULL DEFAULT 1,
  `ordre`         SMALLINT UNSIGNED NOT NULL DEFAULT 0,
  `created_at`    DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`    DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_categorie` (`categorie`),
  KEY `idx_date`      (`date_document`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET foreign_key_checks = 1;

CREATE TABLE IF NOT EXISTS `association_reseaux` (
  `id`              INT UNSIGNED     NOT NULL AUTO_INCREMENT,
  `association_id`  INT UNSIGNED     NOT NULL,
  `label`           VARCHAR(60)      NOT NULL,   -- ex: 'Facebook', 'Site web', 'Instagram'
  `url`             VARCHAR(255)     NOT NULL,
  `ordre`           SMALLINT UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_association` (`association_id`),
  CONSTRAINT `fk_reseaux_association`
    FOREIGN KEY (`association_id`) REFERENCES `associations` (`id`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================================
--  DONNÉES INITIALES
-- ============================================================

-- Compte admin par défaut
-- Mot de passe : A_CHANGER_EN_PRODUCTION (généré avec password_hash())
INSERT INTO `admin_users` (`nom`, `prenom`, `email`, `password_hash`) VALUES
('Admin', 'Mairie', 'admin@mairie-montjean53.fr', '$2y$12$PLACEHOLDER_REMPLACER_AVANT_PROD');

-- Horaires mairie
INSERT INTO `horaires` (`service`, `label`, `heure_debut`, `heure_fin`, `ferme`, `ordre`) VALUES
('mairie', 'Lundi',    '09:00', '12:00', 0, 1),
('mairie', 'Mardi',    '09:00', '12:00', 0, 2),
('mairie', 'Mercredi', '09:00', '12:00', 0, 3),
('mairie', 'Jeudi',    '09:00', '12:00', 0, 4),
('mairie', 'Vendredi', '09:00', '12:00', 0, 5),
('mairie', 'Samedi',   NULL,    NULL,    1, 6);

-- Horaires bibliothèque (hors vacances / vacances)
INSERT INTO `horaires` (`service`, `label`, `heure_debut`, `heure_fin`, `periode`, `ordre`) VALUES
('bibliotheque', 'Lundi',    '14:00', '17:00', 'hors_vacances',  1),
('bibliotheque', 'Mercredi', '15:30', '17:30', 'hors_vacances',  2),
('bibliotheque', 'Samedi',   '10:30', '12:30', 'hors_vacances',  3),
('bibliotheque', 'Lundi',    '14:00', '17:00', 'vacances',       4),
('bibliotheque', 'Mercredi', '16:30', '17:30', 'vacances',       5),
('bibliotheque', 'Samedi',   '11:00', '12:00', 'vacances',       6);

-- Tarifs cimetière
INSERT INTO `tarifs` (`service`, `groupe`, `label`, `tarif_base`, `ordre`) VALUES
('cimetiere', 'Concessions au cimetière', 'Trentenaire (30 ans)',       95.00,   1),
('cimetiere', 'Concessions au cimetière', 'Cinquantenaire (50 ans)',    155.00,  2),
('cimetiere', 'Columbarium',              '15 ans',                     670.00,  3),
('cimetiere', 'Columbarium',              '30 ans',                     1060.00, 4),
('cimetiere', 'Cavurnes',                 '15 ans',                     400.00,  5),
('cimetiere', 'Cavurnes',                 '30 ans',                     670.00,  6),
('cimetiere', 'Cavurnes',                 '50 ans',                     1060.00, 7);

INSERT INTO `tarifs` (`service`, `groupe`, `label`, `tarif_base`, `tarif_hors_commune`, `unite`, `ordre`) VALUES
('periscolaire', 'Accueil périscolaire — Garderie', 'QF > 750 €',          0.28, 0.33, "Au quart d'heure", 1),
('periscolaire', 'Accueil périscolaire — Garderie', '750 € < QF < 1 100 €', 0.29, 0.35, "Au quart d'heure", 2),
('periscolaire', 'Accueil périscolaire — Garderie', 'QF > 1 100 €',         0.30, 0.36, "Au quart d'heure", 3),


('periscolaire', 'Accueil des mercredis et vacances scolaires', 'QF > 750 €',           0.30, 0.36, "Au quart d'heure", 4),
('periscolaire', 'Accueil des mercredis et vacances scolaires', '750 € < QF < 1 100 €', 0.32, 0.37, "Au quart d'heure", 5),
('periscolaire', 'Accueil des mercredis et vacances scolaires', 'QF > 1 100 €',         0.33, 0.39, "Au quart d'heure", 6),
 
-- Cantine (le repas)
('periscolaire', 'Cantine', 'Maternel', 4.20, 4.95, 'Le repas', 7),
('periscolaire', 'Cantine', 'Primaire', 4.20, 4.95, 'Le repas', 8),
 
-- Centre de loisirs (la demi-journée)
('periscolaire', 'Centre de loisirs', 'QF > 750 €',           4.00, 4.80, 'La demi-journée', 9),
('periscolaire', 'Centre de loisirs', '750 € < QF < 1 100 €', 4.15, 4.98, 'La demi-journée', 10),
('periscolaire', 'Centre de loisirs', 'QF > 1 100 €',         4.25, 5.10, 'La demi-journée', 11);


-- =`logo` VARCHAR(255) NULL AFTER `site`;===========================================================
--  Ajouts au schéma — données complémentaires
-- ============================================================

-- Ajouter la colonne groupe à la table horaires
-- (pour regrouper les créneaux périscolaires en cards)
ALTER TABLE `horaires` ADD COLUMN `groupe` VARCHAR(120) NULL AFTER `service`;

-- ------------------------------------------------------------
--  Assistants maternels
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `assistants_maternels` (
  `id`          INT UNSIGNED     NOT NULL AUTO_INCREMENT,
  `nom`         VARCHAR(80)      NOT NULL,
  `prenom`      VARCHAR(80)      NOT NULL,
  `adresse`     VARCHAR(255)         NULL,
  `telephone`   VARCHAR(20)          NULL,
  `agrement`    TINYINT UNSIGNED NOT NULL DEFAULT 4,
  `mam`         VARCHAR(80)          NULL,
  `actif`       TINYINT(1)       NOT NULL DEFAULT 1,
  `ordre`       SMALLINT UNSIGNED NOT NULL DEFAULT 0,
  `updated_at`  DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
--  Personnel périscolaire
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `personnel_periscolaire` (
  `id`      INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `role`    VARCHAR(80)   NOT NULL,   -- 'Responsable' | 'Animatrice' …
  `prenom`  VARCHAR(80)   NOT NULL,
  `nom`     VARCHAR(80)   NOT NULL,
  `ordre`   TINYINT UNSIGNED NOT NULL DEFAULT 0,
  `actif`   TINYINT(1)    NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
--  Migration — Table messages de contact
-- ============================================================
 
CREATE TABLE IF NOT EXISTS `messages_contact` (
  `id`          INT UNSIGNED     NOT NULL AUTO_INCREMENT,
  `nom`         VARCHAR(120)     NOT NULL,
  `email`       VARCHAR(180)     NOT NULL,
  `telephone`   VARCHAR(20)          NULL,
  `sujet`       VARCHAR(255)     NOT NULL,
  `message`     TEXT             NOT NULL,
  `lu`          TINYINT(1)       NOT NULL DEFAULT 0,
  `created_at`  DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_lu` (`lu`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================================
--  Données initiales
-- ============================================================

-- Horaires déchetterie
INSERT INTO `horaires` (`service`, `label`, `heure_debut`, `heure_fin`, `ferme`, `ordre`) VALUES
('dechetterie', 'Lundi',    NULL,    NULL,    1, 1),
('dechetterie', 'Mardi',    NULL,    NULL,    1, 2),
('dechetterie', 'Mercredi', '08:30', '15:00', 0, 3),
('dechetterie', 'Jeudi',    NULL,    NULL,    1, 4),
('dechetterie', 'Vendredi', NULL,    NULL,    1, 5),
('dechetterie', 'Samedi',   '08:30', '15:00', 0, 6);

-- Horaires périscolaire (avec groupes pour les cards)
INSERT INTO `horaires` (`service`, `groupe`, `label`, `heure_debut`, `heure_fin`, `ordre`) VALUES
('periscolaire', 'Garderie — période scolaire',        'Matin (École Chemin de Cocaigne)', '07:00', '09:00', 1),
('periscolaire', 'Garderie — période scolaire',        'Soir',                             '16:30', '19:00', 2),
('periscolaire', 'Centre de loisirs — vacances',       'Garderie matin',                   '07:00', '09:00', 3),
('periscolaire', 'Centre de loisirs — vacances',       'Animation matin',                  '09:00', '12:00', 4),
('periscolaire', 'Centre de loisirs — vacances',       'Cantine',                          '12:00', '13:30', 5),
('periscolaire', 'Centre de loisirs — vacances',       'Animation après-midi',             '13:30', '17:00', 6),
('periscolaire', 'Centre de loisirs — vacances',       'Garderie soir',                    '17:00', '19:00', 7);

-- Personnel périscolaire
INSERT INTO `personnel_periscolaire` (`role`, `prenom`, `nom`, `ordre`) VALUES
('Responsable',  'Florian',  'GAUTIER',  1),
('Animatrice',   'Linda',    'Tourneux', 2),
('Animatrice',   'Patricia', 'Bouchez',  3),
('Animatrice',   'Sarah',    'Durand',   4);

-- Assistants maternels
INSERT INTO `assistants_maternels` (`nom`, `prenom`, `adresse`, `telephone`, `agrement`, `mam`, `ordre`) VALUES
('BARRE',     'Annie',       '37 bis Rue de Bretagne',  '02 43 69 99 27', 4, 'TOURNICOTI', 1),
('CHARIL',    'Isabelle',    '6 Rue des Lys',           '02 43 91 04 57', 4, NULL,         2),
('GAILLARD',  'Marion',      '1147 La Maison Neuve',    '06 65 31 90 39', 4, NULL,         3),
('GOISBAULT', 'Séverine',    '37 bis Rue de Bretagne',  '02 43 69 99 27', 4, 'TOURNICOTI', 4),
('GORRE',     'Sandra',      '37 bis Rue de Bretagne',  '02 43 69 99 27', 4, NULL,         5),
('LEBLANC',   'Angélina',    '37 bis Rue de Bretagne',  '02 53 22 80 25', 4, 'TOURNICOTI', 6),
('MASMOUDI',  'Zina',        '4 Rue des Lilas',         '02 43 66 02 87', 4, NULL,         7),
('RIOU',      'Anne Sophie', '13 Rue du Vieux Château', '02 43 26 33 36', 4, NULL,         8),
('TRAVERS',   'Charlotte',   '37 bis Rue de Bretagne',  '02 43 01 96 02', 4, 'TOURNICOTI', 9);


CREATE TABLE IF NOT EXISTS `page_liens` (
  `id`      INT UNSIGNED      NOT NULL AUTO_INCREMENT,
  `page`    VARCHAR(80)       NOT NULL,            -- slug de la page, ex: 'argent-de-poche'
  `label`   VARCHAR(255)      NOT NULL,            -- texte du bouton
  `type`    ENUM('fichier','externe') NOT NULL DEFAULT 'fichier',
  `valeur`  VARCHAR(500)      NOT NULL,            -- nom de fichier OU url externe
  `visible` TINYINT(1)        NOT NULL DEFAULT 1,
  `ordre`   SMALLINT UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_page` (`page`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
--  Données initiales — reprend les liens actuellement
--  codés en dur dans les pages publiques
-- ============================================================

-- Argent de poche
INSERT INTO `page_liens` (`page`, `label`, `type`, `valeur`, `ordre`) VALUES
('argent-de-poche', 'Accéder à la plateforme',   'externe', 'https://argentdepoche.agglo-laval.fr', 1),
('argent-de-poche', 'Télécharger le contrat',     'fichier', 'argent-de-poche/1_CONTRAT_AGP_AGGLO_PLATEFORME.pdf', 2),
('argent-de-poche', 'Guide d\'inscription',       'fichier', 'argent-de-poche/Guide_d_utilisation_jeune_Plat.pdf', 3);

-- Salle des fêtes
INSERT INTO `page_liens` (`page`, `label`, `type`, `valeur`, `ordre`) VALUES
('salle-des-fetes', 'Télécharger la grille tarifaire', 'fichier', 'salle-des-fetes/tarification_20salles.pdf', 1),
('salle-des-fetes', 'Nous contacter',                  'externe', '/contact.php', 2);

-- Périscolaire
INSERT INTO `page_liens` (`page`, `label`, `type`, `valeur`, `ordre`) VALUES
('periscolaire', 'Dossier d\'inscription', 'fichier', 'periscolaire/dossier-inscription-periscolaire(2022-2023).pdf', 1),
('periscolaire', 'Fiche sanitaire',        'fichier', 'periscolaire/Fiche-sanitaire.pdf', 2),
('periscolaire', 'Règlement intérieur',    'fichier', 'periscolaire/Reglement-interieur(2022-2023).pdf', 3);

-- Recensement citoyen
INSERT INTO `page_liens` (`page`, `label`, `type`, `valeur`, `ordre`) VALUES
('recensement-citoyen', 'Accéder à la démarche', 'externe', 'https://demarche.numerique.gouv.fr/commencer/centre-du-service-national-jeunesse', 1),
('recensement-citoyen', 'Nous contacter',         'externe', '/contact.php', 2),
('recensement-citoyen', 'Service-public.fr',      'externe', 'https://www.service-public.fr/particuliers/vosdroits/F870', 3);

-- Urbanisme
INSERT INTO `page_liens` (`page`, `label`, `type`, `valeur`, `ordre`) VALUES
('urbanisme', 'Déposer une demande',  'externe', 'https://www.service-public.fr/particuliers/vosdroits/R52221', 1),
('urbanisme', 'Géoportail urbanisme', 'externe', 'https://www.geoportail-urbanisme.gouv.fr/', 2),
('urbanisme', 'En savoir plus',       'externe', 'https://www.service-public.fr/particuliers/vosdroits/N319', 3);

-- ============================================================
--  Migration — Élus / Équipe municipale
-- ============================================================

CREATE TABLE IF NOT EXISTS `elus` (
  `id`       INT UNSIGNED      NOT NULL AUTO_INCREMENT,
  `ordre`    SMALLINT UNSIGNED NOT NULL DEFAULT 0,
  `fonction` VARCHAR(120)      NOT NULL,
  `civilite` ENUM('M.','Mme') NOT NULL DEFAULT 'M.',
  `nom`      VARCHAR(80)       NOT NULL,
  `prenom`   VARCHAR(80)       NOT NULL,
  `actif`    TINYINT(1)        NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
--  Données — Conseil municipal de Montjean (mars 2026)
-- ============================================================

INSERT INTO `elus` (`ordre`, `fonction`, `civilite`, `nom`, `prenom`) VALUES
(1,  'Maire',                    'M.',  'PAILLARD',  'Vincent'),
(2,  'Premier adjoint',          'Mme', 'DESSE',     'Christine'),
(3,  'Deuxième adjoint',         'M.',  'GOUAC',     'Frédéric'),
(4,  'Troisième adjoint',        'Mme', 'PETIT',     'Aurore'),
(5,  'Conseiller municipal',     'M.',  'PERBUD',    'Éric'),
(6,  'Conseiller municipal',     'M.',  'BRIANS',    'Stéphane'),
(7,  'Conseillère municipale',   'Mme', 'GAUDAU',    'Karine'),
(8,  'Conseillère municipale',   'Mme', 'DESBOIS',   'Nadia'),
(9,  'Conseiller municipal',     'M.',  'TRAVERS',   'Christophe'),
(10, 'Conseiller municipal',     'M.',  'ARNAND',    'Antoine'),
(11, 'Conseillère municipale',   'Mme', 'GUERRIER',  'Karine'),
(12, 'Conseillère municipale',   'Mme', 'LEPAROUX',  'Gaëlle'),
(13, 'Conseillère municipale',   'Mme', 'RONNIER',   'Vanessa'),
(14, 'Conseillère municipale',   'Mme', 'NEDELEC',   'Aurélie'),
(15, 'Conseiller municipal',     'M.',  'DALIN',     'Tristan');