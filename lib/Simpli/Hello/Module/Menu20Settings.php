<?php

/**
 * Admin Settings Module
 *
 * Adds the SettingsExample page.
 *
 * @author Andrew Druffner
 * @package SimpliFramework
 * @subpackage SimpliHello
 *
 */
class Simpli_Hello_Module_Menu20Settings extends Simpli_Basev1c0_Plugin_Module {

    private $moduleName;
    private $moduleSlug;

    /**
     * Initialize Module
     *
     * @param none
     * @return void
     */
    public function init() {
       if (!is_admin()) {return;}

            /*
             *
             * Set the Module Name based on the name of this file
             * for MyModule.php , moduleName=MyModule
             *
             */


            $this->moduleName = basename(__FILE__, ".php");

            /*
             * Create an equivilent 'module slug' that is the module name but lower cased and separated by underscores
             * for for MyModule.php , moduleName=MyModule , moduleSlug='my_module'
             * http://stackoverflow.com/q/8611617
             */
            $regex = '/(?<!^)((?<![[:upper:]])[[:upper:]]|[[:upper:]](?![[:upper:]]))/';
            $this->moduleSlug = strtolower(preg_replace($regex, '_$1', $this->moduleName));


            /*
             * Add filter to toggle the listing for the 'must use' plugins
             *
             */
 add_filter( 'show_advanced_plugins', array(&$this,'toggle_mu_plugins_listing') , 10, 2 );
//http://stackoverflow.com/questions/3707134/easiest-way-to-hide-some-wordpress-plugins-from-users



            /*
             *  add custom ajax handlers
             *
             * adding a wp_ajax hook in this format will execute the specified class method whenever the ajax request specifies an action = to $this->getPlugin()->getSlug() . '_xxxx'
              // see http://codex.wordpress.org/Plugin_API/Action_Reference/wp_ajax_%28action%29
             *
             */
            /* save without reloading the page */
            add_action('wp_ajax_' . $this->getPlugin()->getSlug() . '_settings_save', array(&$this, 'save'));

            /* save with reloading the page */
            add_action('wp_ajax_' . $this->getPlugin()->getSlug() . '_settings_save_with_reload', array(&$this, 'save_with_reload'));

/*
 * Reset a specific metabox's settings to default
 *
 */

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
                    && strpos($_GET['page'], $this->getPlugin()->getSlug()) !== false  // if page query variable contains our plugin slug
            // && strpos($_SERVER['REQUEST_URI'], 'settings_main_menu_example') !== false // and if request_uri contains the menu slug
            ) {
                // Add meta boxes
                add_action('admin_init', array(&$this, 'add_meta_boxes'));

                // Add scripts
           //     add_action('admin_enqueue_scripts', array(&$this, 'admin_enqueue_scripts'));
            }

            // Add admin menus
            add_action('admin_menu', array(&$this, 'admin_menu'));

    }

    /**
     * Admin panel menu option
     * WordPress Hook - admin_menu
     *
     * @param none
     * @return void
     */
    public function admin_menu() {

        add_submenu_page(
                $this->getPlugin()->getSlug() .'_menu10_settings' // parent slug
                , $this->getPlugin()->getName() . ' - Settings Submenu' // page title
                , 'Advanced' // menu title
                , 'manage_options'  // capability
                , $this->getPlugin()->getSlug() . '_' .$this->moduleSlug  // menu slug
                , array($this->getPlugin()->getModule('Menu20Settings'), 'dispatch') //function that provides the html. You will receive a 'Module not found' error if the name doesnt match any class names in the Module directory
        );



    }

    /**
     * Add meta boxes
     *
     * @param none
     * @return void
     */
    public function add_meta_boxes() {


                add_meta_box(
                $this->getPlugin()->getSlug() . '_maintain'  //HTML id attribute of metabox
                , __('Maintenance', $this->getPlugin()->getSlug()) //title of the metabox.
                , array($this->getPlugin()->getModule('Admin'), 'meta_box_render') //function that prints the html
                , 'toplevel_page_' . $this->getPlugin()->getSlug() . '_' . $this->moduleSlug . '_group1' //the post type to show the metabox
                , 'main' //normal advanced or side The part of the page where the metabox should show
                , 'high' // 'high' , 'core','default', 'low' The priority within the context where the box should show
                , array('metabox' => $this->moduleSlug . '_metabox_maintain') //callback arguments.  'metabox' is the folder,  'settings_sub_menu_example_metabox1' is the template file
        );





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
        wp_enqueue_style($this->getPlugin()->getSlug() . '-admin-page', $this->getPlugin()->getPluginUrl() . '/admin/css/settings.css', array(), $this->getPlugin()->getVersion());
        wp_enqueue_script('jquery-form');
        wp_enqueue_script('post');

        if (function_exists('add_thickbox')) {
            add_thickbox();
        }
    }

    /**
     * Render settings page
     *
     * @param none
     * @return void
     */
    public function render() {
        //require a template whose name is the same as the menu_slug
        require_once($this->getPlugin()->getDirectory() . '/admin/templates/' . $this->moduleSlug . '.php');
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
    public function _save($reload=false) {
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
        $query='delete from wp_options where option_name = \'' . SIMPLI_HELLO_SLUG . '_options\'';
	$dbresult=$wpdb->query($query);

       /* if no rows affected, that means the defaults havent been changed yet and stored in the database*/
        if ($dbresult===0) {
            $message='Settings are already at defaults!';
        }elseif($dbresult===false){//returns false on error

            $message='Setting reset failed due to database error.';
        }

        $this->getPlugin()->saveSettings();

        if ($logout) {
            wp_logout();
        }
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
        $option_defaults= $this->getPlugin()->getSettingDefaults();
        $options=array_merge($option_defaults,$existing_options);


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
     * Controls hiding or unhiding of mu-plugin listing
       * If listing is disabled, will show only if there are other must use plugins.
     * WordPress Hook - enqueue_scripts
     *
     * @param none
     * @return void
     */

         function toggle_mu_plugins_listing($show, $type)
    {

             if ($this->getPlugin()->getSetting('must_use_plugins_listing')==='enabled') {
                 return true;
             }
             elseif(count(get_mu_plugins())===1){
                 return false;
             }
             else
             {
           return true;
             }
    }
}
