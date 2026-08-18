<?php
/**
 * Collection Single template part.
 */

$isGrid = $isGrid ?? false;
$excludeTag = $excludeTag ?? '';
$primary = $primary ?? false;
$collectionImage = record_image($collection, 'fullsize');
$title = metadata($collection, 'display_title');
$description = metadata($collection, array('Dublin Core', 'Description'));
$contributor = $collection->hasContributor() ? metadata($collection, array('Dublin Core', 'Contributor'), array('all' => true, 'delimiter' => ', ')) : '';
$truncateDesc = get_theme_option('truncate_body_property') ?? 'ellipsis';
$decoration = get_theme_option('image_decoration');
$decorationClass = '';
$style = '';

if ($decoration) {
    $decorationClass = $isGrid ? 'decoration' : 'decoration decoration--thumbnail';
}

if ($primary) {
    $style = 'background-image: url(' . record_image_url($collection, 'fullsize') . ');';
    $truncateDesc = 'full';
}
?>

<li class="collection resource <?php echo ($isGrid) ? '' : 'media-object'; ?>" style="<?php echo $style; ?>">
    <!-- Thumbnail -->
    <?php if ($collectionImage) : ?>
        <div class="resource__thumbnail <?php echo $decorationClass; ?>">
            <?php echo link_to($collection, 'show', $collectionImage, array('class' => 'thumbnail')); ?>
        </div>
    <?php endif; ?>

    <!-- Content -->
    <div class="resource__content">
        <?php echo freedom_record_tags($collection, '', $excludeTag); ?>
        <!-- Metadata -->
        <div class="resource__meta <?php echo ($isGrid) ? '' : 'media-object-section'; ?>">
            <h2 class="resource__heading"><?php echo link_to($collection, 'show', $title); ?></h2>
            <?php if ($description) : ?>
                <p class="description <?php echo $truncateDesc; ?>"><?php echo $description; ?></p>
            <?php endif; ?>

            <?php if ($contributor) : ?>
                <p class="contributor"><strong><?php echo __('Contributors'); ?>: </strong><?php echo $contributor; ?></p>
            <?php endif; ?>

            <?php echo link_to_items_browse(__('View the items in %s', metadata($collection, 'rich_title', array('no_escape' => true))), array('collection' => metadata($collection, 'id')), array('class' => 'view-items-link')); ?>
        </div>
    </div>

    <?php fire_plugin_hook('public_collections_browse_each', array('view' => $this, 'collection' => $collection)); ?>

</li>
