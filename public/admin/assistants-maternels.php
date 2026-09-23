<?php
define('APP_RUNNING', true);
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/function.php';
require_once __DIR__ . '/../../includes/admin/auth.php';

requireAdmin();

$pageTitle = "Assistants maternels";
$activeNav = 'assistants';

$success = '';
$error   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    if (deleteAssistant((int) $_POST['delete_id'])) {
        $success = 'Assistant maternel supprimé avec succès.';
    } else {
        $error = "Erreur lors de la suppression.";
    }
}

$assistants = getTousLesAssistants();

require_once __DIR__ . '/../../includes/admin/admin-header.php';
?>

<div class="admin-panel-header">
    <h2 style="margin:0;padding:0;border:none;">Assistants maternels agréés</h2>
    <a href="/admin/assistants-maternels-form.php" class="admin-btn admin-btn--add">+ Ajouter</a>
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
                <th>Nom</th>
                <th>Prénom</th>
                <th>Téléphone</th>
                <th>Agrément</th>
                <th>MAM</th>
                <th>Actif</th>
                <th>Ordre</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($assistants)): ?>
                <tr><td colspan="8">Aucun assistant maternel enregistré.</td></tr>
            <?php endif; ?>

            <?php foreach ($assistants as $a): ?>
                <tr<?= (int) $a['actif'] === 0 ? ' style="opacity:.5;"' : '' ?>>
                    <td><?= htmlspecialchars($a['nom']) ?></td>
                    <td><?= htmlspecialchars($a['prenom']) ?></td>
                    <td><?= htmlspecialchars($a['telephone'] ?? '—') ?></td>
                    <td><?= (int) $a['agrement'] ?> place(s)</td>
                    <td><?= htmlspecialchars($a['mam'] ?? '—') ?></td>
                    <td><?= (int) $a['actif'] === 1 ? 'Oui' : 'Non' ?></td>
                    <td><?= (int) $a['ordre'] ?></td>
                    <td class="admin-table-actions">
                        <a href="/admin/assistants-maternels-form.php?id=<?= (int) $a['id'] ?>" class="admin-btn admin-btn--edit">Modifier</a>
                        <form method="post" action="/admin/assistants-maternels.php"
                              onsubmit="return confirm('Supprimer cet assistant maternel ?');" style="display:inline;">
                            <input type="hidden" name="delete_id" value="<?= (int) $a['id'] ?>">
                            <button type="submit" class="admin-btn admin-btn--delete">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/../../includes/admin/admin-footer.php'; ?>