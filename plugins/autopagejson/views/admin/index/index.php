<?php 
echo head(array('title' => __('AutoPage JSON Importer Pipeline'))); 
echo flash(); 
?>

<form method="post" enctype="multipart/form-data" id="autopage-form" style="margin-top: 20px;">
    
    <div class="field">
        <div class="two columns alpha">
            <label for="json_file" style="font-weight: bold;"><?= __('Choose JSON Data File'); ?></label>
        </div>
        <div class="five columns omega inputs">
            <input type="file" name="json_file" id="json_file" required />
            <p class="explanation">Upload the .json file containing the database catalog array structure (e.g., 3D models, video links, maps, or general items).</p>
        </div>
    </div>

    <div class="field">
        <div class="two columns alpha">
            <label for="target_type" style="font-weight: bold;"><?= __('Select Target Destination'); ?></label>
        </div>
        <div class="five columns omega inputs">
            <select name="target_type" id="target_type" style="padding: 5px; width: 100%;">
                <option value="exhibit"><?= __('Exhibit Page (Use Omeka Grid Exhibition Structure)'); ?></option>
                <option value="simple_page"><?= __('Simple Page (Convert to Custom HTML/CSS Cards)'); ?></option>
            </select>
        </div>
    </div>

    <div class="field">
        <div class="two columns alpha">
            <label for="kategori_halaman" style="font-weight: bold;"><?= __('Menu Name / Page Title'); ?></label>
        </div>
        <div class="five columns omega inputs">
            <input type="text" name="kategori_halaman" id="kategori_halaman" placeholder="e.g., Virtual Gallery Tour, Product Catalog" style="width: 100%; padding: 5px;" required />
            <p class="explanation">This name will appear as the webpage heading title and the navigation link text in the top Navbar.</p>
        </div>
    </div>

    <div id="exhibit-selector-group" class="field">
        <div class="two columns alpha">
            <label for="exhibit_id" style="font-weight: bold;"><?= __('Select Parent Exhibit'); ?></label>
        </div>
        <div class="five columns omega inputs">
            <?php if (!empty($this->exhibits)): ?>
                <select name="exhibit_id" id="exhibit_id" style="padding: 5px; width: 100%;">
                    <?php foreach ($this->exhibits as $ex): ?>
                        <option value="<?= $ex->id; ?>"><?= html_escape($ex->title); ?></option>
                    <?php endforeach; ?>
                </select>
            <?php else: ?>
                <p style="color: red; font-weight: bold; margin:0;">No parent exhibit found! Please create a main exhibit first.</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="field" style="border-top: 1px dashed #ddd; padding-top: 15px;">
        <div class="two columns alpha">
            <label for="auto_navbar" style="font-weight: bold;"><?= __('Automatically Show in Navbar?'); ?></label>
        </div>
        <div class="five columns omega inputs">
            <label style="font-weight: normal; display: inline-flex; align-items: center;">
                <input type="checkbox" name="auto_navbar" id="auto_navbar" value="1" checked style="transform: scale(1.2); margin-right: 10px;" />
                <span>If checked, the system will automatically register this new page link into the website's main <strong>Appearance -> Navigation</strong> panel.</span>
            </label>
        </div>
    </div>

    <div class="field" style="margin-top: 30px;">
        <div class="two columns alpha">
            &nbsp;
        </div>
        <div class="five columns omega inputs">
            <input type="submit" name="submit_autopage" id="submit_autopage" class="big green button" value="Create" style="padding: 10px 30px; font-weight: bold; font-size: 15px;" />
        </div>
    </div>
</form>

<script type="text/javascript">
document.getElementById('target_type').addEventListener('change', function() {
    var selectorGroup = document.getElementById('exhibit-selector-group');
    if (this.value === 'exhibit') {
        selectorGroup.style.display = 'block';
    } else {
        selectorGroup.style.display = 'none';
    }
});
</script>

<?php echo foot(); ?>