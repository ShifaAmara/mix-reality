<?php
$exhibitTitle = metadata('exhibit', 'title');
$exhibitDescription = metadata('exhibit', 'description', array('no_escape' => true));
$exhibitCredits = metadata('exhibit', 'credits');
$pageTree = exhibit_builder_page_tree();

echo head(array('title' => $exhibitTitle, 'bodyclass'=>'exhibits summary'));
?>

<?php echo freedom_record_tags($exhibit); ?>
<h1><?php echo $exhibitTitle; ?></h1>
<?php echo exhibit_builder_page_nav(); ?>

<div class="regions-container">
    <div class="sidebar-region sidebar-region--left">
        <div class="metadata">
            <?php echo record_image($exhibit, 'fullsize'); ?>
        </div>
    </div>

    <div class="main-region">
        <div class="metadata">
            <?php if ($exhibitDescription) : ?>
                <div class="element-set exhibit-description">
                    <?php echo $exhibitDescription; ?>
                </div>
            <?php endif; ?>

            <?php if ($exhibitCredits) : ?>
                <div class="element-set exhibit-credits">
                    <h2><?php echo __('Credits'); ?></h2>
                    <p><?php echo $exhibitCredits; ?></p>
                </div>
            <?php endif; ?>
            
            <?php if ($pageTree) : ?>
                <nav id="element-set exhibit-pages">
                    <h2><?php echo __('Exhibit Pages'); ?></h2>
                    <?php echo $pageTree; ?>
                </nav>
            <?php endif; ?>

            <!-- The following prints a list of all tags associated with the item -->
            <?php if (metadata('exhibit', 'has tags')) : ?>
                <div id="record-tags" class="element anchor">
                    <h2><?php echo __('Tags'); ?></h2>
                    <div class="element-text"><?php echo freedom_record_tags($exhibit, 'tags', '', false); ?></div>
                </div>
            <?php endif;?>
        </div>
    </div>
</div>

<?php echo foot(); ?>
