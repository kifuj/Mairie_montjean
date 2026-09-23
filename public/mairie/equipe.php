<?php
define('APP_RUNNING', true);

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/function.php';

$pageClass = "equipe";
$pageTitle = "Équipe municipale";
$pageDescription = "Découvrez l'équipe municipale de Montjean (53320), en Mayenne.";
$pageCss = "/asset/css/pages/mairie/equipe.css";

require_once __DIR__ . '/../../includes/components/loader.php';
include __DIR__ . '/../../includes/header.php';

renderHero(
    $pageClass,
    "Équipe municipale",
    "Votre conseil municipal au service de la commune."
);

$elus = getElusActifs();

// Séparation maire/adjoints / conseillers
$bureau    = array_filter($elus, fn($e) => str_contains(strtolower($e['fonction']), 'adjoint') || strtolower($e['fonction']) === 'maire');
$conseillers = array_filter($elus, fn($e) => str_contains(strtolower($e['fonction']), 'conseiller'));

// Construction du tableau
$headers = ['Fonction', 'Nom et prénom'];
$rows    = [];
foreach ($elus as $e) {
    $rows[] = [
        $e['fonction'],
        $e['civilite'] . ' ' . $e['nom'] . ' ' . $e['prenom'],
    ];
}

renderSection(
    $pageClass,
    "presentation",
    "Le conseil municipal",
    "Le conseil municipal de Montjean est composé de 15 élus bénévoles engagés au service des habitants. Ils se réunissent régulièrement pour délibérer sur les affaires de la commune : budget, projets d'aménagement, vie locale et services publics.",
    renderImage(
        $pageClass,
        "/asset/images/mairie/equipe/nouvelle-equipe.png",
        "Photo de l'équipe municipale de Montjean (53320)"
    )
);

renderSection(
    $pageClass,
    "liste",
    "Composition du conseil",
    "",
    renderTable($pageClass, $headers, $rows)
);

renderSection(
    $pageClass,
    "contact",
    "Contacter la mairie",
    "Pour toute question ou démarche, l'équipe municipale est à votre disposition aux horaires d'ouverture de la mairie.",
    renderActions($pageClass, [
        ['link' => '/contact.php', 'label' => 'Nous contacter'],
        ['link' => '/mairie/proces-verbaux.php', 'label' => 'Procès-verbaux du conseil'],
    ])
);

require_once __DIR__ . "/../../includes/footer.php";
?>