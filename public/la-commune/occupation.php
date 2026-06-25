<?php
define('APP_RUNNING', true);

$pageClass = "occupation";
$pageTitle = "Occupation des sols";
$pageDescription = "Découvrez l'occupation des sols de Montjean (53320), en Mayenne.";
$pageCss = "/asset/css/pages/la-commune/occupation.css";

require_once __DIR__ . "/../../includes/components/loader.php";
include __DIR__ . '/../../includes/header.php';

renderHero(
    $pageClass,
    $pageTitle,
    $pageDescription
);

renderSection(
    $pageClass,
    "carte",
    "Carte de l'occupation des sols",
    "",
    renderImage(
        $pageClass,
        "/asset/images/la-commune/occupation/53158-Montjean-Sols.png",
        "Carte de l'occupation des sols de Montjean (53320)"
    )
);

renderSection(
    $pageClass,
    "source",
    "Source",
    "",
    renderCards($pageClass, [
        [
            'title' => 'Crédits cartographiques',
            'lines' => [
                'Auteur : Roland45',
                'Découpage administratif : Portail data.gouv.fr, janvier 2021',
                'Cours d\'eau : BD Carthage — Métropole 2017',
                'Occupation des sols : Corine Land Cover 2018',
                'Infrastructures : OpenStreetMap / Geofabrik, avril 2021',
                'Traitement : QGIS — Licence CC BY-SA 4.0',
            ],
        ],
    ])
);

require_once __DIR__ . "/../../includes/footer.php";
?>