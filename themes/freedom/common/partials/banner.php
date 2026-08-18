<?php
/**
 * Footer template part.
 */

$banner = freedom_asset_uri('banner');
$bannerWidth = get_theme_option('banner_width') ?? '';
$bannerHeight = get_theme_option('banner_height') ?? '';
$bannerVerticalPosition = (get_theme_option('banner_v_position')) ?? 'center';
$bannerHorizontalPosition = (get_theme_option('banner_h_position')) ?? 'center';

$bannerHeading = get_theme_option('banner_heading');
$bannerDescription = get_theme_option('banner_description');
$bannerContentPosition = get_theme_option('banner_content_position') ?? 'left';

$bannerHeightProperty = $bannerHeading || $bannerDescription ? 'min-height' : 'height';

$bannerInlineStyles = '';
$imageInlineStyles = '';

if ($bannerHeight) {
    $bannerInlineStyles .= "{$bannerHeightProperty}: {$bannerHeight};";
} else {
    $bannerInlineStyles .= "{$bannerHeightProperty}: 20vh;";
}

$imageInlineStyles .= "object-position: {$bannerHorizontalPosition} {$bannerVerticalPosition};";

$class = ['banner'];
$class[] = $bannerWidth;
if ($bannerHeading || $bannerDescription) {
    $class[] = 'has-text';
}
?>

<?php if ($banner) : ?>
    <div class="<?php echo implode(' ', $class); ?>" style="<?php echo $bannerInlineStyles; ?>">

        <img
            src="<?php echo $banner; ?>"
            role="presentation"
            aria-hidden="true"
            style="<?php echo $imageInlineStyles; ?>" />

        <div class="banner__content banner__content--<?php echo $bannerContentPosition; ?>">
            <?php if($bannerHeading) : ?>
                <h2 class="banner__heading"><?php echo html_escape($bannerHeading); ?></h2>
            <?php endif; ?>
            <?php if($bannerDescription) : ?>
                <p class="banner__description"><?php echo html_escape($bannerDescription); ?></p>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>
