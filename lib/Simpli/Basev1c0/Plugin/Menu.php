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
    static private $_menus = null;

    /**
     * Get Top Level Menu Slug
     *
     * @param none
     * @return string
     */
    public function getTopLevelMenuSlug() {
        $this->debug()->t();
        $menus = $this->getMenuTracker();
        $this->debug()->logVar('$menus = ', $menus);
        $result = key($menus);
        $result = $menus[$result]['top_level_slug'];
        /*
         * if there is only one menu item, then
         * the item must be this one, so use this slug.
         * this allows custom post types to change the top level menu
         * to their own slug in the event they are made the top level menu
         */
        $this->debug()->logVars(get_defined_vars());

        $this->debug()->logVar('$result = ', $result);




        return $result;
    }

    /**
     * Get Menu Tracker
     *
     * Returns an array if the menus added, with the menu slug as the associate index
     * @param none
     * @return string
     */
    public function getMenuTracker() {
        $this->debug()->t();
        if (is_null(self::$_menus)) {
            self::$_menus = array();
        }
        return self::$_menus;
    }

    /**
     * Add Menu To Tracker
     *
     * @param none
     * @return string
     */
    public function addMenuToTracker($menu_slug) {
        self::$_menus[$menu_slug] = array();
    }

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
        $this->debug()->t();

        if (!is_admin()) {
            return;
        }



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



        // $this->addMenuHooks();
    }

    /**
     * Config
     *
     * Configures the module. Must be called by child module or menus wont be loaded.
     *
     * @param none
     * @return void
     */
    public function config() {
        $this->debug()->t();
        /*
         * We track whether this is a top_level menu or a sub menu by
         * checking to see if this is the first menu added. This is strictly based
         * on the order the modules are loaded, which is dictated by the alphabetical order
         * of the names of your module files.
         * If you want a menu to be top level,  you need to change the naming of your module class and
         * the the module file name. A good convention is MenuXX<arbitrary_name> , where XX is a number which
         * forces the correct sorting.
         * The purpose of this architecture is to make it easier to rearrange the sorting by simply renaming modules, without
         * having to make any code changes.
         */
        $menus = $this->getMenuTracker();
        $this->debug()->logVar('$menus = ', $menus);

        if (is_null($menus) || (empty($menus) === true)) {
            $this->debug()->log('Setting Menu Level to Top Level');
            $this->setMenuLevel('top_level');
        } else {
            $this->debug()->log('Setting Menu Level to Sub Menu');
            $this->setMenuLevel('sub_menu');
        }

        /*
         * Set the Menu Slug
         *          *
         */


        $this->setMenuSlug($this->getPlugin()->getSlug() . '_' . $this->getSlug());
        //$this->updateMenuTracker($this->getMenuSlug(), array('top_level_slug'=>'edit.php?post_type=simpli_hello_snippet'));

        $this->updateMenuTracker($this->getMenuSlug(), array('top_level_slug' => $this->getMenuSlug()));
    }

    protected $_page_check_cache = null;

    /**
     * Page Check
     *
     * Use for hook functions. Checks to see if we are on the right page before we add any hook actions.
     * For optimization, cache the result the first time,so subsequent checks on the same page dont have to rebuild the result.
     * Usage:
     * if ($this->pageCheck(get_query_var('page'),$this->getMenuSlug)
     * @param none
     * @return boolean
     */
    protected function pageCheck() {

        /*
         * if either the page or the post_type matches, return true to verify the page check
         */

        if (is_null($this->_page_check_cache)) {


            $postCheck = (isset($_GET['post_type']) && $_GET['post_type'] === $this->getPostType()); //does post type in query match?
            $pageQueryVarCheck = (isset($_GET['page']) && $_GET['page'] === $this->getMenuSlug()); //does page match our page?


            $result = ($postCheck || $pageQueryVarCheck);
            /*
             * if the previous checks fail, then check the post object's post type
             */
            if (!$result) {
                $post = $this->getPlugin()->getTools()->getPost(); //get the post object
                $this->debug()->logVar('$post = ', $post);
                if (is_object($post)) {
                    $result = ($post->post_type === $this->getPostType()); //check the post object type against what we want
                } else {
                    $result = false;
                }
            }
            $result = ($pageQueryVarCheck); //remove this after testing
            $this->_page_check_cache = $result; //save the result to 'cache'
            $this->debug()->logVar('$result = ', $result);
        }



        return ($this->_page_check_cache);
    }

    /**
     * Hook Current Screen
     *
     * Hook Function on Current Screen
     * @param string $content The shortcode content
     * @return string The parsed output of the form body tag
     */
    function hookCurrentScreen() {


        if (!$this->pageCheck()) {
            return;
        }

        /*
         * Set some metaboxes as closed.
         * Must hook into current screen so we dont hook in too early
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
         * Add metaboxes and scripts only when we are on the page we want
         *
         */

        if (!$this->pageCheck()) {
            return;
        }

        /*
         * Add our meta boxes
         * Hook into 'current_screen' .
         * We dont use the 'add_meta_boxes' action since it will not work when used
         * with a custom post editor.
         */

        add_action('current_screen', array($this, 'hookAddMetaBoxes')); //action must be 'current_screen' so screen object can be accessed by the add_meta_boxes function
        // Add scripts
        add_action('admin_enqueue_scripts', array($this, 'hookEnqueueBaseClassScripts'));
    }

    /**
     * is Top Level
     *
     * Whether the current menu is the top level menu.
     *
     * @param none
     * @return boolean
     */
    public function isTopLevel() {
        $menus = $this->getMenuTracker();
        if (key($menus) === $this->getMenuSlug()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Removes a Menu Page
     *
     * Removes a menu page from the menu
     *
     * @param none
     * @return void
     */
    public function removeMenuPage($menu_slug) {
        reset(self::$_menus);
        $parent_slug = key(self::$_menus);
        remove_submenu_page($parent_slug, $menu_slug);
    }

    protected $_menu_level;

    /**
     * Set Menu Level
     *
     * @param $menu_level
     * @return none
     */
    public function setMenuLevel($menu_level) {
        $this->debug()->t();
        $this->debug()->log('Set Menu Level for ' . $this->getSlug() . ' to ' . $menu_level);
        $this->_menu_level = $menu_level;
    }

    /**
     * Get Menu Level
     *
     * @param none
     * @return string
     */
    public function getMenuLevel() {
        return $this->_menu_level;
    }

    /**
     * Add a Menu Page
     *
     * Wrapper around add_menu_page so we can capture the page hook and still provide a nice api interface
     * @param string $content The shortcode content
     * @return string The parsed output of the form body tag
     */
    public function addMenuPage($page_title, $menu_titles, $capability, $icon_url = '', $position = null) {

        $this->debug()->logVars(get_defined_vars());

        /*
         * If $menu_title is an array, we use the 'menu' element as the menu title,
         * and the 'sub_menu' element as the sub menu title that can be seen when hovering over the main menu name.
         */
        if (is_array($menu_titles)) {

            if ($this->isTopLevel()) {
                $menu_title = $menu_titles['menu'];
                $sub_menu_title = $menu_titles['sub_menu'];
            } else {
                $menu_title = $menu_titles['sub_menu'];
            }
        } else {
            $menu_title = $menu_titles;
            $sub_menu_title = null;
        }


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

        if ($this->getMenuLevel() === 'top_level') {
            $this->debug()->log('Adding menu ' . $menu_title . '  as top level');
            $this->setMenuPageHookName(
                    add_menu_page(
                            $page_title// page title
                            , $menu_title // menu title
                            , $capability // capability
                            , $this->getMenuSlug()  // menu slug .
                            , $function //function to display the html
                            , $icon_url // icon url
                            , $position //position in the menu
                    )
            );

            //  add_action($this->getMenuPageHookName(), array($this, 'addPageActions'));
            if (!is_null($sub_menu_title)) {
                add_submenu_page(
                        $this->getTopLevelMenuSlug()  // parent slug
                        , $page_title // page title
                        , $sub_menu_title // Submenu title
                        , $capability  // capability
                        , $this->getMenuSlug()  // make sure this is the same slug as the main menu so it overwrites the main menus submenu title
                        , $function //function to display the html
                );
            }
        } else {//if not top level, add it as a submenu

            /*
             * Get the parent slug
             *
             */

            //$parent_slug =$this->getTopLevelMenuSlug();

            /*
             * Add the submenu
             */
            $this->debug()->log('Adding menu ' . $menu_title . '  as sub menu');
            add_submenu_page(
                    $this->getTopLevelMenuSlug()// parent slug
                    , $page_title // page title
                    , $menu_title // Submenu title
                    , $capability  // capability
                    , $this->getMenuSlug()  // make sure this is the same slug as the main menu so it overwrites the main menus submenu title
                    , $function //function to display the html
            );
        }

        /*
         * Add an entry into the menus array so we can
         * both determine the top level menu,as well
         * as access the key properties from other methods
         */

        $this->updateMenuTracker($this->getMenuSlug(), array('capability' => $capability
            , 'level' => $this->getMenuLevel(), 'top_level_slug' => $this->getMenuSlug()));
        do_action($this->getPlugin()->getSlug() . '_menuPageAdded');
    }

    /**
     * Update Menu Tracker
     *
     * Updates Menu Tracker . The menu tracker is used to keep track of which menu is top level,
     * which is sublevel, and other properties
     *
     * @param string $menu_slug
     * @param array $properties Selected properties of the menu
     * @return void
     */
    public function updateMenuTracker($menu_slug, $properties) {
        $this->debug()->t();
        $this->debug()->logVar('$menu_slug = ', $menu_slug);
        $this->debug()->logVar('$properties = ', $properties);
        $menus = $this->getMenuTracker();
        $this->debug()->logVar('$menus = ', $menus);

        /*
         * if there are already properties associated with the menu tracker for
         * this element, then merge them, otherwise, just add the properties that
         * were passed
         */
        if (isset($menus[$menu_slug]) and is_array($menus[$menu_slug])) {
            $properties = array_merge($menus[$menu_slug], $properties);
        }

        self::$_menus[$menu_slug] = $properties;
        $this->debug()->logVar('self:$_menus = ', self::$_menus);
    }

    /**
     * Get Editor Top Level Menu Slug
     *
     * Returns null unless the we are on the page that is actually the editor.
     * This method allows the editor to appear on the main menu only when actually editing.
     * All other times it will be null, removing  the title from the menu.
     *
     * @param none
     * @return void
     */
    public function getEditorTopLevelMenuSlug() {

        if (isset($_GET['page']) && $_GET['page'] === $this->getMenuSlug()) {
            return $this->getTopLevelMenuSlug();
        } else {
            return null;
        }
    }

    /**
     * Add a Custom Post Editor Page
     *
     * This is very similar to addMenuPage but forces parent to be edit.php and hardcodes some other paramaters, as well
     * as immediately removing the page from the menu. This allows you to use the page added as an editor by redirecting
     * the edit action to it.
     * the page can be accessed at : /wp-admin/edit.php?page=simpli_hello_post_editor
     * you can look at $hookname to confirm the page slug.
     *
     * Wrapper around add_menu_page so we can capture the page hook and still provide a nice api interface
     * @param string $content The shortcode content
     * @return string The parsed output of the form body tag
     */
    public function addCustomPostEditor($page_title, $menu_title, $capability, $icon_url = '') {

        $this->debug()->t();

        if (!$this->CUSTOM_POST_EDITOR_ENABLED) {
            return;
        }

        /*
         * Class Method to display the HTML for the menu
         */

        $function = array($this, 'renderMenuPage');


        /*
         * Add the submenu
         * $hookname returns the page slug in the format post_page_<page_slug>
         */

        $hookname = add_submenu_page(
                $this->getEditorTopLevelMenuSlug() // parent slug
                , $page_title // page title
                , 'Custom Post Editor' // Submenu title
                , $capability  // capability
                , $this->getMenuSlug()
                , $function //function to display the html
        );

        $this->debug()->logVar('$hookname = ', $hookname);
        /*
         * immediately remove the submenu item we just added so
         * we still have the mapping of the url, but dont keep the menu item visible
         */
        // remove_submenu_page($this->getTopLevelMenuSlug() , $this->getMenuSlug() );

        /*
         * Add an entry into the menus array so we can
         * both determine the top level menu,as well
         * as access the key properties from other methods
         */

        $this->updateMenuTracker($this->getMenuSlug(), array('capability' => $capability
            , 'level' => $this->getMenuLevel()));
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

        if (!$this->pageCheck()) {
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
        if (!$this->pageCheck()) {
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

        //skip the pageCheck check since this is an ajax request and wont contain the $_GET page variable
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
        //skip the pageCheck check since this is an ajax request and wont contain the $_GET page variable
        $this->_AjaxMetabox(30);
    }

    /**
     * Ajax Action - Get Metabox without Cache
     *
     * @param none
     * @return void
     */
    public function hookAjaxMetabox() {
        //skip the pageCheck check since this is an ajax request and wont contain the $_GET page variable
        $this->_AjaxMetabox(0);
    }

    /**
     * Enqueue Base Class Scripts ( Hook Function )
     *
     * Adds javascript and stylesheets to settings page in the admin panel.
     * WordPress Hook - admin_enqueue_scripts
     *
     * @param none
     * @return void
     */
    public function hookEnqueueBaseClassScripts() {
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

        ob_start();
        require($template_path);
        $output = ob_get_clean();

        echo do_shortcode($output);

    }

    /**
     * Reset Settings
     *
     * @param none
     * @return void
     */
    public function hookAjaxReset() {

        //skip the pageCheck check since this is an ajax request and wont contain the $_GET page variable


        if (!wp_verify_nonce($_POST['_wpnonce'], $this->getPlugin()->getSlug())) {
            //    return false;
        }

        $message = "Settings reset.";
        $errors = array();
        $reload = true;
        $logout = false; //whether you want to logout after settings are saved
        $user_option_defaults = $this->getPlugin()->getUserOptionDefaults();
        foreach ($this->getPlugin()->getUserOptions() as $setting_name => $setting_value) {
            /**
             * Set new setting value equal to the post value only if the setting was actually submitted, otherwise, keep the setting value the same.
             *  Add extra code to scrub the values for specific settings if needed
             */
            $setting_value = ((isset($_POST[$setting_name]) === true) ? $user_option_defaults[$setting_name] : $setting_value);

            $this->getPlugin()->setUserOption($setting_name, $setting_value);
        }


        $this->getPlugin()->saveUserOptions();

        if ($logout) {
            wp_logout();
        }
        require_once($this->getPlugin()->getDirectory() . '/admin/templates/ajax_message.php');

        die(); //required after require to ensure ajax request exits cleanly; otherwise it hangs and browser request is garbled.
    }

    /**
     * Save Wrapper - No Page Reload
     *
     * @param none
     * @return void
     */
    public function hookAjaxSave() {

        //skip the pageCheck check since this is an ajax request and wont contain the $_GET page variable


        $this->_save(false);
    }

    /**
     * Save Wrapper with Page Reload
     *
     * @param none
     * @return void
     */
    public function hookAjaxSaveWithReload() {
        //skip the pageCheck check since this is an ajax request and wont contain the $_GET page variable

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


        foreach ($this->getPlugin()->getUserOptions() as $setting_name => $setting_value) {
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






            $this->getPlugin()->setUserOption($setting_name, $setting_value);
        }


        $this->getPlugin()->saveUserOptions();

        if ($logout) {
            wp_logout();
        }
        //return a success message on submission
        require_once($this->getPlugin()->getDirectory() . '/admin/templates/ajax_message.php');

        die(); //required after require to ensure ajax request exits cleanly; otherwise it hangs and browser request is garbled.
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

        //skip the pageCheck check since this is an ajax request and wont contain the $_GET page variable


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
        $existing_options = $this->getPlugin()->getUserOptions();
        $option_defaults = $this->getPlugin()->getUserOptionDefaults();
        $options = array_merge($option_defaults, $existing_options);


        /*
         * Save back to the database ( do not use the $this->getPlugin()->saveUserOptions() method since that
         * will only use existing settings)
         *
         */

        $this->getPlugin()->saveUserOptions($options);


        if ($logout) {
            wp_logout();
        }
        require_once($this->getPlugin()->getDirectory() . '/admin/templates/ajax_message.php');

        die(); //required after require to ensure ajax request exits cleanly; otherwise it hangs and browser request is garbled.
    }

    /**
     * Reset All Settings
     *
     * @param none
     * @return void
     */
    public function hookAjaxResetAll() {

        //skip the pageCheck check since this is an ajax request and wont contain the $_GET page variable


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

        $this->getPlugin()->saveUserOptions();

        if ($logout) {
            wp_logout();
        }
        require_once($this->getPlugin()->getDirectory() . '/admin/templates/ajax_message.php');
        die(); //required after require to ensure ajax request exits cleanly; otherwise it hangs and browser request is garbled.
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
        if (!$this->pageCheck()) {
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
        $template = ob_get_clean();

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

    public $_post_type = null;

    /**
     * Set Post Type
     *
     * @param array $post_type
     * @return none
     */
    public function setPostType($post_type) {
        $this->_post_type = $post_type;
    }

    /**
     * Get Post Type
     *
     * @param none
     * @return string
     */
    public function getPostType() {
        return $this->_post_type;
    }

}