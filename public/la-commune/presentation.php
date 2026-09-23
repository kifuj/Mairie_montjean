<?php
define('APP_RUNNING', true);

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/function.php';

$pageClass = 'presentation';
$pageTitle = "Présentation de la commune";
$pageDescription = "Découvrez l'histoire, la situation géographique et le patrimoine naturel de Montjean (53320), en Mayenne.";
$pageCss = "/assets/css/pages/la-commune/presentation.css";

include __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/components/loader.php';

renderHero($pageClass, $pageTitle, $pageDescription, "/asset/images/la-commune/presentation/presentation.png" ); 


renderSection(
    $pageClass,
    'origine',
    'Origine et histoire',
    'Le nom de Montjean provient du latin Mons Joannis ("le Mont de Jean"). Le bourg est mentionné dès le Moyen-Âge, notamment sur la liste des croisés de Mayenne partis en 1158.',
);


renderSection(
    $pageClass,
    'situation',
    'Situation géographique',
    'Montjean se situe à 16 kilomètres de Laval, dans le canton de Loiron, en bordure de l\'Oudon. La commune s\'étend sur une superficie de 20 km² et son altitude moyenne est de 90 mètres.',
    // TODO Possibilité d'intégrer une carte ici
);



renderSection(
    $pageClass,
    'cours-eau',
    'Relief et cours d\'eau',
    'Le territoire communal, traversé par l\'Oudon, présente un relief peu marqué. L\'étang de Montjean, d\'une superficie de 25 hectares, est dominé par les ruines d\'un ancien château fort et constitue un site naturel remarquable.',
);

require_once __DIR__ . "/../../includes/footer.php";
?>