<?php
$collectionTitle = metadata('collection', 'display_title');
$totalItems = metadata('collection', 'total_items');
?>

<?php echo head(array('title' => $collectionTitle, 'bodyclass' => 'collections show')); ?>

<?php echo freedom_record_tags($collection); ?>
<h1><?php echo metadata('collection', 'rich_title', array('no_escape' => true)); ?></h1>

<div class="regions-container">
    <div class="sidebar-region sidebar-region--left">
        <div class="metadata">
            <?php echo record_image($collection, 'fullsize'); ?>
        </div>
    </div>

    <div class="main-region">
        <div class="metadata">
            <?php echo all_element_texts('collection'); ?>

            <div class="element-set">

                <h2><?php echo __('Collection Items'); ?></h2>

                <?php if ($totalItems > 0) : ?>
                    <ul class="resources resource-list">
                        <?php foreach (loop('items') as $item): ?>
                            <?php echo $this->partial('items/single.php', array('item' => $item, 'isGrid' => false)); ?>
                        <?php endforeach; ?>
                    </ul>
                    <?php echo link_to_items_browse(__(plural('View item', 'View all %s items', $totalItems), $totalItems), array('collection' => metadata('collection', 'id')), array('class' => 'view-items-link')); ?>
                <?php else: ?>
                    <p><?php echo __("There are currently no items within this collection."); ?></p>
                <?php endif; ?>

            </div>

            <?php fire_plugin_hook('public_collections_show', array('view' => $this, 'collection' => $collection)); ?>
        </div>
    </div>
</div>

<?php echo foot(); ?>
