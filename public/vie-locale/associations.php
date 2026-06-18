<?php
define('APP_RUNNING', true);
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/function.php';

$pageTitle = "Associations";
$pageDescription = "Retrouvez les associations de Montjean : coordonnées, activités et réseaux sociaux.";

require_once __DIR__ . '/../../includes/header.php';

$associations = getAssociationsMontjean();
?>

<h1>Associations</h1>
<p>Découvrez les associations présentes sur la commune de Montjean.</p>

<?php if (empty($associations)): ?>
    <p>Aucune association trouvée.</p>
<?php else: ?>

    <?php foreach ($associations as $asso): ?>
        <div class="association-card">
            <h3><?= htmlspecialchars($asso['nom']) ?></h3>

            <?php if (!empty($asso['objet'])): ?>
                <p><?= htmlspecialchars($asso['objet']) ?></p>
            <?php endif; ?>

            <?php if (!empty($asso['adresse'])): ?>
                <p><?= htmlspecialchars($asso['adresse']) ?></p>
            <?php endif; ?>

            <?php if (!empty($asso['telephone'])): ?>
                <p>Tél : <?= htmlspecialchars($asso['telephone']) ?></p>
            <?php endif; ?>

            <?php if (!empty($asso['email'])): ?>
                <p><a href="mailto:<?= htmlspecialchars($asso['email']) ?>"><?= htmlspecialchars($asso['email']) ?></a></p>
            <?php endif; ?>

            <?php if (!empty($asso['site'])): ?>
                <p><a href="<?= htmlspecialchars($asso['site']) ?>" target="_blank" rel="noopener">Site internet</a></p>
            <?php endif; ?>

            <?php if (!empty($asso['facebook'])): ?>
                <p><a href="<?= htmlspecialchars($asso['facebook']) ?>" target="_blank" rel="noopener">Facebook</a></p>
            <?php endif; ?>

            <?php if (!empty($asso['instagram'])): ?>
                <p><a href="<?= htmlspecialchars($asso['instagram']) ?>" target="_blank" rel="noopener">Instagram</a></p>
            <?php endif; ?>

            <?php if (!empty($asso['youtube'])): ?>
                <p><a href="<?= htmlspecialchars($asso['youtube']) ?>" target="_blank" rel="noopener">YouTube</a></p>
            <?php endif; ?>

            <?php if (!empty($asso['linkedin'])): ?>
                <p><a href="<?= htmlspecialchars($asso['linkedin']) ?>" target="_blank" rel="noopener">LinkedIn</a></p>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>

<?php endif; ?>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>