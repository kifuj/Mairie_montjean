<?php
define('APP_RUNNING', true);
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/function.php';
require_once __DIR__ . '/../../includes/components/loader.php';

$pageClass       = 'cimetiere';
$pageTitle       = "Cimetière";
$pageDescription = "Concessions et tarifs du cimetière de Montjean (53320), en Mayenne.";

require_once __DIR__ . '/../../includes/header.php';

renderHero($pageClass, "Cimetière", "Concessions et tarifs.");

renderSection(
    $pageClass,
    "presentation",
    "Le cimetière de Montjean",
    "Le cimetière communal de Montjean propose différentes formules de concessions. Pour toute demande, rapprochez-vous directement de la mairie."
);

renderSection(
    $pageClass,
    "tarifs",
    "Tarifs",
    "",
    renderTable($pageClass,
        ['Type', 'Durée', 'Tarif'],
        [
            ['group' => 'Concessions au cimetière'],
            ['Concession', 'Trentenaire (30 ans)',  '95 €'],
            ['Concession', 'Cinquantenaire (50 ans)', '155 €'],

            ['group' => 'Columbarium'],
            ['Columbarium', '15 ans', '670 €'],
            ['Columbarium', '30 ans', '1 060 €'],

            ['group' => 'Cavurnes'],
            ['Cavurnes', '15 ans', '400 €'],
            ['Cavurnes', '30 ans', '670 €'],
            ['Cavurnes', '50 ans', '1 060 €'],
        ]
    )
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