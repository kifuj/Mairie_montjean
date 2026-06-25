<?php
function renderTable(string $pageClass, array $headers, array $rows): string
{
    ob_start();
    ?>
    <div class="table-wrapper <?= htmlspecialchars($pageClass) ?>__table-wrapper">
        <table class="table <?= htmlspecialchars($pageClass) ?>__table">

            <thead class="table-head <?= htmlspecialchars($pageClass) ?>__table-head">
                <tr>
                    <?php foreach ($headers as $header): ?>
                        <th class="table-th <?= htmlspecialchars($pageClass) ?>__table-th">
                            <?= htmlspecialchars($header) ?>
                        </th>
                    <?php endforeach; ?>
                </tr>
            </thead>

            <tbody class="table-body <?= htmlspecialchars($pageClass) ?>__table-body">
                <?php foreach ($rows as $row): ?>

                    <?php if (isset($row['group'])): ?>
                        <tr>
                            <td colspan="<?= count($headers) ?>" class="table-group <?= htmlspecialchars($pageClass) ?>__table-group">
                                <?= htmlspecialchars($row['group']) ?>
                            </td>
                        </tr>
                    <?php else: ?>
                        <tr class="table-row <?= htmlspecialchars($pageClass) ?>__table-row">
                            <?php foreach ($row as $cell): ?>
                                <td class="table-td <?= htmlspecialchars($pageClass) ?>__table-td">
                                    <?= htmlspecialchars((string) $cell) ?>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endif; ?>

                <?php endforeach; ?>
            </tbody>

        </table>
    </div>
    <?php
    return ob_get_clean();
}