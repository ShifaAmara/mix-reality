<?php
echo head(array(
    'title' => metadata('exhibit_page', 'title') . ' &middot; ' . metadata('exhibit', 'title'),
    'bodyclass' => 'exhibits show'));

$prevText = __('&larr; Previous Exhibit page');
$prevLink = exhibit_builder_link_to_previous_page($prevText);

$nextText = __("Next Exhibit page &rarr;");
$nextLink = exhibit_builder_link_to_next_page($nextText);
?>

<h1><span class="exhibit-page"><?php echo metadata('exhibit_page', 'title'); ?></span></h1>

<div id="exhibit-blocks">
    <?php exhibit_builder_render_exhibit_page(); ?>
</div>

<?php if ($prevLink || $nextLink) : ?>
    <nav>
        <ul class="site-page-pagination">
            <?php if ($prevLink) : ?>
                <li id="previous-item" class="site-page-pagination-button previous"><?php echo $prevLink; ?></li>
            <?php endif; ?>
            <?php if ($nextLink) : ?>
                <li id="next-item" class="site-page-pagination-button next"><?php echo $nextLink; ?></li>
            <?php endif; ?>
        </ul>
    </nav>
<?php endif; ?>

<nav id="exhibit-pages">
    <p class="textcenter">Summary page: <?php echo exhibit_builder_link_to_exhibit($exhibit); ?></p>
</nav>

<?php echo foot(); ?>
