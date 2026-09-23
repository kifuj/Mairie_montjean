<?php
define('APP_RUNNING', true);
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/function.php';
require_once __DIR__ . '/../../includes/admin/auth.php';

requireAdmin();

$id = isset($_GET['id']) ? (int) $_GET['id'] : null;
$isEdit = $id !== null;
$tarif = $isEdit ? getTarifById($id) : null;

if ($isEdit && !$tarif) {
    header('Location: /admin/tarifs.php');
    exit;
}

$pageTitle = $isEdit ? "Modifier un tarif" : "Ajouter un tarif";
$activeNav = 'tarifs';

$error = '';

$values = [
    'service' => $tarif['service'] ?? '',
    'groupe' => $tarif['groupe'] ?? '',
    'label' => $tarif['label'] ?? '',
    'tarif_base' => $tarif['tarif_base'] ?? '',
    'tarif_hors_commune' => $tarif['tarif_hors_commune'] ?? '',
    'unite' => $tarif['unite'] ?? '',
    'ordre' => $tarif['ordre'] ?? 0,
    'actif' => $tarif['actif'] ?? 1,
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $values = [
        'service' => trim((string) ($_POST['service'] ?? '')),
        'groupe' => trim((string) ($_POST['groupe'] ?? '')),
        'label' => trim((string) ($_POST['label'] ?? '')),
        'tarif_base' => trim((string) ($_POST['tarif_base'] ?? '')),
        'tarif_hors_commune' => trim((string) ($_POST['tarif_hors_commune'] ?? '')),
        'unite' => trim((string) ($_POST['unite'] ?? '')),
        'ordre' => (int) ($_POST['ordre'] ?? 0),
        'actif' => isset($_POST['actif']) ? 1 : 0,
    ];

    if ($values['service'] === '' || $values['label'] === '') {
        $error = "Le service et le label sont obligatoires.";
    } elseif ($values['tarif_base'] !== '' && !is_numeric($values['tarif_base'])) {
        $error = "Le tarif commune doit être un nombre.";
    } elseif ($values['tarif_hors_commune'] !== '' && !is_numeric($values['tarif_hors_commune'])) {
        $error = "Le tarif hors commune doit être un nombre.";
    } else {
        $ok = $isEdit
            ? updateTarif($id, $values)
            : createTarif($values);

        if ($ok) {
            header('Location: /admin/tarifs.php');
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

<form class="admin-form" method="post" action="/admin/tarifs-form.php<?= $isEdit ? '?id=' . (int) $id : '' ?>">

    <div class="admin-form-row">
        <label class="admin-field">
            <span>Service</span>
            <input type="text" name="service" value="<?= htmlspecialchars($values['service']) ?>"
                placeholder="ex: cimetiere, periscolaire, salle_fetes" required>
        </label>

        <label class="admin-field">
            <span>Groupe (optionnel)</span>
            <input type="text" name="groupe" value="<?= htmlspecialchars($values['groupe']) ?>"
                placeholder="ex: Columbarium, Cantine...">
        </label>
    </div>

    <div class="admin-form-row admin-form-row--single">
        <label class="admin-field">
            <span>Label</span>
            <input type="text" name="label" value="<?= htmlspecialchars($values['label']) ?>"
                placeholder="ex: Concession 30 ans, Maternel..." required>
        </label>
    </div>

    <div class="admin-form-row">
        <label class="admin-field">
            <span>Tarif commune (€)</span>
            <input type="text" name="tarif_base" value="<?= htmlspecialchars((string) $values['tarif_base']) ?>"
                placeholder="ex: 95.00" inputmode="decimal">
        </label>

        <label class="admin-field">
            <span>Tarif hors commune (€, optionnel)</span>
            <input type="text" name="tarif_hors_commune"
                value="<?= htmlspecialchars((string) $values['tarif_hors_commune']) ?>"
                placeholder="laisser vide si pas de distinction" inputmode="decimal">
        </label>
    </div>

    <div class="admin-form-row">
        <label class="admin-field">
            <span>Unité (optionnel)</span>
            <input type="text" name="unite" value="<?= htmlspecialchars($values['unite']) ?>"
                placeholder="ex: / an, le repas, la demi-journée...">
        </label>

        <label class="admin-field">
            <span>Ordre d'affichage</span>
            <input type="number" name="ordre" value="<?= (int) $values['ordre'] ?>" min="0">
        </label>
    </div>

    <label class="admin-field admin-field-checkbox">
        <input type="checkbox" name="actif" id="actif" <?= $values['actif'] ? 'checked' : '' ?>>
        <span>Tarif actif (visible sur le site public)</span>
    </label>

    <div class="admin-form-actions">
        <button type="submit" class="btn admin-btn--add" style="padding: var(--sp-3) var(--sp-6);">
            <?= $isEdit ? 'Enregistrer les modifications' : 'Créer le tarif' ?>
        </button>
        <a href="/admin/tarifs.php" class="admin-form-cancel">Annuler</a>
    </div>

</form>

<?php
require_once __DIR__ . '/../../includes/admin/admin-footer.php';
?>