<?php
define('APP_RUNNING', true);
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/function.php';
require_once __DIR__ . '/../../includes/components/loader.php';

$pageClass = 'associations';
$pageTitle = "Associations";
$pageDescription = "Retrouvez les associations de Montjean : coordonnées, activités et réseaux sociaux.";
$pageCss = "/asset/css/pages/vie-locale/associations.css";

require_once __DIR__ . '/../../includes/header.php';

renderHero($pageClass, "Associations", "La vie associative de Montjean.", "/asset/images/vie-locale/association/association.png");

$associations = getAssociationsMontjean();

if (empty($associations)) {
    renderSection($pageClass, 'liste-associations', 'Liste des associations', '', '<p>Aucune association trouvée.</p>');
} else {
    $cards = [];
    foreach ($associations as $asso) {
        $lines = [];
        if (!empty($asso['objet']))     $lines[] = $asso['objet'];
        if (!empty($asso['adresse']))   $lines[] = $asso['adresse'];
        if (!empty($asso['telephone'])) $lines[] = 'Tél : ' . $asso['telephone'];
        if (!empty($asso['email']))     $lines[] = 'Email : ' . $asso['email'];

        $card = ['title' => $asso['nom'], 'lines' => $lines];

        if (!empty($asso['logo'])) {
            $card['image'] = '/uploads/associations/' . $asso['logo'];
        }

        // Réseaux saisis via la table dédiée (Facebook, site web...)
        $reseaux = $asso['reseaux'] ?? [];

        // Compatibilité : si l'ancien champ "site" est rempli mais qu'aucun
        // réseau n'a été ajouté, on l'affiche quand même comme "Site web".
        if (empty($reseaux) && !empty($asso['site'])) {
            $reseaux = [['label' => 'Site web', 'url' => $asso['site']]];
        }

        if (!empty($reseaux)) {
            $card['reseaux'] = $reseaux;
        }

        $cards[] = $card;
    }
    renderSection($pageClass, 'liste-associations', 'Liste des associations', '', renderCards($pageClass, $cards));
}

require_once __DIR__ . '/../../includes/footer.php';
?>