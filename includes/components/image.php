<?php
function renderImage(string $pageClass, string $src, string $alt, ?string $class = null, bool $lazy = true): string
{
    ob_start();
    ?>
    <img
        src="<?= htmlspecialchars($src) ?>"
        alt="<?= htmlspecialchars($alt) ?>"
        class="image <?= htmlspecialchars($pageClass) ?>__image<?= $class ? ' ' . htmlspecialchars($class) : '' ?>"
        <?= $lazy ? 'loading="lazy"' : '' ?>>
    <?php
    return ob_get_clean();
}