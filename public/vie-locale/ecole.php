<?php
define('APP_RUNNING', true);

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/function.php';
require_once __DIR__ . '/../../includes/components/loader.php';

$pageClass = 'ecole';
$pageTitle = "Ecole";
$pageDescription = "Retrouvez les ecoles de Montjean ";
$pageCss = "/asset/css/pages/vie-locale/ecole.css";

require_once __DIR__ . '/../../includes/header.php';

renderHero(
    $pageClass,
    $pageTitle,
    $pageDescription
);

renderSection(
    $pageClass,
    "chemin-de-cocaigne",
    "ecole publique \"Chemin de Cocaigne\"",
    "l'ecole du chemin de cocaigne est l'ecole publique de montjean eduquant les jeunes de la TPS au CM2",
    renderActions(
        $pageClass,
        [['link' => 'https://ecoleprimairechemindecocaigne-montjean.e-primo.fr', 'label' => 'En savoir plus']]
    )
);

require_once __DIR__ . '/../../includes/footer.php';