<?php
define('APP_RUNNING', true);
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/function.php';
require_once __DIR__ . '/../../includes/components/loader.php';

$pageClass       = 'bibliotheque';
$pageTitle       = "Bibliothèque";
$pageDescription = "Découvrez la bibliothèque municipale de Montjean, accès libre et gratuit.";
$pageCss         = "/asset/css/vie-locale/bibliotheque.css";

require_once __DIR__ . '/../../includes/header.php';

renderHero($pageClass, "Bibliothèque municipale", "Accès libre et gratuit.");

renderSection(
    $pageClass,
    "presentation",
    "La bibliothèque de Montjean",
    "Au cœur de Montjean, la bibliothèque municipale propose une offre variée de collections et d'animations pour tous les publics. Un lieu convivial, accessible et en constante évolution.",
    renderCards($pageClass, [
        [
            'title' => 'Collections',
            'lines' => [
                'Livres pour petits et grands',
                'Revues et magazines',
                'CD et vinyles',
                'DVD et jeux vidéo',
            ],
        ],
        [
            'title' => 'Animations',
            'lines' => [
                'Ateliers jeux pour enfants et familles',
                'Expositions temporaires',
                'Rencontres et animations culturelles',
            ],
        ],
    ])
);

renderSection(
    $pageClass,
    "horaires",
    "Horaires",
    "",
    renderTable($pageClass,
        ['Jour', 'Hors vacances', 'Vacances scolaires'],
        [
            ['Lundi',    '14h - 17h',    '14h - 17h'],
            ['Mercredi', '15h30 - 17h30',    '16h30 - 17h30'],
            ['Samedi',   '10h30 - 12h30','11h - 12h'],
        ]
    )
);

renderSection(
    $pageClass,
    "reseau",
    "Réseau La Bib",
    "La bibliothèque fait partie d'un réseau de 29 bibliothèques gratuites en libre accès. Une seule carte d'adhésion pour emprunter partout et accéder au catalogue en ligne.",
    renderActions($pageClass, [
        ['link' => 'https://labib.agglo-laval.fr', 'label' => 'Catalogue en ligne'],
    ])
);

require_once __DIR__ . '/../../includes/footer.php';
?>