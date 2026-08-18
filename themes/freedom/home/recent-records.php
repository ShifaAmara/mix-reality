<?php
/**
 * Recents Records template part.
 */

$default_recent_types = ['item', 'collection', 'exhibit'];

for ($i=0; $i < 3; $i++) {
    $recent_type = get_theme_option('homepage_recent_type_' . ($i+1)) ?? $default_recent_types[$i];

    if ($recent_type !== 'none') {
        $recent_title = get_theme_option('homepage_recent_title_' . ($i+1)) ?? 'Recently Added ' . ucfirst($default_recent_types[$i]) . 's';
        $recent_records_count = get_theme_option('homepage_recent_records_count_' . ($i+1));

        if ($recent_records_count === null || $recent_records_count === '') {
            $recent_records_count = 4;
        } else {
            $recent_records_count = (int) $recent_records_count;
        }

        if ($recent_records_count) {
        ?>
            <section class="recent-<?php echo $recent_type . 's'; ?>-section">
    
                <?php if ($recent_title) : ?>
                    <h2 class="title"><?php echo $recent_title; ?></h2>
                <?php endif; ?>

                <div class="recent-<?php echo $recent_type . 's'; ?>">
                    <ul class="resources resource-grid">
                        <?php echo freedom_recent_records($recent_type, $recent_records_count); ?>
                    </ul>
                    <?php if (total_records($recent_type) > 1) : ?>
                        <p class="view-<?php echo $recent_type . 's'; ?>-link textcenter">
                            <a class="button btn--secondary" href="<?php echo html_escape(url($recent_type . 's')); ?>">
                                <?php echo sprintf(__('View All %ss (%s)'), ucfirst($recent_type), total_records($recent_type)); ?>
                            </a>
                        </p>
                    <?php endif; ?>
                </div>

            </section>
        <?php
        }
    }
}
?>
