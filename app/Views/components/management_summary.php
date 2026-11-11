<section class="mg-summary" aria-label="Summary of records on this page">
    <?php foreach ($summaryCards as $card): ?>
        <div class="mg-summary-card">
            <span class="mg-summary-icon mg-tone-<?= esc($card['tone'] ?? 'blue') ?>"><i class="bi <?= esc($card['icon']) ?>" aria-hidden="true"></i></span>
            <div><span class="mg-summary-label"><?= esc($card['label']) ?></span><strong><?= (int) $card['value'] ?></strong></div>
        </div>
    <?php endforeach; ?>
</section>
