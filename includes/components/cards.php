<?php
function renderCards($pageClass, $cards) {
    ob_start();
?>
<div class="cards <?= htmlspecialchars($pageClass) ?>__cards">

    <?php foreach ($cards as $card): ?>
        <div class="card <?= htmlspecialchars($pageClass) ?>__card">

            <?php if (!empty($card['title'])): ?>
                <h3 class="card-title <?= htmlspecialchars($pageClass) ?>__card-title">
                    <?= htmlspecialchars($card['title']) ?>
                </h3>
            <?php endif; ?>

            <?php foreach ($card['lines'] ?? [] as $line): ?>
                <p class="card-text <?= htmlspecialchars($pageClass) ?>__card-text">
                    <?= htmlspecialchars($line) ?>
                </p>
            <?php endforeach; ?>

        </div>
    <?php endforeach; ?>

</div>
<?php
    return ob_get_clean();
}