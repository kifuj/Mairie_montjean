<?php

define('APP_RUNNING', true);

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/function.php';

$pageTitle = "Artisans et entreprises";
$pageDescription = "Retrouvez les artisans, commerçants et entreprises de Montjean.";

require_once __DIR__ . '/../../includes/header.php';

$entreprises = getEntreprisesMontjean();

?>

<section class="page-header">
    <div class="container">
        <h1>Artisans & Entreprises</h1>
        <p>Découvrez les professionnels présents sur la commune de Montjean.</p>
    </div>
</section>

<section class="entreprises">
    <div class="container">

        <?php if (empty($entreprises)): ?>

            <p>Aucune entreprise trouvée.</p>

        <?php else: ?>

            <div class="entreprises-grid">

                <?php foreach ($entreprises as $entreprise) {
                    echo '<div class="entreprise-card">';
                    echo '<h3>' . htmlspecialchars($entreprise['nom']) . '</h3>';
                    if (!empty($entreprise['adresse'])) {
                        echo '<p>' . htmlspecialchars($entreprise['adresse']) . '</p>';
                    }
                    if (!empty($entreprise['activite'])) {
                        echo '<p>' . htmlspecialchars($entreprise['activite']) . '</p>';
                    }
                    echo '</div>';
                } ?>

            </div>

        <?php endif; ?>

    </div>
</section>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>