<!DOCTYPE html>
<html lang="<?php echo get_html_lang(); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php if ($author = option('author')) : ?>
    <meta name="author" content="<?php echo $author; ?>" />
    <?php endif; ?>
    <?php if ($copyright = option('copyright')) : ?>
    <meta name="copyright" content="<?php echo $copyright; ?>" />
    <?php endif; ?>
    <?php if ($description = option('description')) : ?>
    <meta name="description" content="<?php echo $description; ?>" />
    <?php endif; ?>
    <?php
    if (isset($title)) {
        $titleParts[] = strip_formatting($title);
    }
    $titleParts[] = option('site_title');
    ?>
    <title><?php echo implode(' &middot; ', $titleParts); ?></title>

    <?php echo auto_discovery_link_tags(); ?>

    <?php
    $header_layout = (get_theme_option('header_layout')) ?? 'inline';
    $header_layout_class = 'main-header--' . $header_layout;
    $bannerHeightMobile = get_theme_option('banner_height_mobile');
    $bannerHeading = get_theme_option('banner_heading');
    $bannerDescription = get_theme_option('banner_description');
    $bannerHeightProperty = $bannerHeading || $bannerDescription ? 'min-height' : 'height';
    $primaryColor = get_theme_option('primary_color') ?? '#e77f11';
    ?>

    <?php fire_plugin_hook('public_head', array('view'=>$this)); ?>

    <?php
    queue_css_file(array('iconfonts','style'));
    queue_css_url('//fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,600;0,700;1,300;1,400;1,600;1,700&display=swap');
    queue_css_url('//fonts.googleapis.com/css2?family=Noto+Serif:ital,wght@0,400;0,700;1,400;1,700&display=swap');
    
    queue_css_string(
        '
        :root {
            --primary: ' . $primaryColor . ';
            --primary-dark: ' . freedom_shade_color($primaryColor, -10) . ';
        }'
    );

    if($bannerHeightMobile) {
        queue_css_string(
            '
            @media screen and (max-width:768px) {
                .banner {'
                    . $bannerHeightProperty . ':' . $bannerHeightMobile . ' !important;
                }
            }'
        );
    }
    
    echo head_css();

    echo theme_header_background();
    ?>
    
    <?php
    queue_js_file(array('globals', 'navigation', 'script'));
    echo head_js();
    ?>
</head>
<?php echo body_tag(array('id' => @$bodyid, 'class' => @$bodyclass . ' ' . $header_layout_class)); ?>
    
    <a id="skipnav" href="#content"><?php echo __('Skip to main content'); ?></a>

    <?php echo $this->partial('common/partials/main-header.php'); ?>

    <?php if (@$bodyid === 'home'): ?>
        <?php echo $this->partial('common/partials/banner.php'); ?>
    <?php endif; ?>

    <div id="main-content" class="container" role="main">
    <?php fire_plugin_hook('public_content_top', array('view'=>$this)); ?>