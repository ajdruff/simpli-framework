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
class Simpli_Hello_Module_Menu10Settings extends Simpli_Basev1c0_Plugin_Module {

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
               add_action('admin_enqueue_scripts', array(&$this, 'admin_enqueue_scripts'));
               add_action('admin_notices', array(&$this, 'showDisabledMessage'));
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

/*
 *
 * Add the main menu
 *
 */

        add_menu_page(
                $this->getPlugin()->getName() . ' - General Settings' // page title
                , $this->getPlugin()->getName() // menu title
                , 'manage_options'  // capability
                , $this->getPlugin()->getSlug() . '_' . $this->moduleSlug  // menu slug
                // , array($this->getPlugin()->getModule($this->moduleName), 'dispatch') //function
                , array($this->getPlugin()->getModule('Menu10Settings'), 'dispatch') //function to display the html
                , $this->getPlugin()->getPluginUrl() . '/admin/images/menu.png' // icon url
                , $this->getPlugin()->getModule('Admin')->getMenuPosition() //position in the menu

                      );







/*
 *
 * Add a submenu that points to the same page as the main menu
 * This allows us to create a menu title that is different than the main heading
 *
 */

             add_submenu_page(
                $this->getPlugin()->getSlug() .'_menu10_settings' // parent slug
                , $this->getPlugin()->getName() . ' - General Settings' // page title
                , 'General Settings' // menu title
                , 'manage_options'  // capability
                , $this->getPlugin()->getSlug() .'_menu10_settings'  // make sure this is the same slug as the main menu so it overwrites the main menus submenu title
                , array($this->getPlugin()->getModule('Menu10Settings'), 'dispatch') //function to display the html
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
                $this->getPlugin()->getSlug() . '_general'  //HTML id attribute of metabox
                , __('Basic Setup', $this->getPlugin()->getSlug()) //title of the metabox.
                , array($this->getPlugin()->getModule('Admin'), 'meta_box_render') //function that prints the html
                , 'toplevel_page_' . $this->getPlugin()->getSlug() . '_' . $this->moduleSlug . '_group1' //the post type to show the metabox
                , 'main' //normal advanced or side The part of the page where the metabox should show
                , 'high' // 'high' , 'core','default', 'low' The priority within the context where the box should show
                , array('metabox' => $this->moduleSlug . '_metabox_general') //callback arguments.  'metabox' is the folder,  'settings_sub_menu_example_metabox1' is the template file
        );


        add_meta_box(
                $this->getPlugin()->getSlug() . '_updates' //HTML id attribute of metabox
                , __('Plugin Updates', $this->getPlugin()->getSlug()) //title of the metabox
                , array($this->getPlugin()->getModule('Admin'), 'meta_box_render') //function that prints the html
                , 'toplevel_page_' . $this->getPlugin()->getSlug() . '_' . $this->moduleSlug . '_group2' //the post type to show the metabox
                , 'side'//normal advanced or side The part of the page where the metabox should show
                , 'low' // 'high' , 'core','default', 'low' The priority within the context where the box should show
                , array('metabox' => 'ajax', 'url' => 'http://www.simpliwp.com/simpli-framework/metabox-updates-example/') //callback arguments. file that contains the html for the metabox. metabox is the folder, 'settings-example' is the file in the folder
        );

        add_meta_box(
                $this->getPlugin()->getSlug() . '_support' //HTML id attribute of metabox
                , __('Support', $this->getPlugin()->getSlug()) //title of the metabox
                , array($this->getPlugin()->getModule('Admin'), 'meta_box_render') //function that prints the html
                , 'toplevel_page_' . $this->getPlugin()->getSlug() . '_' . $this->moduleSlug . '_group2' //the post type to show the metabox
                , 'side'//normal advanced or side The part of the page where the metabox should show
                , 'low' // 'high' , 'core','default', 'low' The priority within the context where the box should show
                , array('metabox' => 'ajax', 'url' => 'http://www.simpliwp.com/simpli-framework/metabox-support-example/') //callback arguments. file that contains the html for the metabox. metabox is the folder, 'settings-example' is the file in the folder
        );

        add_meta_box(
                $this->getPlugin()->getSlug() . '_feedback' //HTML id attribute of metabox
                , __('Feedback', $this->getPlugin()->getSlug()) //title of the metabox
                , array($this->getPlugin()->getModule('Admin'), 'meta_box_render') //function that prints the html
                , 'toplevel_page_' . $this->getPlugin()->getSlug() . '_' . $this->moduleSlug . '_group2' //the post type to show the metabox
                , 'side'//normal advanced or side The part of the page where the metabox should show
                , 'low' // 'high' , 'core','default', 'low' The priority within the context where the box should show
                , array('metabox' => 'ajax', 'url' => 'http://www.simpliwp.com/simpli-framework/metabox-feedback-example/') //callback arguments. file that contains the html for the metabox. metabox is the folder, 'settings-example' is the file in the folder
        );

        add_meta_box(
                $this->getPlugin()->getSlug() . '_donate' //HTML id attribute of metabox
                , __('Donate', $this->getPlugin()->getSlug()) //title of the metabox
                , array($this->getPlugin()->getModule('Admin'), 'meta_box_render') //function that prints the html
                , 'toplevel_page_' . $this->getPlugin()->getSlug() . '_' . $this->moduleSlug . '_group2' //the post type to show the metabox
                , 'side'//normal advanced or side The part of the page where the metabox should show
                , 'low' // 'high' , 'core','default', 'low' The priority within the context where the box should show
                , array('metabox' => 'ajax', 'url' => 'http://www.simpliwp.com/simpli-framework/metabox-donate-example/') //callback arguments. file that contains the html for the metabox. metabox is the folder, 'settings-example' is the file in the folder
        );


    }

    public function metaboxEcho($module, $metabox = array()) {

        echo $metabox['args']['text'];
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
          wp_enqueue_script('jquery');
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
     * Shows a disabled message if the plugin is disabled via the settings
     * This will only appear when first switching to the general settings page. Its assumed that the settings that trigger
     * it are set on a different (advanced) menu page.
     *
     */
    public  function showDisabledMessage(){


        //dont show if you are not on the main menu ( general settings )
if (isset($_GET['page']) && $_GET['page']!='simpli-hello_menu10_settings') {return;}

//dont show if the plugin is enabled
if (($this->getPlugin()->getSetting('plugin_enabled')=='enabled') ) {return;}

        ?>



    <div class="error">
        <p><strong>You have disabled <?php echo $this->getPlugin()->getName() ?> functionality.</strong> To re-enable <?php echo $this->getPlugin()->getName() ?> , set  'Maintenance -> Enable Plugin' to 'Yes'.</p>
    </div>

            <?php

    }

}
