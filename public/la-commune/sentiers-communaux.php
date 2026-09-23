<?php
define('APP_RUNNING', true);

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/function.php';

$pageTitle = "Sentiers communaux";
$pageDescription = "Découvrez les sentiers communaux de Montjean (53320), en Mayenne.";
$pageClass = "sentier";
$pageCss = "/assets/css/pages/la-commune/sentiers.css";

require_once __DIR__ . "/../../includes/components/loader.php";
include __DIR__ . '/../../includes/header.php';

renderHero(
    $pageClass,
    $pageTitle,
    $pageDescription,
    "/asset/images/la-commune/sentiers/sentier.png"
);

renderSection(
    $pageClass,
    "chemin",
    "Les Chemins Pédestres",
    "Les chemins pédestres sont accessible depuis l'étang et dispose de 3 parcours allant de 1,7 km à 3,7 km.",
    renderImage(
        $pageClass,
        "/asset/images/la-commune/sentiers/Chemin-pedestre.png",
        "images qui montre les different parcours du chemin pedestre de montjean"
    )
);


require_once __DIR__ . "/../../includes/footer.php";
?>