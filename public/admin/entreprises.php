<?php
define('APP_RUNNING', true);
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/function.php';
require_once __DIR__ . '/../../includes/admin/auth.php';

requireAdmin();

$pageTitle = "Artisans & entreprises";
$activeNav = 'entreprises';

$success = '';
$error   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $id = (int) $_POST['delete_id'];

    if (deleteEntreprise($id)) {
        $success = 'Entreprise supprimée avec succès.';
    } else {
        $error = "Erreur lors de la suppression de l'entreprise.";
    }
}

$entreprises = getToutesLesEntreprises();

require_once __DIR__ . '/../../includes/admin/admin-header.php';
?>

<div class="admin-panel-header">
    <h2 style="margin:0;padding:0;border:none;">Liste des artisans & entreprises</h2>
    <a href="/admin/entreprises-form.php" class="admin-btn admin-btn--add">+ Ajouter une entreprise</a>
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
                <th>Activité</th>
                <th>Téléphone</th>
                <th>Email</th>
                <th>Actif</th>
                <th>Ordre</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($entreprises)): ?>
                <tr>
                    <td colspan="7">Aucune entreprise enregistrée.</td>
                </tr>
            <?php endif; ?>

            <?php foreach ($entreprises as $e): ?>
                <tr<?= (int) $e['actif'] === 0 ? ' style="opacity:.5;"' : '' ?>>
                    <td><?= htmlspecialchars($e['nom']) ?></td>
                    <td><?= htmlspecialchars($e['activite'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($e['telephone'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($e['email'] ?? '—') ?></td>
                    <td><?= (int) $e['actif'] === 1 ? 'Oui' : 'Non' ?></td>
                    <td><?= (int) $e['ordre'] ?></td>
                    <td class="admin-table-actions">
                        <a href="/admin/entreprises-form.php?id=<?= (int) $e['id'] ?>" class="admin-btn admin-btn--edit">
                            Modifier
                        </a>
                        <form method="post" action="/admin/entreprises.php"
                              onsubmit="return confirm('Supprimer cette entreprise ?');" style="display:inline;">
                            <input type="hidden" name="delete_id" value="<?= (int) $e['id'] ?>">
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