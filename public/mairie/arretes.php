<?php
define('APP_RUNNING', true);

$pageClass = 'arretes';
$pageTitle = "Arrêtés";
$pageDescription = "Consultez les arrêtés municipaux, préfectoraux et départementaux de Montjean (53320), en Mayenne.";
$pageCss = "/assets/css/pages/mairie/arretes.css";

include __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/components/loader.php';



renderHero($pageClass, $pageTitle, $pageDescription);

$categories = [
    'municipal'      => 'Arrêtés municipaux',
    'prefectoral'    => 'Arrêtés préfectoraux',
    'departemental'  => 'Arrêtés départementaux',
];

foreach ($categories as $slug => $label) {
    $arretes = getArretesParCategorie( $slug);

    $cards = array_map(function ($arrete) {
        return [
            'title' => $arrete['titre'],
            'lines' => [
                'Du ' . date('d/m/Y', strtotime($arrete['date_arrete'])),
            ],
            'link' => '/documents/arretes/' . $arrete['fichier'],
        ];
    }, $arretes);

    renderSection($pageClass, $slug, $label, '', renderCards($pageClass, $cards));
}

require_once __DIR__ . "/../../includes/footer.php";
?>