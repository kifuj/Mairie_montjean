<?php
define('APP_RUNNING', true);
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/function.php';
require_once __DIR__ . '/../includes/components/loader.php';

$pageTitle = "Accueil";
$pageDescription = "Site officiel de la Mairie de Montjean (53320) : actualités, démarches administratives, vie locale et informations pratiques.";
$pageClass = "index";
$pageCss = "/asset/css/pages/index.css";
require_once __DIR__ . '/../includes/header.php';

renderHero($pageClass, "Bienvenue à Montjean", "Site officiel", "/asset/images/index.png");

require_once __DIR__ . "/../includes/acces-rapide.php"; 


renderSection(
    $pageClass,
    'acces-rapides',
    'Accès rapides',
    '',
    '<div class="qa-track">'
    . implode('', array_map(function ($item) {
        return '
        <a href="' . htmlspecialchars($item['href']) . '" class="qa-item">
            <span class="qa-icon">
                <i data-lucide="' . htmlspecialchars($item['icon']) . '"></i>
            </span>
            <span class="qa-label">' . htmlspecialchars($item['label']) . '</span>
        </a>';
    }, $qaItems))
    . '</div>'
);


renderSection(
    $pageClass,
    "presentation",
    "Bienvenue à Montjean",
    "
    Située au cœur de la Mayenne, Montjean est une commune dynamique qui allie le charme de la campagne à une vie locale riche. Ses habitants profitent d'un cadre de vie agréable, d'un tissu associatif actif et de nombreux services destinés aux familles, aux jeunes et aux seniors.

    À travers ce site, retrouvez toutes les informations utiles concernant la Mairie, les démarches administratives, les actualités de la commune, les événements ainsi que les services municipaux.
    "
);

renderSection(
    $pageClass,
    "chiffres",
    "Montjean en quelques chiffres",
    "",
    renderCards(
        $pageClass,
        [
            [
                'title' => 'Habitants',
                'lines' => [
                    '1033',
                    'Population municipale'
                ]
            ],
            [
                'title' => 'Code postal',
                'lines' => [
                    '53320'
                ]
            ],
            [
                'title' => 'Territoire',
                'lines' => [
                    '53 km²'
                ]
            ]
        ]
    )
);



renderSection(
    $pageClass,
    "actualites",
    "Actualités",
    "",
    renderIntegration(
        $pageClass,
        [
            "https://app.panneaupocket.com/embeded/1295380060?mode=widgetConfig&autoNavigation=0&widgetConfigId=1f1697e4-c2b7-6862-afd1-318931428377",
            "https://app.panneaupocket.com/embeded/1295380060?mode=widgetConfig&autoNavigation=0&widgetConfigId=1f1697e0-4c68-6c1e-a9f1-4bbb187e512f"
        ],
        "height:518px;width:1280px; max-height:100%; width: 100%; border:none;"
    )
);

renderSection(
    $pageClass,
    "horaires",
    "Horaires de la Mairie",
    "",
    renderHoraires($pageClass, getHorairesMairie())
);



require_once __DIR__ . '/../includes/footer.php';
?>