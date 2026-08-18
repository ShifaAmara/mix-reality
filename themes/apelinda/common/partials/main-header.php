<header class="main-header">

    <div class="main-header__top-bar container">
        <?php echo link_to_item_search('Advanced Search', ['class' => 'main-header__advanced-search']); ?>
        <div class="main-header__search-form">
            <?php echo search_form(array('form_attributes' => array('role' => 'search'))); ?>
        </div>
    </div>
    <hr class="alignfull">

    <div class="main-header__main-bar container">

        <div class="main-header__site-title">

            <?php if (get_theme_option('Logo')) : ?>
                <?php $site_logo = theme_logo(); ?>
            <?php else : ?>
                <?php $site_logo = option('site_title'); ?>
            <?php endif; ?>

            <?php echo link_to_home_page($site_logo); ?>

        </div>

        <nav class="main-navigation">
            <div class="main-navigation__container">
                <?php echo public_nav_main(); ?>
            </div>
            <div class="main-navigation__toggle">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </nav>
    </div>

</header>
