<?php
// ============================================================
//  Layout partagé admin — ouverture
//  Destination réelle : includes/admin/admin-header.php
//
//  Attendu avant inclusion :
//    - requireAdmin() déjà appelé par la page parente
//    - $pageTitle (string)
//    - $activeNav (string, optionnel) parmi :
//        dashboard | horaires | tarifs | associations |
//        entreprises | documents | assistants | personnel
// ============================================================
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) . ' — Admin Montjean' : 'Admin — Mairie de Montjean' ?></title>
    <link rel="icon" href="/asset/images/favicon.png">
    <link rel="stylesheet" href="/asset/css/style.css">
    <link rel="stylesheet" href="/asset/css/admin.css">
</head>
<body class="admin-body">

    <div class="admin-shell">

        <aside class="admin-sidebar">

            <a href="/admin/index.php" class="admin-sidebar-logo">
                <img src="/asset/images/logo.png" alt="Mairie de Montjean">
            </a>

            <nav class="admin-nav">
                <a href="/admin/index.php" class="admin-nav-link<?= ($activeNav ?? '') === 'dashboard' ? ' is-active' : '' ?>">
                    Tableau de bord
                </a>
                <a href="/admin/bulletins.php" class="admin-nav-link<?= ($activeNav ?? '') === 'bulletins' ? ' is-active' : '' ?>">
                    Bulletins municipaux
                </a>
                <a href="/admin/elus.php" class="admin-nav-link<?= ($activeNav ?? '') === 'elus' ? ' is-active' : '' ?>">
                    Équipe municipale
                </a>
                <a href="/admin/horaires.php" class="admin-nav-link<?= ($activeNav ?? '') === 'horaires' ? ' is-active' : '' ?>">
                    Horaires
                </a>
                <a href="/admin/tarifs.php" class="admin-nav-link<?= ($activeNav ?? '') === 'tarifs' ? ' is-active' : '' ?>">
                    Tarifs
                </a>
                <a href="/admin/associations.php" class="admin-nav-link<?= ($activeNav ?? '') === 'associations' ? ' is-active' : '' ?>">
                    Associations
                </a>
                <a href="/admin/entreprises.php" class="admin-nav-link<?= ($activeNav ?? '') === 'entreprises' ? ' is-active' : '' ?>">
                    Artisans &amp; entreprises
                </a>
                <a href="/admin/documents.php" class="admin-nav-link<?= ($activeNav ?? '') === 'documents' ? ' is-active' : '' ?>">
                    Documents (PV / Arrêtés)
                </a>
                <a href="/admin/assistants-maternels.php" class="admin-nav-link<?= ($activeNav ?? '') === 'assistants' ? ' is-active' : '' ?>">
                    Assistants maternels
                </a>
                <a href="/admin/personnel-periscolaire.php" class="admin-nav-link<?= ($activeNav ?? '') === 'personnel' ? ' is-active' : '' ?>">
                    Personnel périscolaire
                </a>

                <?php
                try {
                    $nbNonLus = countMessagesNonLus();
                } catch (Throwable $e) {
                    $nbNonLus = 0;
                }
                ?>
                <a href="/admin/liens.php" class="admin-nav-link<?= ($activeNav ?? '') === 'liens' ? ' is-active' : '' ?>">
                    Liens &amp; documents
                </a>

                <a href="/admin/messages.php" class="admin-nav-link<?= ($activeNav ?? '') === 'messages' ? ' is-active' : '' ?>">
                    Messages
                    <?php if ($nbNonLus > 0): ?>
                        <span class="admin-nav-badge"><?= $nbNonLus ?></span>
                    <?php endif; ?>
                </a>
            </nav>

            <div class="admin-sidebar-footer">
                <a href="/" class="admin-nav-link admin-nav-link--muted">← Voir le site</a>
                <a href="/admin/logout.php" class="admin-nav-link admin-nav-link--muted">Déconnexion</a>
            </div>

        </aside>

        <div class="admin-main">

            <header class="admin-topbar">
                <h1 class="admin-topbar-title"><?= isset($pageTitle) ? htmlspecialchars($pageTitle) : '' ?></h1>
                <span class="admin-topbar-user">
                    Connecté en tant que <strong><?= htmlspecialchars(currentAdminName()) ?></strong>
                </span>
            </header>

            <main class="admin-content">