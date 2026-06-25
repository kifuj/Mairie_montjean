<?php
define('APP_RUNNING', true);
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/function.php';
require_once __DIR__ . '/../../includes/components/loader.php';

$pageClass       = 'urbanisme';
$pageTitle       = "Urbanisme et PLUi";
$pageDescription = "Démarches d'urbanisme et Plan Local d'Urbanisme intercommunal de Montjean (53320).";

require_once __DIR__ . '/../../includes/header.php';

renderHero($pageClass, "Urbanisme et PLUi", "Permis, déclarations et règles d'urbanisme.");

renderSection(
    $pageClass,
    "autorisation",
    "Autorisation d'urbanisme",
    "Depuis le 1er janvier 2022, vous pouvez déposer vos demandes de permis de construire ou de déclaration préalable de travaux en ligne, à tout moment et sans frais.",
    renderActions($pageClass, [
        ['link' => 'https://www.service-public.fr/particuliers/vosdroits/R52221', 'label' => 'Déposer une demande'],
        ['link' => 'https://www.geoportail-urbanisme.gouv.fr/', 'label' => 'Géoportail urbanisme'],
        ['link' => 'https://www.service-public.fr/particuliers/vosdroits/N319', 'label' => 'En savoir plus'],
    ])
);

renderSection(
    $pageClass,
    "plui",
    "Plan Local d'Urbanisme intercommunal (PLUi)",
    "Le PLUi définit les règles d'urbanisme applicables sur le territoire de la Communauté de Communes du Pays de Loiron. Il encadre les projets de construction, rénovation et aménagement.",
    renderActions($pageClass, [
        ['link' => 'https://www.ccpaysdeloiron.fr', 'label' => 'CC Pays de Loiron'],
    ])
);

require_once __DIR__ . '/../../includes/footer.php';
?>