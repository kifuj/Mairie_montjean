<?php
define('APP_RUNNING', true);
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/function.php';
require_once __DIR__ . '/../../includes/admin/auth.php';

requireAdmin();

$id         = isset($_GET['id']) ? (int) $_GET['id'] : null;
$isEdit     = $id !== null;
$entreprise = $isEdit ? getEntrepriseById($id) : null;

if ($isEdit && !$entreprise) {
    header('Location: /admin/entreprises.php');
    exit;
}

$pageTitle = $isEdit ? "Modifier une entreprise" : "Ajouter une entreprise";
$activeNav = 'entreprises';

$error = '';

$values = [
    'nom'       => $entreprise['nom']       ?? '',
    'activite'  => $entreprise['activite']  ?? '',
    'adresse'   => $entreprise['adresse']   ?? '',
    'telephone' => $entreprise['telephone'] ?? '',
    'email'     => $entreprise['email']     ?? '',
    'site'      => $entreprise['site']      ?? '',
    'ordre'     => $entreprise['ordre']     ?? 0,
    'actif'     => $entreprise['actif']     ?? 1,
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $values = [
        'nom'       => trim((string) ($_POST['nom'] ?? '')),
        'activite'  => trim((string) ($_POST['activite'] ?? '')),
        'adresse'   => trim((string) ($_POST['adresse'] ?? '')),
        'telephone' => trim((string) ($_POST['telephone'] ?? '')),
        'email'     => trim((string) ($_POST['email'] ?? '')),
        'site'      => trim((string) ($_POST['site'] ?? '')),
        'ordre'     => (int) ($_POST['ordre'] ?? 0),
        'actif'     => isset($_POST['actif']) ? 1 : 0,
    ];

    if ($values['nom'] === '') {
        $error = "Le nom de l'entreprise est obligatoire.";
    } elseif ($values['email'] !== '' && !filter_var($values['email'], FILTER_VALIDATE_EMAIL)) {
        $error = "L'adresse email n'est pas valide.";
    } else {
        $ok = $isEdit
            ? updateEntreprise($id, $values)
            : createEntreprise($values);

        if ($ok) {
            header('Location: /admin/entreprises.php');
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

<form class="admin-form" method="post" action="/admin/entreprises-form.php<?= $isEdit ? '?id=' . (int) $id : '' ?>">

    <div class="admin-form-row admin-form-row--single">
        <label class="admin-field">
            <span>Nom de l'entreprise</span>
            <input type="text" name="nom" value="<?= htmlspecialchars($values['nom']) ?>" required>
        </label>
    </div>

    <div class="admin-form-row admin-form-row--single">
        <label class="admin-field">
            <span>Activité</span>
            <input type="text" name="activite" value="<?= htmlspecialchars($values['activite']) ?>"
                   placeholder="ex: Plombier, Boulangerie, Maçon...">
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
            <span>Email</span>
            <input type="email" name="email" value="<?= htmlspecialchars($values['email']) ?>">
        </label>
    </div>

    <div class="admin-form-row">
        <label class="admin-field">
            <span>Site web</span>
            <input type="text" name="site" value="<?= htmlspecialchars($values['site']) ?>" placeholder="ex: www.exemple.fr">
        </label>

        <label class="admin-field">
            <span>Ordre d'affichage</span>
            <input type="number" name="ordre" value="<?= (int) $values['ordre'] ?>" min="0">
        </label>
    </div>

    <label class="admin-field admin-field-checkbox">
        <input type="checkbox" name="actif" id="actif" <?= $values['actif'] ? 'checked' : '' ?>>
        <span>Entreprise active (visible sur le site public)</span>
    </label>

    <div class="admin-form-actions">
        <button type="submit" class="btn admin-btn--add" style="padding: var(--sp-3) var(--sp-6);">
            <?= $isEdit ? 'Enregistrer les modifications' : 'Créer l\'entreprise' ?>
        </button>
        <a href="/admin/entreprises.php" class="admin-form-cancel">Annuler</a>
    </div>

</form>

<?php
require_once __DIR__ . '/../../includes/admin/admin-footer.php';
?>