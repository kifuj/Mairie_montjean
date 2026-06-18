<?php
function renderList($pageClass, $items) {
    ob_start();
?>
<ul class="list <?= htmlspecialchars($pageClass) ?>__list">
    <?php foreach ($items as $item): ?>
        <li class="list-item <?= htmlspecialchars($pageClass) ?>__list-item">
            <?= htmlspecialchars($item) ?>
        </li>
    <?php endforeach; ?>
</ul>
<?php
    return ob_get_clean();
}