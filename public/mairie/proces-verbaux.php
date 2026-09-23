<?php
define('APP_RUNNING', true);
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/function.php';
require_once __DIR__ . '/../../includes/components/loader.php';

$pageClass = 'pv';
$pageTitle = "Procès-verbaux";
$pageDescription = "Consultez les procès-verbaux des conseils municipaux de Montjean (53320), en Mayenne.";
$pageCss = "/asset/css/pages/mairie/pv.css";

require_once __DIR__ . '/../../includes/header.php';

renderHero($pageClass, $pageTitle, $pageDescription, "/asset/images/mairie/document/document.png");

$pvs = getProcesVerbaux();

$cards = array_map(function ($pv) {
    return [
        'title' => $pv['titre'],
        'lines' => [
            'Séance du ' . date('d/m/Y', strtotime($pv['date_seance'])),
        ],
        'link' => '/uploads/documents/' . $pv['fichier'],
    ];
}, $pvs);

renderSection($pageClass, 'pv', 'Procès-verbaux', '', renderCards($pageClass, $cards));

require_once __DIR__ . '/../../includes/footer.php';
?>