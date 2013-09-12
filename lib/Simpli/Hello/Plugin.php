<?php

/**
 * Simpli Hello World
 *
 * @author Andrew Druffner
 * @package SimpliFramework
 * @subpackage SimpliHello
 */
class Simpli_Hello_Plugin extends Simpli_Basev1c0_Plugin {

    /**
     * Add Hooks
     *
     * Adds WordPress Hooks.
     * @param none
     * @return void
     */
    public function addHooks() {



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
         * Enqueue the framework's namespace script so we can namespace our javascript
         * Add our local variables
         *
         */
        if (is_admin()) {
            /*
             * Enqueue Inline and External Scripts
             */
            add_action('admin_enqueue_scripts', array($this, 'hookEnqueueScripts'), 0);
            /*
             * Print Header Inline Scripts
             */
            add_action('admin_print_scripts', array($this, 'hookPrintInlineHeaderScripts'));
            /*
             * Print Local Vars ( always print to the footer)
             */
            add_action('admin_print_footer_scripts', array($this, 'hookPrintLocalVars'));
            /*
             * Print Footer Inline Scripts
             */
            add_action('admin_print_footer_scripts', array($this, 'hookPrintInlineFooterScripts'));
        } else {
            /*
             * Enqueue Inline and External Scripts
             */
            add_action('wp_enqueue_scripts', array($this, 'hookEnqueueScripts'));
            /*
             * Print Header Inline Scripts
             */
            add_action('wp_print_scripts', array($this, 'hookPrintInlineHeaderScripts'));
            /*
             * Print Local Vars ( always print to the footer)
             */
            add_action('wp_print_footer_scripts', array($this, 'hookPrintLocalVars'));
            /*
             * Print Footer Inline Scripts
             */
            add_action('wp_print_footer_scripts', array($this, 'hookPrintInlineFooterScripts'));

//            add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
//            add_action('wp_print_footer_scripts', array($this, 'printLocalVars'));
//            add_action('wp_print_footer_scripts', array($this, 'printInlineFooterScripts'));
        }










        $this->debug()->log('Plugin Directory: ' . $this->getDirectory());
        $this->debug()->log('Module Directory: ' . $this->getModuleDirectory());


        $this->debug()->log('Plugin URL: ' . $this->getUrl());
    }

    /**
     * Configure
     *
     * Configures Plugin
     *
     *@param none
     * @return void
     */
    public function config() {


        $this->debug()->t();

        /*
         *  Load any libraries you need that may not be included with the default wordpress installation
         */

        if (!class_exists('WP_Http'))
            include_once( ABSPATH . WPINC . '/class-http.php' );

        /*
         * Set the Default User Options
         *
         * Edit the _setUserOptionDefaults() method to change
         * individual default values. An option must have a default
         * before being recognized by the save/load methods
         */
        $this->_setUserOptionDefaults();


        /*
         * DISABLED_MODULES
         *
         * Disable any modules that you don't want loaded
         * Usage Example:
          $this->setConfig(
          'DISABLED_MODULES'
          , array('QueryVars', 'Shortcodes')
          );
         */
        $this->setConfig(
                'DISABLED_MODULES'
               // , array('Menu01CustomPostType','Shortcodes', 'ExampleModule')
                 , array('Shortcodes', 'ExampleModule')
        );



        /*
         * ALWAYS_ENABLED_REGEX_PATTERN
         *
         * sets the regex pattern that allows matching modules to remain loaded even after the user selects 'disabled' from the plugin options. This allows the user to continue to acccess the admin options to re-enable the plugin. Must be set prior to the call to loadModules or it will be igored.
         */
        $this->setConfig(
                'ALWAYS_ENABLED_REGEX_PATTERN'
                , '/menu|admin/s'
        );



        /*
         * DISABLED_ADDONS
         *
         * Disable any Addons that you don't want loaded.
         */
        $this->setConfig(
                'DISABLED_ADDONS'
                , array()
        );


                $this->setConfig(
                'DEBUG'
                , true
        );


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


        $activation_error = get_transient($this->getSlug() . '_activation_error');

        if ($activation_error != '') {
            ?>


            <div class="updated">
                <p><strong>Unexpected Output generated during activation:</strong></p>
                <p style="border:gray solid 1px"><?php echo $activation_error; ?></p>
            </div>
            <?php
        }
    }

    /**
     * Set User Option Defaults
     *
     * Sets the default values for the user options that are
     * configured from within the admin panels
     *
     * @param none
     * @return void
     */
    private function _setUserOptionDefaults() {
        /*
         *
         * Start Default Admin Settings
         *
         */

        /*
         *
         * Hello World Settings
         *
         */

        /*
         * Hello World Text
         * The text to insert into the post
         */
        $this->setUserOptionDefault(
                'hello_global_default_text', 'Global Hello World!'
        );
        /*
         * Text Placement - 'before' or 'after'
         */
        $this->setUserOptionDefault(
                'hello_global_default_placement', 'after'
        );

        /*
         * Global Enable - 'enabled' or 'disabled'
         */
        $this->setUserOptionDefault(
                'hello_global_default_enabled', 'enabled'
        );
        /*
         *
         * Defaults for Example Settings in 'Example Settings Metabox'
         *
         */

        /*
         * Radio Button Setting Example
         * 'yes' or 'no'
         */
        $this->setUserOptionDefault(
                'radio_setting', 'yes'
        );

        /*
         * Text Setting Example
         * any string
         */
        $this->setUserOptionDefault(
                'text_setting', 'Joe Smith'
        );

        /*
         * Dropdown Setting Example
         * Set equal to the value that you want selected
         */
        $this->setUserOptionDefault(
                'dropdown_setting', 'red'
        );


        /*
         * Checkbox Setting Example
         * The index is the value of the checkbox,
         * the value is either 'yes' or 'no' , indicating whether you want it checked or not
         */
        $this->setUserOptionDefault(
                'checkbox_setting', array(
            'yellow' => 'yes'
            , 'red' => 'no'
            , 'orange' => 'yes'
            , 'blue' => 'yes'
                )
        );



        /*
         *
         * Advanced Settings
         *
         */

        /*
         * Plugin Enabled Setting
         * 'enabled' or 'disabled' Controls whether the plugins modules are loaded. Disabled still loads the admin pages
         */
        $this->setUserOptionDefault(
                'plugin_enabled', 'enabled'
        );


        /*
         *
         * End Default Admin Settings
         *
         */
    }

}
