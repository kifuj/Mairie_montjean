<?php
define('APP_RUNNING', true);

require_once __DIR__ . '/../includes/function.php';
require_once __DIR__ . '/sync_entreprises.php';

echo "Début sync...\n";

$count = syncEntreprisesFromSirene('53158', null, true);

echo "Entreprises : $count\n";
echo "Sync terminé\n";