<?php
define('APP_RUNNING', true);
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/function.php';
require_once __DIR__ . '/../../includes/admin/auth.php';

requireAdmin();

$id        = isset($_GET['id']) ? (int) $_GET['id'] : null;
$isEdit    = $id !== null;
$personnel = $isEdit ? getPersonnelById($id) : null;

if ($isEdit && !$personnel) {
    header('Location: /admin/personnel-periscolaire.php');
    exit;
}

$pageTitle = $isEdit ? "Modifier un membre du personnel" : "Ajouter un membre du personnel";
$activeNav = 'personnel';
$error     = '';

$values = [
    'role'   => $personnel['role']   ?? '',
    'prenom' => $personnel['prenom'] ?? '',
    'nom'    => $personnel['nom']    ?? '',
    'ordre'  => $personnel['ordre']  ?? 0,
    'actif'  => $personnel['actif']  ?? 1,
];

$roles = ['Responsable', 'Animatrice', 'Animateur', 'Aide-animateur', 'Aide-animatrice', 'Autre'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $values = [
        'role'   => trim((string) ($_POST['role'] ?? '')),
        'prenom' => trim((string) ($_POST['prenom'] ?? '')),
        'nom'    => trim((string) ($_POST['nom'] ?? '')),
        'ordre'  => (int) ($_POST['ordre'] ?? 0),
        'actif'  => isset($_POST['actif']) ? 1 : 0,
    ];

    if ($values['role'] === '' || $values['nom'] === '' || $values['prenom'] === '') {
        $error = "Le rôle, le prénom et le nom sont obligatoires.";
    } else {
        $ok = $isEdit
            ? updatePersonnel($id, $values)
            : createPersonnel($values);

        if ($ok) {
            header('Location: /admin/personnel-periscolaire.php');
            exit;
        }
        $error = "Une erreur est survenue lors de l'enregistrement.";
    }
}

require_once __DIR__ . '/../../includes/admin/admin-header.php';
?>

<?php if ($error): ?>
    <p class="admin-alert admin-alert--error"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form class="admin-form" method="post"
      action="/admin/personnel-periscolaire-form.php<?= $isEdit ? '?id=' . (int) $id : '' ?>">

    <div class="admin-form-row admin-form-row--single">
        <label class="admin-field">
            <span>Rôle</span>
            <select name="role">
                <?php foreach ($roles as $r): ?>
                    <option value="<?= htmlspecialchars($r) ?>"
                        <?= $values['role'] === $r ? 'selected' : '' ?>>
                        <?= htmlspecialchars($r) ?>
                    </option>
                <?php endforeach; ?>
                <?php if (!in_array($values['role'], $roles, true) && $values['role'] !== ''): ?>
                    <option value="<?= htmlspecialchars($values['role']) ?>" selected>
                        <?= htmlspecialchars($values['role']) ?>
                    </option>
                <?php endif; ?>
            </select>
        </label>
    </div>

    <div class="admin-form-row">
        <label class="admin-field">
            <span>Prénom</span>
            <input type="text" name="prenom" value="<?= htmlspecialchars($values['prenom']) ?>" required>
        </label>
        <label class="admin-field">
            <span>Nom</span>
            <input type="text" name="nom" value="<?= htmlspecialchars($values['nom']) ?>" required>
        </label>
    </div>

    <div class="admin-form-row">
        <label class="admin-field">
            <span>Ordre d'affichage</span>
            <input type="number" name="ordre" value="<?= (int) $values['ordre'] ?>" min="0">
        </label>
        <div class="admin-field" style="justify-content:flex-end;">
            <label class="admin-field-checkbox">
                <input type="checkbox" name="actif" <?= $values['actif'] ? 'checked' : '' ?>>
                <span>Actif (visible sur le site public)</span>
            </label>
        </div>
    </div>

    <div class="admin-form-actions">
        <button type="submit" class="btn admin-btn--add" style="padding:var(--sp-3) var(--sp-6);">
            <?= $isEdit ? 'Enregistrer les modifications' : 'Ajouter' ?>
        </button>
        <a href="/admin/personnel-periscolaire.php" class="admin-form-cancel">Annuler</a>
    </div>

</form>

<?php require_once __DIR__ . '/../../includes/admin/admin-footer.php'; ?>