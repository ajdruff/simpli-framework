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
    private function setDefaultSettings() {

        $default_settings = array(




            /*
             *
             * Defaults for Hello World Default Settings
             *
             */

             'hello_global_default_text' => 'Global Hello World!'
             , 'hello_global_default_placement' => 'after'
             , 'hello_global_default_enabled' => 'enabled'

            /*
             *
             * Defaults for Example Settings in 'Example Settings Metabox'
             *
             */

            ,'checkbox_settings' =>
            array(
                'yellow' => 'no'
                , 'red' => 'yes'
                , 'orange' => 'yes'
                , 'blue' => 'yes'
            )
            , 'dropdown_setting' => 'orange'
            , 'text_setting' => 'Joe Smith'
            , 'radio_setting' => 'yes'

            /*
             *
             * Advanced Settings
             *
             */
            , 'plugin_enabled' => 'enabled'    //'enabled' or 'disabled' Controls whether the plugins modules are loaded. Disabled still loads the admin pages
        );




        $this->_setting_defaults = $default_settings;
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
         * Save Activation Errors for later display
         */
        add_action('activated_plugin', array($this, 'save_activation_error'));

        /*
         * Show Activation Error
         *
         */

        add_action('admin_notices', array($this, 'show_activation_extra_characters'));


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
         * Execute all the Activate Actions
         * Due to the way that WordPress handles the activation events,its not possible to add a custom hook and use the add_action function
         * in the normal way to enable modules to hook into this event.
         * Instead, we implement the actions a different way, through the Plugin class's AddActivateAction method.
         * So now, we just need to cycle through the actions, calling each method as they are provided. Note its not possible at this time to
         * include arguments.
         */


        $activate_actions = $this->getActivateActions();
        foreach ($activate_actions as $action) {

            $object = $action[0];
            $method = $action[1];
            // this is equivilent to $object->method();
            call_user_func(array($object, $method));
        }




        /*
         *
         * Add any installation routines that you need
         * Modify as necessary if single or multi-site
         *
         */
    }

    /* Save Activation Error
     *
     * @param none
     * @return void
     */

    public function save_activation_error() {
        set_transient($this->getSlug() . '_activation_error', ob_get_contents(), 5);
    }

    /* Show Activation Extra Characters
     *
     * Shows any output that occurred during activation
     * Note: Logs do not work in activation - use echo instead to troubleshoot.
     * @param none
     * @return void
     */

    public function show_activation_extra_characters() {


        $activation_error=get_transient($this->getSlug() . '_activation_error');

        if ( $activation_error != '' ) {
            ?>


            <div class="updated">
                <p><strong>Unexpected Output generated during activation:</strong></p>
                <p style="border:gray solid 1px"><?php echo $activation_error; ?></p>
            </div>
            <?php
        }
    }

}

//todo: make sense of the above
//todo: add similar deactivation routines
//todo: add logging for deactivation if possible. use transients. consider dumping log to transient, and then accessing transient after activation.