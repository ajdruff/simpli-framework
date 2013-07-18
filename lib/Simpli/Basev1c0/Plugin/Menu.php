<?php

/**
 * Admin Menu
 *
 *
 * @author Andrew Druffner
 * @package SimpliFramework
 * @subpackage SimpliHello
 *
 */
class Simpli_Basev1c0_Plugin_Menu extends Simpli_Basev1c0_Plugin_Module {

    /**
     * Initialize Module
     *
     * @param none
     * @return void
     */
    public function init() {


        if (!is_admin()) {
            return;
        }


        /*
         * Module base class requires
         * setting Name first, then slug
         */
        $this->setName();
        $this->setSlug();


        /*
         *  add custom ajax handlers
         *
         * adding a wp_ajax hook in this format will execute the specified class method whenever the ajax request specifies an action = to $this->getPlugin()->getSlug() . '_xxxx'
          // see http://codex.wordpress.org/Plugin_API/Action_Reference/wp_ajax_%28action%29
         *
         */
        //this is where you map any form actions with the php function that handles the ajax request

        /* save without reloading the page */
        add_action('wp_ajax_' . $this->getPlugin()->getSlug() . '_settings_save', array(&$this, 'save'));

        /* save with reloading the page */
        add_action('wp_ajax_' . $this->getPlugin()->getSlug() . '_settings_save_with_reload', array(&$this, 'save_with_reload'));




        add_action('wp_ajax_' . $this->getPlugin()->getSlug() . '_settings_reset', array(&$this, 'reset'));

        /*
         * Reset all settings to defaults
         *
         */
        add_action('wp_ajax_' . $this->getPlugin()->getSlug() . '_settings_reset_all', array(&$this, 'reset_all'));
        /*
         * Manuall Update settings so as to add any newly added settings due to a developer update
         *
         */
        add_action('wp_ajax_' . $this->getPlugin()->getSlug() . '_settings_update_all', array(&$this, 'update_all'));



// add ajax action
        add_action('wp_ajax_' . $this->getPlugin()->getSlug() . '_ajax_metabox', array(&$this, 'ajax_metabox'));


        /*
         *
         * Add metaboxes whenever the page matches the plugin's slug
         *
         */

        if (isset($_GET['page']) //if 'page' variable is in url
                && strpos($_GET['page'], $this->getPlugin()->getSlug()) !== false
        ) {
            // Add meta boxes
            // add_action('admin_init', array(&$this, 'add_meta_boxes'));
add_action('admin_init', array(&$this, 'add_meta_boxes'));
        //    add_action('load-toplevel_page_simpli_hello_menu10_settings', array(&$this, 'add_meta_boxes'));

//    add_action('load-toplevel_page_simpli_hello_menu10_settings', array(&$this, 'add_meta_boxes'));
//add_action('load-toplevel_page_simpli_hello_menu10_settings_group1', array(&$this, 'add_meta_boxes'));

            // Add scripts
            add_action('admin_enqueue_scripts', array(&$this, 'admin_enqueue_scripts'));
        }

        // Add admin menus
        add_action('admin_menu', array(&$this, 'admin_menu'));



        $this->getPlugin()->getLogger()->log($this->getPlugin()->getSlug() . ': initialized  module ' . $this->getName());
    }

    /**
     * Admin panel menu option
     * WordPress Hook - admin_menu
     *
     * @param none
     * @return void
     */
    public function admin_menu() {

        throw new Exception('No admin_menu method in  ' . get_class($this));
    }

    /**
     * Add meta boxes
     *
     * @param none
     * @return void
     */
    public function add_meta_boxes() {

    }

    /**
     * Dispatch request for settings page
     *
     * @param none
     * @return void
     */
    public function dispatch() {

        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }

        self::render();
    }

    /**
     * Dispatch request for ajax metabox
     *
     * @param none
     * @return void
     */
    public function ajax_metabox() {
        // Disable errors
        error_reporting(0);

        // Set headers
        header("Status: 200");
        header("HTTP/1.1 200 OK");
        header('Content-Type: text/html');
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header('Cache-Control: post-check=0, pre-check=0', FALSE);
        header('Pragma: no-cache');
        header("Vary: Accept-Encoding");

        if (!wp_verify_nonce($_POST['_nonce'], $this->getPlugin()->getSlug())) {
            exit;
        }


        $request = new WP_Http;
        $request_result = $request->request($_POST['url']);
        $content = $request_result['body'];
        if ($content) {
            echo $content;
        }
        exit;
    }

    /**
     * Adds javascript and stylesheets to settings page in the admin panel.
     * WordPress Hook - enqueue_scripts
     *
     * @param none
     * @return void
     */
    public function admin_enqueue_scripts() {

        //add any   wp_enqueue_script calls here
    }

    /**
     * Render settings page
     *
     * @param none
     * @return void
     */
    public function render() {
        //require a template whose name is the same as the menu_slug
        require_once($this->getPlugin()->getDirectory() . '/admin/templates/' . $this->getSlug() . '.php');
    }

    /**
     * Reset Settings
     *
     * @param none
     * @return void
     */
    public function reset() {
        if (!wp_verify_nonce($_POST['_wpnonce'], $this->getPlugin()->getSlug())) {
            //    return false;
        }

        $message = "Settings reset.";
        $errors = array();
        $reload = true;
        $logout = false; //whether you want to logout after settings are saved

        foreach ($this->getPlugin()->getSettings() as $setting_name => $setting_value) {
            /**
             * Set new setting value equal to the post value only if the setting was actually submitted, otherwise, keep the setting value the same.
             *  Add extra code to scrub the values for specific settings if needed
             */
            $setting_value = ((isset($_POST[$setting_name]) === true) ? $this->getPlugin()->_setting_defaults[$setting_name] : $setting_value);

            $this->getPlugin()->setSetting($setting_name, $setting_value);
        }


        $this->getPlugin()->saveSettings();

        if ($logout) {
            wp_logout();
        }
        require_once($this->getPlugin()->getDirectory() . '/admin/templates/ajax_message.php');
    }

    /**
     * Save Wrapper - No Page Reload
     *
     * @param none
     * @return void
     */
    public function save() {


        $this->_save(false);
    }

    /**
     * Save Wrapper with Page Reload
     *
     * @param none
     * @return void
     */
    public function save_with_reload() {


        $this->_save(true);
    }

    /**
     * Save Settings
     *
     * @param none
     * @return void
     */
    public function _save($reload = false) {
        if (!wp_verify_nonce($_POST['_wpnonce'], $this->getPlugin()->getSlug())) {
            return false;
        }

        $message = "Settings saved.";
        $errors = array(); // initialize the error array , add any validation errors when you scrub the form_field values
        // eg: $errors[]="You really screwed up on that one";
        // $reload = false; //whether to reload the page after settings are saved
        $logout = false; //whether you want to logout after settings are saved
        // cycle through the settings and update them with the values submitted by the form

        /*
         * The original code didn't make much sense to me annd looked like it was over engineered
         * and used too many trips to the database.
         * Here, we just save each setting thats submitted to a cache, and then
         * save the settings to the database when we are done.
         * The setSetting method will not save the setting to the array if the setting name didn't already
         * exist as a key  in the original _settings array
         *          */


        foreach ($this->getPlugin()->getSettings() as $setting_name => $setting_value) {
            /**
             * Set new setting value equal to the post value only if the setting was actually submitted, otherwise, keep the setting value the same.
             *  Add extra code to scrub the values for specific settings if needed
             */
//print_r($_POST);die();
            $previous_setting_value = $setting_value;
            $setting_value = ((isset($_POST[$setting_name]) === true) ? $_POST[$setting_name] : $previous_setting_value);

            if ($setting_name == 'cache_timeout') {
                if (!is_numeric($setting_value)) {
                    $errors[] = "Cache Timeout must be a number";
                    $setting_value = $previous_setting_value;
                }
            }


            if ($setting_name == 'feed_urls') {

                foreach ($setting_value as $key => $feed_url) {

                    if ($feed_url != '') {
                        // echo $feed_url;die();
                        if (!filter_var($feed_url, FILTER_VALIDATE_URL)) {
                            $errors[] = "Feed URL " . $feed_url . " is not a valid url";
                            $setting_value = $previous_setting_value;
                        }
                    }
                }
            }






            $this->getPlugin()->setSetting($setting_name, $setting_value);
        }


        $this->getPlugin()->saveSettings();

        if ($logout) {
            wp_logout();
        }
        //return a success message on submission
        require_once($this->getPlugin()->getDirectory() . '/admin/templates/ajax_message.php');
    }

    /**
     * Update All Settings
     *
     * add the update_all method to the Simpli Plugin.php class and make this method a wrapper that calls it
     * Takes the default array for settings and merges it with existing settings. This results in the database being updated with any new
     * settings added by development changes while retainining the existing setting values.
     * @param none
     * @return void
     */
    public function update_all() {
        if (!wp_verify_nonce($_POST['_wpnonce'], $this->getPlugin()->getSlug())) {
            return false;
        }

        $message = "Settings have been updated";
        $errors = array();
        $reload = true;
        $logout = false; //whether you want to logout after settings are saved


        /*
         * Merge existing options with the defaults
         * Will not delete old settings, but will add new ones.
         *
         */


        $wp_option_name = $this->getPlugin()->getSlug() . '_options';
        $existing_options = $this->getPlugin()->getSettings();
        $option_defaults = $this->getPlugin()->getSettingDefaults();
        $options = array_merge($option_defaults, $existing_options);


        /*
         * Save back to the database ( do not use the $this->getPlugin()->saveSettings() method since that
         * will only use existing settings)
         *
         */

        if ($blog_id > 0) {
            update_blog_option($blog_id, $wp_option_name, $options);
        } else {
            update_option($wp_option_name, $options);
        }



        if ($logout) {
            wp_logout();
        }
        require_once($this->getPlugin()->getDirectory() . '/admin/templates/ajax_message.php');
    }

    /**
     * Reset All Settings
     *
     * @param none
     * @return void
     */
    public function reset_all() {
        if (!wp_verify_nonce($_POST['_wpnonce'], $this->getPlugin()->getSlug())) {
            return false;
        }

        $message = "All Settings Have been reset to initial defaults.";
        $errors = array();
        $reload = true;
        $logout = false; //whether you want to logout after settings are saved

        global $wpdb;
        $query = 'delete from wp_options where option_name = \'' . $this->getPlugin()->getSlug() . '_options\'';
        $dbresult = $wpdb->query($query);

        /* if no rows affected, that means the defaults havent been changed yet and stored in the database */
        if ($dbresult === 0) {
            $message = 'Settings are already at defaults!';
        } elseif ($dbresult === false) {//returns false on error
            $message = 'Setting reset failed due to database error.';
        }

        $this->getPlugin()->saveSettings();

        if ($logout) {
            wp_logout();
        }
        require_once($this->getPlugin()->getDirectory() . '/admin/templates/ajax_message.php');
    }

}