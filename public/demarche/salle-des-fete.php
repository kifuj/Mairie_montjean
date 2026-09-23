<?php
define('APP_RUNNING', true);
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/function.php';
require_once __DIR__ . '/../../includes/components/loader.php';

$pageClass       = 'salle-des-fetes';
$pageTitle       = "Salle des fêtes";
$pageDescription = "Réservation et tarifs des salles municipales de Montjean (53320), en Mayenne.";

require_once __DIR__ . '/../../includes/header.php';

renderHero($pageClass, "Salles municipales", "Location ouverte aux associations, particuliers et entreprises.","/asset/images/demarche/salle-des-fete/salle-des-fete.png");

renderSection(
    $pageClass,
    "reservation",
    "Réservation",
    "Les réservations se font directement à la mairie de Montjean.",
    renderActions($pageClass, getLiensPage('salle-des-fetes'))
);

require_once __DIR__ . '/../../includes/footer.php';
?>