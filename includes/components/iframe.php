<?php
function renderIntegration(string $pageClass, array $iframes, ?string $style = null): string
{
    ob_start();
    ?>
    <div class="integration <?= htmlspecialchars($pageClass) ?>__integration">

        <?php foreach ($iframes as $src): ?>
            <?php
            if (!is_string($src) || $src === '') {
                continue;
            }
            ?>
            <iframe
                class="integration-iframe <?= htmlspecialchars($pageClass) ?>__iframe"
                src="<?= htmlspecialchars($src) ?>"
                loading="lazy"
                referrerpolicy="strict-origin-when-cross-origin"
                allowfullscreen
                <?= $style ? 'style="' . htmlspecialchars($style) . '"' : '' ?>>
            </iframe>
        <?php endforeach; ?>

    </div>
    <?php
    return ob_get_clean();
}