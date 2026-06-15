# Refonte du site internet de la Mairie de Montjean (53320)

Projet de refonte complète du site internet de la commune de Montjean, réalisé dans le cadre du BTS SIO.

## 📋 Description

Site institutionnel moderne, responsive et accessible permettant :

- la consultation des actualités et événements via **PanneauPocket**
- l'accès aux démarches administratives et documents municipaux
- la présentation des associations, commerces et services locaux
- une gestion de contenu simplifiée via back-office

## 🛠️ Technologies

- **Front-End** : HTML5, CSS3, JavaScript ES6+
- **Back-End** : PHP 8.x
- **Base de données** : MySQL 8.x
- **Serveur** : Apache / Nginx

## 📁 Structure du projet

montjean-site/

├── public/         # Pages accessibles publiquement

├── assets/         # CSS, JS, images

├── documents/      # Documents administratifs (PDF, comptes-rendus...)

├── admin/          # Back-office d'administration

├── includes/       # Code PHP partagé (header, footer, fonctions, auth, API)

├── config/         # Configuration (base de données, clés API)

├── uploads/        # Fichiers envoyés via l'administration

├── cache/          # Cache des données PanneauPocket

└── sql/            # Schéma de base de données

## ✨ Fonctionnalités principales

- Synchronisation automatique des publications et événements **PanneauPocket**
- Gestion documentaire (PDF, comptes-rendus, délibérations, bulletins)
- Pages dédiées : associations, artisans/entreprises, bibliothèque, école, urbanisme
- Formulaire de contact (RGPD, anti-spam)
- Back-office avec gestion des utilisateurs et des droits (Visiteur / Éditeur / Administrateur)
- Journalisation des actions d'administration

## 👤 Rôles utilisateurs

| Rôle | Droits |
|------|--------|
| Visiteur | Consultation, téléchargement, formulaire de contact |
| Administrateur | Gestion complète (utilisateurs, pages, paramètres, logs) |

## 📱 Responsive

- Ordinateur : ≥ 1280px
- Tablette : 768px – 1279px
- Mobile : 320px – 767px

## 🔒 Sécurité & conformité

- HTTPS obligatoire
- Protection CSRF, anti-injection SQL, anti-XSS
- Mots de passe hashés (≥ 12 caractères)
- Conformité RGPD (mentions légales, cookies, consentement)

## 📄 Documentation

Le cahier des charges complet est disponible dans `/docs/cahier-des-charges.pdf`.

## 🚀 Installation

1. Cloner le dépôt

```bash
   git clone https://github.com/<ton-compte>/montjean-site.git
```

1. Importer le schéma de base de données : `sql/schema.sql`
2. Configurer `config/config.php` (accès BDD, clé API PanneauPocket)
3. Lancer sur un serveur PHP 8.x / MySQL 8.x

## 📌 Statut du projet

🚧 En cours de développement — version 1.2.0

## 👨‍💻 Auteur

**Matteo Lamare** — BTS SIO
