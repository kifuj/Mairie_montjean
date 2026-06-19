<?php
define('APP_RUNNING', true);
$pageTitle = "Sentier communaux";
require_once __DIR__ . "/../../includes/components/loader.php";
$pageDescription = "Découvrez les sentiers communaux de Montjean (53320), en Mayenne.";
include __DIR__ . '/../../includes/header.php';
$pageClass = "sentier";



renderHero($pageClass, 
"Montjean", 
"Les sentiers communaux");

renderSection($pageClass, 
"chemin", 
"Chemin Pedestre", 
"Le chemin pedestre accessible depuis l'etang dispose de 3 parcours allant de 1,7km a 3,7km");

?>