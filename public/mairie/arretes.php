<?php
define('APP_RUNNING', true);
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/function.php';
require_once __DIR__ . '/../../includes/components/loader.php';

$pageClass = 'arretes';
$pageTitle = "Arrêtés";
$pageDescription = "Consultez les arrêtés municipaux, préfectoraux et départementaux de Montjean (53320), en Mayenne.";
$pageCss = "/asset/css/pages/mairie/arretes.css";

require_once __DIR__ . '/../../includes/header.php';

renderHero($pageClass, $pageTitle, $pageDescription, "/asset/images/mairie/document/document.png");

$categories = [
    'municipal'     => 'Arrêtés municipaux',
    'prefectoral'   => 'Arrêtés préfectoraux',
    'departemental' => 'Arrêtés départementaux',
];

foreach ($categories as $slug => $label) {
    $arretes = getArretesParCategorie($slug);

    $cards = array_map(function ($arrete) {
        return [
            'title' => $arrete['titre'],
            'lines' => [
                'Du ' . date('d/m/Y', strtotime($arrete['date_arrete'])),
            ],
            'link' => '/uploads/documents/' . $arrete['fichier'],
        ];
    }, $arretes);

    renderSection($pageClass, $slug, $label, '', renderCards($pageClass, $cards));
}

require_once __DIR__ . '/../../includes/footer.php';
?>