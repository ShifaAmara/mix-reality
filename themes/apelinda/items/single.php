<?php
/**
 * Item Single template part.
 */

$isGrid = $isGrid ?? false;
$excludeTag = $excludeTag ?? '';
$primary = $primary ?? false;
$primaryMedia = item_image('fullsize', array(), 0, $item);
$title = metadata($item, 'rich_title', array('no_escape' => true));
$description = metadata($item, array('Dublin Core', 'Description'));
$truncateDesc = get_theme_option('truncate_body_property') ?? 'ellipsis';
$decoration = get_theme_option('image_decoration');
$decorationClass = '';
$style = '';

if ($decoration) {
    $decorationClass = $isGrid ? 'decoration' : 'decoration decoration--thumbnail';
}

if ($primary) {
    $style = 'background-image: url(' . record_image_url($item, 'fullsize') . ');';
    $truncateDesc = 'full';
}
?>

<li class="item resource <?php echo ($isGrid) ? '' : 'media-object'; ?>" style="<?php echo $style; ?>">
    <!-- Thumbnail -->
    <?php if ($primaryMedia) : ?>
        <div class="resource__thumbnail <?php echo $decorationClass; ?>">
            <?php echo link_to($item, 'show', $primaryMedia, array('class' => 'thumbnail')); ?>
        </div>
    <?php endif; ?>

    <!-- Content -->
    <div class="resource__content">
        <?php echo freedom_record_tags($item, '', $excludeTag); ?>
        <!-- Metadata -->
        <div class="resource__meta <?php echo ($isGrid) ? '' : 'media-object-section'; ?>">
            <h2 class="resource__heading"><?php echo link_to($item, 'show', $title); ?></h2>
            <?php if ($description) : ?>
                <div class="description <?php echo $truncateDesc; ?>"><?php echo $description; ?></div>
            <?php endif; ?>
        </div>
    </div>
</li>
