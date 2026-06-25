<?php
define('APP_RUNNING', true);
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/function.php';
require_once __DIR__ . '/../../includes/components/loader.php';

$pageClass       = 'etat-civil';
$pageTitle       = "État civil";
$pageDescription = "Démarches d'état civil à la mairie de Montjean (53320), en Mayenne.";

require_once __DIR__ . '/../../includes/header.php';

renderHero($pageClass, "État civil", "Naissances, mariages, décès et autres démarches.");

renderSection(
    $pageClass,
    "presentation",
    "Le service état civil",
    "La mairie de Montjean assure l'ensemble des démarches d'état civil pour les habitants de la commune. Pour toute démarche, munissez-vous des pièces justificatives nécessaires et présentez-vous directement en mairie."
);

renderSection(
    $pageClass,
    "demarches",
    "Démarches",
    "",
    renderCards($pageClass, [
        [
            'title' => 'Naissance',
            'lines' => [
                'Déclaration à effectuer dans les 5 jours suivant la naissance.',
                'Se présenter en mairie avec le certificat d\'accouchement.',
            ],
        ],
        [
            'title' => 'Mariage',
            'lines' => [
                'Dépôt du dossier en mairie au moins un mois avant la date.',
                'Les deux futurs époux doivent résider dans la commune.',
            ],
        ],
        [
            'title' => 'PACS',
            'lines' => [
                'Enregistrement du PACS en mairie depuis novembre 2017.',
                'Dépôt du dossier complet avant le rendez-vous.',
            ],
        ],
        [
            'title' => 'Décès',
            'lines' => [
                'Déclaration à effectuer dans les 24 heures suivant le décès.',
                'Se présenter en mairie avec le certificat de décès.',
            ],
        ],
        [
            'title' => 'Reconnaissance',
            'lines' => [
                'Reconnaissance anticipée possible avant la naissance.',
                'Se présenter en mairie avec une pièce d\'identité.',
            ],
        ],
        [
            'title' => 'Copies et extraits d\'actes',
            'lines' => [
                'Actes de naissance, mariage ou décès disponibles en mairie.',
                'Demande possible en ligne sur service-public.fr.',
            ],
        ],
    ])
);

renderSection(
    $pageClass,
    "contact",
    "Nous contacter",
    "Pour toute démarche d'état civil, contactez la mairie de Montjean.",
    renderActions($pageClass, [
        ['link' => '/contact.php', 'label' => 'Contacter la mairie'],
        ['link' => 'https://www.service-public.fr', 'label' => 'Service-public.fr'],
    ])
);

require_once __DIR__ . '/../../includes/footer.php';
?>