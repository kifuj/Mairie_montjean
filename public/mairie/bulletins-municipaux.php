<?php
define('APP_RUNNING', true);
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/function.php';
require_once __DIR__ . '/../../includes/components/loader.php';

$pageClass       = 'bulletins';
$pageTitle       = "Bulletins municipaux";
$pageDescription = "Consultez les bulletins municipaux de Montjean (53320), en Mayenne.";

require_once __DIR__ . '/../../includes/header.php';

renderHero($pageClass, $pageTitle, "Restez informés de la vie de la commune.", "/asset/images/mairie/document/document.png");

$bulletins = getBulletinsMunicipaux();

if (empty($bulletins)) {
    renderSection(
        $pageClass,
        'liste',
        'Bulletins municipaux',
        'Aucun bulletin disponible pour le moment.'
    );
} else {
    $cards = array_map(function ($b) {
        return [
            'title' => $b['titre'],
            'lines' => $b['date_document']
                ? [date('d/m/Y', strtotime($b['date_document']))]
                : [],
            'link'  => '/uploads/documents/' . $b['fichier'],
        ];
    }, $bulletins);

    renderSection(
        $pageClass,
        'liste',
        'Bulletins municipaux',
        '',
        renderCards($pageClass, $cards)
    );
}

require_once __DIR__ . '/../../includes/footer.php';
?>