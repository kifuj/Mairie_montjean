<?php
define('APP_RUNNING', true);
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/function.php';
require_once __DIR__ . '/../../includes/components/loader.php';

$pageClass       = 'utile';
$pageTitle       = "Infos utiles";
$pageDescription = "Numéros utiles, transports et santé à Montjean (53320), en Mayenne.";

require_once __DIR__ . '/../../includes/header.php';

renderHero($pageClass, "Infos utiles", "Contacts et services essentiels.", "/asset/images/vie-locale/utile/utile.png");

renderSection(
    $pageClass,
    "numeros",
    "Numéros utiles",
    "",
    renderCards($pageClass, [
        [
            'title' => 'Institutions',
            'lines' => [
                'Préfecture de la Mayenne : 02 43 01 50 00',
                'Conseil départemental : 02 43 66 53 53',
                'DDT : 02 43 67 89 20',
                'CC Pays de Loiron : 02 43 02 19 31',
                'Trésorerie de Laval : 02 43 49 34 43',
                'Allo service public : 3939',
            ],
        ],
        [
            'title' => 'Urgences et services',
            'lines' => [
                'Vétérinaire Loiron : 02 43 02 10 13',
                'Vétérinaire Cossé-le-Vivien : 02 43 98 80 80',
                'Destruction insectes nuisibles : 02 43 56 12 40',
                'ACM Taxis : 06 20 48 73 11',
            ],
        ],
    ])
);

// ── Assistants maternels depuis la DB ────────────────────────
$assistants = getAssistantsMaternels();

$rows = array_map(fn($a) => [
    $a['nom'],
    $a['prenom'],
    $a['adresse'] ?? '',
    $a['telephone'] ?? '',
    (string) $a['agrement'],
    $a['mam'] ?? '',
], $assistants);

renderSection(
    $pageClass,
    "assistants-maternels",
    "Assistants maternels agréés",
    "Liste des assistants maternels agréés à Montjean. Mise à jour depuis l'administration.",
    renderTable($pageClass,
        ['Nom', 'Prénom', 'Adresse', 'Téléphone', 'Agrément', 'MAM'],
        $rows
    )
);

renderSection(
    $pageClass,
    "transport",
    "ACM Taxi",
    "Située à Cossé-le-Vivien, la société ACM Taxi propose le ramassage scolaire, le transport de personnes toutes distances et est conventionnée par l'assurance maladie pour les rendez-vous médicaux.",
    renderCards($pageClass, [
        [
            'title' => 'Contact',
            'lines' => [
                '6 bis Rue du Point du Jour, 53230 Cossé-le-Vivien',
                'Tél : 02 43 98 43 37',
                'Mobile : 06 20 48 73 11',
                'mail.acmtaxis53@gmail.com',
            ],
        ],
    ])
);

renderSection(
    $pageClass,
    "sante",
    "Maison Médicale Loiron-Ruillé",
    "",
    renderCards($pageClass, [
        [
            'title' => 'Médecins généralistes',
            'lines' => [
                'Roux Joël : 02 43 68 93 28',
                'Ricardo de Matos Ferreira : 02 43 37 27 47',
            ],
        ],
        [
            'title' => 'Spécialistes',
            'lines' => [
                'Kiné Laëtitia Malinge : 02 43 37 27 38',
                'Kiné Philippe Robert : 02 43 37 27 38',
                'Orthophoniste Elisa Renou : 02 43 65 38 73',
                'Psychologue Eva Gandon-Cretois : 06 21 98 56 99',
                'Infirmière Karine Domas : 02 43 68 84 48',
                'Podologue : Astrid Odic',
            ],
        ],
        [
            'title' => 'Assistante sociale',
            'lines' => [
                'Antenne Solidarité Mme Poirier : 02 43 59 99 00',
                '18 Bd Louis Armand, 53940 St-Berthevin',
            ],
        ],
    ])
);

require_once __DIR__ . '/../../includes/footer.php';
?>