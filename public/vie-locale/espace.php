<?php
define('APP_RUNNING', true);
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/function.php';
require_once __DIR__ . '/../../includes/components/loader.php';

$pageClass       = 'espace';
$pageTitle       = "Mon espace famille";
$pageDescription = "Réservez vos prestations périscolaires en ligne depuis le portail famille de Montjean.";
$pageCss         = "/asset/css/vie-locale/espace;css";

require_once __DIR__ . '/../../includes/header.php';

renderHero($pageClass, "Mon espace famille", "Cantine, centre de loisirs et plus.");

renderSection(
    $pageClass,
    "portail",
    "Portail famille",
    "Depuis le 1er septembre 2017, les familles de Montjean peuvent réserver les prestations qui leur conviennent (cantine, centre de loisirs...) sur le portail famille.",
    renderActions($pageClass, [
        ['link' => 'http://www.monespacefamille.fr/accueil/', 'label' => 'Portail famille'],
    ])
);

require_once __DIR__ . '/../../includes/footer.php';
?>