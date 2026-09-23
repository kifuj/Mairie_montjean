<?php
define('APP_RUNNING', true);

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/function.php';

$pageClass = 'patrimoine';
$pageTitle = "Patrimoine";
$pageDescription = "Découvrez le patrimoine de Montjean (53320), en Mayenne.";
$pageCss = "/asset/css/pages/la-commune/patrimoine.css";

include __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/components/loader.php';



$items = [
    [
        'id'    => 'lanfriere',
        'image' => '/assets/images/la-commune/chateau-lanfriere.jpg',
        'alt'   => 'Le château de la Lanfrière à Montjean, manoir du XIXe siècle',
        'title' => 'Le château de la Lanfrière',
        'text'  => "Le château de Lanfrière, situé à 800 mètres du bourg de Montjean en Mayenne, est un manoir du XIXe siècle érigé en 1840 par Morin de la Blottais. Ce dernier, en restaurant le domaine, y ajoute des éléments néogothiques comme des tourelles, des balustres et des sculptures en pierre, bien que le tuffeau friable utilisé menace leur durabilité. Le portail, datant de 1642, était autrefois flanqué d'une tour ronde, vestige d'une structure plus ancienne.",
        'actions' => [
            ['link' => 'https://www.destination-mayenne.com/offres/chateau-de-la-lanfriere-montjean-fr-555477/#sheetPart-opening', 'label' => 'Visiter'],
            ['link' => 'https://museedupatrimoine.fr/chateau-de-lanfriere-mayenne/89465.html', 'label' => 'Son histoire'],
        ],
    ],
    [
        'id'    => 'eglise',
        'image' => '/asset/images/la-commune/patrimoine/eglise.png',
        'alt'   => 'Eglise Saint-Martin à Montjean, église du XIXe siècle',
        'title' => "L'église Saint-Martin",
        'text'  => "L'église Saint-Martin de Montjean est un monument religieux implanté dans la commune de Montjean, en région Pays de la Loire.",
        'actions' => [
            ['link' => 'https://museedupatrimoine.fr/eglise-saint-martin-de-montjean-mayenne/76478.html', 'label' => 'Son Histoire'],
        ],
    ],
    [
        'id'    => 'chateau-montjean',
        'image' => '/assets/images/la-commune/chateau-montjean.jpg',
        'alt'   => 'Le château de Montjean à Montjean, manoir du XVIe siècle',
        'title' => 'Château de Montjean',
        'text'  => "Le château de Montjean, situé près d'un étang à 2,5 km à l'Est du bourg de Montjean (Mayenne), était au XVIe siècle une place forte défendant le comté de Laval contre les ligueurs. Reconstruit par André de Lohéac après 1450, il fut un enjeu stratégique pendant les guerres de Cent Ans et les conflits religieux.",
        'actions' => [
            ['link' => 'https://museedupatrimoine.fr/chateau-de-montjean-mayenne/89496.html', 'label' => 'Son histoire'],
        ],
    ],
];


renderHero($pageClass, $pageTitle, $pageDescription, "/asset/images/la-commune/patrimoine/patrimoine.png"); 



foreach ($items as $item): 

    if ($item['id'] === 'eglise') {
    echo renderImage(
        $pageClass,
        $item['image'],
        $item['alt'],
        'eglise-photo'
    );
}
    renderSection(
        $pageClass,
        $item['id'],
        $item['title'],
        $item['text'],
        renderActions($pageClass, $item['actions'])
    );

endforeach; 


require_once __DIR__ . "/../../includes/footer.php";
?>