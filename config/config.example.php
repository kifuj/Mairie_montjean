<?php
// config/config.example.php
//
// Copier ce fichier en config.php et renseigner les vraies valeurs.
// config.php ne doit JAMAIS être commité sur Git (voir .gitignore).

// Empêche l'accès direct au fichier
if (!defined('APP_RUNNING')) {
    die('Accès direct interdit');
}

// ----------------------------------------
// Environnement
// ----------------------------------------
define('APP_ENV', 'development'); // 'development' ou 'production'

if (APP_ENV === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
}

// ----------------------------------------
// URL du site
// ----------------------------------------
define('SITE_URL', 'https://www.mairie-montjean53.fr');
define('SITE_NAME', 'Mairie de Montjean');

// ----------------------------------------
// Base de données
// (voir database.php / database.example.php pour la connexion PDO)
// ----------------------------------------
define('DB_HOST', 'localhost');
define('DB_NAME', 'montjean_site');
define('DB_USER', 'CHANGE_MOI');
define('DB_PASS', 'CHANGE_MOI');
define('DB_CHARSET', 'utf8mb4');

// ----------------------------------------
// API PanneauPocket
// ----------------------------------------
define('PANNEAUPOCKET_API_URL', 'https://api.panneaupocket.com/CHANGE_MOI');
define('PANNEAUPOCKET_API_KEY', 'CHANGE_MOI');
define('PANNEAUPOCKET_CACHE_DURATION', 900); // en secondes (ex: 15 min)

// ----------------------------------------
// Sécurité
// ----------------------------------------
define('SESSION_LIFETIME', 1800);     // 30 min d'inactivité avant déconnexion auto
define('PASSWORD_MIN_LENGTH', 12);
define('CSRF_TOKEN_NAME', 'csrf_token');

// ----------------------------------------
// Upload de fichiers
// ----------------------------------------
define('UPLOAD_DIR', __DIR__ . '/../uploads/');
define('UPLOAD_MAX_SIZE', 10 * 1024 * 1024); // 10 Mo
define('ALLOWED_FILE_TYPES', ['pdf', 'jpg', 'jpeg', 'png']);

// ----------------------------------------
// Fuseau horaire
// ----------------------------------------
date_default_timezone_set('Europe/Paris');