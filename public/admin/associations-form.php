<?php
define('APP_RUNNING', true);
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/function.php';
require_once __DIR__ . '/../../includes/admin/auth.php';

requireAdmin();

$id          = isset($_GET['id']) ? (int) $_GET['id'] : null;
$isEdit      = $id !== null;
$association = $isEdit ? getAssociationById($id) : null;

if ($isEdit && !$association) {
    header('Location: /admin/associations.php');
    exit;
}

$pageTitle = $isEdit ? "Modifier une association" : "Ajouter une association";
$activeNav = 'associations';

$error = '';

$values = [
    'nom'       => $association['nom']       ?? '',
    'objet'     => $association['objet']     ?? '',
    'adresse'   => $association['adresse']   ?? '',
    'telephone' => $association['telephone'] ?? '',
    'email'     => $association['email']     ?? '',
    'site'      => $association['site']      ?? '',
    'logo'      => $association['logo']      ?? '',
    'ordre'     => $association['ordre']     ?? 0,
    'actif'     => $association['actif']     ?? 1,
];

$reseaux = $isEdit ? getReseauxByAssociation($id) : [];

// Dossier de stockage des logos — DOIT être dans public/ pour être servi par le navigateur
// (public/admin/ -> ../uploads/associations = public/uploads/associations)
$logoDir = __DIR__ . '/../uploads/associations';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $values = [
        'nom'       => trim((string) ($_POST['nom'] ?? '')),
        'objet'     => trim((string) ($_POST['objet'] ?? '')),
        'adresse'   => trim((string) ($_POST['adresse'] ?? '')),
        'telephone' => trim((string) ($_POST['telephone'] ?? '')),
        'email'     => trim((string) ($_POST['email'] ?? '')),
        'site'      => trim((string) ($_POST['site'] ?? '')),
        'logo'      => $values['logo'], // conservé par défaut, ajusté plus bas
        'ordre'     => (int) ($_POST['ordre'] ?? 0),
        'actif'     => isset($_POST['actif']) ? 1 : 0,
    ];

    // Reconstruction des réseaux soumis (lignes label/url dynamiques)
    $reseauxLabels = $_POST['reseau_label'] ?? [];
    $reseauxUrls   = $_POST['reseau_url'] ?? [];
    $reseauxSoumis = [];
    foreach ($reseauxLabels as $i => $label) {
        $reseauxSoumis[] = [
            'label' => trim((string) $label),
            'url'   => trim((string) ($reseauxUrls[$i] ?? '')),
        ];
    }
    // Pour ré-affichage en cas d'erreur de validation
    $reseaux = array_filter($reseauxSoumis, fn($r) => $r['label'] !== '' || $r['url'] !== '');

    // Suppression explicite du logo existant
    if (!empty($_POST['remove_logo']) && $values['logo'] !== '') {
        $oldPath = $logoDir . '/' . $values['logo'];
        if (is_file($oldPath)) {
            @unlink($oldPath);
        }
        $values['logo'] = '';
    }

    // Upload d'un nouveau logo (remplace l'ancien le cas échéant)
    if (!empty($_FILES['logo']['name']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        $allowedExt  = ['jpg', 'jpeg', 'png', 'webp', 'svg'];
        $maxSize     = 2 * 1024 * 1024; // 2 Mo
        $originalExt = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));

        if (!in_array($originalExt, $allowedExt, true)) {
            $error = "Format de logo non autorisé (jpg, png, webp ou svg uniquement).";
        } elseif ($_FILES['logo']['size'] > $maxSize) {
            $error = "Le logo dépasse la taille maximale autorisée (2 Mo).";
        } else {
            if (!is_dir($logoDir)) {
                mkdir($logoDir, 0755, true);
            }

            // Nom de fichier unique pour éviter les collisions
            $safeBase = preg_replace('/[^a-z0-9]+/i', '-', pathinfo($_FILES['logo']['name'], PATHINFO_FILENAME));
            $filename = strtolower($safeBase) . '-' . uniqid() . '.' . $originalExt;

            if (move_uploaded_file($_FILES['logo']['tmp_name'], $logoDir . '/' . $filename)) {
                // On supprime l'ancien logo s'il existait
                if ($values['logo'] !== '') {
                    $oldPath = $logoDir . '/' . $values['logo'];
                    if (is_file($oldPath)) {
                        @unlink($oldPath);
                    }
                }
                $values['logo'] = $filename;
            } else {
                $error = "Erreur lors de l'envoi du logo.";
            }
        }
    }

    if ($error === '' && $values['nom'] === '') {
        $error = "Le nom de l'association est obligatoire.";
    } elseif ($error === '' && $values['email'] !== '' && !filter_var($values['email'], FILTER_VALIDATE_EMAIL)) {
        $error = "L'adresse email n'est pas valide.";
    } elseif ($error === '') {
        $ok = $isEdit
            ? updateAssociation($id, $values)
            : createAssociation($values);

        if ($ok) {
            $associationId = $isEdit ? $id : (int) getPdo()->lastInsertId();
            saveReseauxForAssociation($associationId, $reseauxSoumis);

            header('Location: /admin/associations.php');
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

<form class="admin-form" method="post" action="/admin/associations-form.php<?= $isEdit ? '?id=' . (int) $id : '' ?>" enctype="multipart/form-data">

    <div class="admin-form-row admin-form-row--single">
        <label class="admin-field">
            <span>Nom de l'association</span>
            <input type="text" name="nom" value="<?= htmlspecialchars($values['nom']) ?>" required>
        </label>
    </div>

    <div class="admin-form-row admin-form-row--single">
        <label class="admin-field">
            <span>Objet / Description</span>
            <textarea name="objet" rows="3"><?= htmlspecialchars($values['objet']) ?></textarea>
        </label>
    </div>

    <div class="admin-form-row admin-form-row--single">
        <label class="admin-field">
            <span>Logo (optionnel — jpg, png, webp ou svg, 2 Mo max)</span>

            <?php if ($values['logo'] !== ''): ?>
                <div class="admin-logo-preview">
                    <img src="/uploads/associations/<?= htmlspecialchars($values['logo']) ?>" alt="Logo actuel">
                    <label class="admin-logo-remove">
                        <input type="checkbox" name="remove_logo" value="1">
                        Supprimer le logo actuel
                    </label>
                </div>
            <?php endif; ?>

            <input type="file" name="logo" accept=".jpg,.jpeg,.png,.webp,.svg,image/*">
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

    <div class="admin-form-row admin-form-row--single">
        <label class="admin-field">
            <span>Réseaux & liens (Facebook, site web, Instagram...)</span>
        </label>

        <div id="reseaux-rows">
            <?php if (empty($reseaux)): ?>
                <div class="admin-reseau-row">
                    <input type="text" name="reseau_label[]" placeholder="ex: Facebook" value="">
                    <input type="url" name="reseau_url[]" placeholder="https://..." value="">
                    <button type="button" class="admin-reseau-remove" aria-label="Supprimer cette ligne">✕</button>
                </div>
            <?php else: ?>
                <?php foreach ($reseaux as $r): ?>
                    <div class="admin-reseau-row">
                        <input type="text" name="reseau_label[]" placeholder="ex: Facebook" value="<?= htmlspecialchars($r['label']) ?>">
                        <input type="url" name="reseau_url[]" placeholder="https://..." value="<?= htmlspecialchars($r['url']) ?>">
                        <button type="button" class="admin-reseau-remove" aria-label="Supprimer cette ligne">✕</button>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <button type="button" id="add-reseau-row" class="admin-btn admin-btn--edit" style="align-self:flex-start;margin-top:var(--sp-2);">
            + Ajouter un réseau
        </button>
    </div>

    <label class="admin-field admin-field-checkbox">
        <input type="checkbox" name="actif" id="actif" <?= $values['actif'] ? 'checked' : '' ?>>
        <span>Association active (visible sur le site public)</span>
    </label>

    <div class="admin-form-actions">
        <button type="submit" class="btn admin-btn--add" style="padding: var(--sp-3) var(--sp-6);">
            <?= $isEdit ? 'Enregistrer les modifications' : 'Créer l\'association' ?>
        </button>
        <a href="/admin/associations.php" class="admin-form-cancel">Annuler</a>
    </div>

</form>

<script>
(function () {
    const rowsContainer = document.getElementById('reseaux-rows');
    const addBtn = document.getElementById('add-reseau-row');

    function createRow() {
        const row = document.createElement('div');
        row.className = 'admin-reseau-row';
        row.innerHTML = `
            <input type="text" name="reseau_label[]" placeholder="ex: Facebook" value="">
            <input type="url" name="reseau_url[]" placeholder="https://..." value="">
            <button type="button" class="admin-reseau-remove" aria-label="Supprimer cette ligne">✕</button>
        `;
        return row;
    }

    addBtn.addEventListener('click', function () {
        rowsContainer.appendChild(createRow());
    });

    rowsContainer.addEventListener('click', function (e) {
        if (e.target.classList.contains('admin-reseau-remove')) {
            const rows = rowsContainer.querySelectorAll('.admin-reseau-row');
            if (rows.length > 1) {
                e.target.closest('.admin-reseau-row').remove();
            } else {
                // Garde toujours au moins une ligne, mais la vide
                e.target.closest('.admin-reseau-row').querySelectorAll('input').forEach(i => i.value = '');
            }
        }
    });
})();
</script>

<?php
require_once __DIR__ . '/../../includes/admin/admin-footer.php';
?>