<?php
function renderSection($pageClass, $id, $title, $text = '', $extraContent = '') {
?>
<section id="<?= htmlspecialchars($id) ?>" class="section <?= htmlspecialchars($pageClass) ?>">

    <div class="section-content <?= htmlspecialchars($pageClass) ?>__content">

        <h2 class="section-title <?= htmlspecialchars($pageClass) ?>__title">
            <?= htmlspecialchars($title) ?>
        </h2>

        <?php if ($text): ?>
            <p class="section-text <?= htmlspecialchars($pageClass) ?>__text">
                <?= htmlspecialchars($text) ?>
            </p>
        <?php endif; ?>

        <?= $extraContent ?>

    </div>

</section>
<?php
}