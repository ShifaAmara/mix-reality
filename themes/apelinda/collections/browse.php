<?php
$pageTitle = __('Browse Collections');

queue_js_file(array('minimasonry.min', 'browse'));

$layoutSetting = get_theme_option('browse_layout') ?? 'grid';
$gridState = ($layoutSetting == 'togglegrid') ? 'disabled' : '';
$listState = ($layoutSetting == 'togglelist') ? 'disabled': '';
$isGrid = (strpos($layoutSetting, 'grid') !== false) ? true : false;
$excludeTag = 'record_type';

echo head(array('title' => $pageTitle, 'bodyclass' => 'collections browse'));
?>

<h1><?php echo $pageTitle; ?> <?php echo __('(%s total)', $total_results); ?></h1>

<div class="browse-controls">

    <?php if (strpos($layoutSetting, 'toggle') !== false) : ?>
        <div class="layout-toggle">
            <button type="button" aria-label="<?php echo $this->translate('Grid'); ?>" class="grid icon-btn o-icon-grid" <?php echo $gridState; ?>></button>
            <button type="button" aria-label="<?php echo $this->translate('List'); ?>" class="list icon-btn o-icon-list" <?php echo $listState; ?>></button>
        </div>
    <?php endif; ?>

    <?php if ($total_results > 0) : ?>

        <?php
        $sortLinks[__('Title')] = 'Dublin Core,Title';
        $sortLinks[__('Date Added')] = 'added';
        ?>
        <div id="sort-links">
            <span class="sort-label"><?php echo __('Sort by: '); ?></span><?php echo browse_sort_links($sortLinks); ?>
        </div>

    <?php endif; ?>

</div>

<ul class="resources <?php echo ($isGrid) ? 'resource-grid' : 'resource-list'; ?>">

    <?php foreach (loop('collections') as $collection): ?>
        <?php echo $this->partial('collections/single.php', array('collection' => $collection, 'isGrid' => $isGrid, 'excludeTag' => $excludeTag)); ?>
    <?php endforeach; ?>

</ul>

<?php echo pagination_links(); ?>

<?php fire_plugin_hook('public_collections_browse', array('collections' => $collections, 'view' => $this)); ?>

<?php echo foot(); ?>
