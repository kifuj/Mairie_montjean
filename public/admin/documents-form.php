<?php
define('APP_RUNNING', true);
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/function.php';
require_once __DIR__ . '/../../includes/admin/auth.php';

requireAdmin();

$id     = isset($_GET['id']) ? (int) $_GET['id'] : null;
$isEdit = $id !== null;
$doc    = $isEdit ? getDocumentById($id) : null;

if ($isEdit && !$doc) {
    header('Location: /admin/documents.php');
    exit;
}

$pageTitle = $isEdit ? "Modifier un document" : "Ajouter un document";
$activeNav = 'documents';

$error = '';

$pdfDir = __DIR__ . '/../../uploads/documents';

$values = [
    'categorie'     => $doc['categorie']     ?? 'pv',
    'titre'         => $doc['titre']         ?? '',
    'fichier'       => $doc['fichier']       ?? '',
    'date_document' => $doc['date_document'] ?? '',
    'visible'       => $doc['visible']       ?? 1,
    'ordre'         => $doc['ordre']         ?? 0,
];

$categories = [
    'pv'                   => 'Procès-verbal',
    'arrete_municipal'     => 'Arrêté municipal',
    'arrete_prefectoral'   => 'Arrêté préfectoral',
    'arrete_departemental' => 'Arrêté départemental',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $values = [
        'categorie'     => (string) ($_POST['categorie'] ?? 'pv'),
        'titre'         => trim((string) ($_POST['titre'] ?? '')),
        'fichier'       => $values['fichier'], // conservé par défaut
        'date_document' => (string) ($_POST['date_document'] ?? ''),
        'visible'       => isset($_POST['visible']) ? 1 : 0,
        'ordre'         => (int) ($_POST['ordre'] ?? 0),
    ];

    if (!array_key_exists($values['categorie'], $categories)) {
        $values['categorie'] = 'pv';
    }

    // Upload du PDF
    if (!empty($_FILES['fichier']['name']) && $_FILES['fichier']['error'] === UPLOAD_ERR_OK) {
        $ext     = strtolower(pathinfo($_FILES['fichier']['name'], PATHINFO_EXTENSION));
        $maxSize = 10 * 1024 * 1024; // 10 Mo

        if ($ext !== 'pdf') {
            $error = "Seuls les fichiers PDF sont acceptés.";
        } elseif ($_FILES['fichier']['size'] > $maxSize) {
            $error = "Le fichier dépasse la taille maximale autorisée (10 Mo).";
        } else {
            if (!is_dir($pdfDir)) {
                mkdir($pdfDir, 0755, true);
            }

            $safeBase = preg_replace('/[^a-z0-9]+/i', '-', pathinfo($_FILES['fichier']['name'], PATHINFO_FILENAME));
            $filename = strtolower($safeBase) . '-' . uniqid() . '.pdf';

            if (move_uploaded_file($_FILES['fichier']['tmp_name'], $pdfDir . '/' . $filename)) {
                // Supprime l'ancien PDF si remplacement
                if ($values['fichier'] !== '') {
                    $oldPath = $pdfDir . '/' . $values['fichier'];
                    if (is_file($oldPath)) {
                        @unlink($oldPath);
                    }
                }
                $values['fichier'] = $filename;
            } else {
                $error = "Erreur lors de l'envoi du fichier.";
            }
        }
    }

    if ($error === '' && $values['titre'] === '') {
        $error = "Le titre est obligatoire.";
    } elseif ($error === '' && $values['fichier'] === '') {
        $error = "Un fichier PDF est obligatoire.";
    } elseif ($error === '') {
        $ok = $isEdit
            ? updateDocument($id, $values)
            : createDocument($values);

        if ($ok) {
            header('Location: /admin/documents.php');
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
      action="/admin/documents-form.php<?= $isEdit ? '?id=' . (int) $id : '' ?>"
      enctype="multipart/form-data">

    <div class="admin-form-row">
        <label class="admin-field">
            <span>Catégorie</span>
            <select name="categorie">
                <?php foreach ($categories as $slug => $label): ?>
                    <option value="<?= htmlspecialchars($slug) ?>"
                        <?= $values['categorie'] === $slug ? 'selected' : '' ?>>
                        <?= htmlspecialchars($label) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>

        <label class="admin-field">
            <span>Date du document</span>
            <input type="date" name="date_document"
                   value="<?= htmlspecialchars($values['date_document']) ?>">
        </label>
    </div>

    <div class="admin-form-row admin-form-row--single">
        <label class="admin-field">
            <span>Titre</span>
            <input type="text" name="titre" value="<?= htmlspecialchars($values['titre']) ?>"
                   placeholder="ex: Conseil municipal du 12 mars 2025" required>
        </label>
    </div>

    <div class="admin-form-row admin-form-row--single">
        <label class="admin-field">
            <span>Fichier PDF<?= $isEdit ? ' (laisser vide pour conserver l\'actuel)' : '' ?></span>

            <?php if ($isEdit && $values['fichier'] !== ''): ?>
                <div class="admin-logo-preview">
                    <span style="font-size:var(--text-sm);color:var(--clr-text-mid);">
                        Fichier actuel :
                        <a href="/uploads/documents/<?= htmlspecialchars($values['fichier']) ?>"
                           target="_blank" rel="noopener">
                            <?= htmlspecialchars($values['fichier']) ?>
                        </a>
                    </span>
                </div>
            <?php endif; ?>

            <input type="file" name="fichier" accept=".pdf,application/pdf"
                   <?= !$isEdit ? 'required' : '' ?>>
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
        <button type="submit" class="btn admin-btn--add" style="padding: var(--sp-3) var(--sp-6);">
            <?= $isEdit ? 'Enregistrer les modifications' : 'Ajouter le document' ?>
        </button>
        <a href="/admin/documents.php" class="admin-form-cancel">Annuler</a>
    </div>

</form>

<?php
require_once __DIR__ . '/../../includes/admin/admin-footer.php';
?>