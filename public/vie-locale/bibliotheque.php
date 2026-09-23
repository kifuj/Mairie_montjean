<?php
define('APP_RUNNING', true);
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/function.php';
require_once __DIR__ . '/../../includes/components/loader.php';

$pageClass       = 'bibliotheque';
$pageTitle       = "Bibliothèque";
$pageDescription = "Découvrez la bibliothèque municipale de Montjean, accès libre et gratuit.";
$pageCss         = "/asset/css/pages/vie-locale/bibliotheque.css";

require_once __DIR__ . '/../../includes/header.php';

renderHero($pageClass, "Bibliothèque municipale", "Accès libre et gratuit.", "/asset/images/vie-locale/bibliotheque/bibliotheque.png");

renderSection(
    $pageClass,
    "presentation",
    "La Bibliothèque de Montjean",
    "Au cœur de Montjean, la bibliothèque municipale propose une offre variée de collections et d'animations pour tous les publics. Un lieu convivial, accessible et en constante évolution.",
    renderCards($pageClass, [
        [
            'title' => 'Collections',
            'lines' => [
                'Livres pour petits et grands',
                'Revues et magazines',
                'CD et vinyles',
                'DVD et jeux vidéo',
                'platines vinyles et liseuses'
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

// ── Horaires depuis la DB ────────────────────────────────────
$hors_vacances = getHorairesService('bibliotheque', 'hors_vacances');
$vacances      = getHorairesService('bibliotheque', 'vacances');

$rows = [];
foreach ($hors_vacances as $i => $h) {
    $debut_hv = $h['ferme'] ? 'Fermé' : substr($h['heure_debut'], 0, 5) . ' – ' . substr($h['heure_fin'], 0, 5);
    $debut_v  = isset($vacances[$i])
        ? ($vacances[$i]['ferme'] ? 'Fermé' : substr($vacances[$i]['heure_debut'], 0, 5) . ' – ' . substr($vacances[$i]['heure_fin'], 0, 5))
        : '–';
    $rows[] = [$h['label'], $debut_hv, $debut_v];
}

renderSection(
    $pageClass,
    "horaires",
    "Horaires",
    "",
    renderTable($pageClass, ['Jour', 'Hors vacances', 'Vacances scolaires'], $rows)
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