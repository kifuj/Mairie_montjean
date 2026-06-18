<?php
function renderActions($pageClass, $buttons)
{
    ob_start();
    ?>
    <div class="actions <?= htmlspecialchars($pageClass) ?>__actions">

        <?php foreach ($buttons as $btn): ?>
            <a href="<?= htmlspecialchars($btn['link']) ?>" class="btn <?= htmlspecialchars($pageClass) ?>__btn">

                <?= htmlspecialchars($btn['label']) ?>
            </a>
        <?php endforeach; ?>

    </div>
    <?php
    return ob_get_clean();
}