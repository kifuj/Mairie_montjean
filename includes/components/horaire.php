<?php
/**
 * Affiche un planning hebdomadaire sous forme de grille jour/horaires.
 *
 * @param string $pageClass  Classe CSS de la page (pour BEM)
 * @param array  $horaires   Tableau associatif ['Lundi' => '09:00 – 12:00', ...]
 *                           tel que retourné par getHorairesMairie()
 */
function renderHoraires(string $pageClass, array $horaires): string
{
    ob_start();
    ?>
    <div class="horaires <?= htmlspecialchars($pageClass) ?>__horaires">
        <?php foreach ($horaires as $label => $plage): ?>
            <?php $ferme = strtolower(trim($plage)) === 'fermé'; ?>
            <div class="horaires-row <?= $ferme ? 'horaires-row--ferme' : '' ?>">
                <span class="horaires-label"><?= htmlspecialchars((string) $label) ?></span>
                <span class="horaires-plage"><?= htmlspecialchars((string) $plage) ?></span>
            </div>
        <?php endforeach; ?>
    </div>
    <?php
    return ob_get_clean();
}