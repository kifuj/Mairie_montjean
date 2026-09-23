<?php
define('APP_RUNNING', true);
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/function.php';
require_once __DIR__ . '/../../includes/admin/auth.php';

requireAdmin();

$pageTitle = "Liens & documents";
$activeNav = 'liens';

$success = '';
$error   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $lien = getLienById((int) $_POST['delete_id']);

    // Supprime le fichier physique si c'est un upload
    if ($lien && $lien['type'] === 'fichier') {
        $path = __DIR__ . '/../../uploads/demarche/' . $lien['valeur'];
        if (is_file($path)) {
            @unlink($path);
        }
    }

    deleteLien((int) $_POST['delete_id'])
        ? $success = 'Lien supprimé.'
        : $error   = 'Erreur lors de la suppression.';
}

$liens = getTousLesLiens();

// Regroupement par page slug
$groupes = [];
foreach ($liens as $l) {
    $groupes[$l['page']][] = $l;
}

// Libellés lisibles des pages
$labelsPages = [
    'argent-de-poche'        => 'Argent de poche',
    'salle-des-fetes'        => 'Salle des fêtes',
    'periscolaire'           => 'Périscolaire',
    'recensement-citoyen'    => 'Recensement citoyen',
    'urbanisme'              => 'Urbanisme',
];

require_once __DIR__ . '/../../includes/admin/admin-header.php';
?>

<div class="admin-panel-header">
    <h2 style="margin:0;padding:0;border:none;">Liens & documents par page</h2>
    <a href="/admin/liens-form.php" class="admin-btn admin-btn--add">+ Ajouter un lien</a>
</div>

<?php if ($success): ?>
    <p class="admin-alert admin-alert--success"><?= htmlspecialchars($success) ?></p>
<?php endif; ?>
<?php if ($error): ?>
    <p class="admin-alert admin-alert--error"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<?php if (empty($groupes)): ?>
    <p class="admin-panel">Aucun lien enregistré.</p>
<?php endif; ?>

<?php foreach ($groupes as $page => $lignes): ?>
    <section class="admin-service-block">

        <h3 class="admin-service-title">
            <?= htmlspecialchars($labelsPages[$page] ?? ucfirst(str_replace('-', ' ', $page))) ?>
            <span class="admin-service-count"><?= count($lignes) ?></span>
        </h3>

        <div class="admin-table-wrapper">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Label du bouton</th>
                        <th>Type</th>
                        <th>Fichier / URL</th>
                        <th>Visible</th>
                        <th>Ordre</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lignes as $l): ?>
                        <tr>
                            <td><?= htmlspecialchars($l['label']) ?></td>
                            <td>
                                <span class="admin-type-badge admin-type-badge--<?= $l['type'] ?>">
                                    <?= $l['type'] === 'fichier' ? 'PDF' : 'URL' ?>
                                </span>
                            </td>
                            <td class="admin-lien-valeur">
                                <?php if ($l['type'] === 'fichier'): ?>
                                    <a href="/uploads/demarche/<?= htmlspecialchars($l['valeur']) ?>"
                                       target="_blank" rel="noopener">
                                        <?= htmlspecialchars($l['valeur']) ?>
                                    </a>
                                <?php else: ?>
                                    <a href="<?= htmlspecialchars($l['valeur']) ?>"
                                       target="_blank" rel="noopener">
                                        <?= htmlspecialchars($l['valeur']) ?>
                                    </a>
                                <?php endif; ?>
                            </td>
                            <td><?= (int) $l['visible'] === 1 ? 'Oui' : 'Non' ?></td>
                            <td><?= (int) $l['ordre'] ?></td>
                            <td class="admin-table-actions">
                                <a href="/admin/liens-form.php?id=<?= (int) $l['id'] ?>"
                                   class="admin-btn admin-btn--edit">Modifier</a>
                                <form method="post" action="/admin/liens.php"
                                      onsubmit="return confirm('Supprimer ce lien ?');" style="display:inline;">
                                    <input type="hidden" name="delete_id" value="<?= (int) $l['id'] ?>">
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

<?php require_once __DIR__ . '/../../includes/admin/admin-footer.php'; ?>