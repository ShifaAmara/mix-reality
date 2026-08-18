<?php
$pageTitle = __('Search') . ' ' . __('(%s total)', $total_results);
echo head(array('title' => $pageTitle, 'bodyclass' => 'search'));
$searchRecordTypes = get_search_record_types();

$filter = new Zend_Filter_Word_CamelCaseToDash;
?>

<h1><?php echo $pageTitle; ?></h1>

<nav class="navigation secondary-nav">
    <?php echo search_filters(); ?>
</nav>

<?php if ($total_results) : ?>
    <ul class="resources resource-list">
        <?php 
        foreach (loop('search_texts') as $searchText) {
            echo $this->partial('search/single.php', array('searchText' => $searchText, 'filter' => $filter, 'isGrid' => false));
        }
        ?>
    </ul>

    <?php echo pagination_links(); ?>

<?php else : ?>
    <div id="no-results">
        <p><?php echo __('Your query returned no results.');?></p>
    </div>
<?php endif; ?>

<?php echo foot(); ?>
