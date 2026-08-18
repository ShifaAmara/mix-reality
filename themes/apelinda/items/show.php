<?php
$title = metadata('item', 'display_title');
echo head(array('title' => $title, 'bodyclass' => 'items show'));
?>

<?php echo freedom_record_tags($item); ?>
<h1><?php echo metadata('item', 'rich_title', array('no_escape' => true)); ?></h1>

<div class="regions-container">
    <div class="sidebar-region sidebar-region--left">
        <div class="metadata">
            <?php echo record_image($item, 'fullsize');; ?>
        </div>
    </div>

    <div class="main-region">
        <div class="metadata">
            <?php echo all_element_texts('item'); ?>

            <!-- The following returns all of the files associated with an item. -->
            <?php if (metadata('item', 'has files')) : ?>
            <div id="itemfiles" class="element">
                <h2><?php echo __('Files'); ?></h2>
                <div class="element-text files-container"><?php echo files_for_item(); ?></div>
            </div>
            <?php endif; ?>

            <!-- If the item belongs to a collection, the following creates a link to that collection. -->
            <?php if (metadata('item', 'Collection Name')) : ?>
            <div id="collection" class="element">
                <h2><?php echo __('Collection'); ?></h2>
                <div class="element-text"><p><?php echo link_to_collection_for_item(); ?></p></div>
            </div>
            <?php endif; ?>

            <!-- The following prints a list of all tags associated with the item -->
            <?php if (metadata('item', 'has tags')) : ?>
            <div id="record-tags" class="element anchor">
                <h2><?php echo __('Tags'); ?></h2>
                <div class="element-text"><?php echo freedom_record_tags($item, 'tags', '', false); ?></div>
            </div>
            <?php endif;?>

            <!-- The following prints a citation for this item. -->
            <div id="item-citation" class="element">
                <h2><?php echo __('Citation'); ?></h2>
                <div class="element-text"><?php echo metadata('item', 'citation', array('no_escape' => true)); ?></div>
            </div>

            <div id="item-output-formats" class="element">
                <h2><?php echo __('Output Formats'); ?></h2>
                <div class="element-text"><?php echo output_format_list(); ?></div>
            </div>

            <?php fire_plugin_hook('public_items_show', array('view' => $this, 'item' => $item)); ?>
        </div>
    </div>
</div>

<nav>
<ul class="site-page-pagination">
    <li id="previous-item" class="site-page-pagination-button previous"><?php echo link_to_previous_item_show(); ?></li>
    <li id="next-item" class="site-page-pagination-button next"><?php echo link_to_next_item_show(); ?></li>
</ul>
</nav>

<?php echo foot(); ?>
