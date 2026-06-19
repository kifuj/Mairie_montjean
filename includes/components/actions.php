<?php
function renderActions(string $pageClass, array $buttons): string
{
    ob_start();
    ?>
    <div class="actions <?= htmlspecialchars($pageClass) ?>__actions">

        <?php foreach ($buttons as $btn): ?>
            <?php
            $link = $btn['link'] ?? '#';
            $label = $btn['label'] ?? '';
            ?>
            <a href="<?= htmlspecialchars($link) ?>" class="btn <?= htmlspecialchars($pageClass) ?>__btn">
                <?= htmlspecialchars($label) ?>
            </a>
        <?php endforeach; ?>

    </div>
    <?php
    return ob_get_clean();
}