<?php
define('APP_RUNNING', true);
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/function.php';
require_once __DIR__ . '/../../includes/components/loader.php';

$pageClass       = 'argent-de-poche';
$pageTitle       = "Argent de poche";
$pageDescription = "Dispositif argent de poche pour les jeunes de 16 à 18 ans de Montjean (53320), en Mayenne.";

require_once __DIR__ . '/../../includes/header.php';

renderHero($pageClass, "Argent de poche", "Un job citoyen pour les 16-18 ans.");

renderSection(
    $pageClass,
    "presentation",
    "Le dispositif",
    "Mis en place par Laval Agglomération, le dispositif Argent de poche permet aux jeunes de 16 à 18 ans résidant sur le territoire de réaliser de petits chantiers de proximité pendant les vacances scolaires, en contrepartie d'une rétribution de 15 € par demi-journée. Les chantiers concernent l'entretien d'espaces verts, de petits travaux de peinture, de rénovation ou d'animation d'événements locaux."
);

renderSection(
    $pageClass,
    "conditions",
    "Conditions",
    "",
    renderCards($pageClass, [
        [
            'title' => 'Qui peut participer ?',
            'lines' => [
                'Avoir entre 16 et 18 ans (à la veille de ses 18 ans)',
                'Résider sur le territoire de Laval Agglomération',
            ],
        ],
        [
            'title' => 'Pièces à fournir',
            'lines' => [
                'Contrat de participation signé par le jeune et le tuteur légal',
                'Pièce d\'identité recto/verso',
                'Attestation d\'assuré social ou carte vitale',
                'Attestation de responsabilité civile en cours',
            ],
        ],
    ])
);

renderSection(
    $pageClass,
    "inscription",
    "S'inscrire",
    "Les dossiers sont à déposer en ligne sur la plateforme Argent de poche de Laval Agglomération. La validation de votre dossier se fait par le service gestionnaire — elle n'équivaut pas à une inscription sur un chantier.",
    renderActions($pageClass, [
        ['link' => 'https://argentdepoche.agglo-laval.fr', 'label' => 'Accéder à la plateforme'],
        ['link' => '/uploads/demarche/argent-de-poche/1_CONTRAT_AGP_AGGLO_PLATEFORME.pdf', 'label' => 'Télécharger le contrat'],
        ['link' => '/uploads/demarche/argent-de-poche/Guide_d_utilisation_jeune_Plat.pdf', 'label' => 'guide d\'inscription']
    ])
);

renderSection(
    $pageClass,
    "contact",
    "Contact",
    "",
    renderCards($pageClass, [
        [
            'title' => 'Laval Agglomération — Contrat de ville',
            'lines' => [
                'Place du Général Ferrié, 53000 Laval',
                'Tél : 02 43 49 86 60',
                'Coordinatrice : Fleur GUESNÉ',
            ],
        ],
    ])
);

require_once __DIR__ . '/../../includes/footer.php';
?>