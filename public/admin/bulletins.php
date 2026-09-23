<?php
define('APP_RUNNING', true);
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/function.php';
require_once __DIR__ . '/../../includes/admin/auth.php';

requireAdmin();

$pageTitle = "Bulletins municipaux";
$activeNav = 'bulletins';

$success = '';
$error   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $id  = (int) $_POST['delete_id'];
    $doc = getDocumentById($id);

    if ($doc && $doc['categorie'] === 'bulletin') {
        $path = __DIR__ . '/../../uploads/documents/' . $doc['fichier'];
        if (is_file($path)) @unlink($path);

        deleteDocument($id)
            ? $success = 'Bulletin supprimé avec succès.'
            : $error   = 'Erreur lors de la suppression.';
    }
}

$bulletins = getTousLesBulletins();

require_once __DIR__ . '/../../includes/admin/admin-header.php';
?>

<div class="admin-panel-header">
    <h2 style="margin:0;padding:0;border:none;">Bulletins municipaux</h2>
    <a href="/admin/bulletins-form.php" class="admin-btn admin-btn--add">+ Ajouter un bulletin</a>
</div>

<?php if ($success): ?>
    <p class="admin-alert admin-alert--success"><?= htmlspecialchars($success) ?></p>
<?php endif; ?>
<?php if ($error): ?>
    <p class="admin-alert admin-alert--error"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<?php if (empty($bulletins)): ?>
    <div class="admin-panel">
        <p>Aucun bulletin enregistré pour l'instant.</p>
    </div>
<?php else: ?>
    <div class="admin-table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Date</th>
                    <th>Fichier</th>
                    <th>Visible</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bulletins as $b): ?>
                    <tr>
                        <td><?= htmlspecialchars($b['titre']) ?></td>
                        <td>
                            <?= $b['date_document']
                                ? date('d/m/Y', strtotime($b['date_document']))
                                : '—' ?>
                        </td>
                        <td>
                            <a href="/uploads/documents/<?= htmlspecialchars($b['fichier']) ?>"
                               target="_blank" rel="noopener">
                                <?= htmlspecialchars($b['fichier']) ?>
                            </a>
                        </td>
                        <td><?= (int) $b['visible'] === 1 ? 'Oui' : 'Non' ?></td>
                        <td class="admin-table-actions">
                            <a href="/admin/bulletins-form.php?id=<?= (int) $b['id'] ?>"
                               class="admin-btn admin-btn--edit">Modifier</a>
                            <form method="post" action="/admin/bulletins.php"
                                  onsubmit="return confirm('Supprimer ce bulletin et son PDF ?');"
                                  style="display:inline;">
                                <input type="hidden" name="delete_id" value="<?= (int) $b['id'] ?>">
                                <button type="submit" class="admin-btn admin-btn--delete">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/../../includes/admin/admin-footer.php'; ?>