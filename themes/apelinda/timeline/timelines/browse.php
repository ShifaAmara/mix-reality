<?php
/**
 * The public browse view for Timeline.
 */

queue_js_file(array('minimasonry.min', 'browse'));
 
$head = array('bodyclass' => 'timelines primary',
              'title' => html_escape(__('Browse Timelines')));
echo head($head);

$layoutSetting = get_theme_option('browse_layout') ?? 'grid';
$gridState = ($layoutSetting == 'togglegrid') ? 'disabled' : '';
$listState = ($layoutSetting == 'togglelist') ? 'disabled': '';
$isGrid = (strpos($layoutSetting, 'grid') !== false) ? true : false;
?>

<h1><?php echo __('Browse Timelines'); ?></h1>

<div class="browse-controls">

    <?php if (strpos($layoutSetting, 'toggle') !== false) : ?>
        <div class="layout-toggle">
            <button type="button" aria-label="<?php echo $this->translate('Grid'); ?>" class="grid icon-btn o-icon-grid" <?php echo $gridState; ?>></button>
            <button type="button" aria-label="<?php echo $this->translate('List'); ?>" class="list icon-btn o-icon-list" <?php echo $listState; ?>></button>
        </div>
    <?php endif; ?>

</div>

<?php if ($timelines) : ?>
    <ul class="timelines resources <?php echo ($isGrid) ? 'resource-grid' : 'resource-list'; ?>">
        <?php foreach ($timelines as $timeline): ?>
            <?php echo $this->partial('timeline/timelines/single.php', array('timeline' => $timeline, 'isGrid' => $isGrid)); ?>
        <?php endforeach; ?>
    </ul>

    <?php echo pagination_links(); ?>
<?php else: ?>
    <p><?php echo __('You have no timelines.'); ?></p>
<?php endif; ?>

<?php echo foot(); ?>
