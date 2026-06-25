<?php
define('APP_RUNNING', true);

$pageClass = "equipe";
$pageTitle = "Equipe municipale";
$pageDescription = "Découvrez l'équipe municipale de Montjean (53320), en Mayenne.";
$pageCss = "/assets/css/pages/mairie/equipe.css";

include __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/components/loader.php';



renderHero(
    $pageClass,
    $pageTitle,
    $pageDescription

);

?>
<img src="/asset/images/mairie/equipe/nouvelle-equipe.png" alt="image de l'equipe municipale" class="image equipe__image">


<?php
require_once __DIR__ . "/../../includes/footer.php";
?>