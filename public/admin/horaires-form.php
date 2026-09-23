<?php
define('APP_RUNNING', true);
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/function.php';
require_once __DIR__ . '/../../includes/admin/auth.php';

requireAdmin();

$id = isset($_GET['id']) ? (int) $_GET['id'] : null;
$isEdit = $id !== null;
$horaire = $isEdit ? getHoraireById($id) : null;

if ($isEdit && !$horaire) {
    header('Location: /admin/horaires.php');
    exit;
}

$pageTitle = $isEdit ? "Modifier un horaire" : "Ajouter un horaire";
$activeNav = 'horaires';

$error = '';

// Valeurs par défaut / pré-remplissage
$values = [
    'service' => $horaire['service'] ?? '',
    'groupe' => $horaire['groupe'] ?? '',
    'label' => $horaire['label'] ?? '',
    'heure_debut' => isset($horaire['heure_debut'])
        ? substr((string) $horaire['heure_debut'], 0, 5)
        : '',

    'heure_fin' => isset($horaire['heure_fin'])
        ? substr((string) $horaire['heure_fin'], 0, 5)
        : '',
    'periode' => $horaire['periode'] ?? '',
    'ferme' => $horaire['ferme'] ?? 0,
    'ordre' => $horaire['ordre'] ?? 0,
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $values = [
        'service' => trim((string) ($_POST['service'] ?? '')),
        'groupe' => trim((string) ($_POST['groupe'] ?? '')),
        'label' => trim((string) ($_POST['label'] ?? '')),
        'heure_debut' => trim((string) ($_POST['heure_debut'] ?? '')),
        'heure_fin' => trim((string) ($_POST['heure_fin'] ?? '')),
        'periode' => trim((string) ($_POST['periode'] ?? '')),
        'ferme' => isset($_POST['ferme']) ? 1 : 0,
        'ordre' => (int) ($_POST['ordre'] ?? 0),
    ];

    if ($values['service'] === '' || $values['label'] === '') {
        $error = "Le service et le label sont obligatoires.";
    } elseif (!$values['ferme'] && ($values['heure_debut'] === '' || $values['heure_fin'] === '')) {
        $error = "Merci de renseigner une heure de début et de fin, ou de cocher « Fermé ».";
    } else {
        $data = [
            'service' => $values['service'],
            'groupe' => $values['groupe'],
            'label' => $values['label'],
            'heure_debut' => $values['ferme'] ? null : $values['heure_debut'],
            'heure_fin' => $values['ferme'] ? null : $values['heure_fin'],
            'periode' => $values['periode'],
            'ferme' => $values['ferme'],
            'ordre' => $values['ordre'],
        ];

        $ok = $isEdit
            ? updateHoraire($id, $data)
            : createHoraire($data);

        if ($ok) {
            header('Location: /admin/horaires.php');
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

<form class="admin-form" method="post" action="/admin/horaires-form.php<?= $isEdit ? '?id=' . (int) $id : '' ?>">

    <div class="admin-form-row">
        <label class="admin-field">
            <span>Service</span>
            <input type="text" name="service" value="<?= htmlspecialchars($values['service']) ?>"
                placeholder="ex: mairie, bibliotheque, periscolaire" required>
        </label>

        <label class="admin-field">
            <span>Groupe (optionnel)</span>
            <input type="text" name="groupe" value="<?= htmlspecialchars($values['groupe']) ?>"
                placeholder="ex: Garderie — période scolaire">
        </label>
    </div>

    <div class="admin-form-row admin-form-row--single">
        <label class="admin-field">
            <span>Label</span>
            <input type="text" name="label" value="<?= htmlspecialchars($values['label']) ?>"
                placeholder="ex: Lundi, Matin..." required>
        </label>
    </div>

    <div class="admin-form-row">
        <label class="admin-field">
            <span>Heure de début</span>
            <input type="time" name="heure_debut" value="<?= htmlspecialchars($values['heure_debut']) ?>">
        </label>

        <label class="admin-field">
            <span>Heure de fin</span>
            <input type="time" name="heure_fin" value="<?= htmlspecialchars($values['heure_fin']) ?>">
        </label>
    </div>

    <div class="admin-form-row">
        <label class="admin-field">
            <span>Période (optionnel)</span>
            <input type="text" name="periode" value="<?= htmlspecialchars($values['periode']) ?>"
                placeholder="ex: hors_vacances, vacances">
        </label>

        <label class="admin-field">
            <span>Ordre d'affichage</span>
            <input type="number" name="ordre" value="<?= (int) $values['ordre'] ?>" min="0">
        </label>
    </div>

    <label class="admin-field admin-field-checkbox">
        <input type="checkbox" name="ferme" id="ferme" <?= $values['ferme'] ? 'checked' : '' ?>>
        <span>Fermé ce jour-là (ignore les heures de début/fin)</span>
    </label>

    <div class="admin-form-actions">
        <button type="submit" class="btn admin-btn--add" style="padding: var(--sp-3) var(--sp-6);">
            <?= $isEdit ? 'Enregistrer les modifications' : 'Créer l\'horaire' ?>
        </button>
        <a href="/admin/horaires.php" class="admin-form-cancel">Annuler</a>
    </div>

</form>

<?php
require_once __DIR__ . '/../../includes/admin/admin-footer.php';
?>