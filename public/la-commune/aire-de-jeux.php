<?php
define('APP_RUNNING', true);

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/function.php';

$pageClass = 'aire';
$pageTitle = "Aire de jeux";
$pageDescription = "Découvrez les differentes aires de jeux de Montjean (53320), en Mayenne.";
$pageCss = "/assets/css/pages/la-commune/aire.css";

include __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/components/loader.php';



renderHero(
    $pageClass,
    $pageTitle,
    $pageDescription,
    "/asset/images/la-commune/aire-de-jeux/aire.png"
);

renderSection(
    $pageClass,
    "aire-de-jeux",
    "Aire de jeux derrière la Mairie",
    "L'aire de jeux, est située derrière la Mairie. C'est une aire de jeux pour les enfants entre x et x, qui est ouverte en permanence" // TODO aller voir l'age d'utilisation de l'air de jeux
    
);

renderSection(
    $pageClass,
    "city",
    "City Stade",
    "Le city stade, est situé en contrebas du terrain de football. Il est accessible en permanence à tous."
);

renderSection(
    $pageClass,
    "tennis",
    "Terrain de tennis",
    "Le terrain de tennis, est situé a côté du city stade. Il est accessible en permanence à tous."

);

renderSection(
    $pageClass,
    "foot",
    "Terrain de football",
    "Les terrains de football, ils sont situés au-dessus du city stade et sont accessibles en permanence à tous, sauf lors des entraînements de l'équipe locale."

);

renderSection(
    $pageClass,
    "petanque",
    "Terrain de pétanque",
    "Les terrains de pétanque, ils sont situés entre la mairie et l'aire de jeux pour enfants sont ouvert en permanance à tous."

);

require_once __DIR__ . "/../../includes/footer.php";
?>