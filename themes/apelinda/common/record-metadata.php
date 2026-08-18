<?php foreach ($elementsForDisplay as $setName => $setElements): ?>
<dl class="element-set">
    <?php if ($showElementSetHeadings): ?>
    <h2 class="element-set-heading"><?php echo html_escape(__($setName)); ?></h2>
    <?php endif; ?>
    <?php foreach ($setElements as $elementName => $elementInfo): ?>
    <div id="<?php echo text_to_id(html_escape("$setName $elementName")); ?>" class="element property">
        <dt><?php echo html_escape(__($elementName)); ?></dt>
        <?php foreach ($elementInfo['texts'] as $text): ?>
            <dd class="element-text value"><?php echo $text; ?></dd>
        <?php endforeach; ?>
    </div><!-- end element -->
    <?php endforeach; ?>
</dl><!-- end element-set -->
<?php endforeach;
