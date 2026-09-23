<?php
define('APP_RUNNING', true);
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/function.php';
require_once __DIR__ . '/../../includes/admin/auth.php';

requireAdmin();

$pageTitle = "Horaires";
$activeNav = 'horaires';

$success = '';
$error   = '';

// ── Suppression ───────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $id = (int) $_POST['delete_id'];

    if (deleteHoraire($id)) {
        $success = 'Horaire supprimé avec succès.';
    } else {
        $error = "Erreur lors de la suppression de l'horaire.";
    }
}

$horaires = getTousLesHoraires();

// ── Regroupement par service ──────────────────────────────
// getTousLesHoraires() trie déjà par service puis ordre, donc
// un simple regroupement séquentiel préserve l'ordre voulu.
$groupes = [];
foreach ($horaires as $h) {
    $groupes[$h['service']][] = $h;
}

// Libellés lisibles pour les services connus (fallback : slug brut)
$labelsServices = [
    'mairie'        => 'Mairie',
    'bibliotheque'  => 'Bibliothèque',
    'periscolaire'  => 'Périscolaire',
    'dechetterie'   => 'Déchetterie',
];

require_once __DIR__ . '/../../includes/admin/admin-header.php';
?>

<div class="admin-panel-header">
    <h2 style="margin:0;padding:0;border:none;">Liste des horaires</h2>
    <a href="/admin/horaires-form.php" class="admin-btn admin-btn--add">+ Ajouter un horaire</a>
</div>

<?php if ($success): ?>
    <p class="admin-alert admin-alert--success"><?= htmlspecialchars($success) ?></p>
<?php endif; ?>

<?php if ($error): ?>
    <p class="admin-alert admin-alert--error"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<?php if (empty($groupes)): ?>
    <p class="admin-panel">Aucun horaire enregistré.</p>
<?php endif; ?>

<?php foreach ($groupes as $service => $lignes): ?>

    <section class="admin-service-block">

        <h3 class="admin-service-title">
            <?= htmlspecialchars($labelsServices[$service] ?? ucfirst($service)) ?>
            <span class="admin-service-count"><?= count($lignes) ?></span>
        </h3>

        <div class="admin-table-wrapper">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Groupe</th>
                        <th>Label</th>
                        <th>Horaires</th>
                        <th>Période</th>
                        <th>Ordre</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lignes as $h): ?>
                        <tr>
                            <td><?= htmlspecialchars($h['groupe'] ?? '—') ?></td>
                            <td><?= htmlspecialchars($h['label']) ?></td>
                            <td>
                                <?php if ((int) $h['ferme'] === 1): ?>
                                    Fermé
                                <?php else: ?>
                                    <?= htmlspecialchars(substr((string) $h['heure_debut'], 0, 5)) ?>
                                    –
                                    <?= htmlspecialchars(substr((string) $h['heure_fin'], 0, 5)) ?>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($h['periode'] ?? '—') ?></td>
                            <td><?= (int) $h['ordre'] ?></td>
                            <td class="admin-table-actions">
                                <a href="/admin/horaires-form.php?id=<?= (int) $h['id'] ?>" class="admin-btn admin-btn--edit">
                                    Modifier
                                </a>
                                <form method="post" action="/admin/horaires.php"
                                      onsubmit="return confirm('Supprimer cet horaire ?');" style="display:inline;">
                                    <input type="hidden" name="delete_id" value="<?= (int) $h['id'] ?>">
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