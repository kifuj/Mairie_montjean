<?php
define('APP_RUNNING', true);
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/function.php';
require_once __DIR__ . '/../../includes/components/loader.php';

$pageClass       = 'citoyen';
$pageTitle       = "Recensement citoyen";
$pageDescription = "Tout savoir sur le recensement citoyen obligatoire à 16 ans à Montjean (53320).";

require_once __DIR__ . '/../../includes/header.php';

renderHero($pageClass, "Recensement citoyen", "Obligatoire à 16 ans.");

renderSection(
    $pageClass,
    "presentation",
    "Qu'est-ce que le recensement citoyen ?",
    "Le recensement citoyen est une démarche obligatoire pour tout jeune Français, garçon ou fille, dans les trois mois suivant son 16e anniversaire. Il permet d'être convoqué à la Journée Défense et Citoyenneté (JDC) et d'accéder aux concours et examens d'État."
);

renderSection(
    $pageClass,
    "procedure",
    "Comment se recenser ?",
    "",
    renderCards($pageClass, [
        [
            'title' => '1. Se présenter à la mairie',
            'lines' => [
                'Munissez-vous de votre pièce d\'identité et du livret de famille.',
                'La mairie de Montjean vous délivre une attestation de recensement.',
            ],
        ],
        [
            'title' => '2. Ou en ligne',
            'lines' => [
                'Le recensement est également possible sur service-public.fr.',
            ],
        ],
        [
            'title' => '3. La JDC',
            'lines' => [
                'Après le recensement, vous serez convoqué à la Journée Défense et Citoyenneté.',
                'Cette journée est obligatoire et conditionne l\'accès aux examens d\'État (permis, bac...).',
            ],
        ],
    ])
);

renderSection(
    $pageClass,
    "jdc",
    "Journée Défense et Citoyenneté (JDC)",
    "Depuis le 1er avril 2026, les demandes relatives à la JDC se font uniquement via le portail de démarche numérique du Centre du Service National.",
    renderActions($pageClass, [
        ['link' => 'https://demarche.numerique.gouv.fr/commencer/centre-du-service-national-jeunesse', 'label' => 'Accéder à la démarche'],
    ])
);

renderSection(
    $pageClass,
    "contact",
    "Besoin d'aide ?",
    "La mairie de Montjean est à votre disposition pour vous accompagner dans votre démarche de recensement.",
    renderActions($pageClass, [
        ['link' => '/contact.php', 'label' => 'Nous contacter'],
        ['link' => 'https://www.service-public.fr/particuliers/vosdroits/F870', 'label' => 'Service-public.fr'],
    ])
);

require_once __DIR__ . '/../../includes/footer.php';
?>