<?php
define('APP_RUNNING', true);
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/function.php';
require_once __DIR__ . '/../../includes/admin/auth.php';

requireAdmin();

$pageTitle = "Tableau de bord";
$activeNav = 'dashboard';

require_once __DIR__ . '/../../includes/admin/admin-header.php';

$stats = [
    ['label' => 'Horaires',     'count' => countHoraires(),     'link' => '/admin/horaires.php'],
    ['label' => 'Tarifs',       'count' => countTarifs(),       'link' => '/admin/tarifs.php'],
    ['label' => 'Documents',    'count' => countDocuments(),    'link' => '/admin/documents.php'],
    ['label' => 'Associations', 'count' => countAssociations(), 'link' => '/admin/associations.php'],
    ['label' => 'Entreprises',  'count' => countEntreprises(),  'link' => '/admin/entreprises.php'],
];
?>

<div class="admin-stat-grid">
    <?php foreach ($stats as $s): ?>
        <a href="<?= htmlspecialchars($s['link']) ?>" class="admin-stat-card">
            <span class="admin-stat-count"><?= (int) $s['count'] ?></span>
            <span class="admin-stat-label"><?= htmlspecialchars($s['label']) ?></span>
        </a>
    <?php endforeach; ?>
</div>

<section class="admin-panel">
    <h2>Bienvenue, <?= htmlspecialchars(currentAdminName()) ?></h2>
    <p>
        Utilisez le menu à gauche pour gérer le contenu du site : horaires, tarifs,
        associations, entreprises, documents officiels, assistants maternels et
        personnel périscolaire.
    </p>
</section>

<?php
require_once __DIR__ . '/../../includes/admin/admin-footer.php';
?>