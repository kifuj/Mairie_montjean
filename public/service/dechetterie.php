    <?php
define('APP_RUNNING', true);
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/function.php';
require_once __DIR__ . '/../../includes/components/loader.php';

$pageClass       = 'dechetteries';
$pageTitle       = "Déchetteries";
$pageDescription = "Horaires et informations sur les déchetteries proches de Montjean (53320), en Mayenne.";

require_once __DIR__ . '/../../includes/header.php';

renderHero($pageClass, "Déchetteries", "Triez vos déchets, préservez l'environnement.");

renderSection(
    $pageClass,
    "montjean",
    "Déchetterie de Montjean",
    "Route des Hubinières, 53320 Montjean. Fermée le dimanche et les jours fériés.",
    renderTable($pageClass,
        ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
        [
            ['FERMÉ', 'FERMÉ', '8h30 - 15h', 'FERMÉ', 'FERMÉ', '8h30 - 15h'],
        ]
    )
);

renderSection(
    $pageClass,
    "reseau",
    "Autres déchetteries du réseau",
    "En tant qu'habitant de Montjean, vous pouvez également utiliser les déchetteries du réseau Laval Agglomération.",
    renderActions($pageClass, [
        ['link' => 'https://www.laval.fr/utile-au-quotidien/dechets/la-collecte-des-dechets/les-dechetteries', 'label' => 'Voir toutes les déchetteries'],
    ])
);

renderSection(
    $pageClass,
    "interdits",
    "Déchets interdits en déchetterie",
    "",
    renderCards($pageClass, [
        [
            'title' => 'À rapporter à votre fournisseur ou professionnel',
            'lines' => [
                'Médicaments → votre pharmacie',
                'Bouteilles de gaz → repreneur',
                'Extincteurs → repreneur',
                'Cadavres d\'animaux → vétérinaire',
            ],
        ],
        [
            'title' => 'Déchets non acceptés',
            'lines' => [
                'Déchets d\'amiante',
                'DASRI (Déchets d\'Activités de Soins à Risque Infectieux)',
                'Souches et troncs d\'arbres',
            ],
        ],
    ])
);

renderSection(
    $pageClass,
    "collecte",
    "Collecte des déchets",
    "Consultez le calendrier de collecte des déchets 2026 de Laval Agglomération.",
    renderActions($pageClass, [
        ['link' => 'https://www.laval.fr/utile-au-quotidien/dechets/la-collecte-des-dechets', 'label' => 'Calendrier de collecte'],
    ])
);

require_once __DIR__ . '/../../includes/footer.php';
?>