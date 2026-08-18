<?php
if (!empty($formActionUri)) :
    $formAttributes['action'] = $formActionUri;
else:
    $formAttributes['action'] = url(
        array('controller' => 'items',
        'action' => 'browse')
    );
endif;
$formAttributes['method'] = 'GET';
?>

<form <?php echo tag_attributes($formAttributes); ?>>
    <div class="advanced-search-content">
        <div id="search-keywords" class="field">
            <?php echo $this->formLabel('keyword-search', __('Search for Keywords')); ?>
            <div class="inputs">
            <?php
                echo $this->formText(
                    'search',
                    @$_REQUEST['search'],
                    array('id' => 'keyword-search', 'size' => '40')
                );
                ?>
            </div>
        </div>

        <div id="search-narrow-by-fields" class="field removable multi-value">
            <div id="search-narrow-by-field-alerts" class="field-meta">
                <span id="by-value-label" class="label"><?php echo __('Narrow by Specific Fields'); ?></span>
            </div>
            <div class="inputs">
                <?php
                // If the form has been submitted, retain the number of search
                // fields used and rebuild the form
                if (!empty($_GET['advanced'])) {
                    $search = $_GET['advanced'];
                } else {
                    $search = array(array('field' => '', 'type' => '', 'value' => ''));
                }

                //Here is where we actually build the search form
                //The POST looks like =>
                // advanced[0] =>
                //[field] = 'description'
                //[type] = 'contains'
                //[terms] = 'foobar'
                //etc
                foreach ($search as $i => $rows): ?>
                    <div class="search-entry value" id="search-row-<?php echo $i; ?>" aria-label="<?php echo __('Row %s', $i+1); ?>">
                        <label class="sub-label logical-operator">Logical operator
                            <?php 
                            echo $this->formSelect(
                                "advanced[$i][joiner]",
                                @$rows['joiner'],
                                array(
                                    'id' => null,
                                    'class' => 'joiner',
                                    'aria-labelledby' => 'search-narrow-by-fields-label search-row-' . $i . ' search-narrow-by-fields-joiner',
                                ),
                                array(
                                    'and' => __('AND'),
                                    'or' => __('OR'),
                                )
                            );
                            ?>
                        </label>
                        <label class="sub-label"><?php echo __('Field'); ?>
                            <?php 
                            echo $this->formSelect(
                                "advanced[$i][element_id]",
                                @$rows['element_id'],
                                array(
                                    'aria-labelledby' => 'search-narrow-by-fields-label search-row-' . $i . ' search-narrow-by-fields-property',
                                    'id' => null,
                                ),
                                get_table_options(
                                    'Element', null, array(
                                    'record_types' => array('Item', 'All'),
                                    'sort' => 'orderBySet')
                                )
                            );
                            ?>
                        </label>
                        <label class="sub-label"><?php echo __('Type'); ?>
                            <?php 
                            echo $this->formSelect(
                                "advanced[$i][type]",
                                @$rows['type'],
                                array(
                                    'aria-labelledby' => 'search-narrow-by-fields-label search-row-' . $i . ' search-narrow-by-fields-type',
                                    'id' => null,
                                ),
                                label_table_options(
                                    array(
                                    'contains' => __('contains'),
                                    'does not contain' => __('does not contain'),
                                    'is exactly' => __('is exactly'),
                                    'is empty' => __('is empty'),
                                    'is not empty' => __('is not empty'),
                                    'starts with' => __('starts with'),
                                    'ends with' => __('ends with'))
                                )
                            );
                            ?>
                        </label>
                        <label class="sub-label"><?php echo __('Terms'); ?>
                            <?php 
                            echo $this->formText(
                                "advanced[$i][terms]",
                                @$rows['terms'],
                                array(
                                    'size' => '20',
                                    'aria-labelledby' => 'search-narrow-by-fields-label search-row-' . $i . ' search-narrow-by-fields-terms',
                                    'id' => null,
                                )
                            );
                            ?>
                        </label>
                        <button type="button" class="remove_search remove-value" disabled="disabled" style="display: none;" aria-labelledby="search-narrow-by-fields-label search-row-<?php echo $i; ?> search-narrow-by-fields-remove-field" title="<?php echo __('Remove field'); ?>"></button>
                    </div>
                <?php endforeach; ?>
            </div>
            <button type="button" class="add_search add-value button" aria-label="<?php echo __('Add a Field'); ?>" title="<?php echo __('Add a Field'); ?>"></button>
        </div>

        <div id="search-by-range" class="field">
            <?php echo $this->formLabel('range', __('Search by a range of ID#s (example: 1-4, 156, 79)')); ?>
            <div class="inputs">
            <?php
                echo $this->formText(
                    'range', @$_GET['range'],
                    array('size' => '40')
                );
                ?>
            </div>
        </div>

        <div id="search-by-collection" class="field half">
            <?php echo $this->formLabel('collection-search', __('Search By Collection')); ?>
            <div class="inputs">
            <?php
                echo $this->formSelect(
                    'collection',
                    @$_REQUEST['collection'],
                    array('id' => 'collection-search'),
                    get_table_options('Collection', null, array('include_no_collection' => true))
                );
                ?>
            </div>
        </div>

        <div id="search-by-type" class="field half">
            <?php echo $this->formLabel('item-type-search', __('Search By Type')); ?>
            <div class="inputs">
            <?php
                echo $this->formSelect(
                    'type',
                    @$_REQUEST['type'],
                    array('id' => 'item-type-search'),
                    get_table_options('ItemType')
                );
                ?>
            </div>
        </div>

        <?php if (is_allowed('Users', 'browse')) : ?>
        <div id="search-by-user" class="field">
            <?php
            echo $this->formLabel('user-search', __('Search By User'));?>
            <div class="inputs">
            <?php
                echo $this->formSelect(
                    'user',
                    @$_REQUEST['user'],
                    array('id' => 'user-search'),
                    get_table_options('User')
                );
            ?>
            </div>
        </div>
        <?php endif; ?>

        <div id="search-by-tag" class="field half">
            <?php echo $this->formLabel('tag-search', __('Search By Tags')); ?>
            <div class="inputs">
            <?php
                echo $this->formText(
                    'tags', @$_REQUEST['tags'],
                    array('size' => '40', 'id' => 'tag-search')
                );
                ?>
            </div>
        </div>

        <div id="search-by-featured" class="field half">
            <?php echo $this->formLabel('featured', __('Featured/Non-Featured')); ?>
            <div class="inputs">
            <?php
                echo $this->formSelect(
                    'featured',
                    @$_REQUEST['featured'],
                    array(),
                    label_table_options(
                        array(
                        '1' => __('Only Featured Items'),
                        '0' => __('Only Non-Featured Items')
                        )
                    )
                );
                ?>
            </div>
        </div>


        <?php if (is_allowed('Items', 'showNotPublic')) : ?>
        <div id="search-by-public" class="field">
            <?php echo $this->formLabel('public', __('Public/Non-Public')); ?>
            <div class="inputs">
            <?php
                echo $this->formSelect(
                    'public',
                    @$_REQUEST['public'],
                    array(),
                    label_table_options(
                        array(
                        '1' => __('Only Public Items'),
                        '0' => __('Only Non-Public Items')
                        )
                    )
                );
            ?>
            </div>
        </div>
        <?php endif; ?>

        <?php fire_plugin_hook('public_items_search', array('view' => $this)); ?>

    </div>

    <div id="page-actions">
        <input type="submit" name="submit" value="<?php echo __('Search'); ?>">
        <input type="reset" name="reset" value="Reset" class="reset-button btn--secondary" />
    </div>
</form>

<?php echo js_tag('items-search'); ?>
<script type="text/javascript">
    jQuery(document).ready(function () {
        Omeka.Search.activateSearchButtons();
    });
</script>
