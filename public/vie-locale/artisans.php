<?php

define('APP_RUNNING', true);

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/function.php';
require_once __DIR__ . '/../../includes/components/loader.php';

$pageClass = 'artisans';
$pageTitle = "Artisans et entreprises";
$pageDescription = "Retrouvez les artisans, commerçants et entreprises de Montjean.";
$pageCss = "/assets/css/pages/vie-locale/artisans.css";

require_once __DIR__ . '/../../includes/header.php';

$entreprises = getEntreprisesMontjean();

renderHero(
    $pageClass,
    $pageTitle,
    $pageDescription,
    "/asset/images/vie-locale/artisant/artisant.png"
);

if (empty($entreprises)) {
    $entreprisesContent = '<p>Aucune entreprise trouvée.</p>';
} else {
    $cards = array_map(function ($entreprise) {
        $lines = [];

        if (!empty($entreprise['adresse'])) {
            $lines[] = $entreprise['adresse'];
        }
        if (!empty($entreprise['activite'])) {
            $lines[] = $entreprise['activite'];
        }

        return [
            'title' => $entreprise['nom'],
            'lines' => $lines,
        ];
    }, $entreprises);

    $entreprisesContent = renderCards($pageClass, $cards);
}

renderSection(
    $pageClass,
    'entreprises',
    'Annuaire des artisans',
    '',
    $entreprisesContent
);

require_once __DIR__ . '/../../includes/footer.php';
?>