<?php
define('APP_RUNNING', true);
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/function.php';
require_once __DIR__ . '/../../includes/admin/auth.php';

requireAdmin();

$id     = isset($_GET['id']) ? (int) $_GET['id'] : null;
$isEdit = $id !== null;
$lien   = $isEdit ? getLienById($id) : null;

if ($isEdit && !$lien) {
    header('Location: /admin/liens.php');
    exit;
}

$pageTitle = $isEdit ? "Modifier un lien" : "Ajouter un lien";
$activeNav = 'liens';
$error     = '';

$pdfDir = __DIR__ . '/../../uploads/demarche';

$pagesDisponibles = [
    'argent-de-poche'     => 'Argent de poche',
    'salle-des-fetes'     => 'Salle des fêtes',
    'periscolaire'        => 'Périscolaire',
    'recensement-citoyen' => 'Recensement citoyen',
    'urbanisme'           => 'Urbanisme',
];

$values = [
    'page'    => $lien['page']    ?? array_key_first($pagesDisponibles),
    'label'   => $lien['label']   ?? '',
    'type'    => $lien['type']    ?? 'fichier',
    'valeur'  => $lien['valeur']  ?? '',
    'visible' => $lien['visible'] ?? 1,
    'ordre'   => $lien['ordre']   ?? 0,
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $values = [
        'page'    => (string) ($_POST['page'] ?? ''),
        'label'   => trim((string) ($_POST['label'] ?? '')),
        'type'    => in_array($_POST['type'] ?? '', ['fichier', 'externe']) ? $_POST['type'] : 'externe',
        'valeur'  => $values['valeur'], // conservé par défaut
        'visible' => isset($_POST['visible']) ? 1 : 0,
        'ordre'   => (int) ($_POST['ordre'] ?? 0),
    ];

    if (!array_key_exists($values['page'], $pagesDisponibles)) {
        $error = "Page invalide.";
    }

    // Type externe : on prend directement l'URL
    if ($error === '' && $values['type'] === 'externe') {
        $url = trim((string) ($_POST['url_externe'] ?? ''));
        if ($url === '') {
            $error = "L'URL est obligatoire pour un lien externe.";
        } else {
            $values['valeur'] = $url;
        }
    }

    // Type fichier : upload PDF
    if ($error === '' && $values['type'] === 'fichier') {
        if (!empty($_FILES['fichier']['name']) && $_FILES['fichier']['error'] === UPLOAD_ERR_OK) {
            $ext     = strtolower(pathinfo($_FILES['fichier']['name'], PATHINFO_EXTENSION));
            $maxSize = 10 * 1024 * 1024;

            if ($ext !== 'pdf') {
                $error = "Seuls les fichiers PDF sont acceptés.";
            } elseif ($_FILES['fichier']['size'] > $maxSize) {
                $error = "Le fichier dépasse 10 Mo.";
            } else {
                $subDir = $pdfDir . '/' . $values['page'];
                if (!is_dir($subDir)) {
                    mkdir($subDir, 0755, true);
                }

                $safe     = preg_replace('/[^a-z0-9]+/i', '-', pathinfo($_FILES['fichier']['name'], PATHINFO_FILENAME));
                $filename = strtolower($safe) . '-' . uniqid() . '.pdf';

                if (move_uploaded_file($_FILES['fichier']['tmp_name'], $subDir . '/' . $filename)) {
                    // Supprime l'ancien fichier si remplacement
                    if ($values['valeur'] !== '' && $lien && $lien['type'] === 'fichier') {
                        $old = $pdfDir . '/' . $values['valeur'];
                        if (is_file($old)) @unlink($old);
                    }
                    $values['valeur'] = $values['page'] . '/' . $filename;
                } else {
                    $error = "Erreur lors de l'envoi du fichier.";
                }
            }
        } elseif ($values['valeur'] === '') {
            $error = "Un fichier PDF est obligatoire.";
        }
    }

    if ($error === '' && $values['label'] === '') {
        $error = "Le label du bouton est obligatoire.";
    }

    if ($error === '') {
        $ok = $isEdit ? updateLien($id, $values) : createLien($values);

        if ($ok) {
            header('Location: /admin/liens.php');
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
      action="/admin/liens-form.php<?= $isEdit ? '?id=' . (int) $id : '' ?>"
      enctype="multipart/form-data">

    <div class="admin-form-row">
        <label class="admin-field">
            <span>Page concernée</span>
            <select name="page">
                <?php foreach ($pagesDisponibles as $slug => $label): ?>
                    <option value="<?= htmlspecialchars($slug) ?>"
                        <?= $values['page'] === $slug ? 'selected' : '' ?>>
                        <?= htmlspecialchars($label) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>

        <label class="admin-field">
            <span>Label du bouton</span>
            <input type="text" name="label" value="<?= htmlspecialchars($values['label']) ?>"
                   placeholder="ex: Télécharger le contrat" required>
        </label>
    </div>

    <div class="admin-form-row admin-form-row--single">
        <label class="admin-field">
            <span>Type de lien</span>
            <div class="admin-type-choice">
                <label class="admin-type-option">
                    <input type="radio" name="type" value="fichier"
                           <?= $values['type'] === 'fichier' ? 'checked' : '' ?> id="type-fichier">
                    Fichier PDF à uploader
                </label>
                <label class="admin-type-option">
                    <input type="radio" name="type" value="externe"
                           <?= $values['type'] === 'externe' ? 'checked' : '' ?> id="type-externe">
                    URL externe
                </label>
            </div>
        </label>
    </div>

    <!-- Zone fichier -->
    <div class="admin-form-row admin-form-row--single" id="zone-fichier"
         <?= $values['type'] === 'externe' ? 'style="display:none"' : '' ?>>
        <label class="admin-field">
            <span>Fichier PDF<?= $isEdit && $values['type'] === 'fichier' ? ' (laisser vide pour conserver l\'actuel)' : '' ?></span>
            <?php if ($isEdit && $values['type'] === 'fichier' && $values['valeur'] !== ''): ?>
                <div class="admin-logo-preview">
                    <span style="font-size:var(--text-sm);color:var(--clr-text-mid);">
                        Actuel :
                        <a href="/uploads/demarche/<?= htmlspecialchars($values['valeur']) ?>"
                           target="_blank"><?= htmlspecialchars($values['valeur']) ?></a>
                    </span>
                </div>
            <?php endif; ?>
            <input type="file" name="fichier" accept=".pdf,application/pdf">
        </label>
    </div>

    <!-- Zone URL externe -->
    <div class="admin-form-row admin-form-row--single" id="zone-externe"
         <?= $values['type'] === 'fichier' ? 'style="display:none"' : '' ?>>
        <label class="admin-field">
            <span>URL</span>
            <input type="text" name="url_externe"
                   value="<?= $values['type'] === 'externe' ? htmlspecialchars($values['valeur']) : '' ?>"
                   placeholder="https://...">
        </label>
    </div>

    <div class="admin-form-row">
        <label class="admin-field">
            <span>Ordre d'affichage</span>
            <input type="number" name="ordre" value="<?= (int) $values['ordre'] ?>" min="0">
        </label>
        <div class="admin-field" style="justify-content:flex-end;">
            <label class="admin-field-checkbox">
                <input type="checkbox" name="visible" <?= $values['visible'] ? 'checked' : '' ?>>
                <span>Visible sur le site public</span>
            </label>
        </div>
    </div>

    <div class="admin-form-actions">
        <button type="submit" class="btn admin-btn--add" style="padding:var(--sp-3) var(--sp-6);">
            <?= $isEdit ? 'Enregistrer' : 'Créer le lien' ?>
        </button>
        <a href="/admin/liens.php" class="admin-form-cancel">Annuler</a>
    </div>

</form>

<script>
document.querySelectorAll('input[name="type"]').forEach(function(radio) {
    radio.addEventListener('change', function() {
        document.getElementById('zone-fichier').style.display = this.value === 'fichier' ? '' : 'none';
        document.getElementById('zone-externe').style.display = this.value === 'externe' ? '' : 'none';
    });
});
</script>

<?php require_once __DIR__ . '/../../includes/admin/admin-footer.php'; ?>