<?php
define('APP_RUNNING', true);

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/function.php';
require_once __DIR__ . '/../../includes/components/loader.php';

$pageClass = 'associations';
$pageTitle = "Associations";
$pageDescription = "Retrouvez les associations de Montjean : coordonnées, activités et réseaux sociaux.";
$pageCss = "/assets/css/pages/vie-locale/associations.css";

require_once __DIR__ . '/../../includes/header.php';

$associations = getAssociationsMontjean();

renderHero(
    $pageClass,
    'Associations',
    'Découvrez les associations présentes sur la commune de Montjean.'
);

if (empty($associations)) {
    renderSection(
        $pageClass,
        'liste-associations',
        'Liste des associations',
        '',
        '<p>Aucune association trouvée.</p>'
    );
} else {
    $cards = [];

    foreach ($associations as $asso) {
        $lines = [];

        if (!empty($asso['objet'])) {
            $lines[] = $asso['objet'];
        }

        if (!empty($asso['adresse'])) {
            $lines[] = $asso['adresse'];
        }

        if (!empty($asso['telephone'])) {
            $lines[] = 'Tél : ' . $asso['telephone'];
        }

        if (!empty($asso['email'])) {
            $lines[] = 'Email : ' . $asso['email'];
        }

        if (!empty($asso['site'])) {
            $lines[] = 'Site : ' . $asso['site'];
        }

        $cards[] = [
            'title' => $asso['nom'],
            'lines' => $lines
        ];
    }

    renderSection(
        $pageClass,
        'liste-associations',
        'Liste des associations',
        '',
        renderCards($pageClass, $cards)
    );
}

require_once __DIR__ . '/../../includes/footer.php';
?>