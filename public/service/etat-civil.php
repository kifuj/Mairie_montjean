<?php
define('APP_RUNNING', true);
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/function.php';
require_once __DIR__ . '/../../includes/components/loader.php';

$pageClass       = 'etat-civil';
$pageTitle       = "État civil";
$pageDescription = "Démarches d'état civil à la mairie de Montjean (53320), en Mayenne.";

require_once __DIR__ . '/../../includes/header.php';

renderHero($pageClass, "ÉTAT-CIVIL", "Naissances, mariages, décès et autres démarches.", "/asset/images/service/etat-civil/etat-civil.png");

renderSection(
    $pageClass,
    "presentation",
    "Le service État-civil",
    "La Mairie de Montjean assure l'ensemble des démarches d'état-civil pour les habitants de la commune. Pour toute formalité, munissez-vous des pièces justificatives nécessaires et contacter directement en mairie."
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
                'Contacter la Mairie au moins 3 mois avant la date prévue.',
                'Les deux futurs époux doivent résider ou avoir une adresse dans la commune.',
            ],
        ],
        [
            'title' => 'Décès',
            'lines' => [
                'Déclaration à effectuer dans les 24 heures suivant le décès.',
                'Se présenter en mairie avec le certificat médical de décès, le livret de famille, ...',
            ],
        ],
        [
            'title' => 'Reconnaissance',
            'lines' => [
                'Reconnaissance anticipée possible avant la naissance.',
                'Se présenter en mairie avec une pièce d\'identité et un justificatif de domicile.',
            ],
        ],
        [
            'title' => 'Copies et extraits d\'actes',
            'lines' => [
                'Naissance, Mariage ou décès a demander en mairie.',
            ],
        ],
        [
            'title' => 'Généalogie',
            'lines' => [
                'Contacter la Mairie avec actes(Naissance, mariage de plus de 75 ans et déces).',
            ],
        ],
        [
            'title' => 'Changement de Nom',
            'lines' => [
                'Demande à effectuer à la Mairie de résidence.',
            ],
        ],
    ])
);

renderSection(
    $pageClass,
    "autre",
    "Autre Démarche",
    "",
    renderCards(
        $pageClass,
        [
        [
            'title' => 'PACS',
            'lines' => [
                'Imprimé à compléter.',
                'Contacter la mairie pour l\'enregistrement du PACS.',
            ],
        ],
        [
            'title' => 'Parrainage civil',
            'lines' => [
                'la copie intégrale de l\'acte de naissance de l\'enfant.',
                'La photocopie de la carte d\'idantité des parents, du parrain et de la marraine.',
            ],
        ],
        ]
    )
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