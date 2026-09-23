<?php
define('APP_RUNNING', true);
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/function.php';
require_once __DIR__ . '/../../includes/components/loader.php';

$pageClass       = 'cimetiere';
$pageTitle       = "Cimetière";
$pageDescription = "Concessions et tarifs du cimetière de Montjean (53320), en Mayenne.";

require_once __DIR__ . '/../../includes/header.php';

renderHero($pageClass, "Cimetière", "Concessions et tarifs", "/asset/images/service/cimetiere/cimetiere.png");

renderSection(
    $pageClass,
    "presentation",
    "Le cimetière de Montjean",
    "Le cimetière communal de Montjean propose différentes formules de concessions. Pour toute demande, rapprochez-vous directement de la Mairie."
);

// ── Tarifs depuis la DB ──────────────────────────────────────
$tarifs = getTarifsService('cimetiere');

$rows = [];
$groupeCourant = null;

foreach ($tarifs as $tarif) {
    if ($tarif['groupe'] !== $groupeCourant) {
        $rows[] = ['group' => $tarif['groupe']];
        $groupeCourant = $tarif['groupe'];
    }
    $prix = number_format((float) $tarif['tarif_base'], 0, ',', ' ') . ' €';
    $rows[] = [$tarif['groupe'], $tarif['label'], $prix];
}

renderSection(
    $pageClass,
    "tarifs",
    "Tarifs",
    "",
    renderTable($pageClass, ['Type', 'Durée', 'Tarif'], $rows)
);

renderSection(
    $pageClass,
    "contact",
    "Nous contacter",
    "Pour toute demande de concession, contactez la mairie de Montjean.",
    renderActions($pageClass, [
        ['link' => '/contact.php', 'label' => 'Contacter la mairie'],
    ])
);

require_once __DIR__ . '/../../includes/footer.php';
?>