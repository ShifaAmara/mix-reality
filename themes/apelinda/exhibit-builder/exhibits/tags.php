<?php
$pageTitle = __('Browse Exhibits by Tag');
echo head(array('title' => $pageTitle, 'bodyclass' => 'exhibits tags'));
?>

<h1><?php echo $pageTitle; ?></h1>

<nav class="navigation exhibits-nav secondary-nav">
    <?php echo nav(array(
            array(
                'label' => __('Browse All'),
                'uri' => url('exhibits/browse')
            ),
            array(
                'label' => __('Browse by Tag'),
                'uri' => url('exhibits/tags')
            )
        )
    ); ?>
</nav>

<?php echo freedom_tag_cloud($tags, 'exhibits/browse'); ?>

<?php echo foot(); ?>
