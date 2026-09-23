<?php
define('APP_RUNNING', true);
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/function.php';
require_once __DIR__ . '/../../includes/admin/auth.php';

requireAdmin();

$id        = isset($_GET['id']) ? (int) $_GET['id'] : null;
$isEdit    = $id !== null;
$assistant = $isEdit ? getAssistantById($id) : null;

if ($isEdit && !$assistant) {
    header('Location: /admin/assistants-maternels.php');
    exit;
}

$pageTitle = $isEdit ? "Modifier un assistant maternel" : "Ajouter un assistant maternel";
$activeNav = 'assistants';
$error     = '';

$values = [
    'nom'       => $assistant['nom']       ?? '',
    'prenom'    => $assistant['prenom']    ?? '',
    'adresse'   => $assistant['adresse']   ?? '',
    'telephone' => $assistant['telephone'] ?? '',
    'agrement'  => $assistant['agrement']  ?? 4,
    'mam'       => $assistant['mam']       ?? '',
    'ordre'     => $assistant['ordre']     ?? 0,
    'actif'     => $assistant['actif']     ?? 1,
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $values = [
        'nom'       => trim((string) ($_POST['nom'] ?? '')),
        'prenom'    => trim((string) ($_POST['prenom'] ?? '')),
        'adresse'   => trim((string) ($_POST['adresse'] ?? '')),
        'telephone' => trim((string) ($_POST['telephone'] ?? '')),
        'agrement'  => max(0, (int) ($_POST['agrement'] ?? 4)),
        'mam'       => trim((string) ($_POST['mam'] ?? '')),
        'ordre'     => (int) ($_POST['ordre'] ?? 0),
        'actif'     => isset($_POST['actif']) ? 1 : 0,
    ];

    if ($values['nom'] === '' || $values['prenom'] === '') {
        $error = "Le nom et le prénom sont obligatoires.";
    } else {
        $ok = $isEdit
            ? updateAssistant($id, $values)
            : createAssistant($values);

        if ($ok) {
            header('Location: /admin/assistants-maternels.php');
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
      action="/admin/assistants-maternels-form.php<?= $isEdit ? '?id=' . (int) $id : '' ?>">

    <div class="admin-form-row">
        <label class="admin-field">
            <span>Nom</span>
            <input type="text" name="nom" value="<?= htmlspecialchars($values['nom']) ?>" required>
        </label>
        <label class="admin-field">
            <span>Prénom</span>
            <input type="text" name="prenom" value="<?= htmlspecialchars($values['prenom']) ?>" required>
        </label>
    </div>

    <div class="admin-form-row admin-form-row--single">
        <label class="admin-field">
            <span>Adresse</span>
            <input type="text" name="adresse" value="<?= htmlspecialchars($values['adresse']) ?>">
        </label>
    </div>

    <div class="admin-form-row">
        <label class="admin-field">
            <span>Téléphone</span>
            <input type="text" name="telephone" value="<?= htmlspecialchars($values['telephone']) ?>">
        </label>
        <label class="admin-field">
            <span>Nombre de places agréées</span>
            <input type="number" name="agrement" value="<?= (int) $values['agrement'] ?>" min="0" max="20">
        </label>
    </div>

    <div class="admin-form-row">
        <label class="admin-field">
            <span>MAM (Maison d'Assistantes Maternelles, optionnel)</span>
            <input type="text" name="mam" value="<?= htmlspecialchars($values['mam']) ?>"
                   placeholder="ex: TOURNICOTI">
        </label>
        <label class="admin-field">
            <span>Ordre d'affichage</span>
            <input type="number" name="ordre" value="<?= (int) $values['ordre'] ?>" min="0">
        </label>
    </div>

    <label class="admin-field admin-field-checkbox">
        <input type="checkbox" name="actif" <?= $values['actif'] ? 'checked' : '' ?>>
        <span>Actif (visible sur le site public)</span>
    </label>

    <div class="admin-form-actions">
        <button type="submit" class="btn admin-btn--add" style="padding:var(--sp-3) var(--sp-6);">
            <?= $isEdit ? 'Enregistrer les modifications' : 'Ajouter' ?>
        </button>
        <a href="/admin/assistants-maternels.php" class="admin-form-cancel">Annuler</a>
    </div>

</form>

<?php require_once __DIR__ . '/../../includes/admin/admin-footer.php'; ?>