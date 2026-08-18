<?php
/**
 * Footer template part.
 */

$footerLogo      = get_theme_option('footer_logo');
$footerSiteInfo  = get_theme_option('footer_site_info');
$footerMenu      = get_theme_option('footer_menu');
$footerContent   = get_theme_option('footer_content');
$socialNetworks  = array( 'facebook', 'twitter', 'linkedin', 'instagram', 'youtube', 'mastodon' );

$additional_classes = '';

if (! $footerMenu ) {
    $additional_classes .= ' no-menu';
} elseif (! $footerLogo && ! $footerSiteInfo && ! $footerContent ) {
    $additional_classes .= ' menu-only';
}

if ($footerMenu && ( $footerLogo || $footerSiteInfo ) && $footerContent ) {
    $additional_classes .= ' all-columns';
}

$hasSocialNetworks = false;

foreach ( $socialNetworks as $social_network ) {
    if (get_theme_option("{$social_network}_url") ) {
        $hasSocialNetworks = true;
        break;
    }
}
?>

    </div><!-- end content -->

    <footer class="main-footer">

        <!-- Footer Top -->
        <?php if ($footerLogo || $footerSiteInfo || $footerMenu || $footerContent ) : ?>
            <div class="main-footer__top">
                <div class="container main-footer__top-container <?php echo $additional_classes; ?>">

                    <!-- Column 1 -->
                    <?php if ($footerLogo || $footerSiteInfo ) : ?>

                        <div class="main-footer__col1">
                            <?php if ($footerLogo ) : ?>
                                <img
                                    src="<?php echo freedom_asset_uri('footer_logo'); ?>"
                                    alt="<?php echo html_escape(option('site_title')); ?>"
                                    style="<?php echo $footerSiteInfo ? 'margin-bottom: 30px;' : ''; ?>" />
                            <?php endif; ?>

                            <div class="footer_site_info">
                                <?php if ($footerSiteInfo ) : ?>
                                    <?php echo $footerSiteInfo; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                    <?php endif; ?>

                    <!-- Column 2 -->
                    <?php if ($footerMenu ) : ?>
                    
                        <div class="main-footer__col2">
                            <?php echo public_nav_main(); ?>
                        </div>

                    <?php endif; ?>

                    <!-- Column 3 -->
                    <?php if ($footerContent ) : ?>

                        <div class="main-footer__col3">
                            <?php if ($footerContent ) : ?>
                                <?php echo $footerContent; ?>
                            <?php endif; ?>
                        </div>

                    <?php endif; ?>

                </div>
            </div>
        <?php endif; ?>    

        <hr class="alignfull">

        <!-- Footer Bottom -->
        <div class="main-footer__bottom">
            <div class="container main-footer__bottom-container <?php echo $hasSocialNetworks ? 'has-social-networks' : ''; ?>">

                <!-- Social Networks -->
                <?php if ($hasSocialNetworks) : ?>
                    <div class="main-footer__social-network">
                        <?php foreach ( $socialNetworks as $social_network ) : ?>
                            <?php if ($social_network_url = get_theme_option("{$social_network}_url") ) : ?>
                                <a href="<?php echo $social_network_url; ?>">
                                    <img src="<?php echo img("{$social_network}.svg"); ?>"
                                        alt="<?php echo html_escape($social_network); ?>">
                                </a>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- Copyright -->
                <div class="main-footer__copyright">
                    <?php if ($footer_copyright = get_theme_option('footer_copyright') ) : ?>
                        <?php echo $footer_copyright; ?>
                    <?php else: ?>
                        <?php echo $this->translate('Powered by Omeka'); ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </footer>

    <?php echo $this->partial('common/partials/menu-drawer.php'); ?>

    <script type="text/javascript">
        jQuery(document).ready(function(){
            Omeka.skipNav();
        });
    </script>

</body>
</html>
