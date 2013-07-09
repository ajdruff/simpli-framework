<?php

/**
 * Simpli Hello World
 *
 * @author Andrew Druffner
 * @package SimpliFramework
 * @subpackage SimpliHello
 *
 */
class Simpli_Hello_Plugin extends Simpli_Basev1c0_Plugin {

    public $_setting_defaults = array();


    /**
     * Set Default Settings
     *
     * Add the settings and their defaults here. The plugin will use the
     * default values when first activating the plugin and when using the reset
     * buttons.
     *  
     * @param void
     * @return string The parsed output of the form body tag
 */
   private function setDefaultSettings()
    {

       $default_settings= array(
            /*
             *
             * General Settings
             *
             */
            'first_name' => 'Default First Name'       //
            , 'last_name' => 'Last Name'  //
            , 'option_checkbox' => array('table' => 'no', 'chair' => 'yes') //
            , 'option_radio' => 'maybe'
            , 'admin_menu_side' => 'side'
            , 'option_select' => 'blue'
            , 'test_array' => array('first' => 'first_element', 'second' => 'second_element')
            /*
             *
             * Advanced Settings
             *
             */
            , 'plugin_enabled' => 'enabled'    //'enabled' or 'disabled' Controls whether the plugins modules are loaded. Disabled still loads the admin pages
        );




        $this->_setting_defaults=$default_settings;
        return $this->_setting_defaults;

}

    public function __construct() {



        /*
         *
         * Set Default Settings
         *
         */


$this->setDefaultSettings();







        parent::__construct(); //call the base constructor which adds logging capability.
    }

    /**
     * Initialize
     *
     * @param none
     * @return void
     */
    public function init() {

        /*
         * make sure wordpress is installed properly
         */
        if (!defined('ABSPATH'))
            die('Cannot Load Plugin - WordPress installation not found');

        /*
         *  Load any libraries you need that may not be included with the default wordpress installation
         */

        if (!class_exists('WP_Http'))
            include_once( ABSPATH . WPINC . '/class-http.php' );




        /*
         * Load the text domain
         */
        load_plugin_textdomain($this->getTextDomain(), false, dirname(plugin_basename(__FILE__)) . '/languages/');



        /*
         * Add some log messages
         *
         */

        $this->getLogger()->log(' Starting ' . $this->getName() . ' Debug Log');

        $this->getLogger()->log('Version: ' . $this->getVersion());

        /*
         * set the directory of the Plugin          *
         */



        $this->setDirectory(dirname(dirname(dirname(dirname(__FILE__))))); //e.g.: /home/username/public_html/wp-content/plugins/simpli-framework

        /*
         * set the Module Directory
         *         */
        $this->setModuleDirectory($this->getDirectory() . '/lib/Simpli/Hello/Module/'); //e.g. /home/username/public_html/wp-content/plugins/simpli-framework/lib/simpli/hello/Module/

        /*
         * Set the Plugin Url
         */

        $this->setPluginUrl(plugins_url('', $this->getDirectory() . '/plugin.php'));



        /**
         * Load Settings
         */
        $this->loadSettings();

        /**
         * Load Modules
         */
        $this->loadModules();

        /*
         * Finally, call the base class initialization routines
         */

        parent::init();
    }

    /**
     * Install
     *
     * @param none
     * @return void
     */
    public function install() {
        global $wpdb;

        if (is_multisite() && is_network_admin()) {
            $blogs = $wpdb->get_col($wpdb->prepare("SELECT blog_id FROM " . $wpdb->blogs, NULL));
        } else {
            $blogs = array($wpdb->blogid);
        }

        $defaults = $this->getSetting('network_defaults');
        foreach ($blogs as $blog_id) {
            // Add Settings
            foreach ($this->getSettings() as $option => $value) {
                if (is_multisite()) {
                    if (add_blog_option($blog_id, $option, $value) && isset($defaults[$option])) {
                        $this->setSetting($option, $value, $blog_id);
                    }
                } else {
                    add_option($option, $value);
                }
            }
        }
    }

    /**
     * Get relevent files and directories within WordPress
     *
     * @param none
     * @return void
     */
    public function getDirectories() {
        $directories = array();
        $scannedDirectories = array();

        // add any directories you need here
        $directories[] = get_theme_root() . '/' . get_template();
// $directories[] = /another/directory;
        //for each directory, make sure they are accessible, then return them as an array with the key as the directory path
        foreach ($directories as $directory) {
            $scannedDirectories[$directory]['name'] = $directory;
            if (is_readable($directory) && ($files = scandir($directory))) {
                $scannedDirectories[$directory]['files'] = $files;
                unset($files);
            } else {
                $scannedDirectories[$directory]['error'] = "Unable to read directory.";
            }
        }
        return $scannedDirectories;
    }

    /**
     * Load Modules
     *
     * Load specified modules. If no modules are specified, all modules are loaded.
     *
     * @param array $modules
     * @return $this
     */
    public function loadModules($modules = array()) {



        if (sizeof($modules) == 0) {
            $modules = $this->getAvailableModules();
        }

        foreach ($modules as $module) {

            //if plugin was disabled in settings, load only the admin and menu modules
            if ($this->getSetting('plugin_enabled') == 'disabled') {
                $haystack = strtolower($module);
                $pattern = '/menu|admin/s';
                if (preg_match_all($pattern, $haystack, $matches) < 1) {

                    //      echo '<br>skipping module ' . $module;
                    continue;
                }
            }

            $this->loadModule($module);
        }

//        echo 'plugin_enabled setting = ' ;
//print_r($this->getSettings());
        return $this;
    }

}