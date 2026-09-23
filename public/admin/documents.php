<?php
define('APP_RUNNING', true);
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/function.php';
require_once __DIR__ . '/../../includes/admin/auth.php';

requireAdmin();

$pageTitle = "Documents (PV & Arrêtés)";
$activeNav = 'documents';

$success = '';
$error   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $id  = (int) $_POST['delete_id'];
    $doc = getDocumentById($id);

    if ($doc) {
        // Supprime le fichier PDF du disque
        $fichierPath = __DIR__ . '/../../uploads/documents/' . $doc['fichier'];
        if (is_file($fichierPath)) {
            @unlink($fichierPath);
        }

        if (deleteDocument($id)) {
            $success = 'Document supprimé avec succès.';
        } else {
            $error = "Erreur lors de la suppression du document.";
        }
    } else {
        $error = "Document introuvable.";
    }
}

$documents = getTousLesDocuments();

// Regroupement par catégorie
$groupes = [];
foreach ($documents as $d) {
    $groupes[$d['categorie']][] = $d;
}

$labelsCategories = [
    'pv'                   => 'Procès-verbaux',
    'arrete_municipal'     => 'Arrêtés municipaux',
    'arrete_prefectoral'   => 'Arrêtés préfectoraux',
    'arrete_departemental' => 'Arrêtés départementaux',
];

require_once __DIR__ . '/../../includes/admin/admin-header.php';
?>

<div class="admin-panel-header">
    <h2 style="margin:0;padding:0;border:none;">Documents officiels</h2>
    <a href="/admin/documents-form.php" class="admin-btn admin-btn--add">+ Ajouter un document</a>
</div>

<?php if ($success): ?>
    <p class="admin-alert admin-alert--success"><?= htmlspecialchars($success) ?></p>
<?php endif; ?>

<?php if ($error): ?>
    <p class="admin-alert admin-alert--error"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<?php if (empty($groupes)): ?>
    <p class="admin-panel">Aucun document enregistré.</p>
<?php endif; ?>

<?php foreach ($groupes as $categorie => $lignes): ?>

    <section class="admin-service-block">

        <h3 class="admin-service-title">
            <?= htmlspecialchars($labelsCategories[$categorie] ?? ucfirst(str_replace('_', ' ', $categorie))) ?>
            <span class="admin-service-count"><?= count($lignes) ?></span>
        </h3>

        <div class="admin-table-wrapper">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Date</th>
                        <th>Fichier</th>
                        <th>Visible</th>
                        <th>Ordre</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lignes as $d): ?>
                        <tr<?= (int) $d['visible'] === 0 ? ' style="opacity:.5;"' : '' ?>>
                            <td><?= htmlspecialchars($d['titre']) ?></td>
                            <td>
                                <?= $d['date_document']
                                    ? date('d/m/Y', strtotime($d['date_document']))
                                    : '—' ?>
                            </td>
                            <td>
                                <a href="/uploads/documents/<?= htmlspecialchars($d['fichier']) ?>"
                                   target="_blank" rel="noopener">
                                    <?= htmlspecialchars($d['fichier']) ?>
                                </a>
                            </td>
                            <td><?= (int) $d['visible'] === 1 ? 'Oui' : 'Non' ?></td>
                            <td><?= (int) $d['ordre'] ?></td>
                            <td class="admin-table-actions">
                                <a href="/admin/documents-form.php?id=<?= (int) $d['id'] ?>" class="admin-btn admin-btn--edit">
                                    Modifier
                                </a>
                                <form method="post" action="/admin/documents.php"
                                      onsubmit="return confirm('Supprimer ce document et son fichier PDF ?');" style="display:inline;">
                                    <input type="hidden" name="delete_id" value="<?= (int) $d['id'] ?>">
                                    <button type="submit" class="admin-btn admin-btn--delete">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </section>

<?php endforeach; ?>

<?php
require_once __DIR__ . '/../../includes/admin/admin-footer.php';
?>