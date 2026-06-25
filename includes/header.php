<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        <?= isset($pageTitle) ? htmlspecialchars($pageTitle) . ' - Mairie de Montjean' : 'Mairie de Montjean (53320)' ?>
    </title>
    <meta name="description"
        content="<?= isset($pageDescription) ? htmlspecialchars($pageDescription) : 'Site officiel de la commune de Montjean (53320)' ?>">

    <!-- Open Graph -->
    <meta property="og:title" content="<?= isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Mairie de Montjean' ?>">
    <meta property="og:description"
        content="<?= isset($pageDescription) ? htmlspecialchars($pageDescription) : 'Site officiel de la commune de Montjean' ?>">
    <meta property="og:type" content="website">
    <meta property="og:image" content="/asset/images/logo.png">

    <?php if (!empty($currentUrl)): ?>
        <link rel="canonical" href="<?= htmlspecialchars($currentUrl) ?>">
    <?php endif; ?>
    <link rel="icon" href="/asset/images/favicon.png">
    <link rel="stylesheet" href="/asset/css/style.css">

    <?php if (isset($pageCss) && !empty($pageCss)): ?>
        <link rel="stylesheet" href="<?= htmlspecialchars($pageCss) ?>">
    <?php endif; ?>
</head>

<body>

    <header class="site-header">

        <!-- Bande supérieure : liens rapides + logo -->
        <div class="header-top">
            <a href="/contact.php#demarches" class="quick-link">Mes démarches</a>

            <a href="/" class="logo-link" aria-label="Retour à l'accueil - Mairie de Montjean">
                <img src="/asset/images/logo.png" alt="Logo de la commune de Montjean">
            </a>

            <a href="/contact.php" class="quick-link">Nous contacter</a>
        </div>

        <!-- Navigation principale avec menus déroulants -->
        <nav class="main-nav" aria-label="Navigation principale">

            <!-- Bouton burger mobile -->
            <button class="nav-burger" aria-label="Ouvrir le menu" aria-expanded="false" aria-controls="nav-list">
                <span class="burger-bar"></span>
                <span class="burger-bar"></span>
                <span class="burger-bar"></span>
            </button>

            <ul class="nav-list" id="nav-list">

                <!-- La Commune -->
                <li class="nav-item has-dropdown">
                    <button class="nav-trigger" aria-expanded="false" aria-haspopup="true">
                        La Commune
                        <svg class="nav-arrow" aria-hidden="true" width="10" height="6" viewBox="0 0 10 6" fill="none">
                            <path d="M1 1l4 4 4-4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                        </svg>
                    </button>
                    <ul class="nav-dropdown" role="menu">
                        <li role="none"><a href="/la-commune/presentation.php" role="menuitem">Présentation</a></li>
                        <li role="none"><a href="/la-commune/patrimoine.php" role="menuitem">Patrimoine</a></li>
                        <li role="none"><a href="/la-commune/sentiers-communaux.php" role="menuitem">Sentiers communaux</a></li>
                        <li role="none"><a href="/la-commune/aire-de-jeux.php" role="menuitem">Aire de jeux</a></li>
                        <li role="none"><a href="/la-commune/occupation.php" role="menuitem">Occupation des sols</a></li>
                    </ul>
                </li>

                <!-- Mairie -->
                <li class="nav-item has-dropdown">
                    <button class="nav-trigger" aria-expanded="false" aria-haspopup="true">
                        Mairie
                        <svg class="nav-arrow" aria-hidden="true" width="10" height="6" viewBox="0 0 10 6" fill="none">
                            <path d="M1 1l4 4 4-4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                        </svg>
                    </button>
                    <ul class="nav-dropdown" role="menu">
                        <li role="none"><a href="/mairie/equipe.php" role="menuitem">Équipe municipale</a></li>
                        <li role="none"><a href="/mairie/proces-verbaux.php" role="menuitem">Procès-verbaux</a></li>
                        <li role="none"><a href="/mairie/arretes.php" role="menuitem">Arrêtés</a></li>
                    </ul>
                </li>

                <!-- Vie locale -->
                <li class="nav-item has-dropdown">
                    <button class="nav-trigger" aria-expanded="false" aria-haspopup="true">
                        Vie locale
                        <svg class="nav-arrow" aria-hidden="true" width="10" height="6" viewBox="0 0 10 6" fill="none">
                            <path d="M1 1l4 4 4-4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                        </svg>
                    </button>
                    <ul class="nav-dropdown" role="menu">
                        <li role="none"><a href="/vie-locale/ecole.php" role="menuitem">École</a></li>
                        <li role="none"><a href="/vie-locale/periscolaire.php" role="menuitem">Périscolaire</a></li>
                        <li role="none"><a href="/vie-locale/espace.php" role="menuitem">Espace famille</a></li>
                        <li role="none"><a href="/vie-locale/bibliotheque.php" role="menuitem">Bibliothèque</a></li>
                        <li role="none"><a href="/vie-locale/artisans.php" role="menuitem">Artisans & Entreprises</a></li>
                        <li role="none"><a href="/vie-locale/associations.php" role="menuitem">Associations</a></li>
                        <li role="none"><a href="/vie-locale/utile.php" role="menuitem">Infos utiles</a></li>
                    </ul>
                </li>

                <!-- Démarches -->
                <li class="nav-item has-dropdown">
                    <button class="nav-trigger" aria-expanded="false" aria-haspopup="true">
                        Démarches
                        <svg class="nav-arrow" aria-hidden="true" width="10" height="6" viewBox="0 0 10 6" fill="none">
                            <path d="M1 1l4 4 4-4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                        </svg>
                    </button>
                    <ul class="nav-dropdown" role="menu">
                        <li role="none"><a href="/demarche/urbanisme.php" role="menuitem">Urbanisme</a></li>
                        <li role="none"><a href="/demarche/recensement-citoyen.php" role="menuitem">Recensement citoyen</a></li>
                        <li role="none"><a href="/demarche/recensement-population.php" role="menuitem">Recensement population</a></li>
                        <li role="none"><a href="/demarche/argent-de-poche.php" role="menuitem">Argent de poche</a></li>
                        <li role="none"><a href="/demarche/salle-des-fete.php" role="menuitem">Salle des fêtes</a></li>
                    </ul>
                </li>

                <!-- Services -->
                <li class="nav-item has-dropdown">
                    <button class="nav-trigger" aria-expanded="false" aria-haspopup="true">
                        Services
                        <svg class="nav-arrow" aria-hidden="true" width="10" height="6" viewBox="0 0 10 6" fill="none">
                            <path d="M1 1l4 4 4-4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                        </svg>
                    </button>
                    <ul class="nav-dropdown" role="menu">
                        <li role="none"><a href="/service/etat-civil.php" role="menuitem">État civil</a></li>
                        <li role="none"><a href="/service/cimetiere.php" role="menuitem">Cimetière</a></li>
                        <li role="none"><a href="/service/election.php" role="menuitem">Élections</a></li>
                        <li role="none"><a href="/service/dechetterie.php" role="menuitem">Déchetterie</a></li>
                    </ul>
                </li>

            </ul><!-- /nav-list -->
        </nav>

    </header>

    <main class="site-content">