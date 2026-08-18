<?php
$title = __('Browse Exhibits');

queue_js_file(array('minimasonry.min', 'browse'));

$layoutSetting = get_theme_option('browse_layout') ?? 'grid';
$gridState = ($layoutSetting == 'togglegrid') ? 'disabled' : '';
$listState = ($layoutSetting == 'togglelist') ? 'disabled': '';
$isGrid = (strpos($layoutSetting, 'grid') !== false) ? true : false;
$excludeTag = 'record_type';

echo head(array('title' => $title, 'bodyclass' => 'exhibits browse'));
?>

<h1><?php echo $title; ?> <?php echo __('(%s total)', $total_results); ?></h1>

<?php if (count($exhibits) > 0): ?>

    <nav class="navigation secondary-nav">
        <?php echo nav(array(
            array(
                'label' => __('Browse All'),
                'uri' => url('exhibits')
            ),
            array(
                'label' => __('Browse by Tag'),
                'uri' => url('exhibits/tags')
            )
        )); ?>
        <?php echo freedom_exhibit_search_filters(); ?>
    </nav>

    <div class="browse-controls">

        <?php if (strpos($layoutSetting, 'toggle') !== false) : ?>
            <div class="layout-toggle">
                <button type="button" aria-label="<?php echo $this->translate('Grid'); ?>" class="grid icon-btn o-icon-grid" <?php echo $gridState; ?>></button>
                <button type="button" aria-label="<?php echo $this->translate('List'); ?>" class="list icon-btn o-icon-list" <?php echo $listState; ?>></button>
            </div>
        <?php endif; ?>

        <?php
        $sortLinks[__('Title')] = 'title';
        $sortLinks[__('Date Added')] = 'added';
        ?>
        <div id="sort-links">
            <span class="sort-label"><?php echo __('Sort by: '); ?></span><?php echo browse_sort_links($sortLinks); ?>
        </div>

    </div>

    <ul class="resources <?php echo ($isGrid) ? 'resource-grid' : 'resource-list'; ?>">
        <?php
        foreach (loop('exhibit') as $exhibit) {
            echo $this->partial('exhibit-builder/exhibits/single.php', array('exhibit' => $exhibit, 'isGrid' => $isGrid, 'excludeTag' => $excludeTag));
        }
        ?>
    </ul>

    <?php echo pagination_links(); ?>

<?php else: ?>
    <p><?php echo __('There are no exhibits available yet.'); ?></p>
<?php endif; ?>

<?php echo foot(); ?>
