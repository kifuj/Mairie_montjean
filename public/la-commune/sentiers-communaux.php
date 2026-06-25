<?php
define('APP_RUNNING', true);


$pageTitle = "Sentier communaux";
$pageDescription = "Découvrez les sentiers communaux de Montjean (53320), en Mayenne.";
$pageClass = "sentier";
$pageCss = "/assets/css/pages/la-commune/sentiers.css";

require_once __DIR__ . "/../../includes/components/loader.php";
include __DIR__ . '/../../includes/header.php';

renderHero(
    $pageClass,
    $pageTitle,
    $pageDescription
);

renderSection(
    $pageClass,
    "chemin",
    "Chemin Pedestre",
    "Le chemin pedestre accessible depuis l'etang dispose de 3 parcours allant de 1,7km a 3,7km",
    renderImage(
        $pageClass,
        "/asset/images/la-commune/sentiers/Chemin-pedestre.png",
        "images qui montre les different parcours du chemin pedestre de montjean"
    )
);


require_once __DIR__ . "/../../includes/footer.php";
?>