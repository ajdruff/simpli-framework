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
     * @author Andrew Druffner
     * @param void
     * @return string The parsed output of the form body tag
 */
   private function setDefaultSettings()
    {

       $default_settings= array(

           /*
            *
            * Defaults for Example Settings in 'Example Settings Metabox'
            *
            */

           'checkbox_setting' =>
           array(
               'yellow' => 'no'
               ,'red' => 'yes'
                ,'orange' => 'yes'
                ,'blue' => 'yes'
               )

           ,'dropdown_setting' => 'orange'

           ,'text_setting'=>'Joe Smith'

           ,'radio_setting'=>'yes'

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



    /**
     * Initialize
     *
     *
     * @author Andrew Druffner
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
         *
         * Set Default Settings
         *
         */

        $this->setDefaultSettings();



        /*
         * Add some log messages
         *
         */

        $this->getLogger()->log(' Starting ' . $this->getName() . ' Debug Log');

        $this->getLogger()->log('Plugin Version: ' . $this->getVersion() . ' Framework Version: ' . $this->getFrameworkVersion() . ' Base Class Version: ' . $this->getBaseClassVersion());



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

        /*
         *
         * Add any installation routines that you need
         * Modify as necessary if single or multi-site
         *
         */

    }







}