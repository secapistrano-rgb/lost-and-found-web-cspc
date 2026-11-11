<?= $this->extend('layout/main') ?>
<?= $this->section('title') ?>Item Management<?= $this->endSection() ?>
<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/item-management.css') ?>">
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<?php
$allItems = $allItems ?? [];
$statusLabels = [
    'active' => 'Open case', 'pending' => 'Pending review', 'inactive' => 'Archived',
    'claimed' => 'Case closed', 'returned' => 'Returned to owner', 'resolved' => 'Resolved',
    'Pending Turning-in' => 'Pending turn-in', 'Fake Report' => 'Fake report',
];
$normalizeStatus = static function ($value) {
    $value = strtolower(trim((string) $value));
    return ['pending_turnin' => 'pending turning-in', 'fake_report' => 'fake report'][$value] ?? $value;
};
$labelForStatus = static function ($value) use ($statusLabels, $normalizeStatus) {
    foreach ($statusLabels as $key => $label) {
        if ($normalizeStatus($key) === $normalizeStatus($value)) return $label;
    }
    return $value ? ucfirst(str_replace('_', ' ', $value)) : 'Not specified';
};
$resolveImages = static function ($images) {
    if (is_string($images)) {
        $decoded = json_decode($images, true);
        $images = is_array($decoded) ? $decoded : [$images];
    }
    if (!is_array($images)) return [];
    if (isset($images['uri']) || isset($images['imageUrl']) || isset($images['image_url'])) $images = [$images];
    $urls = [];
    foreach ($images as $image) {
        if (is_array($image)) $image = $image['imageUrl'] ?? $image['image_url'] ?? $image['uri'] ?? '';
        if (!is_string($image) || trim($image) === '') continue;
        $image = trim($image);
        if (preg_match('#^https?://#i', $image) && filter_var($image, FILTER_VALIDATE_URL)) $urls[] = $image;
        elseif (preg_match('#^data:image/(png|jpe?g|gif|webp);base64,[a-z0-9+/=\s]+$#i', $image)) $urls[] = $image;
        elseif (preg_match('#^(/9j/|iVBORw0KGgo|R0lGOD|UklGR)[a-z0-9+/=\s]+$#i', $image)) {
            $mime = str_starts_with($image, 'iVBOR') ? 'png' : (str_starts_with($image, 'R0lGOD') ? 'gif' : (str_starts_with($image, 'UklGR') ? 'webp' : 'jpeg'));
            $urls[] = 'data:image/' . $mime . ';base64,' . $image;
        } elseif (!str_contains($image, ':') && preg_match('/\.(png|jpe?g|gif|webp)$/i', $image)) {
            $urls[] = base_url('uploads/' . rawurlencode(basename($image)));
        }
    }
    return array_values(array_unique($urls));
};
$formatDate = static function ($value, $format = 'M j, Y') {
    $timestamp = $value ? strtotime($value) : false;
    return $timestamp === false ? 'Not recorded' : date($format, $timestamp);
};
$previewItems = [];
$counts = ['lost' => 0, 'found' => 0, 'review' => 0];
foreach ($allItems as $item) {
    $type = strtolower(trim($item['type'] ?? ''));
    if (isset($counts[$type])) $counts[$type]++;
    if (in_array($normalizeStatus($item['status'] ?? ''), ['active', 'pending', 'pending turning-in'], true)) $counts['review']++;
    $previewItems[] = [
        'title' => $item['title'] ?? 'Untitled item', 'id' => (string) $item['id'],
        'type' => $type, 'status' => $labelForStatus($item['status'] ?? ''),
        'category' => ($item['category'] ?? '') ?: 'Uncategorized', 'location' => ($item['location'] ?? '') ?: 'Not recorded',
        'description' => $item['description'] ?? '', 'reporter' => $item['reporter_name'] ?? '',
        'reported' => $formatDate($item['created_at'] ?? null, 'M j, Y · g:i A'),
        'date' => $formatDate($item['date_lost_found'] ?? $item['created_at'] ?? null),
        'claims' => (int) ($item['claim_count'] ?? 0), 'images' => $resolveImages($item['images'] ?? []),
        'viewUrl' => site_url('items/view/' . rawurlencode($item['id'])),
        'editUrl' => site_url('items/edit/' . rawurlencode($item['id'])),
    ];
}
$categoryOptions = array_values(array_unique(array_filter(array_merge($categories ?? [], array_column($allItems, 'category')))));
sort($categoryOptions, SORT_NATURAL | SORT_FLAG_CASE);
$hasFilters = (string) ($search ?? '') !== '' || !empty($selectedCategory) || !empty($selectedStatus) || !empty($selectedType);
$pageSize = in_array((int) ($per_page ?? 50), [25, 50, 75, 100], true) ? (int) ($per_page ?? 50) : 50;
$total = isset($pager) ? $pager->getTotal('items') : count($allItems);
$currentPage = isset($pager) ? $pager->getCurrentPage('items') : 1;
$first = count($allItems) ? ($currentPage - 1) * $pageSize + 1 : 0;
?>
<div class="im-page">
    <header class="im-heading">
        <div><p class="im-eyebrow">CAMPUS LOST &amp; FOUND</p><h1>Item Management</h1><p class="im-subtitle">Keep every report in view. Help belongings find their way home.</p></div>
        <a class="im-button im-button-white" href="<?= site_url('reports') ?>"><i class="bi bi-bar-chart-line" aria-hidden="true"></i> View reports</a>
    </header>
    <section class="im-stats" aria-label="Item counts on this page">
        <div class="im-stat"><span class="im-stat-icon im-blue"><i class="bi bi-box-seam" aria-hidden="true"></i></span><div><span class="im-stat-label">Items on this page</span><strong><?= count($allItems) ?></strong></div></div>
        <div class="im-stat"><span class="im-stat-icon im-red"><i class="bi bi-search" aria-hidden="true"></i></span><div><span class="im-stat-label">Reported lost</span><strong><?= $counts['lost'] ?></strong></div></div>
        <div class="im-stat"><span class="im-stat-icon im-green"><i class="bi bi-check2-circle" aria-hidden="true"></i></span><div><span class="im-stat-label">Reported found</span><strong><?= $counts['found'] ?></strong></div></div>
        <div class="im-stat"><span class="im-stat-icon im-amber"><i class="bi bi-clock-history" aria-hidden="true"></i></span><div><span class="im-stat-label">Awaiting action</span><strong><?= $counts['review'] ?></strong></div></div>
    </section>
    <section class="im-panel im-filters" aria-label="Filter item reports">
        <form id="item-filters" action="<?= site_url('items') ?>" method="get">
            <div class="im-filter-grid">
                <div class="im-search-field"><label for="im-search">Search reports</label><div class="im-search"><i class="bi bi-search" aria-hidden="true"></i><input id="im-search" name="search" type="search" value="<?= esc($search ?? '') ?>" placeholder="Title, description, location, or reporter…"></div></div>
                <div><label for="im-type">Report type</label><select id="im-type" name="type"><option value="">All types</option><?php foreach (['lost' => 'Lost items', 'found' => 'Found items'] as $value => $label): ?><option value="<?= $value ?>" <?= ($selectedType ?? '') === $value ? 'selected' : '' ?>><?= $label ?></option><?php endforeach; ?></select></div>
                <div><label for="im-category">Category</label><select id="im-category" name="category"><option value="">All categories</option><?php foreach ($categoryOptions as $category): ?><option value="<?= esc($category) ?>" <?= ($selectedCategory ?? '') === $category ? 'selected' : '' ?>><?= esc(ucfirst($category)) ?></option><?php endforeach; ?></select></div>
                <div><label for="im-status">Case status</label><select id="im-status" name="status"><option value="">All statuses</option><?php foreach ($statusLabels as $value => $label): ?><option value="<?= esc($value) ?>" <?= $normalizeStatus($selectedStatus ?? '') === $normalizeStatus($value) ? 'selected' : '' ?>><?= esc($label) ?></option><?php endforeach; ?></select></div>
            </div>
            <div class="im-filter-footer"><span><i class="bi bi-sliders" aria-hidden="true"></i> Filters search across all reports.</span><div class="im-filter-actions"><label class="im-page-size" for="im-per-page">Show <select id="im-per-page" name="per_page"><?php foreach ([25, 50, 75, 100] as $size): ?><option value="<?= $size ?>" <?= $pageSize === $size ? 'selected' : '' ?>><?= $size ?></option><?php endforeach; ?></select> per page</label><a class="im-reset" href="<?= site_url('items') ?>">Reset</a><button class="im-button im-button-primary" type="submit">Apply filters <i class="bi bi-arrow-right" aria-hidden="true"></i></button></div></div>
        </form>
    </section>
    <section class="im-panel im-records" aria-labelledby="im-records-title">
        <div class="im-records-heading"><div><h2 id="im-records-title"><?= $hasFilters ? 'Filtered reports' : 'All item reports' ?> <span class="im-count"><?= (int) $total ?></span></h2><p>Review a report or open a quick preview without leaving the list.</p></div><span class="im-sort"><i class="bi bi-sort-down" aria-hidden="true"></i> Newest first</span></div>
        <?php if (!$allItems): ?>
            <div class="im-empty"><span class="im-stat-icon im-blue"><i class="bi bi-search" aria-hidden="true"></i></span><h3><?= $hasFilters ? 'No reports match your filters' : 'No item reports yet' ?></h3><p><?= $hasFilters ? 'Try a different search or reset the filters to see all reports.' : 'Lost and found reports will appear here when they are submitted.' ?></p><?php if ($hasFilters): ?><a class="im-button im-button-white" href="<?= site_url('items') ?>">Reset filters</a><?php endif; ?></div>
        <?php else: ?>
            <div class="im-table-wrap"><table class="im-table"><caption class="visually-hidden">Lost and found item reports, newest first</caption><thead><tr><th scope="col">Item</th><th scope="col">Type</th><th scope="col">Location &amp; date</th><th scope="col">Status</th><th scope="col" class="im-actions-heading">Actions</th></tr></thead><tbody>
            <?php foreach ($previewItems as $index => $record):
                $status = $normalizeStatus($allItems[$index]['status'] ?? '');
                $statusTone = in_array($status, ['claimed', 'returned', 'resolved'], true) ? 'green' : (in_array($status, ['active', 'pending', 'pending turning-in'], true) ? 'amber' : ($status === 'fake report' ? 'red' : 'neutral'));
            ?>
                <tr>
                    <td data-label="Item"><div class="im-item"><button type="button" class="im-thumbnail" data-preview-index="<?= $index ?>" aria-label="Preview <?= esc($record['title']) ?>"><?php if ($record['images']): ?><img src="<?= esc($record['images'][0]) ?>" alt="" loading="lazy"><?php endif; ?><i class="bi bi-image" aria-hidden="true"></i></button><div class="im-item-copy"><a class="im-item-title" href="<?= esc($record['viewUrl']) ?>"><?= esc($record['title']) ?></a><span><?= esc(ucfirst($record['category'])) ?></span><small title="<?= esc($record['id']) ?>">#<?= esc($record['id']) ?></small></div></div></td>
                    <td data-label="Type"><span class="im-badge <?= $record['type'] === 'lost' ? 'im-red' : ($record['type'] === 'found' ? 'im-green' : 'im-neutral') ?>"><i class="bi <?= $record['type'] === 'lost' ? 'bi-search' : 'bi-check2-circle' ?>" aria-hidden="true"></i><?= esc(ucfirst($record['type'] ?: 'Unknown')) ?></span></td>
                    <td data-label="Location & date"><span class="im-location"><i class="bi bi-geo-alt" aria-hidden="true"></i><?= esc($record['location']) ?></span><span class="im-date"><?= esc($record['date']) ?></span></td>
                    <td data-label="Status"><span class="im-badge im-<?= $statusTone ?>"><span class="im-status-dot" aria-hidden="true"></span><?= esc($record['status']) ?></span><?php if ($record['claims']): ?><span class="im-claim-count"><?= $record['claims'] ?> <?= $record['claims'] === 1 ? 'claim' : 'claims' ?> linked</span><?php endif; ?></td>
                    <td data-label="Actions"><div class="im-row-actions"><button type="button" class="im-preview-button" data-preview-index="<?= $index ?>"><i class="bi bi-eye" aria-hidden="true"></i> Preview</button><a class="im-icon-button" href="<?= esc($record['editUrl']) ?>" aria-label="Edit <?= esc($record['title']) ?>" title="Edit report"><i class="bi bi-pencil" aria-hidden="true"></i></a><form class="im-delete-form" method="post" action="<?= site_url('items/delete/' . rawurlencode($record['id'])) ?>" data-item-title="<?= esc($record['title']) ?>"><?= csrf_field() ?><input type="hidden" name="_method" value="DELETE"><button class="im-icon-button im-delete-button" type="submit" aria-label="Delete <?= esc($record['title']) ?>" title="Delete report"><i class="bi bi-trash3" aria-hidden="true"></i></button></form></div></td>
                </tr>
            <?php endforeach; ?>
            </tbody></table></div>
        <?php endif; ?>
        <footer class="im-table-footer"><span><?= $total ? 'Showing ' . $first . '–' . ($first + count($allItems) - 1) . ' of ' . (int) $total . ' reports' : '0 reports' ?></span><?php if (isset($pager) && $pager->getPageCount('items') > 1): ?><div class="im-pagination"><?= $pager->links('items', 'default_full') ?></div><?php else: ?><span>End of results</span><?php endif; ?></footer>
    </section>
    <dialog id="item-preview" class="im-preview" aria-labelledby="im-preview-title">
        <div class="im-preview-heading"><span class="im-eyebrow">ITEM PREVIEW</span><button class="im-icon-button" type="button" data-close-preview aria-label="Close preview"><i class="bi bi-x-lg" aria-hidden="true"></i></button></div>
        <div class="im-preview-image"><img id="im-preview-image" alt="" hidden><div id="im-preview-placeholder"><i class="bi bi-image" aria-hidden="true"></i><span>No photo available</span></div><div id="im-image-controls" class="im-image-controls" hidden><button type="button" class="im-icon-button" id="im-image-previous" aria-label="Previous photo"><i class="bi bi-chevron-left" aria-hidden="true"></i></button><span id="im-image-count"></span><button type="button" class="im-icon-button" id="im-image-next" aria-label="Next photo"><i class="bi bi-chevron-right" aria-hidden="true"></i></button></div></div>
        <div class="im-preview-content"><div class="im-preview-badges"><span id="im-preview-type" class="im-badge"></span><span id="im-preview-status" class="im-badge im-neutral"></span></div><h2 id="im-preview-title"></h2><p id="im-preview-id" class="im-preview-id"></p><dl class="im-preview-facts"><div><dt>Category</dt><dd id="im-preview-category"></dd></div><div><dt>Location</dt><dd id="im-preview-location"></dd></div><div><dt>Date lost / found</dt><dd id="im-preview-date"></dd></div><div><dt>Linked claims</dt><dd id="im-preview-claims"></dd></div></dl><h3>Description</h3><p id="im-preview-description"></p><div class="im-preview-reporter"><i class="bi bi-person-circle" aria-hidden="true"></i><div><strong id="im-preview-reporter"></strong><span id="im-preview-reported"></span></div></div></div>
        <div class="im-preview-footer"><button type="button" class="im-button im-button-white" data-close-preview>Close</button><a id="im-preview-details" class="im-button im-button-primary">Open full details <i class="bi bi-arrow-up-right" aria-hidden="true"></i></a></div>
    </dialog>
    <script type="application/json" id="item-preview-data"><?= json_encode($previewItems, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_INVALID_UTF8_SUBSTITUTE) ?></script>
</div>
<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/js/item-management.js') ?>"></script>
<?= $this->endSection() ?>
