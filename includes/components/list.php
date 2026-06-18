<?php
function renderList(string $pageClass, array $items): string
{
    ob_start();
    ?>
    <ul class="list <?= htmlspecialchars($pageClass) ?>__list">

        <?php foreach ($items as $item): ?>
            <li class="list-item <?= htmlspecialchars($pageClass) ?>__list-item">
                <?= htmlspecialchars((string) $item) ?>
            </li>
        <?php endforeach; ?>

    </ul>
    <?php
    return ob_get_clean();
}