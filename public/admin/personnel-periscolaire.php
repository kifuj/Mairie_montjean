<?php
define('APP_RUNNING', true);
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/function.php';
require_once __DIR__ . '/../../includes/admin/auth.php';

requireAdmin();

$pageTitle = "Personnel périscolaire";
$activeNav = 'personnel';

$success = '';
$error   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    if (deletePersonnel((int) $_POST['delete_id'])) {
        $success = 'Membre du personnel supprimé avec succès.';
    } else {
        $error = "Erreur lors de la suppression.";
    }
}

$personnel = getToutLePersonnel();

require_once __DIR__ . '/../../includes/admin/admin-header.php';
?>

<div class="admin-panel-header">
    <h2 style="margin:0;padding:0;border:none;">Personnel périscolaire</h2>
    <a href="/admin/personnel-periscolaire-form.php" class="admin-btn admin-btn--add">+ Ajouter</a>
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
                <th>Rôle</th>
                <th>Prénom</th>
                <th>Nom</th>
                <th>Actif</th>
                <th>Ordre</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($personnel)): ?>
                <tr><td colspan="6">Aucun membre du personnel enregistré.</td></tr>
            <?php endif; ?>

            <?php foreach ($personnel as $p): ?>
                <tr<?= (int) $p['actif'] === 0 ? ' style="opacity:.5;"' : '' ?>>
                    <td><?= htmlspecialchars($p['role']) ?></td>
                    <td><?= htmlspecialchars($p['prenom']) ?></td>
                    <td><?= htmlspecialchars($p['nom']) ?></td>
                    <td><?= (int) $p['actif'] === 1 ? 'Oui' : 'Non' ?></td>
                    <td><?= (int) $p['ordre'] ?></td>
                    <td class="admin-table-actions">
                        <a href="/admin/personnel-periscolaire-form.php?id=<?= (int) $p['id'] ?>" class="admin-btn admin-btn--edit">Modifier</a>
                        <form method="post" action="/admin/personnel-periscolaire.php"
                              onsubmit="return confirm('Supprimer ce membre du personnel ?');" style="display:inline;">
                            <input type="hidden" name="delete_id" value="<?= (int) $p['id'] ?>">
                            <button type="submit" class="admin-btn admin-btn--delete">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/../../includes/admin/admin-footer.php'; ?>