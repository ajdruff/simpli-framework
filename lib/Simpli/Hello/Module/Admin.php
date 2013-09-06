<?php

/**
 * Admin Module
 *
 * This module creates the admin panel
 *
 * @author Andrew Druffner
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
        $this->debug()->t();




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
        $this->debug()->t();


        $this->_menu_position = $menu_position;
        return $this;
    }

    /**
     * Add Hooks
     *
     * Adds WordPress Hooks, triggered during module initialization
     * @param none
     * @return void
     */
    public function addHooks() {
        $this->debug()->t();



        /*
         * exit if not admin
         */
        if (!is_admin()) {
            return;
        }

        /*
         * Only load the plugin action and row meta actions if you are on the plugins listing page
         * at http://wpdev.com/wp-admin/plugins.php
         *
         */
        if (isset($GLOBALS['pagenow']) && $GLOBALS['pagenow'] == 'plugins.php') {


            $plugin = plugin_basename($this->getPlugin()->getFilePath());

            add_filter('plugin_action_links_' . $plugin, array($this, 'plugin_action_links'), 10, 2);

            add_filter('plugin_row_meta', array($this, 'plugin_links'), 10, 2);
        }

        // Add global admin scripts
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));

    }

    /**
     * Adds javascript and stylesheets to admin panel
     * WordPress Hook - admin_enqueue_scripts
     *
     * @param none
     * @return void
     */
    public function admin_enqueue_scripts() {
        $this->debug()->t();


        wp_enqueue_style($this->getPlugin()->getSlug() . '-admin-global', $this->getPlugin()->getUrl() . '/admin/css/admin.css', array(), $this->getPlugin()->getVersion());
    }


    /**
     * Configure Module
     *
     * @param none
     * @return void
     */
    public function config() {
        $this->debug()->t();
    }

    /**
     * Renders a meta box
     *
     * @param string $module
     * @param array $metabox
     * @return void
     */
    public function meta_box_render($module, $metabox) {
        $this->debug()->t();




        include($this->getPlugin()->getDirectory() . '/admin/templates/metabox/' . $metabox['id'] . '.php');
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
        $this->debug()->t();



        /* Stop and return if this isnt our plugin...
         * Do this by checking if $file ( in the form plugin_subdirectory/plugin_file) matches our plugin
         * $file for your plugin can be found by calling the wordpress api plugin_basename() on the __FILE__ of your main plugin file
         */
        if (strpos($file, plugin_basename($this->getPlugin()->getFilePath())) === false) {
            return $links;
        }

        $links[1] = 'Simpli Framework ' . $this->getPlugin()->getFrameworkVersion() . ' / ' . $this->getPlugin()->getBaseClassVersion();

        $links[] = '<a href="' . get_admin_url() . "admin.php?page=" . $this->getPlugin()->getSlug() . '_' . $this->getPlugin()->getModule('Menu10Settings')->getSlug() . '" title="' . $this->getPlugin()->getName() . ' Settings">Settings</a>';
        $links[] = '<a href="http://wordpress.org/extend/plugins/' . $this->getPlugin()->getSlug() . '/faq/" title="Frequently Asked Questions">FAQ</a>';
        $links[] = '<a href="http://wordpress.org/tags/' . $this->getPlugin()->getSlug() . '#postform" title="Support">Support</a>';
        $links[] = '<a href="your paypal url here" title="Support this plugin\'s development with a donation!">Donate</a>';
        return $links;
    }

    /**
     * Plugin Action Links
     *
     * WordPress Hook - {$prefix}plugin_action_links_{$plugin_file}
     * These are the plugin links that appear on the Plugin listing page (http://wpdev.com/wp-admin/plugins.php) underneath the
     * plugin name. They will only appear *after* the plugin is activated and appear adjacent to the 'Deactivate|Edit|Delete' links.
     * Ref: http://adambrown.info/p/wp_hooks/hook/%7B$prefix%7Dplugin_action_links
     * Ref: http://slobodanmanic.com/256/action-meta-links-wordpress-plugins/
     * @param array $links
     * @return array $links
     */
    public function plugin_action_links($links) {
        $this->debug()->t();


        $links[] = '<a href="' . get_admin_url() . "admin.php?page=" . $this->getPlugin()->getSlug() . '_' . $this->getPlugin()->getModule('Menu10Settings')->getSlug() . '">Settings</a>';
        return $links;
    }

}