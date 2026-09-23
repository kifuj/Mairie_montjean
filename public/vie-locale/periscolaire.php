<?php
define('APP_RUNNING', true);
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/function.php';
require_once __DIR__ . '/../../includes/components/loader.php';

$pageClass = 'periscolaire';
$pageTitle = "Accueil périscolaire";
$pageDescription = "Retrouvez les différentes activités périscolaires de Montjean.";
$pageCss = "/asset/css/pages/vie-locale/periscolaire.css";

require_once __DIR__ . '/../../includes/header.php';

renderHero($pageClass, $pageTitle, $pageDescription, "/asset/images/vie-locale/periscolaire/periscolaire.png");

// ── Horaires groupés + personnel depuis la DB ────────────────
$cardsHoraires = getHorairesPeriscolaireGroupes();

$personnel = getPersonnelPeriscolaire();
$lignesPersonnel = array_map(
    fn($p) => $p['role'] . ' : ' . $p['prenom'] . ' ' . $p['nom'],
    $personnel
);
$cardsHoraires[] = ['title' => 'Équipe', 'lines' => $lignesPersonnel];

renderSection(
    $pageClass,
    "horaires",
    "Horaires",
    "Les horaires des services périscolaires.",
    renderCards($pageClass, $cardsHoraires)
);

// ── Tarifs depuis la DB ──────────────────────────────────────
$tarifs = getTarifsPeriscolaire();
$rows = [];
$groupeCourant = null;
$uniteCourante = null;

foreach ($tarifs as $tarif) {
    if ($tarif['groupe'] !== $groupeCourant) {
        $unite = !empty($tarif['unite']) ? ' — ' . $tarif['unite'] : '';
        $rows[] = ['group' => $tarif['groupe'] . $unite];
        $groupeCourant = $tarif['groupe'];
        $uniteCourante = $tarif['unite'] ?? '';
    }
    $rows[] = [
        $tarif['label'],
        number_format((float) $tarif['tarif_commune'], 2, ',', '') . ' €',
        number_format((float) $tarif['tarif_hors_commune'], 2, ',', '') . ' €',
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
    "Les menus sont disponibles via le bouton ci-dessous.",
    renderActions($pageClass, [
        ['link' => 'https://www.cosse-le-vivien.fr/mairie-cosse/tellement-pratique/vie-scolaire/restaurant-scolaire', 'label' => 'Voir les menus'],
    ])
);

renderSection(
    $pageClass,
    "liens-utiles",
    "Liens utiles",
    "",
    renderActions($pageClass, [
        ['link' => 'http://www.msa-mayenne-orne-sarthe.fr/', 'label' => 'MSA 53'],
    ])
);

renderSection(
    $pageClass,
    "demarches",
    "Démarches et Règlement intérieur",
    "Les parents souhaitant bénéficier du service de centre de loisirs doivent impérativement inscrire leur enfant. Le dossier est disponible au centre de loisirs ou à la Mairie.",
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
    renderCards($pageClass, [[
        'title' => '',
        'lines' => [
            'Tél : 02 43 58 61 28',
            'Tél : 06 14 33 05 15',
            'service.enfance.jeunesse@mairie-montjean53.fr',
        ],
    ]])
);

require_once __DIR__ . '/../../includes/footer.php';
?>