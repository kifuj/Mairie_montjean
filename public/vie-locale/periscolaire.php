<?php
define('APP_RUNNING', true);

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/function.php';
require_once __DIR__ . '/../../includes/components/loader.php';

$pageClass = 'periscolaire';
$pageTitle = "Acceuil periscolaire";
$pageDescription = "Retrouvez lles differente activiter persicolaire de Montjean ";
$pageCss = "/asset/css/pages/vie-locale/periscolaire.css";

require_once __DIR__ . '/../../includes/header.php';


renderHero(
    $pageClass,
    $pageTitle,
    $pageDescription

);

$cards = [
    [
        'title' => 'La garderie — période scolaire',
        'lines' => [
            'Matin École Chemin de Cocaigne : 07h00 - 09h00',
            'Soir : 16h30 - 19h00',
        ],
    ],
    [
        'title' => 'Centre de loisirs — vacances scolaires',
        'lines' => [
            'Garderie matin : 07h00 - 09h00',
            'Animation matin : 09h00 - 12h00',
            'Cantine : 12h00 - 13h30',
            'Animation après-midi : 13h30 - 17h00',
            'Garderie soir : 17h00 - 19h00',
        ],
    ],
    [
        'title' => 'Membre du personnel',
        'lines' => [
            'Responsable : Florian GAUTIER',
            'Animatrices : Linda Tourneux',
            'Animatrices : Patricia Bouchez',
            'Animatrices : Sarah Durand'
        ]
    ]
];

renderSection(
    $pageClass,
    "horaire",
    "Horaire",
    "les horaires des services periscolaires",
    renderCards(
        $pageClass,
        $cards
    )
);

// ----------------------------------------
// Tarifs (depuis la DB)
// ----------------------------------------
$tarifs = getTarifsPeriscolaire();
$rows = [];
$groupeCourant = null;

foreach ($tarifs as $tarif) {
    if ($tarif['groupe'] !== $groupeCourant) {
        $rows[] = ['group' => $tarif['groupe']];
        $groupeCourant = $tarif['groupe'];
    }
    $rows[] = [
        $tarif['label'],
        number_format($tarif['tarif_commune'], 2, ',', '') . ' €',
        number_format($tarif['tarif_hors_commune'], 2, ',', '') . ' €',
    ];
}

renderSection(
    $pageClass,
    "tarifs",
    "Tarifs",
    "",
    renderTable($pageClass, ['', 'Commune', 'Hors commune'], $rows)
);


renderSection(
    $pageClass,
    "cantine",
    "Menu de la cantine",
    "les menus sont disponible via le bouton en savoir plus",
    renderActions(
        $pageClass,
        [['link' => 'https://www.radislatoque.fr/les-menus-de-la-cantine/liste-des-restaurants/entry-801-alsh-de-montjean.html', 'label' => 'En savoir plus']]
    )
);

// ----------------------------------------
// Liens utiles
// ----------------------------------------
renderSection(
    $pageClass,
    "liens-utiles",
    "Liens utiles",
    "",
    renderActions($pageClass, [
        ['link' => 'http://www.msa-mayenne-orne-sarthe.fr/', 'label' => 'MSA 53'],
    ])
);

// ----------------------------------------
// Démarches et règlement intérieur
// ----------------------------------------
renderSection(
    $pageClass,
    "demarches",
    "Démarches et règlement intérieur",
    "Les parents souhaitant bénéficier du service de centre de loisirs doivent impérativement inscrire leur enfant individuellement. Il est possible de récupérer le dossier en version papier au centre de loisirs ou à la Mairie.",
    renderActions($pageClass, [
        ['link' => '/uploads/vie-locale/periscolaire/dossier-inscription-periscolaire(2022-2023).pdf', 'label' => 'Dossier d\'inscription'],
        ['link' => '/uploads/vie-locale/periscolaire/Fiche-sanitaire.pdf', 'label' => 'Fiche sanitaire'],
        ['link' => '/uploads/vie-locale/periscolaire/Reglement-interieur(2022-2023).pdf', 'label' => 'Règlement intérieur'],
    ])
);

renderSection(
    $pageClass,
    "contact",
    "Contact",
    "",
    renderCards(
        $pageClass,
        [[
            "title" => "",
            "lines" =>[
                "Tél : 02 43 58 61 28",
                "Tél : 06 14 33 05 15",
                "mail : service.enfance.jeunesse@mairie-montjean53.fr"]
        ]]
    )
);

require_once __DIR__ . '/../../includes/footer.php';