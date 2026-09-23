<?php
define('APP_RUNNING', true);
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/function.php';
require_once __DIR__ . '/../../includes/components/loader.php';

$pageClass       = 'population';
$pageTitle       = "Recensement de la population";
$pageDescription = "Informations sur le recensement de la population à Montjean (53320).";

require_once __DIR__ . '/../../includes/header.php';

renderHero($pageClass, "Recensement de la population", "Une démarche essentielle pour la commune.", "/asset/images/demarche/recensement-population/population.png");

renderSection(
    $pageClass,
    "presentation",
    "Qu'est-ce que le recensement ?",
    "Le recensement de la population est organisé par l'INSEE en partenariat avec les communes. Il permet de connaître le nombre d'habitants et leurs caractéristiques : âge, profession, conditions de logement. Ces données servent à adapter les services publics et à calculer les dotations de l'État aux communes."
);

renderSection(
    $pageClass,
    "fonctionnement",
    "Comment ça fonctionne ?",
    "",
    renderCards($pageClass, [
        [
            'title' => 'Enquête tournante',
            'lines' => [
                'Les communes de moins de 10 000 habitants sont recensées intégralement tous les 5 ans.',
                'Un agent recenseur mandaté par la mairie passe au domicile de chaque habitant.',
                'La prochaine enquête se deroulera en 2027.'
            ],
        ],
        [
            'title' => 'Vos obligations',
            'lines' => [
                'Il est obligatoire de repondre au recensement.',
                'Vous pouvez répondre sur papier ou en ligne sur le site de l\'INSEE.',
                'Vos données sont strictement confidentielles et utilisées uniquement à des fins statistiques.',
            ],
        ],
    ])
);


renderSection(
    $pageClass,
    "contact",
    "Contact",
    "Pour toute question sur le recensement, contactez la mairie de Montjean.",
    renderActions($pageClass, [
        ['link' => '/contact.php', 'label' => 'Nous contacter'],
        ['link' => 'https://www.le-recensement-et-moi.fr', 'label' => 'Le recensement et moi'],
    ])
);

require_once __DIR__ . '/../../includes/footer.php';
?>