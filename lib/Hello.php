<?php

/**
 * Simpli Hello World
 *
 * @author Andrew Druffner
 * @package Hello
 *
 */
class Hello extends Simpliv1c0_Plugin {

    public $_setting_defaults = array();

    public function __construct() {



        /*
         *
         * Set Setting defaults
         *
         */



        $this->_setting_defaults = array(

            /*
             *
             * General Settings
             *
             */
            'first_name' => 'Default First Name'       //
        , 'last_name' => 'Last Name'  //
        , 'option_checkbox' => array('table'=>'no','chair'=>'yes') //
        , 'option_radio' => 'maybe'
        , 'admin_menu_side' => 'side'
        , 'option_select' => 'blue'

           ,'test_array'=>array('first'=>'first_element','second'=>'second_element')
                        /*
             *
             * Advanced Settings
             *
             */
        , 'plugin_enabled' => 'enabled'    //'enabled' or 'disabled' Controls whether the plugins modules are loaded. Disabled still loads the admin pages
        , 'must_use_plugins_listing'=>'disabled' //or 'disabled' Controls whether the user can see a listing of must use plugins in admin




    );
    }

    /**
     * Initialize
     *
     * @param none
     * @return void
     */
    public function init() {



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