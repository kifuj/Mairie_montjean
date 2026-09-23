<?php
define('APP_RUNNING', true);
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/function.php';
require_once __DIR__ . '/../../includes/admin/auth.php';

requireAdmin();

$id     = isset($_GET['id']) ? (int) $_GET['id'] : null;
$isEdit = $id !== null;
$elu    = $isEdit ? getEluById($id) : null;

if ($isEdit && !$elu) {
    header('Location: /admin/elus.php');
    exit;
}

$pageTitle = $isEdit ? "Modifier un élu" : "Ajouter un élu";
$activeNav = 'elus';
$error     = '';

$values = [
    'ordre'    => $elu['ordre']    ?? 0,
    'civilite' => $elu['civilite'] ?? 'M.',
    'nom'      => $elu['nom']      ?? '',
    'prenom'   => $elu['prenom']   ?? '',
    'fonction' => $elu['fonction'] ?? 'Conseiller municipal',
    'actif'    => $elu['actif']    ?? 1,
];

$fonctions = [
    'Maire',
    'Premier adjoint',
    'Deuxième adjoint',
    'Troisième adjoint',
    'Quatrième adjoint',
    'Conseiller municipal',
    'Conseillère municipale',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $values = [
        'ordre'    => (int) ($_POST['ordre'] ?? 0),
        'civilite' => $_POST['civilite'] === 'Mme' ? 'Mme' : 'M.',
        'nom'      => trim(strtoupper((string) ($_POST['nom'] ?? ''))),
        'prenom'   => trim((string) ($_POST['prenom'] ?? '')),
        'fonction' => trim((string) ($_POST['fonction'] ?? '')),
        'actif'    => isset($_POST['actif']) ? 1 : 0,
    ];

    if ($values['nom'] === '' || $values['prenom'] === '') {
        $error = "Le nom et le prénom sont obligatoires.";
    } elseif ($values['fonction'] === '') {
        $error = "La fonction est obligatoire.";
    } else {
        $ok = $isEdit ? updateElu($id, $values) : createElu($values);

        if ($ok) {
            header('Location: /admin/elus.php');
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
      action="/admin/elus-form.php<?= $isEdit ? '?id=' . (int) $id : '' ?>">

    <div class="admin-form-row">
        <label class="admin-field">
            <span>Civilité</span>
            <select name="civilite">
                <option value="M."  <?= $values['civilite'] === 'M.'  ? 'selected' : '' ?>>M.</option>
                <option value="Mme" <?= $values['civilite'] === 'Mme' ? 'selected' : '' ?>>Mme</option>
            </select>
        </label>

        <label class="admin-field">
            <span>Ordre d'affichage</span>
            <input type="number" name="ordre" value="<?= (int) $values['ordre'] ?>" min="1">
        </label>
    </div>

    <div class="admin-form-row">
        <label class="admin-field">
            <span>Nom (en majuscules)</span>
            <input type="text" name="nom" value="<?= htmlspecialchars($values['nom']) ?>"
                   style="text-transform:uppercase" required>
        </label>

        <label class="admin-field">
            <span>Prénom</span>
            <input type="text" name="prenom" value="<?= htmlspecialchars($values['prenom']) ?>" required>
        </label>
    </div>

    <div class="admin-form-row admin-form-row--single">
        <label class="admin-field">
            <span>Fonction</span>
            <select name="fonction">
                <?php foreach ($fonctions as $f): ?>
                    <option value="<?= htmlspecialchars($f) ?>"
                        <?= $values['fonction'] === $f ? 'selected' : '' ?>>
                        <?= htmlspecialchars($f) ?>
                    </option>
                <?php endforeach; ?>
                <?php if (!in_array($values['fonction'], $fonctions, true) && $values['fonction'] !== ''): ?>
                    <option value="<?= htmlspecialchars($values['fonction']) ?>" selected>
                        <?= htmlspecialchars($values['fonction']) ?>
                    </option>
                <?php endif; ?>
            </select>
        </label>
    </div>

    <label class="admin-field admin-field-checkbox">
        <input type="checkbox" name="actif" <?= $values['actif'] ? 'checked' : '' ?>>
        <span>Actif (visible sur le site public)</span>
    </label>

    <div class="admin-form-actions">
        <button type="submit" class="btn admin-btn--add" style="padding:var(--sp-3) var(--sp-6);">
            <?= $isEdit ? 'Enregistrer' : 'Ajouter' ?>
        </button>
        <a href="/admin/elus.php" class="admin-form-cancel">Annuler</a>
    </div>

</form>

<?php require_once __DIR__ . '/../../includes/admin/admin-footer.php'; ?>