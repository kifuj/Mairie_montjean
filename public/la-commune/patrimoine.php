<?php
define('APP_RUNNING', true);
$pageTitle = "Patrimoine";
$pageDescription = "Découvrez le patrimoine de Montjean (53320), en Mayenne.";
include __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/components/loader.php';

$pageClass = 'patrimoine';

$items = [
    [
        'id'    => 'lanfriere',
        'image' => '/assets/images/la-commune/chateau-lanfriere.jpg',
        'alt'   => 'Le château de la Lanfrière à Montjean, manoir du XIXe siècle',
        'title' => 'Le chateau de la Lanfrière',
        'text'  => "Le château de Lanfrière, situé à 800 mètres du bourg de Montjean en Mayenne, est un manoir du XIXe siècle érigé en 1840 par Morin de la Blottais. Ce dernier, en restaurant le domaine, y ajoute des éléments néogothiques comme des tourelles, des balustres et des sculptures en pierre, bien que le tuf friable utilisé menace leur durabilité. Le portail, datant de 1642, était autrefois flanqué d'une tour ronde, vestige d'une structure plus ancienne.",
        'actions' => [
            ['link' => 'https://www.destination-mayenne.com/offres/chateau-de-la-lanfriere-montjean-fr-555477/#sheetPart-opening', 'label' => 'Visiter'],
            ['link' => 'https://museedupatrimoine.fr/chateau-de-lanfriere-mayenne/89465.html', 'label' => 'Son histoire'],
        ],
    ],
    [
        'id'    => 'eglise',
        'image' => '/assets/images/la-commune/eglise.jpg',
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
        'title' => 'Chateau de Montjean',
        'text'  => "Le château de Montjean, situé près d'un étang à 2,5 km à l'est du bourg de Montjean (Mayenne), était au XVIe siècle une place forte défendant le comté de Laval contre les ligueurs. Reconstruit par André de Lohéac après 1450, il fut un enjeu stratégique pendant les guerres de Cent Ans et les conflits religieux.",
        'actions' => [
            ['link' => 'https://museedupatrimoine.fr/chateau-de-montjean-mayenne/89496.html', 'label' => 'Son histoire'],
        ],
    ],
];
?>

<?php renderHero($pageClass, 'Patrimoine de Montjean en Mayenne', 'Châteaux et église de Montjean en Mayenne.'); ?>

<?php foreach ($items as $i => $item): ?>
    <?php
    $reverse = $i % 2 === 1 ? ' patrimoine__layout--reverse' : '';

    $content = '<div class="patrimoine__layout' . $reverse . '">'
        . renderImage($pageClass, $item['image'], $item['alt'])
        . '<div class="patrimoine__text-block">'
        . '<p class="patrimoine-texte">' . htmlspecialchars($item['text']) . '</p>'
        . renderActions($pageClass, $item['actions'])
        . '</div>'
        . '</div>';

    renderSection($pageClass, $item['id'], $item['title'], '', $content);
    ?>
<?php endforeach; ?>

<?php
require_once __DIR__ . "/../../includes/footer.php";
?>