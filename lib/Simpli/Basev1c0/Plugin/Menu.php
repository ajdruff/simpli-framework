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

    protected $_metabox_default_states;
    protected $_menu_page_hook_name;
    protected $_menu_slug;
    static private $_menus;

    /**
     * Get Metabox States
     *
     * @param none
     * @return array $this->$_metabox_default_states;
     */
    public function getMetaboxDefaultStates() {
        return $this->_metabox_default_states;
    }

    /**
     * Set Metabox Default States
     *
     *
     * Usage:
      setMetaboxDefaultStates(array
      (
      'simpli_hello_about' => array('state' => 'closed', 'first' => false)
      , 'simpli_hello_hellosettings' => array('state' => 'closed', 'first' => true)
      ));
     * @param array $metabox_default_states
      index of each element is the id of the metabox
     * the value of the element is an array with 'state' = 'closed' or 'open'
     * 'first'=>true means that it will keep that state only until the user changes it. the next visit it will reflect the state the user changed it to
     * if 'first'=>false , the box will retain that state with every visit to the page, no matter if the user previously changed it.



     * @return object $this
     */
    public function setMetaboxDefaultStates($metabox_default_states) {

        if (!is_array($metabox_default_states)) {
            return $this;
        }


        /*
         * Apply defaults to array if not all the settings were provided
         * This also ensures that if an element wasnt provided, it wont
         * break while the array is accessed
         */
        $defaults = array('state' => 'open', 'persist' => false);

        foreach ($metabox_default_states as $id => $metabox_state) {
            $metabox_default_states[$id] = array_merge($defaults, $metabox_state);
        }

//            echo '<pre>';

//            echo '</pre>';


        $this->_metabox_default_states = $metabox_default_states;
        return $this;
    }

    /**
     * Get Menu Slug
     *
     * @param none
     * @return string
     */
    public function getMenuSlug() {

        return $this->_menu_slug;
    }

    /**
     * Get Menu Slug (same as Page Query Variable of menu page)
     *
     * @param string $menu_slug The menu_slug as added by add_menu or add_submenu
     * @return object $this
     */
    public function setMenuSlug($menu_slug) {
        $this->_menu_slug = $menu_slug;

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


        if(!is_admin()){return;}



        /*
         * Fire all actions that must occur after the menu page has been added
         */
        add_action($this->getPlugin()->getSlug() . '_menuPageAdded', array($this, 'hookMenuPageAdded'));


        /*
         * Add Menu Page Created in the Parent Class
         */
        add_action('admin_menu', array($this, 'hookAdminMenu'));

        /*
         *  add custom ajax handlers
         *
         * adding a wp_ajax hook in this format will execute the specified class method whenever the ajax request specifies an action = to $this->getPlugin()->getSlug() . '_xxxx'
          // see http://codex.wordpress.org/Plugin_API/Action_Reference/wp_ajax_%28action%29
         *
         */
        //this is where you map any form actions with the php function that handles the ajax request

        /* save without reloading the page */
        add_action('wp_ajax_' . $this->getPlugin()->getSlug() . '_settings_save', array($this, 'hookAjaxSave'));

        /* save with reloading the page */
        add_action('wp_ajax_' . $this->getPlugin()->getSlug() . '_settings_save_with_reload', array($this, 'hookAjaxSaveWithReload'));




        add_action('wp_ajax_' . $this->getPlugin()->getSlug() . '_settings_reset', array($this, 'hookAjaxReset'));

        /*
         * Reset all settings to defaults
         *
         */
        add_action('wp_ajax_' . $this->getPlugin()->getSlug() . '_settings_reset_all', array($this, 'hookAjaxResetAll'));
        /*
         * Manuall Update settings so as to add any newly added settings due to a developer update
         *
         */
        add_action('wp_ajax_' . $this->getPlugin()->getSlug() . '_settings_update_all', array($this, 'hookAjaxUpdateAll'));



// add ajax action
        add_action('wp_ajax_' . $this->getPlugin()->getSlug() . '_ajax_metabox', array($this, 'hookAjaxMetabox'));
        add_action('wp_ajax_' . $this->getPlugin()->getSlug() . '_ajax_metabox_cache', array($this, 'hookAjaxMetaboxCache'));


        add_action('current_screen', array($this, 'hookCurrentScreen'));



        $this->addMenuHooks();

    }

    /**
     * Check if Current Page is Menu
     *
     * Does a simple check of the $_GET['page'] variable to see if it contains this menu's slug.
     * Use it to make sure you dont take any action for pages that this module doesnt apply to
     * @param string $content The shortcode content
     * @return string The parsed output of the form body tag
     */
    public function pageIsMenu() {
        $result = false;
        if (isset($_GET['page']) && strpos($_GET['page'], $this->getMenuSlug()) !== false) {
            $result = true;
        }
        return ($result);
    }

    /**
     * Short Description
     *
     * Long Description
     * @param string $content The shortcode content
     * @return string The parsed output of the form body tag
     */
    function hookCurrentScreen() {


        if (!$this->pageIsMenu()) {
            return;
        }

        /*
         * Set some metaboxes as closed
         */
        add_action('get_user_option_closedpostboxes_' . $this->getScreenId(), array($this, 'hookCloseMetaboxes'));
    }

    /**
     * Short Description
     *
     * Long Description
     * @param string $content The shortcode content
     * @return string The parsed output of the form body tag
     */
    function hookMenuPageAdded() {
        /*
         *
         * Add metaboxes whenever the page matches the menu slug
         *
         */

        if (!$this->pageIsMenu()) {
            return;
        }

        add_action('current_screen', array(&$this, 'add_meta_boxes')); //action must be 'current_screen' so screen object can be accessed by the add_meta_boxes function
        // Add scripts
        add_action('admin_enqueue_scripts', array(&$this, 'base_admin_enqueue_scripts'));
    }

    /**
     * Add a Menu Page
     *
     * Wrapper around add_menu_page so we can capture the page hook and still provide a nice api interface
     * @param string $content The shortcode content
     * @return string The parsed output of the form body tag
     */
    public function addMenuPage($page_title, $menu_title, $capability, $icon_url = '', $position = null) {

        /*
         * We track whether this is a top_level menu or a sub menu by
         * adding it to a static tracking array, self::$_menus.
         */
        if (is_null(self::$_menus)) {
            $type = 'top_level';
        } else {
            $type = 'sub';
        }

        /*
         * If $menu_title is an array, we use the 'menu' element as the menu title,
         * and the 'sub_menu' element as the sub menu title that can be seen when hovering over the main menu name.
         */
        if (is_array($menu_title)) {
            $sub_menu_title = $menu_title['sub_menu'];
            $menu_title = $menu_title['menu'];
        } else {
            $sub_menu_title = null;
        }
        /*
         * Set the Menu Slug
         * One of the reasons that the framework needs to provide
         * its own add_menu_page wrapper is to ensure that the
         * menu slugs are created consistently
         *
         */

        $menu_slug = $this->getPlugin()->getSlug() . '_' . $this->getSlug();
        $this->setMenuSlug($menu_slug);

        /*
         * Class Method to display the HTML for the menu
         */

        $function = array($this, 'renderMenuPage');

        /* Set the Menu Position
         *
         * Using the default menu position is a good way to avoid conflict
         * with other plugins
         */
        if (is_null($position)) {
            $position = $this->getPlugin()->getModule('Admin')->getMenuPosition();
        }

        /*
         * The wrapper will create either a Top Level menu (i.e., main menu) or
         * create a submenu, depending on whether this module is the first to load.
         * If you want to ensure your module creates the main menu, make sure
         * it is the first to load by naming it in a way that sorts at the top of the
         * directory listing for your other menus. This is why the framework names its
         * modules as 'Menu10..., Menu20..,etc;'
         */

        if ($type === 'top_level') {
            $this->setMenuPageHookName(
                    add_menu_page(
                            $page_title// page title
                            , $menu_title // menu title
                            , $capability // capability
                            , $menu_slug  // menu slug .
                            , $function //function to display the html
                            , $icon_url // icon url
                            , $position //position in the menu
                    )
            );

            //  add_action($this->getMenuPageHookName(), array($this, 'addPageActions'));
            if (!is_null($sub_menu_title)) {
                add_submenu_page(
                        $menu_slug  // parent slug
                        , $page_title // page title
                        , $sub_menu_title // Submenu title
                        , $capability  // capability
                        , $menu_slug  // make sure this is the same slug as the main menu so it overwrites the main menus submenu title
                        , $function //function to display the html
                );
            }
        } else {//if not top level, add it as a submenu

            /*
             * Get the parent slug
             * which is the key of the first element of the static _menus array
             */
            reset(self::$_menus);
            $parent_slug = key(self::$_menus);

            /*
             * Add the submenu
             */

            add_submenu_page(
                    $parent_slug // parent slug
                    , $page_title // page title
                    , $menu_title // Submenu title
                    , $capability  // capability
                    , $menu_slug  // make sure this is the same slug as the main menu so it overwrites the main menus submenu title
                    , $function //function to display the html
            );
        }

        /*
         * Add an entry into the menus array so we can
         * both determine the top level menu,as well
         * as access the key properties from other methods
         */
        self::$_menus[$menu_slug] = array(
            'capability' => $capability
            , 'type' => $type
        );
        do_action($this->getPlugin()->getSlug() . '_menuPageAdded');



    }

    /**
     * Add Menu Page Hook
     * WordPress Hook - hookAdminMenu
     *
     * @param none
     * @return void
     */
    public function hookAdminMenu() {

        if (!$this->pageIsMenu()) {
            return;
        }
        throw new Exception('You are missing a required hookAdminMenu method in  ' . get_class($this));
    }

    /**
     * Add meta boxes
     *
     * @param none
     * @return void
     */
    public function hookAddMetaBoxes() {
        if (!$this->pageIsMenu()) {
            return;
        }
    }

    /**
     * Dispatch request for ajax metabox
     *
     * @param none
     * @return void
     */
    public function _AjaxMetabox($cache_timeout = 0) {

        //skip the pageIsMenu check since this is an ajax request and wont contain the $_GET page variable
        // Disable errors
        error_reporting(0);

        // Set headers
        header("Status: 200");
        header("HTTP/1.1 200 OK");
        header('Content-Type: text/html');
        header("Vary: Accept-Encoding");

        /*
         * set cache
         */
        if ($cache_timeout === 0) {
            header('Cache-Control: no-store, no-cache, must-revalidate');
            header('Cache-Control: post-check=0, pre-check=0', FALSE);
            header('Pragma: no-cache');
        } else {

            $expires = 60 * $cache_timeout;        // 15 minutes

            header('Pragma: public');
            header('Cache-Control: maxage=' . $expires);
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
            header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $expires) . ' GMT');
        }


        if (!wp_verify_nonce($_GET['_nonce'], $this->getPlugin()->getSlug())) {
            exit;
        }


        $request = new WP_Http;
        $request_result = $request->request($_GET['url']);
        $result['html'] = $request_result['body'];
        $result['metabox_id'] = $_GET['id'];

        echo json_encode($result);
        die();
    }

    /**
     * Ajax Action - Get Metabox with Cache
     *
     * @param none
     * @return void
     */
    public function hookAjaxMetaboxCache() {
        //skip the pageIsMenu check since this is an ajax request and wont contain the $_GET page variable
        $this->_AjaxMetabox(30);
    }

    /**
     * Ajax Action - Get Metabox without Cache
     *
     * @param none
     * @return void
     */
    public function hookAjaxMetabox() {
        //skip the pageIsMenu check since this is an ajax request and wont contain the $_GET page variable
        $this->_AjaxMetabox(0);
    }

    /**
     * Adds javascript and stylesheets to settings page in the admin panel.
     * WordPress Hook - enqueue_scripts
     *
     * @param none
     * @return void
     */
    public function base_admin_enqueue_scripts() {
        wp_enqueue_style($this->getPlugin()->getSlug() . '-admin-page', $this->getPlugin()->getUrl() . '/admin/css/settings.css', array(), $this->getPlugin()->getVersion());
        wp_enqueue_script('jquery');
        wp_enqueue_script('post');
        /* by enqueuing post, you are enqueuing all the following scripts required to handle metaboxes (except save-metabox-state, which is enqueued in the next step):
          wp_enqueue_script( ' wp-ajax-response' );  //required to save state
          wp_enqueue_script( 'wp-lists' );  //required for collapse/expand
          wp_enqueue_script( 'jquery-ui-core' ); // required for drag and drop
          wp_enqueue_script( 'jquery-ui-widget' ); //required for drag and drop
          wp_enqueue_script( 'jquery-ui-mouse' ); //required for drag and drop
          wp_enqueue_script( 'jquery-ui-sortable' );  //required for drag and drop
          wp_enqueue_script('postbox');  //required for save/state

         */






        $handle = $this->getPlugin()->getSlug() . '_save-metabox-state.js';
        $path = $this->getPlugin()->getDirectory() . '/admin/js/save-metabox-state.js';
        $inline_deps = null;
        $external_deps = array('post');
        $this->getPlugin()->enqueueInlineScript($handle, $path, $inline_deps, $external_deps);


        /*
         * Must pass onto the script the menu slug and current screen id so the metabox placement and expand/collapse will work properly
         */

        $vars = array(
            'menu_slug' => $this->getMenuSlug()
            , 'screen_id' => get_current_screen()->id
        );


        $this->getPlugin()->setLocalVars($vars);


        /*
         * Add javascript for form submission
         *
         */
        $handle = $this->getPlugin()->getSlug() . '_metabox-form.js';
        $path = $this->getPlugin()->getDirectory() . '/admin/js/metabox-form.js';
        $inline_deps = array();
        $external_deps = array('jquery');
        $this->getPlugin()->enqueueInlineScript($handle, $path, $inline_deps, $external_deps);

        $vars = array('metabox_forms' => array(
                'reset_message' => __('Are you sure you want to reset this form?', $this->getPlugin()->getTextDomain())
                , 'reset_all_message' => __('Are you sure you want to reset all the settings for this plugin to installed defaults?', $this->getPlugin()->getTextDomain())
        ));


        $this->getPlugin()->setLocalVars($vars);
    }

    /**
     * Render Menu Page
     *
     * @param none
     * @return void
     */
    public function renderMenuPage() {

        /*
         * Get the capability from the menu array that was updated
         * with the menu properties when it was added
         */

        $menu = self::$_menus[$this->getMenuSlug()];
        $capability = $menu['capability'];


        /*
         * require a template whose name is the same as the menu_slug
         * If it doesnt exist, use the default template
         */
        if (!current_user_can($capability)) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }

        $template_path = $this->getPlugin()->getDirectory() . '/admin/templates/' . $this->getSlug() . '.php';
        if (!file_exists($template_path)) {
            $template_path = $this->getPlugin()->getDirectory() . '/admin/templates/menu_settings_default.php';
        }

        require_once($template_path);
    }

    /**
     * Reset Settings
     *
     * @param none
     * @return void
     */
    public function hookAjaxReset() {

        //skip the pageIsMenu check since this is an ajax request and wont contain the $_GET page variable


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
    public function hookAjaxSave() {

        //skip the pageIsMenu check since this is an ajax request and wont contain the $_GET page variable


        $this->_save(false);
    }

    /**
     * Save Wrapper with Page Reload
     *
     * @param none
     * @return void
     */
    public function hookAjaxSaveWithReload() {
        //skip the pageIsMenu check since this is an ajax request and wont contain the $_GET page variable

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

        $message = __("Settings saved.", $this->getPlugin()->getTextDomain());
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

            $previous_setting_value = $setting_value;
            $setting_value = ((isset($_POST[$setting_name]) === true) ? $_POST[$setting_name] : $previous_setting_value);
// validtion errors here
//            if ($setting_name == 'cache_timeout') {
//                if (!is_numeric($setting_value)) {
//                    $errors[] = "Cache Timeout must be a number";
//                    $setting_value = $previous_setting_value;
//                }
//            }
//
//
//            if ($setting_name == 'feed_urls') {
//
//                foreach ($setting_value as $key => $feed_url) {
//
//                    if ($feed_url != '') {
//                        // echo $feed_url;die();
//                        if (!filter_var($feed_url, FILTER_VALIDATE_URL)) {
//                            $errors[] = "Feed URL " . $feed_url . " is not a valid url";
//                            $setting_value = $previous_setting_value;
//                        }
//                    }
//                }
//            }






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
    public function hookAjaxUpdateAll() {

        //skip the pageIsMenu check since this is an ajax request and wont contain the $_GET page variable


        if (!wp_verify_nonce($_POST['_wpnonce'], $this->getPlugin()->getSlug())) {
            return false;
        }


        $message = __("Settings have been updated", $this->getPlugin()->getTextDomain());
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
    public function hookAjaxResetAll() {

        //skip the pageIsMenu check since this is an ajax request and wont contain the $_GET page variable


        if (!wp_verify_nonce($_POST['_wpnonce'], $this->getPlugin()->getSlug())) {
            return false;
        }

        $message = __("All Settings Have been reset to initial defaults.", $this->getPlugin()->getTextDomain());

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

    /**
     * Get Menu Page Hook Name
     *
     * @param none
     * @return string
     */
    public function getMenuPageHookName() {
        return $this->_menu_page_hook_name;
    }

    /**
     * Set Menu Page Hook Name
     *
     * @param string $menu_page_hook_name
     * @return object $this
     */
    public function setMenuPageHookName($menu_page_hook_name) {
        $this->_menu_page_hook_name = $menu_page_hook_name;
        return $this;
    }

    /**
     * Get Screen
     *
     * @param none
     * @return string
     */
    public function getScreenId() {
        $screen = get_current_screen();
        return $screen->id;
    }

    /**
     * Hook - Close Metaboxes
     * WordPress Hook Filter Function for 'get_user_option_closedpostboxes_{screen_id}'
     *
     * Long Description
     * @param array $closed_metaboxes
     * @return array $closed_metaboxes
     *
     */
    public function hookCloseMetaboxes($closed_metaboxes) {
        if (!$this->pageIsMenu()) {
            return($closed_metaboxes);
        }

        /* Check whether any metabox positions have been saved and if not, consider
         * this a 'first visit'  and ensure the initial argument type is an array
         * ensure that data type is array to avoid errors when empty
         */
        if (!is_array($closed_metaboxes)) {
            $first_visit = true;
            $closed_metaboxes = array();
        } else {
            $first_visit = false;
        }




        $metaboxDefaultStates = $this->getMetaboxDefaultStates();


        /*
         * exit the filter if no default states have been set
         */
        if (!is_array($metaboxDefaultStates)) {
            return $closed_metaboxes;
        }


        /*
         * iterate through each of the default states and add the metabox
         * id to the filter if the metabox is to be closed
         */

        foreach ($metaboxDefaultStates as $metabox_id => $preferences) {

            if ($preferences['state'] == 'closed') {
                /*
                 * if this is the first visit, and user wanted to apply defaults only to first visit
                 * or if this is not the first visit, and the user wanted to apply them always
                 * then apply the preference
                 */
                if (($first_visit) || (!$first_visit && $preferences['persist'])) {
                    if (array_search($metabox_id, $closed_metaboxes) === false) { //if the closed array didnt contain the metabox
                        $closed_metaboxes[] = $metabox_id;
                    }
                } else {

                }
            } else {

                if (($first_visit && $preferences['first']) || (!$first_visit && $preferences['persist'])) {

                    $key = array_search($metabox_id, $closed_metaboxes);
                    if ($key !== false) {
                        $closed_metaboxes[$key] = '';
                    }
                }
            }
        }



        return $closed_metaboxes;
    }

    /**
     * Renders a meta box
     *
     * @param string $module
     * @param array $metabox
     * @return void
     */
    public function renderMetaBoxTemplate($module, $metabox) {

        /*
         * If no template path provided, use the metabox id as the template name and /admin/templates/metabox as the path
         */
        $template_path = $this->getPlugin()->getDirectory() . '/admin/templates/metabox/' . $metabox['id'] . '.php';
        if (isset($metabox['args']['path'])) {
            $template_path = $metabox['args']['path'];
        }
        if (!file_exists($template_path)) {
            _e('Not available at this time.', $this->getPlugin()->getTextDomain());
            $this->getPlugin()->debug()->logcError($this->getPlugin()->getSlug() . ' : Meta Box ' . $metabox['id'] . ' error - template path does not exist ' . $template_path);
            return;
        }
        ob_start();
        include($template_path);
        $template=ob_get_clean();

       echo do_shortcode($template); //using buffer and do_shortcode is required to allow shortcodes to work within an included file, otherwise they dont render.
    }

    /**
     * Renders a meta box using an Ajax Request
     *
     * @param string $module
     * @param array $metabox
     * @return void
     */
    public function renderMetaBoxAjax($module, $metabox) {


        include($this->getPlugin()->getDirectory() . '/admin/templates/metabox/ajax.php');
    }

}