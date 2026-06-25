<?php
define('APP_RUNNING', true);
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/function.php';
require_once __DIR__ . '/../../includes/components/loader.php';

$pageClass       = 'salle-des-fetes';
$pageTitle       = "Salle des fêtes";
$pageDescription = "Réservation et tarifs des salles municipales de Montjean (53320), en Mayenne.";

require_once __DIR__ . '/../../includes/header.php';

renderHero($pageClass, "Salles municipales", "Location ouverte aux associations, particuliers et entreprises.");

renderSection(
    $pageClass,
    "reservation",
    "Réservation",
    "Les réservations se font directement à la mairie de Montjean.",
    renderActions($pageClass, [
        ['link' => '/uploads/demarche/salle-des-fetes/tarification_20salles.pdf', 'label' => 'Télécharger la grille tarifaire'],
        ['link' => '/contact.php', 'label' => 'Nous contacter'],
    ])
);

require_once __DIR__ . '/../../includes/footer.php';
?>