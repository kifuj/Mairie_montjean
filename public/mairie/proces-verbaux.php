<?php
define('APP_RUNNING', true);

$pageClass = 'pv';
$pageTitle = "Procès-verbaux";
$pageDescription = "Consultez les procès-verbaux des conseils municipaux de Montjean (53320), en Mayenne.";
$pageCss = "/assets/css/pages/mairie/pv.css";

include __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/components/loader.php';



renderHero($pageClass, $pageTitle, $pageDescription);

$pvs = getProcesVerbaux();

$cards = array_map(function ($pv) {
    return [
        'title' => $pv['titre'],
        'lines' => [
            'Séance du ' . date('d/m/Y', strtotime($pv['date_seance'])),
        ],
        'link' => '/documents/pv/' . $pv['fichier'],
    ];
}, $pvs);

renderSection($pageClass, 'pv', 'Procès-verbaux', '', renderCards($pageClass, $cards));

require_once __DIR__ . "/../../includes/footer.php";
?>