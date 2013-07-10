<?php

/**
 * Admin Module
 *
 * This module creates the admin panel
 *
 * @author Mike Ems
 * @package SimpliFramework
 * @subpackage SimpliHello
 *
 */
class Simpli_Hello_Module_Admin extends Simpli_Basev1c0_Plugin_Module {

    private $_menu_position = '';

    /**
     * Get Menu Position
     *
     * @param none
     * @return string
     */
    public function getMenuPosition() {

        /*
         * Provide a default menu position
         * This will virtually guarantee uniqueness as long as the slug is unique
         * Default will also automatically sort in alphabetic position in relation
         * to other Simpli Framework plugins
         */

        if ($this->_menu_position === '') {

            $this->_menu_position = '67.141592653597777777' . $this->getPlugin()->getSlug();
        }


        return $this->_menu_position;
    }

    /**
     * Set Menu Position
     *
     * @param string $menu_position
     * @return object $this
     */
    public function setMenuPosition($menu_position) {
        $this->_menu_position = $menu_position;
        return $this;
    }

    /**
     * Initialize Module
     *
     * @param none
     * @return void
     */
    public function init() {

        $plugin = plugin_basename($this->getPlugin()->getFilePath());

        $this->getPlugin()->getLogger()->log('$plugin=' . $plugin);
        add_filter('plugin_action_links_' . $plugin, 'simpli_hello_plugin_action_links', 10, 2);

  $this->getPlugin()->getLogger()->log($this->getPlugin()->getSlug() . ':initializing admin module');


        // Load on plugins page
        $plugin = plugin_basename($this->getPlugin()->getFilePath());
        add_filter('plugin_action_links_' . $plugin, array(&$this, 'plugin_action_links'), 10, 2);

        if (isset($GLOBALS['pagenow']) && $GLOBALS['pagenow'] == 'plugins.php') {
            add_filter('plugin_row_meta', array(&$this, 'plugin_links'), 10, 2);
        }

        // Add global admin scripts
        add_action('admin_enqueue_scripts', array(&$this, 'admin_enqueue_scripts'));
    }

    /**
     * Adds javascript and stylesheets to admin panel
     * WordPress Hook - admin_enqueue_scripts
     *
     * @param none
     * @return void
     */
    public function admin_enqueue_scripts() {
        wp_enqueue_style($this->getPlugin()->getSlug() . '-admin-global', $this->getPlugin()->getPluginUrl() . '/admin/css/admin.css', array(), $this->getPlugin()->getVersion());
    }

    /**
     * Renders a meta box
     *
     * @param string $module
     * @param array $metabox
     * @return void
     */
    public function meta_box_render($module, $metabox = array()) {
        // if ($metabox['id']=='simpli-helloworld_example_ajax') {
//                echo '<pre>';
//               print_r($metabox);
//               echo '</pre>';
        //   die();
        // }
//
//             die('rendering');
//            print_r($metabox);
        // print_r($metabox);
        if (isset($metabox['args']['metabox'])) {
            include($this->getPlugin()->getDirectory() . '/admin/templates/metabox/' . $metabox['args']['metabox'] . '.php');
        }
    }

    /**
     * Plugin links on Manage Plugins page in admin panel
     * WordPress Hook - plugin_row_meta
     *
     * @param array $links
     * @param string $file
     * @return array $links
     */
    public function plugin_links($links, $file) {
        if (strpos($file, $this->getPlugin()->getSlug()) === false) {
            return $links;
        }

        $links[] = '<a href="' . site_url() . '/wp-admin/admin.php?page=' . $this->getPlugin()->getSlug() . '" title=$this->getPlugin()->getName() . " Settings">Settings</a>';
        $links[] = '<a href="http://wordpress.org/extend/plugins/' . $this->getPlugin()->getSlug() . '/faq/" title="Frequently Asked Questions">FAQ</a>';
        $links[] = '<a href="http://wordpress.org/tags/' . $this->getPlugin()->getSlug() . '#postform" title="Support">Support</a>';
        $links[] = '<a href="your paypal url here" title="Support this plugin\'s development with a donation!">Donate</a>';
        return $links;
    }

    /**
     * Plugin links on Manage Plugins page in admin panel
     * WordPress Hook - plugin_row_meta
     *
     * @param array $links
     * @param string $file
     * @return array $links
     */
    public function plugin_action_links($links) {
        echo 'firing action links';
        //$links[] = 'Framework Version:' . $this->getPlugin()->getFrameworkVersion();
        $links['Wow'] = '<a href="#">This is a link</a>';
        return $links;
    }

}