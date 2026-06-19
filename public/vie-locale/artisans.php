<?php

define('APP_RUNNING', true);

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/function.php';
require_once __DIR__ . '/../../includes/components/loader.php';

$pageTitle = "Artisans et entreprises";
$pageDescription = "Retrouvez les artisans, commerçants et entreprises de Montjean.";

require_once __DIR__ . '/../../includes/header.php';

$entreprises = getEntreprisesMontjean();
$pageClass = 'artisans';

renderHero(
    $pageClass,
    'Artisans & Entreprises',
    'Découvrez les professionnels présents sur la commune de Montjean.'
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