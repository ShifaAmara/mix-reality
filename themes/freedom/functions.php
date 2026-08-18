<?php

/**
 * Return a color shade based on a percentage value.
 * 
 * @param string $color   The original color.
 * @param int    $percent The shade percent. Can be a negative vvalue for a darker color shade.
 *
 * @return string
 */
function freedom_shade_color($color, $percent)
{
    $num = base_convert(substr($color, 1), 16, 10);
    $amt = round(2.55 * $percent);
    $r = ($num >> 16) + $amt;
    $b = ($num >> 8 & 0x00ff) + $amt;
    $g = ($num & 0x0000ff) + $amt;

    return '#'.substr(base_convert(0x1000000 + ($r<255?$r<1?0:$r:255)*0x10000 + ($b<255?$b<1?0:$b:255)*0x100 + ($g<255?$g<1?0:$g:255), 10, 16), 1);
}

/**
 * Return a local asset URI from the theme uploads directory.
 * 
 * @param string $asset_name The asset field name in the theme settings (config.ini).
 *
 * @return string
 */
function freedom_asset_uri($asset_name)
{
    if (!$asset_name) {
        return;
    }

    $uri = '';
    $asset_value = get_theme_option($asset_name);

    if ($asset_value) {
        $storage = Zend_Registry::get('storage');
        $uri = $storage->getUri($storage->getPathByType($asset_value, 'theme_uploads'));
    }

    return $uri;
}

/**
 * Returns a set of tags in HTML format.
 *
 * @param object $record     An instance of Omeka_Record_AbstractRecord.
 * @param string $tag_type   The type of tags to render (record_type, item_type or tags). Empty string will render all.
 * @param string $exclude    The type of tags to exclude (record_type, item_type or tags).
 * @param bool   $limit_tags Limit or not the number of tags to render, based on the theme settings.
 *
 * @return string
 */
function freedom_record_tags($record, $tag_type = '', $exclude = '', $limit_tags = true)
{
    if (!$record || !$record instanceof Omeka_Record_AbstractRecord) {
        return '';
    }

    $record_tags_options = ['record_type', 'record_tag', 'item_type'];
    $theme_record_tags   = get_theme_option('record_tags');

    $tags_html = '';

    if (!is_array($theme_record_tags) || !array_intersect($record_tags_options, $theme_record_tags)) {
        return '';
    }

    $record_type = get_class($record);

    if (!$record_type) {
        return '';
    }

    $record_type_id = '';
    $record_type_url = '';

    switch ($record_type) {
        case 'Item':
            $record_type_id = 0;
            $record_type_url = 'items/browse';
            break;

        case 'File':
            $record_type_id = 3;
            break;

        case 'Collection':
            $record_type_id = 7;
            $record_type_url = 'collections/browse';
            break;

        case 'Exhibit':
            $record_type_id = 9;
            $record_type_url = 'exhibits/browse';
            break;

        default:
            $record_type_id = 10;
            break;
    }

    $tags_html .= '<div class="record-tags">';

    // Record Type Tags ('Item', 'Exhibit', 'Collection').

    if ($exclude !== 'record_type'
        && in_array('record_type', $theme_record_tags)
        && ($tag_type === '' || $tag_type === 'record_type')) {

        $tag_color = freedom_get_unique_color_from_id($record_type_id, 'pastel');

        if ($record_type_url) {
            $tags_html .= '<a href="' . html_escape(url($record_type_url)) . '" class="record-tag" style="background-color: ' . $tag_color . ';">' . $record_type . '</a>';
        } else {
            $tags_html .= '<div class="record-tag" style="background-color: ' . $tag_color . ';">' . $record_type . '</div>';
        }
    }

    // Item Type Tags.

    if ($exclude !== 'item_type'
        && in_array('item_type', $theme_record_tags)
        && 'Item' === $record_type && $record->item_type_id
        && ($tag_type === '' || $tag_type === 'item_type')) {

        $typeName = metadata($record, 'Item Type Name');
        $tag_color = freedom_get_unique_color_from_id((int) $record->item_type_id + 100, 'pastel'); // Offset 100 to get more unique colors.
        $tags_html .= '<a href="' . html_escape(url('items/browse', array('type' => $record->item_type_id))) . '" class="record-tag" style="background-color: ' . $tag_color . ';">' . $typeName . '</a>';
    }

    // Record Tags.

    if ($exclude !== 'record_tag'
        && in_array('record_tag', $theme_record_tags)
        && $tag_type === '' || $tag_type === 'tags') {

        $tags = $record->Tags;

        if (is_array($tags) && count($tags)) {
            $hidden_tags = 0;

            if ($limit_tags) {
                $tag_limit = get_theme_option('record_tags_count') ?? 2;
                $tag_limit = is_numeric($tag_limit) ? intval($tag_limit) : 0;
                $total_tags = count($tags);

                if ($tag_limit > 0) {
                    $tags = array_slice($tags, 0, $tag_limit);

                    if ($total_tags > $tag_limit) {
                        $hidden_tags = $total_tags - $tag_limit;
                    }
                }
            }


            foreach ($tags as $tag) {
                $tag_color = freedom_get_unique_color_from_id((int)$tag->id + 200, 'pastel'); // Offset 200 to get more unique colors.
                $tags_html .= '<a href="' . html_escape(url($record_type_url, array('tags' => $tag->name))) . '" class="record-tag" style="background-color: ' . $tag_color . ';">' . $tag->name . '</a>';
            }

            if ($hidden_tags) {
                $hidden_tags_string = '...' . $hidden_tags . ' more';
                $tags_url = record_url($record, 'show', false);
                $tags_html .= '<a href="' . $tags_url . '#record-tags" class="additional-tags">' . $hidden_tags_string . '</a>';
            }
        }
    }

    $tags_html .= '</div>';

    return $tags_html;
}

/**
 * Returns a unique random color.
 *
 * @param int $n     A unique ID (used to generate a unique random color).
 * @param int $style Color style (options: 'solid', 'pastel').
 *
 * @return string
 */
function freedom_get_unique_color_from_id($n, $style = 'pastel')
{
    $n = crc32($n);
    $color = '';

    switch($style) {
    case 'solid':
        $n &= 0xffffffff;
        $color = "#" . substr("000000" . dechex($n), -6);
        break;
        
    case 'pastel':
        $color = "hsl({$n}, 100%, 87.5%)";
        break;
    }

    return $color;
}

/**
 * Create a tag cloud made of divs that follow the hTagcloud microformat
 *
 * @param Omeka_Record_AbstractRecord|array $recordOrTags The record to retrieve
 * tags from, or the actual array of tags
 * @param string|null $link The URI to use in the link for each tag. If none
 * given, tags in the cloud will not be given links.
 * @param int         $maxClasses
 * @param bool        $tagNumber
 * @param string      $tagNumberOrder
 *
 * @return string HTML for the tag cloud
 */
function freedom_tag_cloud($recordOrTags = null, $link = null, $maxClasses = 9, $tagNumber = false, $tagNumberOrder = null)
{
    if (!$recordOrTags) {
        $tags = array();
    } elseif (is_string($recordOrTags)) {
        $tags = get_current_record($recordOrTags)->Tags;
    } elseif ($recordOrTags instanceof Omeka_Record_AbstractRecord) {
        $tags = $recordOrTags->Tags;
    } else {
        $tags = $recordOrTags;
    }

    if (empty($tags)) {
        return '<p>' . __('No tags are available.') . '</p>';
    }

    //Get the largest value in the tags array
    $largest = 0;
    foreach ($tags as $tag) {
        if ($tag["tagCount"] > $largest) {
            $largest = $tag['tagCount'];
        }
    }
    $html = '<div class="hTagcloud">';
    $html .= '<ul class="popularity record-tags">';

    if ($largest < $maxClasses) {
        $maxClasses = $largest;
    }

    foreach ($tags as $tag) {
        $color = freedom_get_unique_color_from_id((int) $tag['id'] + 200, 'pastel');
        $size = (int) (($tag['tagCount'] * $maxClasses) / $largest - 1);
        $class = str_repeat('v', $size) . ($size ? '-' : '') . 'popular';
        $html .= '<li class="' . $class . '">';
        if ($link) {
            $html .= '<a class="record-tag" style="background-color: ' . $color . ';" href="' . html_escape(url($link, array('tags' => $tag['name']))) . '">';
        }
        if ($tagNumber && $tagNumberOrder == 'before') {
            $html .= ' <span class="count">'.$tag['tagCount'].'</span> ';
        }
        $html .= html_escape($tag['name']);
        if ($tagNumber && $tagNumberOrder == 'after') {
            $html .= ' <span class="count">'.$tag['tagCount'].'</span> ';
        }
        if ($link) {
            $html .= '</a>';
        }
        $html .= '</li>' . "\n";
    }
    $html .= '</ul></div>';

    return $html;
}

/**
 * Returns the Exhibit Browse page filters in HTML format.
 *
 * @return string HTML for the Exhibit Browse page filters.
 */
function freedom_exhibit_search_filters()
{
    $request = Zend_Controller_Front::getInstance()->getRequest();
    $requestArray = $request->getParams();

    $db = get_db();
    $displayArray = array();
    foreach ($requestArray as $key => $value) {
        if ($value != null) {
            $filter = ucfirst($key);

            if ($key === 'tags') {
                $displayArray[$filter] = $value;
            }
        }
    }

    $displayArray = apply_filters('item_search_filters', $displayArray, array('request_array' => $requestArray));

    $html = '';
    if (!empty($displayArray)) {
        $html .= '<div id="item-filters">';
        $html .= '<ul>';
        foreach ($displayArray as $name => $query) {
            $class = html_escape(strtolower(str_replace(' ', '-', $name)));
            $html .= '<li class="' . $class . '">' . html_escape(__($name)) . ': ' . html_escape($query) . '</li>';
        }
        $html .= '</ul>';
        $html .= '</div>';
    }
    return $html;
}

/**
 * Returns random featured record IDs by record type.
 *
 * @param string $recordType The record type.
 * @param int    $limit How many record IDs to return.
 * 
 * @return array An array of result record IDs.
 */
function freedom_random_featured_record_ids($recordType, $limit)
{
    $featuredRecords =  get_records(
        ucfirst($recordType), array('featured' => 1,
        'sort_field' => 'random'), $limit
    );

    return $featuredRecords;
}

/**
 * Returns a featured record in HTML format.
 *
 * @param string $recordType The record type.
 * @param object $recordID The record ID. If null, it will be a random one.
 * @param bool   $primary If the featured record will be rendered in the primary region.
 * 
 * @return string
 */
function freedom_featured_record_html($recordType, $recordID = null, $primary = false)
{
    if (!$recordType) {
        return;
    }

    if ($recordID === null) {
        $recordID = freedom_random_featured_record_ids($recordType, 1)[0];
    }

    $thumbnailSize = 'square_thumbnail';

    if ($primary) {
        $thumbnailSize = 'fullsize';
    }

    $recordSinglePartial = [
        'exhibit' => 'exhibit-builder/exhibits/single.php',
        'collection' => 'collections/single.php',
        'item' => 'items/single.php',
        'timeline' => 'timeline/timelines/single.php',
    ];

    $html = get_view()->partial(
        $recordSinglePartial[$recordType], array(
        $recordType => $recordID,
        'thumbnailSize' => $thumbnailSize,
        'featured' => 'featured',
        'primary' => $primary
        )
    );
    
    if ($recordType == 'exhibit') {
        $html = apply_filters('exhibit_builder_display_random_featured_exhibit', $html);
    }
    
    return $html;
}

/**
 * Get HTML for recent records.
 *
 * @param string $type  The Record type (item, collection, exhibit).
 * @param int    $count Maximum number of recent collections to show.
 *
 * @return string
 */
function freedom_recent_records($type, $count = 4)
{
    if (!$type) {
        return;
    }

    $plural = $type . 's';

    $recordDict = [
        'item' => [
            'single_partial' => 'items/single.php',
            'get_function' => 'get_recent_items'
        ],
        'collection' => [
            'single_partial' => 'collections/single.php',
            'get_function' => 'get_recent_collections'
        ],
        'exhibit' => [
            'single_partial' => 'exhibit-builder/exhibits/single.php',
            'get_function' => 'exhibit_builder_recent_exhibits'
        ]
    ];

    $records = $recordDict[$type]['get_function']($count);
    if ($records) {
        $html = '';
        foreach ($records as $record) {
            $html .= get_view()->partial($recordDict[$type]['single_partial'], array($type => $record));
            release_object($record);
        }
    } else {
        $html = '<p>' . __('No recent ' . $plural . ' available.') . '</p>';
    }
    return $html;
}
