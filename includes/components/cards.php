<?php
function renderCards(string $pageClass, array $cards): string
{
    ob_start();
    ?>
    <div class="cards <?= htmlspecialchars($pageClass) ?>__cards">

        <?php foreach ($cards as $card): ?>
            <?php
            $title   = $card['title']   ?? null;
            $lines   = is_array($card['lines']   ?? null) ? $card['lines']   : [];
            $image   = $card['image']   ?? null;
            $reseaux = is_array($card['reseaux'] ?? null) ? $card['reseaux'] : [];
            $link    = $card['link']    ?? null;

            // Si un lien est fourni, la card entière devient un <a>
            $tag   = $link ? 'a' : 'div';
            $attrs = $link
                ? ' href="' . htmlspecialchars($link) . '" target="_blank" rel="noopener"'
                : '';
            ?>

            <<?= $tag . $attrs ?> class="card <?= htmlspecialchars($pageClass) ?>__card<?= $link ? ' card--link' : '' ?>">

                <?php if (!empty($image) || !empty($title)): ?>
                    <div class="card-header <?= htmlspecialchars($pageClass) ?>__card-header">

                        <?php if (!empty($image)): ?>
                            <img src="<?= htmlspecialchars($image) ?>" alt=""
                                 class="card-image <?= htmlspecialchars($pageClass) ?>__card-image" loading="lazy">
                        <?php endif; ?>

                        <?php if (!empty($title)): ?>
                            <h3 class="card-title <?= htmlspecialchars($pageClass) ?>__card-title">
                                <?= htmlspecialchars($title) ?>
                            </h3>
                        <?php endif; ?>

                    </div>
                <?php endif; ?>

                <?php foreach ($lines as $line): ?>
                    <p class="card-text <?= htmlspecialchars($pageClass) ?>__card-text">
                        <?= htmlspecialchars((string) $line) ?>
                    </p>
                <?php endforeach; ?>

                <?php if (!empty($reseaux)): ?>
                    <table class="card-reseaux <?= htmlspecialchars($pageClass) ?>__card-reseaux">
                        <tbody>
                            <?php foreach ($reseaux as $reseau): ?>
                                <?php
                                $rlabel = (string) ($reseau['label'] ?? '');
                                $rurl   = (string) ($reseau['url']   ?? '');
                                if ($rlabel === '' || $rurl === '') continue;
                                ?>
                                <tr>
                                    <th><?= htmlspecialchars($rlabel) ?></th>
                                    <td>
                                        <a href="<?= htmlspecialchars($rurl) ?>" target="_blank" rel="noopener noreferrer">
                                            Voir <span aria-hidden="true">↗</span>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>

                <?php if ($link): ?>
                    <span class="card-dl">Télécharger le PDF ↓</span>
                <?php endif; ?>

            </<?= $tag ?>>

        <?php endforeach; ?>

    </div>
    <?php
    return ob_get_clean();
}