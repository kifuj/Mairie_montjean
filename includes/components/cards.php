<?php
function renderCards(string $pageClass, array $cards): string
{
    ob_start();
    ?>
    <div class="cards <?= htmlspecialchars($pageClass) ?>__cards">

        <?php foreach ($cards as $card): ?>
            <?php
            $title = $card['title'] ?? null;
            $lines = is_array($card['lines'] ?? null) ? $card['lines'] : [];
            ?>

            <div class="card <?= htmlspecialchars($pageClass) ?>__card">

                <?php if (!empty($title)): ?>
                    <h3 class="card-title <?= htmlspecialchars($pageClass) ?>__card-title">
                        <?= htmlspecialchars($title) ?>
                    </h3>
                <?php endif; ?>

                <?php foreach ($lines as $line): ?>
                    <p class="card-text <?= htmlspecialchars($pageClass) ?>__card-text">
                        <?= htmlspecialchars((string) $line) ?>
                    </p>
                <?php endforeach; ?>

            </div>
        <?php endforeach; ?>

    </div>
    <?php
    return ob_get_clean();
}