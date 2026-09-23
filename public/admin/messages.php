<?php
define('APP_RUNNING', true);
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/function.php';
require_once __DIR__ . '/../../includes/admin/auth.php';

requireAdmin();

$pageTitle = "Messages";
$activeNav = 'messages';

$success = '';
$error   = '';

// ── Actions POST ──────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_id'])) {
        deleteMessage((int) $_POST['delete_id'])
            ? $success = 'Message supprimé.'
            : $error   = 'Erreur lors de la suppression.';
    }
    if (isset($_POST['lu_id'])) {
        marquerMessageLu((int) $_POST['lu_id']);
        // Redirect pour éviter le double-submit
        header('Location: /admin/messages.php');
        exit;
    }
}

$messages = getTousLesMessages();

require_once __DIR__ . '/../../includes/admin/admin-header.php';
?>

<div class="admin-panel-header">
    <h2 style="margin:0;padding:0;border:none;">Boîte de réception</h2>
    <?php $nonLus = countMessagesNonLus(); ?>
    <?php if ($nonLus > 0): ?>
        <span class="admin-service-count" style="font-size:var(--text-sm);padding:var(--sp-1) var(--sp-3);">
            <?= $nonLus ?> non lu<?= $nonLus > 1 ? 's' : '' ?>
        </span>
    <?php endif; ?>
</div>

<?php if ($success): ?>
    <p class="admin-alert admin-alert--success"><?= htmlspecialchars($success) ?></p>
<?php endif; ?>
<?php if ($error): ?>
    <p class="admin-alert admin-alert--error"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<?php if (empty($messages)): ?>
    <div class="admin-panel">
        <p>Aucun message reçu pour l'instant.</p>
    </div>
<?php endif; ?>

<div class="admin-messages">
    <?php foreach ($messages as $msg): ?>
        <?php $isLu = (int) $msg['lu'] === 1; ?>

        <div class="admin-message<?= $isLu ? '' : ' admin-message--unread' ?>">

            <div class="admin-message__header">

                <div class="admin-message__meta">
                    <?php if (!$isLu): ?>
                        <span class="admin-message__badge">Nouveau</span>
                    <?php endif; ?>
                    <span class="admin-message__name"><?= htmlspecialchars($msg['nom']) ?></span>
                    <span class="admin-message__email">
                        <a href="mailto:<?= htmlspecialchars($msg['email']) ?>">
                            <?= htmlspecialchars($msg['email']) ?>
                        </a>
                    </span>
                    <?php if (!empty($msg['telephone'])): ?>
                        <span class="admin-message__tel"><?= htmlspecialchars($msg['telephone']) ?></span>
                    <?php endif; ?>
                </div>

                <div class="admin-message__right">
                    <span class="admin-message__date">
                        <?= date('d/m/Y à H:i', strtotime($msg['created_at'])) ?>
                    </span>

                    <div class="admin-message__actions">
                        <?php if (!$isLu): ?>
                            <form method="post" action="/admin/messages.php" style="display:inline;">
                                <input type="hidden" name="lu_id" value="<?= (int) $msg['id'] ?>">
                                <button type="submit" class="admin-btn admin-btn--edit">Marquer comme lu</button>
                            </form>
                        <?php endif; ?>

                        <form method="post" action="/admin/messages.php"
                              onsubmit="return confirm('Supprimer ce message ?');" style="display:inline;">
                            <input type="hidden" name="delete_id" value="<?= (int) $msg['id'] ?>">
                            <button type="submit" class="admin-btn admin-btn--delete">Supprimer</button>
                        </form>
                    </div>
                </div>

            </div>

            <div class="admin-message__subject">
                <?= htmlspecialchars($msg['sujet']) ?>
            </div>

            <div class="admin-message__body">
                <?= nl2br(htmlspecialchars($msg['message'])) ?>
            </div>

            <div class="admin-message__reply">
                <a href="mailto:<?= htmlspecialchars($msg['email']) ?>?subject=Re: <?= urlencode($msg['sujet']) ?>"
                   class="admin-btn admin-btn--edit">
                    Répondre par email
                </a>
            </div>

        </div>
    <?php endforeach; ?>
</div>

<?php require_once __DIR__ . '/../../includes/admin/admin-footer.php'; ?>