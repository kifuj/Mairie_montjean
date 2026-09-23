<?php
define('APP_RUNNING', true);
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/function.php';
require_once __DIR__ . '/../../includes/admin/auth.php';

requireAdmin();

$pageTitle = "Équipe municipale";
$activeNav = 'elus';

$success = '';
$error   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    deleteElu((int) $_POST['delete_id'])
        ? $success = 'Élu supprimé.'
        : $error   = 'Erreur lors de la suppression.';
}

$elus = getTousLesElus();

require_once __DIR__ . '/../../includes/admin/admin-header.php';
?>

<div class="admin-panel-header">
    <h2 style="margin:0;padding:0;border:none;">Conseil municipal</h2>
    <a href="/admin/elus-form.php" class="admin-btn admin-btn--add">+ Ajouter un élu</a>
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
                <th>Ordre</th>
                <th>Civilité</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Fonction</th>
                <th>Actif</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($elus)): ?>
                <tr><td colspan="7">Aucun élu enregistré.</td></tr>
            <?php endif; ?>

            <?php foreach ($elus as $e): ?>
                <tr>
                    <td><?= (int) $e['ordre'] ?></td>
                    <td><?= htmlspecialchars($e['civilite']) ?></td>
                    <td><?= htmlspecialchars($e['nom']) ?></td>
                    <td><?= htmlspecialchars($e['prenom']) ?></td>
                    <td><?= htmlspecialchars($e['fonction']) ?></td>
                    <td><?= (int) $e['actif'] === 1 ? 'Oui' : 'Non' ?></td>
                    <td class="admin-table-actions">
                        <a href="/admin/elus-form.php?id=<?= (int) $e['id'] ?>"
                           class="admin-btn admin-btn--edit">Modifier</a>
                        <form method="post" action="/admin/elus.php"
                              onsubmit="return confirm('Supprimer cet élu ?');" style="display:inline;">
                            <input type="hidden" name="delete_id" value="<?= (int) $e['id'] ?>">
                            <button type="submit" class="admin-btn admin-btn--delete">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/../../includes/admin/admin-footer.php'; ?>