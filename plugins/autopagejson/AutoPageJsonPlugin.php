<?php

class AutoPageJsonPlugin extends Omeka_Plugin_AbstractPlugin
{
    // Mendaftarkan filter untuk memunculkan menu di sidebar admin
    protected $_filters = array('admin_navigation_main');

    public function filterAdminNavigationMain($nav)
    {
        $nav[] = array(
            'label' => __('AutoPage JSON'),
            'uri' => url('autopagejson/index/index')
        );
        return $nav;
    }
}