<?php
/**
 * Timeline Single template part.
 */

$isGrid = $isGrid ?? false;
$title = metadata($timeline, 'title');
$description = metadata($timeline, 'description');
$featured = metadata($timeline, 'featured');
$truncateDesc = get_theme_option('truncate_body_property') ?? 'ellipsis';
?>

<li class="item resource <?php echo ($isGrid) ? '' : 'media-object'; ?>">
    <div class="resource__content">
        <div class="resource__meta <?php echo ($isGrid) ? '' : 'media-object-section'; ?>">
            <h2 class="resource__heading"><?php echo link_to($timeline, 'show', $title); ?></h2>
            <?php if ($description) : ?>
                <div class="description <?php echo $truncateDesc; ?>"><?php echo $description; ?></div>
            <?php endif; ?>
        </div>
    </div>
</li>