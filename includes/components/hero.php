<?php
function renderHero($pageClass, $title, $text, $image = null) {
?>
<section class="hero <?= htmlspecialchars($pageClass) ?>">

    <?php if ($image): ?>
        <img src="<?= htmlspecialchars($image) ?>" alt="bannière">
    <?php endif; ?>

    <div class="hero-content <?= htmlspecialchars($pageClass) ?>__hero-content">
        <h1 class="hero-title"><?= htmlspecialchars($title) ?></h1>
        <p class="hero-text"><?= htmlspecialchars($text) ?></p>
    </div>

</section>
<?php
}