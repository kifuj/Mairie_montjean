<?php
define('APP_RUNNING', true);
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/function.php';
require_once __DIR__ . '/../../includes/admin/auth.php';

requireAdmin();

$pageTitle = "Associations";
$activeNav = 'associations';

$success = '';
$error   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $id = (int) $_POST['delete_id'];

    if (deleteAssociation($id)) {
        $success = 'Association supprimée avec succès.';
    } else {
        $error = "Erreur lors de la suppression de l'association.";
    }
}

$associations = getToutesLesAssociations();

require_once __DIR__ . '/../../includes/admin/admin-header.php';
?>

<div class="admin-panel-header">
    <h2 style="margin:0;padding:0;border:none;">Liste des associations</h2>
    <a href="/admin/associations-form.php" class="admin-btn admin-btn--add">+ Ajouter une association</a>
</div>

<?php if ($success): ?>
    <p class="admin-alert admin-alert--success"><?= htmlspecialchars($success) ?></p>
<?php endif; ?>

<?php if ($error): ?>
    <p class="admin-alert admin-alert--error"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<div class="admin-table-wrapper">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Logo</th>
                <th>Nom</th>
                <th>Objet</th>
                <th>Téléphone</th>
                <th>Email</th>
                <th>Actif</th>
                <th>Ordre</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($associations)): ?>
                <tr>
                    <td colspan="8">Aucune association enregistrée.</td>
                </tr>
            <?php endif; ?>

            <?php foreach ($associations as $a): ?>
                <tr<?= (int) $a['actif'] === 0 ? ' style="opacity:.5;"' : '' ?>>
                    <td>
                        <?php if (!empty($a['logo'])): ?>
                            <img src="I/uploads/associations/<?= htmlspecialchars($a['logo']) ?>" alt="" class="admin-table-logo">
                        <?php else: ?>
                            —
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($a['nom']) ?></td>
                    <td><?= htmlspecialchars($a['objet'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($a['telephone'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($a['email'] ?? '—') ?></td>
                    <td><?= (int) $a['actif'] === 1 ? 'Oui' : 'Non' ?></td>
                    <td><?= (int) $a['ordre'] ?></td>
                    <td class="admin-table-actions">
                        <a href="/admin/associations-form.php?id=<?= (int) $a['id'] ?>" class="admin-btn admin-btn--edit">
                            Modifier
                        </a>
                        <form method="post" action="/admin/associations.php"
                              onsubmit="return confirm('Supprimer cette association ?');" style="display:inline;">
                            <input type="hidden" name="delete_id" value="<?= (int) $a['id'] ?>">
                            <button type="submit" class="admin-btn admin-btn--delete">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php
require_once __DIR__ . '/../../includes/admin/admin-footer.php';
?>