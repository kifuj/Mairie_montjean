<?php
function renderIntegration($pageClass, $iframes, $style = null) {
    ob_start();
?>
<div class="integration <?= htmlspecialchars($pageClass) ?>__integration">

    <?php foreach ($iframes as $src): ?>
        <iframe class="integration-iframe <?= htmlspecialchars($pageClass) ?>__iframe"
                src="<?= htmlspecialchars($src) ?>"
                <?= !empty($style) ? 'style="' . htmlspecialchars($style) . '"' : '' ?>>
        </iframe>
    <?php endforeach; ?>

</div>
<?php
    return ob_get_clean();
}