<?php
define('APP_RUNNING', true);
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/function.php';
require_once __DIR__ . '/../includes/components/loader.php';

$pageTitle = "Accueil";
$pageDescription = "Site officiel de la Mairie de Montjean (53320) : actualités, démarches administratives, vie locale et informations pratiques.";
require_once __DIR__ . '/../includes/header.php';

$pageClass = "index";
$pageCss = "/asset/css/pages/index.css";

renderHero($pageClass, "Bienvenue à Montjean", "Site officiel", "/img/hero.jpg");

renderSection(
    $pageClass,
    "presentation",
    "Montjean et son histoire",
    "Lorem ipsum..."
);

renderSection(
    $pageClass,
    "horaires",
    "Horaires de la mairie",
    "",
    renderList($pageClass, getHorairesMairie())
);

renderSection(
    $pageClass,
    "infos-importantes",
    "Info importante",
    "Lorem ipsum..."
);

renderSection(
    $pageClass,
    "actualites",
    "Actualités",
    "",
    renderIntegration($pageClass, [
        "https://app.panneaupocket.com/embeded/1295380060?mode=widgetConfig&autoNavigation=0&widgetConfigId=1f1697e4-c2b7-6862-afd1-318931428377",
        "https://app.panneaupocket.com/embeded/1295380060?mode=widgetConfig&autoNavigation=0&widgetConfigId=1f1697e0-4c68-6c1e-a9f1-4bbb187e512f"
    ],
    "height:518px;width:1280px; max-height:100%; width: 100%; border:none;")
);

renderSection(
    $pageClass,
    "acces-rapides",
    "Accès rapides",
    "",
    renderActions($pageClass, [
        ["label" => "État civil", "link" => "#"],
        ["label" => "Urbanisme", "link" => "#"]
    ])
);

require_once __DIR__ . '/../includes/footer.php';
?>