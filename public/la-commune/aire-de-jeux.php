<?php
define('APP_RUNNING', true);

$pageClass = 'aire';
$pageTitle = "Aire de jeux";
$pageDescription = "Découvrez les differentes aires de jeux de Montjean (53320), en Mayenne.";
$pageCss = "/assets/css/pages/la-commune/aire.css";

include __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/components/loader.php';



renderHero(
    $pageClass,
    $pageTitle,
    $pageDescription

);

renderSection(
    $pageClass,
    "aire-de-jeux",
    "Aire de jeux derriere la mairie",
    "l'aire de jeux, est situé derrière la mairie, c'est une aire de jeux pour les enfants entre x et x, ouverte en permanence" // TODO aller voir l'age d'utilisation de l'air de jeux
    
);

renderSection(
    $pageClass,
    "city",
    "City Stade",
    "Le city stade, est situé en contrebas du terrain de foot, est accessible en permanence."
);

renderSection(
    $pageClass,
    "tennis",
    "Terrain de tennis",
    "Le terrain de tennis, est situé a côté du city, et est accessible en permanence."

);

renderSection(
    $pageClass,
    "foot",
    "Terrain de foot",
    "Les terrains de foot, situés au-dessus du city stade, sont accessibles en permanence, sauf lors des entraînements de l'équipe locale."

);

renderSection(
    $pageClass,
    "petanque",
    "Terrain de pétanque",
    "Le terrain de pétanque, situé entre la mairie et l'aire de jeux pour enfant , est ouvert en permanance."

);

require_once __DIR__ . "/../../includes/footer.php";
?>