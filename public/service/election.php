<?php
define('APP_RUNNING', true);
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/function.php';
require_once __DIR__ . '/../../includes/components/loader.php';

$pageClass       = 'elections';
$pageTitle       = "Élections";
$pageDescription = "Informations électorales et démarches pour les habitants de Montjean (53320), en Mayenne.";

require_once __DIR__ . '/../../includes/header.php';

renderHero($pageClass, "Élections", "Inscriptions, procurations et informations électorales", "/asset/images/service/election/election.png");

renderSection(
    $pageClass,
    "portail",
    "Portail électoral",
    "Le ministère de l'Intérieur met à disposition un portail dédié aux élections en France, permettant de gérer votre inscription sur les listes électorales et de suivre l'actualité électorale.",
    renderActions($pageClass, [
        ['link' => 'https://elections.interieur.gouv.fr', 'label' => 'Portail électoral'],
    ])
);

renderSection(
    $pageClass,
    "procuration",
    "Procuration",
    "Vous ne pouvez pas vous déplacer le jour du vote ? Donnez procuration à un électeur de votre choix via le service en ligne Maprocuration. La démarche est dématérialisée mais un passage devant une autorité habilitée (commissariat, gendarmerie ou consulat) reste nécessaire pour valider votre identité.",
    renderActions($pageClass, [
        ['link' => 'https://www.maprocuration.gouv.fr', 'label' => 'Faire une procuration'],
    ])
);

renderSection(
    $pageClass,
    "contact",
    "Nous contacter",
    "Pour toute question relative aux listes électorales, contactez la Mairie de Montjean.",
    renderActions($pageClass, [
        ['link' => '/contact.php', 'label' => 'Contacter la mairie'],
    ])
);

require_once __DIR__ . '/../../includes/footer.php';
?>