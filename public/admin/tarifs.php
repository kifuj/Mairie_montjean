<?php
define('APP_RUNNING', true);
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/function.php';
require_once __DIR__ . '/../../includes/admin/auth.php';

requireAdmin();

$pageTitle = "Tarifs";
$activeNav = 'tarifs';

$success = '';
$error = '';

// ── Suppression ───────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $id = (int) $_POST['delete_id'];

    if (deleteTarif($id)) {
        $success = 'Tarif supprimé avec succès.';
    } else {
        $error = "Erreur lors de la suppression du tarif.";
    }
}

$tarifs = getTousLesTarifs();

// ── Regroupement par service ──────────────────────────────
$groupes = [];
foreach ($tarifs as $t) {
    $groupes[$t['service']][] = $t;
}

$labelsServices = [
    'cimetiere' => 'Cimetière',
    'periscolaire' => 'Périscolaire',
    'salle_fetes' => 'Salle des fêtes',
];

function formatMontant(?string $valeur): string
{
    if ($valeur === null || $valeur === '') {
        return '—';
    }

    return number_format((float) $valeur, 2, ',', ' ') . ' €';
}

require_once __DIR__ . '/../../includes/admin/admin-header.php';
?>

<div class="admin-panel-header">
    <h2 style="margin:0;padding:0;border:none;">Liste des tarifs</h2>
    <a href="/admin/tarifs-form.php" class="admin-btn admin-btn--add">+ Ajouter un tarif</a>
</div>

<?php if ($success): ?>
    <p class="admin-alert admin-alert--success"><?= htmlspecialchars($success) ?></p>
<?php endif; ?>

<?php if ($error): ?>
    <p class="admin-alert admin-alert--error"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<?php if (empty($groupes)): ?>
    <p class="admin-panel">Aucun tarif enregistré.</p>
<?php endif; ?>

<?php foreach ($groupes as $service => $lignes): ?>

    <section class="admin-service-block">

        <h3 class="admin-service-title">
            <?= htmlspecialchars($labelsServices[$service] ?? ucfirst(str_replace('_', ' ', $service))) ?>
            <span class="admin-service-count"><?= count($lignes) ?></span>
        </h3>

        <div class="admin-table-wrapper">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Groupe</th>
                        <th>Label</th>
                        <th>Tarif commune</th>
                        <th>Tarif hors commune</th>
                        <th>Unité</th>
                        <th>Actif</th>
                        <th>Ordre</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lignes as $t): ?>
                        <tr<?= (int) $t['actif'] === 0 ? ' style="opacity:.5;"' : '' ?>>
                            <td><?= htmlspecialchars($t['groupe'] ?? '—') ?></td>
                            <td><?= htmlspecialchars($t['label']) ?></td>
                            <td><?= formatMontant($t['tarif_base']) ?></td>
                            <td><?= formatMontant($t['tarif_hors_commune']) ?></td>
                            <td><?= htmlspecialchars($t['unite'] ?? '—') ?></td>
                            <td><?= (int) $t['actif'] === 1 ? 'Oui' : 'Non' ?></td>
                            <td><?= (int) $t['ordre'] ?></td>
                            <td class="admin-table-actions">
                                <a href="/admin/tarifs-form.php?id=<?= (int) $t['id'] ?>" class="admin-btn admin-btn--edit">
                                    Modifier
                                </a>
                                <form method="post" action="/admin/tarifs.php"
                                    onsubmit="return confirm('Supprimer ce tarif ?');" style="display:inline;">
                                    <input type="hidden" name="delete_id" value="<?= (int) $t['id'] ?>">
                                    <button type="submit" class="admin-btn admin-btn--delete">Supprimer</button>
                                </form>
                            </td>
                            </tr>
                        <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </section>

<?php endforeach; ?>

<?php
require_once __DIR__ . '/../../includes/admin/admin-footer.php';
?>