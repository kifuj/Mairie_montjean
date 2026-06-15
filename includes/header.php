<?php
// includes/header.php
require_once __DIR__ . '/../config/config.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) . ' - Mairie de Montjean' : 'Mairie de Montjean (53320)' ?></title>
    <meta name="description" content="<?= isset($pageDescription) ? htmlspecialchars($pageDescription) : 'Site officiel de la commune de Montjean (53320)' ?>">

    <!-- Open Graph -->
    <meta property="og:title" content="<?= isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Mairie de Montjean' ?>">
    <meta property="og:description" content="<?= isset($pageDescription) ? htmlspecialchars($pageDescription) : 'Site officiel de la commune de Montjean' ?>">
    <meta property="og:type" content="website">

    <link rel="canonical" href="<?= htmlspecialchars($currentUrl ?? '') ?>">
    <link rel="icon" href="/assets/images/favicon.png">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>

<header class="site-header">
    <div class="header-top">
        <a href="/contact.php#demarches" class="quick-link">Mes démarches</a>

        <a href="/" class="logo-link" aria-label="Retour à l'accueil - Mairie de Montjean">
            <img src="/assets/images/logo.png" alt="Logo de la commune de Montjean">
        </a>

        <a href="/panneaupocket.php" class="quick-link">PanneauPocket</a>
    </div>

    <nav class="main-nav" aria-label="Navigation principale">
        <ul>
            <li><a href="/la-commune/presentation.php">La Commune</a></li>
            <li><a href="/mairie/equipe.php">Mairie</a></li>
            <li><a href="/vie-locale/ecole.php">Vie locale</a></li>
            <li><a href="/demarches/urbanisme.php">Urbanisme</a></li>
            <li><a href="/demarches/recensement-citoyen.php">Démarches</a></li>
            <li><a href="/mairie/deliberations.php">Documents</a></li>
            <li><a href="/contact.php">Contact</a></li>
        </ul>
    </nav>
</header>

<main class="site-content">
