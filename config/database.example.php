<?php
// config/database.example.php
//
// Copier ce fichier en database.php.
// database.php ne doit JAMAIS être commité sur Git (voir .gitignore).
// Nécessite que config.php ait déjà été chargé (constantes DB_*).

if (!defined('APP_RUNNING')) {
    die('Accès direct interdit');
}

try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET,
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    if (APP_ENV === 'development') {
        die('Erreur de connexion : ' . $e->getMessage());
    } else {
        die('Erreur de connexion à la base de données.');
    }
}