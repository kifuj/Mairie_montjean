<?php
define('APP_RUNNING', true);
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/function.php';
require_once __DIR__ . '/../../includes/admin/auth.php';

requireAdmin();

$id     = isset($_GET['id']) ? (int) $_GET['id'] : null;
$isEdit = $id !== null;
$doc    = $isEdit ? getDocumentById($id) : null;

// Sécurité : on ne peut éditer qu'un bulletin
if ($isEdit && (!$doc || $doc['categorie'] !== 'bulletin')) {
    header('Location: /admin/bulletins.php');
    exit;
}

$pageTitle = $isEdit ? "Modifier un bulletin" : "Ajouter un bulletin";
$activeNav = 'bulletins';
$error     = '';

$pdfDir = __DIR__ . '/../../uploads/documents';

$values = [
    'titre'         => $doc['titre']         ?? '',
    'fichier'       => $doc['fichier']        ?? '',
    'date_document' => $doc['date_document']  ?? '',
    'visible'       => $doc['visible']        ?? 1,
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $values = [
        'titre'         => trim((string) ($_POST['titre'] ?? '')),
        'fichier'       => $values['fichier'],
        'date_document' => (string) ($_POST['date_document'] ?? ''),
        'visible'       => isset($_POST['visible']) ? 1 : 0,
    ];

    // Upload PDF
    if (!empty($_FILES['fichier']['name']) && $_FILES['fichier']['error'] === UPLOAD_ERR_OK) {
        $ext     = strtolower(pathinfo($_FILES['fichier']['name'], PATHINFO_EXTENSION));
        $maxSize = 20 * 1024 * 1024; // 20 Mo

        if ($ext !== 'pdf') {
            $error = "Seuls les fichiers PDF sont acceptés.";
        } elseif ($_FILES['fichier']['size'] > $maxSize) {
            $error = "Le fichier dépasse la taille maximale (20 Mo).";
        } else {
            if (!is_dir($pdfDir)) mkdir($pdfDir, 0755, true);

            $safe     = preg_replace('/[^a-z0-9]+/i', '-', pathinfo($_FILES['fichier']['name'], PATHINFO_FILENAME));
            $filename = 'bulletin-' . strtolower($safe) . '-' . uniqid() . '.pdf';

            if (move_uploaded_file($_FILES['fichier']['tmp_name'], $pdfDir . '/' . $filename)) {
                // Supprime l'ancien
                if ($values['fichier'] !== '') {
                    $old = $pdfDir . '/' . $values['fichier'];
                    if (is_file($old)) @unlink($old);
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
        $data = [
            'categorie'     => 'bulletin',
            'titre'         => $values['titre'],
            'fichier'       => $values['fichier'],
            'date_document' => $values['date_document'],
            'visible'       => $values['visible'],
            'ordre'         => 0,
        ];

        $ok = $isEdit ? updateDocument($id, $data) : createDocument($data);

        if ($ok) {
            header('Location: /admin/bulletins.php');
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
      action="/admin/bulletins-form.php<?= $isEdit ? '?id=' . (int) $id : '' ?>"
      enctype="multipart/form-data">

    <div class="admin-form-row admin-form-row--single">
        <label class="admin-field">
            <span>Titre du bulletin</span>
            <input type="text" name="titre" value="<?= htmlspecialchars($values['titre']) ?>"
                   placeholder="ex: Bulletin municipal n°42 — Printemps 2026" required>
        </label>
    </div>

    <div class="admin-form-row">
        <label class="admin-field">
            <span>Date de parution</span>
            <input type="date" name="date_document"
                   value="<?= htmlspecialchars($values['date_document']) ?>">
        </label>

        <div class="admin-field" style="justify-content:flex-end;">
            <label class="admin-field-checkbox">
                <input type="checkbox" name="visible" <?= $values['visible'] ? 'checked' : '' ?>>
                <span>Visible sur le site public</span>
            </label>
        </div>
    </div>

    <div class="admin-form-row admin-form-row--single">
        <label class="admin-field">
            <span>Fichier PDF du bulletin<?= $isEdit ? ' (laisser vide pour conserver l\'actuel)' : '' ?> — 20 Mo max</span>

            <?php if ($isEdit && $values['fichier'] !== ''): ?>
                <div class="admin-logo-preview">
                    <span style="font-size:var(--text-sm);color:var(--clr-text-mid);">
                        Actuel :
                        <a href="/uploads/documents/<?= htmlspecialchars($values['fichier']) ?>"
                           target="_blank"><?= htmlspecialchars($values['fichier']) ?></a>
                    </span>
                </div>
            <?php endif; ?>

            <input type="file" name="fichier" accept=".pdf,application/pdf"
                   <?= !$isEdit ? 'required' : '' ?>>
        </label>
    </div>

    <div class="admin-form-actions">
        <button type="submit" class="btn admin-btn--add" style="padding:var(--sp-3) var(--sp-6);">
            <?= $isEdit ? 'Enregistrer les modifications' : 'Ajouter le bulletin' ?>
        </button>
        <a href="/admin/bulletins.php" class="admin-form-cancel">Annuler</a>
    </div>

</form>

<?php require_once __DIR__ . '/../../includes/admin/admin-footer.php'; ?>