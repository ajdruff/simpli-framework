<?php

/**
 * Admin Menu (Class extension of Module)
 *
 * Manages the creation and ordering of menu pages with the ability to
 * save, load and manage options.
 *
 * @author Andrew Druffner
 * @package SimpliFramework
 *



 * @property string $MESSAGE_NONCE_FAILED The text to be displayed to the user if the nonce check fails
 * @property string $MESSAGE_SAVE_SUCCESS The text to be displayed to the user after saving the settings successfully
 * @property string $MESSAGE_RESET_SUCCESS The text to be displayed to the user after resetting the settings successfully
 * @property string $MESSAGE_UPDATE_SUCCESS The text to be displayed to the user after updating the settings successfully
 * @property string $MESSAGE_RESET The text to be displayed to the user in a javascript dialog box that will prompt the user if they really want to take this action.
 * @property string $MESSAGE_RESET_ALL The text to be displayed to the user in a javascript dialog box that will prompt the user if they really want to take this action.
 * @property string $MESSAGE_RESET_ALL_SUCCESS The text to be displayed to the user after resetting all the settings successfully
 * @property string $MESSAGE_RESET_ALL_NOCHANGES The text to be displayed to the user when no changes were made after a reset all.
 * @property string $MESSAGE_RESET_ALL_FAILED The text to be displayed to the user when an attempt to reset all the settings failed.
 *
 * @property string $TEXT The text to be displayed to the user *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 */
class Simpli_Frames_Base_v1c2_Plugin_Menu extends Simpli_Frames_Base_v1c2_Plugin_Module {

    protected $_menu_page_hook_name;

    /**
     *
     * @var string A unique identifier for the menu. Used by getMenuSlug
     */
    protected $_menu_slug;

    /**
     *
     * @var array A tracking array that tracks the menus as they are added. Used by getMenuTracker()
     */
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
        $this->debug()->logVar( '$menus = ', $menus );

        $result = $menus[ key( $menus ) ][ 'top_level_slug' ];
        /*
         * if there is only one menu item, then
         * the item must be this one, so use this slug.
         * this allows custom post types to change the top level menu
         * to their own slug in the event they are made the top level menu
         */
        $this->debug()->logVars( get_defined_vars() );

        $this->debug()->logVar( '$result = ', $result );




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
        if ( is_null( self::$_menus ) ) {
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
    protected function _addMenuToTracker( $menu_slug ) {
        self::$_menus[ $menu_slug ] = array();
    }

    /**
     * Get Metabox States
     *
     * @param none
     * @return array $this->$_meta_box_open_states;
     */
    public function getMetaboxOpenStatesOld() {
        return $this->_meta_box_open_states;
    }

    /**
     *
     * @var array Meta Box Initial Open Closed States
     */
    protected $_meta_box_open_statesOLD = null;

    /**
     * Set Meta Box Open State
     *
     * Sets the intial open or closed state of a meta box. If persistance is set to 'true' ,
     * the meta box will retain that state regardless of whether the user changes it.
     * With this method you can :
     * initially set the metabox to closed on first visit:
     * force metabox to always be closed when the page is visited:
     * force metabox to always be open when the page is visited:
     *
     * @param string $id The id of the meta box used in the add_meta_box method. Must be unique to the meta box.
     * @param boolean $open  True for open, False for closed
     * @param boolean $persist True will cause the meta box to keep the state indicated by the $open parameter value
     * at next visit to the page, even if the user changed it (i.e.: it ignores saved changes)
     * @return void
     */
    public function setMetaboxOpenStateOLD( $id, $open = true, $persist = false ) {

        /*
         * Apply defaults to array if not all the settings were provided
         * This also ensures that if an element wasnt provided, it wont
         * break while the array is accessed
         */


        $this->_meta_box_open_states[ $id ] = array( 'open' => $open, 'persist' => $persist );
    }

    /**
     * Set Metabox Default States
     *
     *
     * Usage:
      setMetaboxDefaultStates(array
      (
      'simpli_frames_about' => array('state' => 'closed', 'first' => false)
      , 'simpli_frames_hellosettings' => array('state' => 'closed', 'first' => true)
      ));
     * @param array $meta_box_open_states
      index of each element is the id of the metabox
     * the value of the element is an array with 'state' = 'closed' or 'open'
     * 'first'=>true means that it will keep that state only until the user changes it. the next visit it will reflect the state the user changed it to
     * if 'first'=>false , the box will retain that state with every visit to the page, no matter if the user previously changed it.


     * @return object $this
     */
    public function setMetaboxDefaultStatesOLD( $meta_box_open_states ) {

        if ( !is_array( $meta_box_open_states ) ) {
            return $this;
        }


        /*
         * Apply defaults to array if not all the settings were provided
         * This also ensures that if an element wasnt provided, it wont
         * break while the array is accessed
         */
        $defaults = array( 'state' => 'open', 'persist' => false );

        foreach ( $meta_box_open_states as $id => $metabox_state ) {
            $meta_box_open_states[ $id ] = array_merge( $defaults, $metabox_state );
        }

//            echo '<pre>';
//            echo '</pre>';


        $this->_meta_box_open_states = $meta_box_open_states;
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
    public function setMenuSlug( $menu_slug ) {
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

        if ( !is_admin() ) {
            return;
        }
/*
 * Don't add a pageCheck here or most menus wont load properly if you do
 */

        $this->debug()->log( 'Adding Hooks for ' . $this->getMenuSlug(),true );
        /*
         * Menu Screen Actions
         *
         * Fire all actions that must occur after the menu page has been added
         * Using the 'current_screen' action ensures that the screen object will be available
         * to check if you are on the correct screen.
         * Items to add to the hookMenuScreen are things like adding metaboxes, scripts and styles that are unique to the screen

         * 'current_screen' is the best hook to use and it ensures that the screen object is available for filtering
         * init wont work - its too early and wont give you the screen object
         * it also is early enough that you can enqueue styles and scripts.
         * it is not worth trying to fire off off the '_menuPageAdded' hook, since
         * it  will cause errors when using it for the custom post editor.
         */
        add_action( 'current_screen', array( $this, 'hookMenuScreen' ) );







        /*
         * Add Menu Page Created in the Child Class
         */
        add_action( 'admin_menu', array( $this, 'hookAddMenuPage' ) );

        /*
         * Add Custom Post Editor.
         */


        add_action( 'admin_menu', array( $this, 'hookAddCustomPostEditor' ) );



        /*
         *  Add Ajax Handlers
         * This is where you map any form actions with the php function that handles the ajax request
         * ajax handlers *must* be added to the addHooks() method. if added elsewhere, they may not be added in time and
         * will not be recognized. If you get a 0 as a response from an ajax request, the add_action('wp_ajax_' was not added
         * correctly.
         *
         * adding a wp_ajax hook in this format will execute the specified class method whenever the ajax request specifies an action = to $this->plugin()->getSlug() . '_xxxx'
          // see http://codex.wordpress.org/Plugin_API/Action_Reference/wp_ajax_%28action%29
         *
         */


        /* save without reloading the page */





        add_action( 'wp_ajax_' . $this->plugin()->getSlug() . '_settings_save', array( $this, 'hookFormActionSettingsSave' ) );
// move to hookMenuScreen add_action('wp_ajax_' . $this->plugin()->getSlug() . '_settings_save', array($this, 'hookFormActionSave'));
        /* save with reloading the page */
        add_action( 'wp_ajax_' . $this->plugin()->getSlug() . '_settings_save_with_reload', array( $this, 'hookFormActionSettingsSaveWithReload' ) );




        add_action( 'wp_ajax_' . $this->plugin()->getSlug() . '_settings_reset', array( $this, 'hookFormActionSettingsReset' ) );

        /*
         * Reset all settings to defaults
         *
         */
        add_action( 'wp_ajax_' . $this->plugin()->getSlug() . '_settings_reset_all', array( $this, 'hookFormActionSettingsResetAll' ) );
        /*
         * Manuall Update settings so as to add any newly added settings due to a developer update
         *
         */
        add_action( 'wp_ajax_' . $this->plugin()->getSlug() . '_settings_update_all', array( $this, 'hookFormActionSettingsUpdateAll' ) );



// add ajax action
//       add_action('wp_ajax_' . $this->plugin()->getSlug() . '_ajax_metabox', array($this, 'hookAjaxMetabox'));
//       add_action('wp_ajax_' . $this->plugin()->getSlug() . '_ajax_metabox_cache', array($this, 'hookAjaxMetaboxCache'));
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
        $this->debug()->logVar( '$menus = ', $menus );

        if ( is_null( $menus ) || (empty( $menus ) === true) ) {
            $this->debug()->log( 'Setting Menu Level to Top Level' );
            $this->setMenuLevel( 'top_level' );
        } else {
            $this->debug()->log( 'Setting Menu Level to Sub Menu' );
            $this->setMenuLevel( 'sub_menu' );
        }



        $this->setMenuSlug( $this->plugin()->getSlug() . '_' . $this->getSlug() );
//$this->updateMenuTracker($this->getMenuSlug(), array('top_level_slug'=>'edit.php?post_type=simpli_frames_snippet'));

        $this->updateMenuTracker( $this->getMenuSlug(), array( 'top_level_slug' => $this->getMenuSlug() ) );

        /*
         * Configure the metabox object
         * Pass the optional pageCheck callback method to
         * ensure no hooks fire on pages other than our Menu page
         */
        $this->metabox()->config( array( $this, 'pageCheckMenu' ) );

        /*
         * Configure the Module settings
         * See the class properties comment at the top of this class for a brief description of each setting
         */




        /*
         *
         * Messages to the User
         */
        $this->setConfigDefault( 'MESSAGE_NONCE_FAILED', __( '<div style="color:red">Attempt failed, please try again</div>', $this->plugin()->getTextDomain() ) );

        $this->setConfigDefault( 'MESSAGE_SAVE_SUCCESS', __( "Settings saved.", $this->plugin()->getTextDomain() ) );
        $this->setConfigDefault( 'MESSAGE_UPDATE_SUCCESS', __( "Settings have been updated.", $this->plugin()->getTextDomain() ) );

        $this->setConfigDefault( 'MESSAGE_RESET_SUCCESS', __( "Settings reset.", $this->plugin()->getTextDomain() ) );

        $this->setConfigDefault( 'MESSAGE_RESET_ALL_SUCCESS', __( "All Settings Have been reset to initial defaults.", $this->plugin()->getTextDomain() ) );

        $this->setConfigDefault( 'MESSAGE_RESET_ALL_NOCHANGES', __( "Settings are already at defaults!", $this->plugin()->getTextDomain() ) );
        $this->setConfigDefault( 'MESSAGE_RESET_ALL_FAILED', __( "Setting reset failed due to database error.", $this->plugin()->getTextDomain() ) );

        $this->setConfigDefault( 'MESSAGE_RESET', __( 'Are you sure you want to reset this form?', $this->plugin()->getTextDomain() ) );

        $this->setConfigDefault( 'MESSAGE_RESET_ALL', __( 'Are you sure you want to reset all the settings for this plugin to installed defaults?', $this->plugin()->getTextDomain() ) );
    }

    /**
     * Page Check
     *
     * Use for hook functions. Checks to see if we are on the menu that was added by this module.
     * For optimization, cache the result the first time,so subsequent checks on the same page dont have to rebuild the result.
     * Must be public since it is used by called objects, like metabox()
     * Usage:
     * if (!$this->pageCheckMenu(){return;}
     * @param none
     * @return boolean
     */
    protected $_page_check_menu_cache=null;

    public function pageCheckMenu() {
        $this->debug()->t();

        if ( is_null( $this->_page_check_menu_cache ) ) {



            $result = ($this->plugin()->tools()->getRequestVar( 'page' ) === $this->getMenuSlug());

            /*
             * if false, then try checking for the page variable that is embedded in the _simpli_forms_referral_url which is added automatically by form-submit.js
             */
            if ( !$result ) {
                $result = ($this->plugin()->tools()->getQueryVarFromUrl( 'page', $this->plugin()->tools()->getRequestVar( '_simpli_forms_referer_url' ) ) === $this->getMenuSlug());




}


$this->_page_check_menu_cache=$result;

        }
        $result = $this->_page_check_menu_cache;
        if ( $result ) {
            $this->debug()->log( 'Page Check Passed for ' . $this->getMenuSlug(), true );
}

        return ($result);
    }

    protected $_meta_box_object;

    /**
     * Metabox
     *
     * Provides the Metabox  utility object that manages WordPress Meta Boxes
     *
     * @param none
     * @return object A metabox Class Object
     */
    public function metabox() {

        if ( is_null( $this->_meta_box_object ) ) {
            $this->_meta_box_object = new Simpli_Frames_Base_v1c2_Plugin_Module_Metabox( $this );
        }
        return $this->_meta_box_object;
    }

    /**
     * Hook Current Screen
     *
     * Hook Function on Current Screen
     * * @param none
     * @return void
     */
    function hookCurrentScreenOLD() {


        if ( !$this->pageCheckMenu() ) {
            return;
        }

        /*
         * Set some metaboxes as closed.
         * Must hook into current screen so we dont hook in too early
         */
//OLD  add_action('get_user_option_closedpostboxes_' . $this->getScreenId(), array($this->metabox(), 'hookCloseMetaboxes'));
    }

    /**
     * Hook Menu Screen
     *
     * Hooks into the 'current_screen' action,  and uses pageCheckMenu() to check if the screen object matches the Menu screen created by this module.
     * Usage: From within the addHooks() method, add the following line: add_action('current_screen',($this,'hookMenuScreen')
     *
     * Add method calls here that should occur when the menu page created
     * by this module starts to render. Since this hook is called by the current_screen
     * action, the screen object is available and pageCheckMenu() can be used.
     * Good things to add here: addMetaBoxes(),Enqueue Scripts,etc.
     *
     * * @param none
     * @return void
     */
    function hookMenuScreen() {
        $this->debug()->t();
        /*
         *
         * Use pageCheckMenu() to reject any calls
         * that dont match the menu created by this module.
         *
         */

        if ( !$this->pageCheckMenu() ) {


            $this->debug()->log( 'Exiting from ' . get_class( $this ) . '::' . __FUNCTION__ . '()  since didnt pass pageCheckMenu' );
            return;
        }
        /*
         * add actions
         */

// Add scripts
        add_action( 'admin_enqueue_scripts', array( $this, 'hookEnqueueBaseClassScripts' ) );

        add_action( 'admin_enqueue_scripts', array( $this, 'hookEnqueueFormScripts' ) );
        /*
         * Add our meta boxes using a direct call, which will work
         * since the hookMenuScreen is itself called by the 'current_screen' action
         * The 'add_meta_boxes' action is not used since it will not work when used
         * with a custom post editor.
         */
        $this->metabox()->hookAddMetaBoxes();
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
        if ( key( $menus ) === $this->getMenuSlug() ) {
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
    public function removeMenuPage( $menu_slug ) {
        reset( self::$_menus );
        $parent_slug = key( self::$_menus );
        remove_submenu_page( $parent_slug, $menu_slug );
    }

    /**
     *
     * @var string The level of the menu, either 'sub_menu' or 'top_level'
     */
    protected $_menu_level;

    /**
     * Set Menu Level
     *
     * @param $menu_level
     * @return none
     */
    public function setMenuLevel( $menu_level ) {
        $this->debug()->t();
        $this->debug()->log( 'Set Menu Level for ' . $this->getSlug() . ' to ' . $menu_level );
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
     *
     * @var array Menu Page Configuration added by addMenuPage
     */
    protected $_menu_page = null;

    /**
     * Add Menu Page (Wrapper)
     *
     * Simply adds the Add Menu Page parameters to an array which is later used in a hook to add the menu page
     *
     * @param string $page_title The title of the menu page
     * @param mixed $menu_titles An array of titles or a string providing the main title. If an array, the first describes the name of the top level menu, the second describes the submenu title that appears when the main menu is opened.
     * @param string $capability The capability
     * @param string $icon_url The icon url
     * @param string $position
     * @return void
     */
    public function addMenuPage( $page_title, $menu_titles, $capability, $icon_url = '', $position = null ) {
        $this->debug()->t();
        $this->debug()->logVars( get_defined_vars() );
        $this->_menu_page = compact( 'page_title', 'menu_titles', 'capability', 'icon_url', 'position' );
    }

    /**
     *
     * @var array Custom Editor Args added by addCustomPostEditor
     */
    protected $_custom_post_editor = null;

    /**
     * Add  Custom Post Editor (Wrapper)
     *
     * Simply adds the add Custom Post Editor parameters to an array which is later used in a hook to add the editor page
     *
     * @param string $page_title The title of the menu page
     * @param array $menu_titles An array of titles
     * @param string $capability The capability
     * @param string $icon_url The icon url
     * @return void
     */
    public function addCustomPostEditor( $page_title, $menu_title, $capability ) {
        $this->debug()->t();
        $this->debug()->logVars( get_defined_vars() );
        $this->_custom_post_editor = compact( 'page_title', 'menu_title', 'capability' );
    }

    /**
     * Hook Add Custom Post Editor
     *
     * Adds the configured menu page when the 'admin_menu' action hook is fired.
     *
     * @param none
     * @return void
     */
    public function hookAddCustomPostEditor() {
        $this->debug()->t();
        if ( is_null( $this->_custom_post_editor ) ) {
            $this->debug()->log( 'Exiting hookAddCustomPostEditor since $_custom_post_editor is null' );
            return;
        }

        $this->debug()->logVar( '$this->_custom_post_editor = ', $this->_custom_post_editor );


        /*
         * Call the internal method that actually does the work within the hook
         * Use the parameters provided by the public addMenuPage() method
         */

        $this->_addCustomPostEditor
                (
                $this->_custom_post_editor[ 'page_title' ]
                , $this->_custom_post_editor[ 'menu_title' ]
                , $this->_custom_post_editor[ 'capability' ]
        );
    }

    /**
     * Hook Add Menu Page
     *
     * Adds the configured menu page when the 'admin_menu' action hook is fired.
     *
     * @param none
     * @return void
     */
    public function hookAddMenuPage() {
        $this->debug()->t();
        if ( is_null( $this->_menu_page ) ) {
            $this->debug()->log( 'Exiting hookAddMenuPage since $_menu_page is null' );
            return;
        }
        $this->debug()->log( 'Adding a Menu Page for ' . $this->_menu_page[ 'page_title' ] );

        $this->debug()->logVar( '$this->_menu_page = ', $this->_menu_page );


        /*
         * Now call the internal method that actually does the work within the hook
         * use the array of arguments provided by the public addMenuPage() method
         */

        $this->_addMenuPage
                (
                $this->_menu_page[ 'page_title' ]
                , $this->_menu_page[ 'menu_titles' ]
                , $this->_menu_page[ 'capability' ]
                , $this->_menu_page[ 'icon_url' ]
                , $this->_menu_page[ 'position' ]
        );
    }

    /**
     * Add a Menu Page
     *
     * Wrapper around add_menu_page so we can capture the page hook and still provide a nice api interface
     * * @param none
     * @return void
     */
    protected function _addMenuPage( $page_title, $menu_titles, $capability, $icon_url, $position ) {

        $this->debug()->logVars( get_defined_vars() );

        /*
         * If $menu_title is an array, we use the 'menu' element as the menu title,
         * and the 'sub_menu' element as the sub menu title that can be seen when hovering over the main menu name.
         */
        if ( is_array( $menu_titles ) ) {

            if ( $this->isTopLevel() ) {
                $menu_title = $menu_titles[ 'menu' ];
                $sub_menu_title = $menu_titles[ 'sub_menu' ];
            } else {
                $menu_title = $menu_titles[ 'sub_menu' ];
            }
        } else {
            $menu_title = $menu_titles;
            $sub_menu_title = null;
        }


        /*
         * Class Method to display the HTML for the menu
         */

        $function = array( $this, 'renderMenuPage' );

        /* Set the Menu Position
         *
         * Using the default menu position is a good way to avoid conflict
         * with other plugins
         */
        if ( is_null( $position ) ) {
            $position = $this->plugin()->getModule( 'Admin' )->getMenuPosition();
        }

        /*
         * The wrapper will create either a Top Level menu (i.e., main menu) or
         * create a submenu, depending on whether this module is the first to load.
         * If you want to ensure your module creates the main menu, make sure
         * it is the first to load by naming it in a way that sorts at the top of the
         * directory listing for your other menus. This is why the framework names its
         * modules as 'Menu10..., Menu20..,etc;'
         */

        if ( $this->getMenuLevel() === 'top_level' ) {
            $this->debug()->log( 'Adding menu ' . $menu_title . '  as top level' );
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
            if ( !is_null( $sub_menu_title ) ) {
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
            $this->debug()->log( 'Adding menu ' . $menu_title . '  as sub menu' );
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

        $this->updateMenuTracker( $this->getMenuSlug(), array( 'capability' => $capability
            , 'level' => $this->getMenuLevel(), 'top_level_slug' => $this->getMenuSlug() ) );
        do_action( $this->getMenuSlug() . '_menuPageAdded' );
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
    public function updateMenuTracker( $menu_slug, $properties ) {
        $this->debug()->t();
        $this->debug()->logVar( '$menu_slug = ', $menu_slug );
        $this->debug()->logVar( '$properties = ', $properties );
        $menus = $this->getMenuTracker();
        $this->debug()->logVar( '$menus = ', $menus );

        /*
         * if there are already properties associated with the menu tracker for
         * this element, then merge them, otherwise, just add the properties that
         * were passed
         */
        if ( isset( $menus[ $menu_slug ] ) and is_array( $menus[ $menu_slug ] ) ) {
            $properties = array_merge( $menus[ $menu_slug ], $properties );
        }

        self::$_menus[ $menu_slug ] = $properties;
        $this->debug()->logVar( 'self:$_menus = ', self::$_menus );
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

        if ( isset( $_GET[ 'page' ] ) && $_GET[ 'page' ] === $this->getMenuSlug() ) {
            return $this->getTopLevelMenuSlug();
        } else {
            return null;
        }
    }

    /**
     * Add a Custom Post Editor Page
     *
     * This is very similar to addMenuPage but forces parent to be edit.php and hardcodes some other parameters, as well
     * as immediately removing the page from the menu. This allows you to use the page added as an editor by redirecting
     * the edit action to it.
     * the page can be accessed at : /wp-admin/edit.php?page=simpli_frames_post_editor
     * you can look at $hookname to confirm the page slug.
     *
     * Wrapper around add_menu_page so we can capture the page hook and still provide a nice api interface
     * * @param none
     * @return void
     */
    protected function _addCustomPostEditor( $page_title, $menu_title, $capability ) {

        $this->debug()->t();

        if ( !$this->CUSTOM_POST_EDITOR_ENABLED ) {

            return;
        }

        /*
         * Class Method to display the HTML for the menu
         */

        $function = array( $this, 'renderMenuPage' );


        /*
         * Add the submenu
         * $hookname returns the page slug in the format post_page_<page_slug>
         */

        $hookname = add_submenu_page(
                $this->getEditorTopLevelMenuSlug() // parent slug
                , $page_title // page title
                , $menu_title // Submenu title //'Custom Post Editor'
                , $capability  // capability
                , $this->getMenuSlug()
                , $function //function to display the html
        );

        $this->debug()->logVar( '$hookname = ', $hookname );
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

        $this->updateMenuTracker( $this->getMenuSlug(), array( 'capability' => $capability
            , 'level' => $this->getMenuLevel() ) );
// do_action($this->plugin()->getSlug() . '_menuPageAdded');

        do_action( $this->getMenuSlug() . '_menuPageAdded' );
    }

    /**
     * Add Menu Page Hook
     * WordPress Hook - hookAddMenuPage
     *
     * @param none
     * @return void
     */
    public function hookAddMenuPageOLD() {
        $this->debug()->t();
        if ( !$this->pageCheckMenu() ) {
            return;
        }
        throw new Exception( 'You are missing a required hookAddMenuPage method in  ' . get_class( $this ) );
    }

    /**
     * Dispatch request for ajax metabox
     *
     * @param none
     * @return void
     */
    public function _AjaxMetaboxOLD( $cache_timeout = 0 ) {

//skip the pageCheck check since this is an ajax request and wont contain the $_GET page variable
// Disable errors
        error_reporting( 0 );
        /*
         * Compress Output
         */
        if ( $this->COMPRESS ) {
            $this->tools()->startGzipBuffering();
        }

// Set headers
        header( "Status: 200" );
        header( "HTTP/1.1 200 OK" );
        header( 'Content-Type: text/html' );
        header( "Vary: Accept-Encoding" );

        /*
         * set cache
         */
        if ( $cache_timeout === 0 ) {
            header( 'Cache-Control: no-store, no-cache, must-revalidate' );
            header( 'Cache-Control: post-check=0, pre-check=0', FALSE );
            header( 'Pragma: no-cache' );
        } else {

            $expires = 60 * $cache_timeout;        // 15 minutes

            header( 'Pragma: public' );
            header( 'Cache-Control: maxage=' . $expires );
            header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
            header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', time() + $expires ) . ' GMT' );
        }


        if ( !wp_verify_nonce( $_GET[ '_nonce' ], $this->plugin()->getSlug() ) ) {
            exit;
        }


        $request = new WP_Http;
        $request_result = $request->request( $_GET[ 'url' ] );
        $result[ 'html' ] = $request_result[ 'body' ];
        $result[ 'metabox_id' ] = $_GET[ 'id' ];


        echo json_encode( $result );
        exit();
    }

    /**
     * Ajax Action - Get Metabox with Cache
     *
     * @param none
     * @return void
     */
    public function hookAjaxMetaboxCacheOLD() {
//skip the pageCheck check since this is an ajax request and wont contain the $_GET page variable
        $this->_AjaxMetabox( 30 );
    }

    /**
     * Ajax Action - Get Metabox without Cache
     *
     * @param none
     * @return void
     */
    public function hookAjaxMetaboxOLD() {
//skip the pageCheck check since this is an ajax request and wont contain the $_GET page variable
        $this->_AjaxMetabox( 0 );
    }

    /**
     * Hook - Enqueue Form Scripts
     *
     * Adds the supporting form scripts.
     *
     * @param none
     * @return void
     */
    public function hookEnqueueFormScripts() {

        $this->debug()->t();
        if ( !$this->pageCheckMenu() ) {
            return;
        }



        /*
         * Add javascript for form submission
         *
         *
         *
         */


        $handle = $this->plugin()->getSlug() . '_form-menu-events.js';

        $path = $this->plugin()->getDirectory() . '/admin/js/form-menu-events.js';
        $inline_deps = array();
        $external_deps = array( 'jquery' );
        $this->plugin()->enqueueInlineScript( $handle, $path, $inline_deps, $external_deps );
        $this->debug()->log( 'loaded script ' . $handle );

        $vars = array(
            'metabox_forms' => array(
                'reset_message' => $this->MESSAGE_RESET
                , 'reset_all_message' => $this->MESSAGE_RESET_ALL,
            ),
        );


        /*
         * Add Javascript Hooks
         */
        $handle = $this->plugin()->getSlug() . '_form-menu-hooks.js';

        $path = $this->plugin()->getDirectory() . '/admin/js/form-menu-hooks.js';
        $inline_deps = array();
        $external_deps = array( 'jquery' );
        $this->plugin()->enqueueInlineScript( $handle, $path, $inline_deps, $external_deps );
        $this->debug()->log( 'loaded script ' . $handle );

        $this->plugin()->setLocalVars( $vars );
    }

    /**
     * Enqueue Basev1c2 Class Scripts ( Hook Function )
     *
     * Adds javascript and stylesheets to settings page in the admin panel.
     * WordPress Hook - admin_enqueue_scripts
     *
     * @param none
     * @return void
     */
    public function hookEnqueueBaseClassScripts() {
        $this->debug()->t();
        if ( !$this->pageCheckMenu() ) {
            return;
        }

        wp_enqueue_style( $this->plugin()->getSlug() . '-admin-page', $this->plugin()->getAdminUrl() . '/css/settings.css', array(), $this->plugin()->getVersion() );
        wp_enqueue_script( 'jquery' );

        /* by enqueuing post, you are enqueuing all the following scripts required to handle metaboxes (except save-metabox-state, which is enqueued in the next step):
          wp_enqueue_script( ' wp-ajax-response' );  //required to save state
          wp_enqueue_script( 'wp-lists' );  //required for collapse/expand
          wp_enqueue_script( 'jquery-ui-core' ); // required for drag and drop
          wp_enqueue_script( 'jquery-ui-widget' ); //required for drag and drop
          wp_enqueue_script( 'jquery-ui-mouse' ); //required for drag and drop
          wp_enqueue_script( 'jquery-ui-sortable' );  //required for drag and drop
          wp_enqueue_script('postbox');  //required for save/state

         */
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

        $menu = self::$_menus[ $this->getMenuSlug() ];
        $capability = $menu[ 'capability' ];


        /*
         * require a template whose name is the same as the menu_slug
         * If it doesnt exist, use the default template
         */
        if ( !current_user_can( $capability ) ) {
            wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
        }

        $template_path = $this->plugin()->getDirectory() . '/admin/templates/' . $this->getSlug() . '.php';
        if ( !file_exists( $template_path ) ) {
            $template_path = $this->plugin()->getDirectory() . '/admin/templates/menu_settings_default.php';
        }

        $this->debug()->logVar( '$template_path = ', $template_path );
        $this->debug()->logVar( '$this->plugin()->ALLOW_SHORTCODES = ', $this->plugin()->ALLOW_SHORTCODES );
        if ( $this->plugin()->ALLOW_SHORTCODES ) {

            $this->debug()->log( 'Including template and executing shortcodes' );
            ob_start();
            include($template_path);
            $template = do_shortcode( ob_get_clean() );
            // $template = ob_get_clean();
            //   $this->debug()->logVar('$template = ', $template);
            // echo $template;
            echo $template;
            //$this->debug()->stop(true);
        } else {
            $this->debug()->log( 'Not executing shortcodes since they are turned off' );
            include($template_path);
        }
    }

//            ob_start();
//            include($template_path);
//
//            $template = do_shortcode(ob_get_clean());
//            $this->debug()->logVar('$template = ', $template);
//            echo $template;
    /**
     * Hook -Ajax Settings Reset
     *
     * @param none
     * @return void
     */
    public function hookFormActionAjaxSettingsReset() {

        /*
         * pageCheck
         * Skip the pageCheck, since this is an ajax request and wont contain the $_GET page variable
         *
         */

        /*
         * Check Nonces
         */
        if ( !$this->metabox()->wpVerifyNonce( __FUNCTION__ ) ) {

            return false;
        }

        $message = $this->MESSAGE_RESET_SUCCESS;
        $user_option_defaults = $this->plugin()->getUserOptionDefaults();
        foreach ( $this->plugin()->getUserOptions() as $setting_name => $setting_value ) {
            /**
             * Set new setting value equal to the post value only if the setting was actually submitted, otherwise, keep the setting value the same.
             *  Add extra code to scrub the values for specific settings if needed
             */
            $setting_value = ((isset( $_POST[ $setting_name ] ) === true) ? $user_option_defaults[ $setting_name ] : $setting_value);

            $this->plugin()->setUserOption( $setting_name, $setting_value );
        }


        $this->plugin()->saveUserOptions();



        $this->metabox()->showResponseMessage(
                $this->plugin()->getDirectory() . '/admin/templates/ajax_message_admin_panel.php', //string $template The path to the template to be used
                $message, // string $message The html or text message to be displayed to the user
                array(), //$errors Any error messages to display
                false, //boolean $logout Whether to force a logout after the message is displayed
                true //boolean $reload Whether to force a page reload after the message is displayed
        );
        }

    /**
     * Verify WordPress Nonce
     *
     * Verifies the WordPress Nonce , using either a unique action name (derived from the $function_name parameter) or from the default $this->plugin()->NONCE_ACTION action.
     *
     * The simpli framework automatically handles WordPress Nonces for you for any settings saved by this module. The default configuration is to use a 'one nonce' for each menu, regardless of how many ajax actions are created. This is the easiest to implement, and the least performance heavy, and one that does not require any adherence to method naming conventions for it to work.
     * Alternately, If you wish to use a unique nonce for each action, this is also easily done but is a bit more performance heavy and requires additional understanding if you are to create your own ajax actions.
     * The basic steps are :
     * 1) be sure to stick to naming conventions , where the function for the action hook must be named 'hookFormAction<MyAction>'
     * 2) that $this->setConfig('UNIQUE_ACTION_NONCES',true) in the config() method for your Menu module
     * 3) within your ajax script use simpli_frames.my_action_nonce_value
     * @param $function_name The name of the wp_ajax hook function. Must be in the form 'hookFormAction' , otherwise, the nonce will be rejected.
     * @return void
     */
    public function wpVerifyNonceOLD( $function_name = null ) {
        $this->debug()->t();
        /*
         * Get the nonce value that was submitted by checking
         * the $_REQUEST header ( which includes $_GET and $_POST vars)
         */
        $nonce_value = $this->plugin()->tools()->getRequestVar( $this->plugin()->NONCE_FIELD_NAME );

        $this->debug()->logVar( '$nonce_value = ', $nonce_value );
        /*
         * Check whether unique nonces are enabled.
         *
         */
        if ( $this->plugin()->UNIQUE_ACTION_NONCES && !is_null( $function_name ) ) {
            /*
             * if unique nonces for each action are enabled, then get their action name from the function name
             */
            $nonce_action = $this->plugin()->getSlug() . '_' . $this->getSlug() . '_' . $this->plugin()->tools()->getSlugFromWord( str_replace( 'hookFormAction', '', $function_name ) );
        } else {
            /*
             * otherwise, just use the default action name
             */
            $nonce_action = $this->plugin()->NONCE_ACTION;
        }
        if ( !wp_verify_nonce( $nonce_value, $nonce_action ) ) {

            $this->debug()->log( 'Failed Nonce for ' . $nonce_action );
            return false;
        } else {
            $this->debug()->log( 'Nonce PASSED for ' . $nonce_action );
            return true;
        }
    }

    /**
     * Hook - Ajax Settings Save Wrapper - No Page Reload
     *
     * @param none
     * @return void
     */
    public function hookFormActionAjaxSettingsSave() {
        $this->debug()->t();

        $this->debug()->logVar( '$_POST = ', $_POST );
        $this->debug()->t();


        if ( !$this->pageCheckMenu() ) {
            return;
        }


        /*
         * Check Nonces
         */
        if ( !$this->metabox()->form_helper()->wpVerifyNonce( __FUNCTION__ ) ) {

            return false;
        }

//skip the pageCheck check since this is an ajax request and wont contain the $_GET page variable

        $this->_save( false );
    }

    /**
     * Hook Settings Save With Reload
     *
     * @param none
     * @return void
     */
    public function hookFormActionAjaxSettingsSaveWithReload() {
//skip the pageCheck check since this is an ajax request and wont contain the $_GET page variable
        $this->debug()->t();

        /*
         * Check Nonces
         */
        if ( !$this->metabox()->wpVerifyNonce( __FUNCTION__ ) ) {
            return false;
        }


        $this->_save( true );
    }

    /**
     * Save Settings
     *
     * @param boolean $reload Whether to reload (refresh) the page after the message.
     * @return void
     */
    public function _save( $reload = false ) {

        $this->debug()->t();

        $message = $this->MESSAGE_SAVE_SUCCESS;

        /*
         * The original code didn't make much sense to me annd looked like it was over engineered
         * and used too many trips to the database.
         * Here, we just save each setting thats submitted to a cache, and then
         * save the settings to the database when we are done.
         * The setSetting method will not save the setting to the array if the setting name didn't already
         * exist as a key  in the original _settings array
         *          */


        foreach ( $this->plugin()->getUserOptions() as $setting_name => $setting_value ) {
            /**
             * Set new setting value equal to the post value only if the setting was actually submitted, otherwise, keep the setting value the same.
             *  Add extra code to scrub the values for specific settings if needed
             */
            $previous_setting_value = $setting_value;
            $setting_value = ((isset( $_POST[ $setting_name ] ) === true) ? $_POST[ $setting_name ] : $previous_setting_value);



            $this->plugin()->setUserOption( $setting_name, $setting_value );
        }


        $this->plugin()->saveUserOptions();

//        $this->metabox()->showResponseMessage(
//                $this->plugin()->getDirectory() . '/admin/templates/ajax_message_admin_panel.php', //string $template The path to the template to be used
//                $message, // string $message The html or text message to be displayed to the user
//                array(), //$errors Any error messages to display
//                false, //boolean $logout Whether to force a logout after the message is displayed
//                $reload //boolean $reload Whether to force a page reload after the message is displayed
//        );


        $this->metabox()->form_helper()->showResponseMessage(
                $this->plugin()->getDirectory() . '/admin/templates/ajax_message_admin_panel.php', //string $template The path to the template to be used
                $message, // string $message The html or text message to be displayed to the user
                array(), //$errors Any error messages to display
                false, //boolean $logout Whether to force a logout after the message is displayed
                $reload //boolean $reload Whether to force a page reload after the message is displayed
        );
    }

    /**
     * Hook - Settings Update All
     *
     * add the update_all method to the Simpli Plugin.php class and make this method a wrapper that calls it
     * Takes the default array for settings and merges it with existing settings. This results in the database being updated with any new
     * settings added by development changes while retainining the existing setting values.
     * @param none
     * @return void
     */
    public function hookFormActionAjaxSettingsUpdateAll() {


        /*
         * pageCheck
         * Skip the pageCheck, since this is an ajax request and wont contain the $_GET page variable
         *
         */

        /*
         * Check Nonces
         */
        if ( !$this->metabox()->wpVerifyNonce( __FUNCTION__ ) ) {

            return false;
        }


        $message = $this->MESSAGE_UPDATE_SUCCESS;




        /*
         * Merge existing options with the defaults
         * Will not delete old settings, but will add new ones.
         *
         */


        $wp_option_name = $this->plugin()->getSlug() . '_options';
        $existing_options = $this->plugin()->getUserOptions();
        $option_defaults = $this->plugin()->getUserOptionDefaults();
        $options = array_merge( $option_defaults, $existing_options );


        /*
         * Save back to the database ( do not use the $this->plugin()->saveUserOptions() method since that
         * will only use existing settings)
         *
         */

        $this->plugin()->saveUserOptions( $options );

        $this->metabox()->showResponseMessage(
                $this->plugin()->getDirectory() . '/admin/templates/ajax_message_admin_panel.php', //string $template The path to the template to be used
                $message, // string $message The html or text message to be displayed to the user
                array(), //$errors Any error messages to display
                false, //boolean $logout Whether to force a logout after the message is displayed
                true //boolean $reload Whether to force a page reload after the message is displayed
        );
    }

    /**
     * Hook - Ajax Settings Reset All
     *
     * @param none
     * @return void
     */
    public function hookFormActionAjaxSettingsResetAll() {


        /*
         * pageCheck
         * Skip the pageCheck, since this is an ajax request and wont contain the $_GET page variable
         *
         */

        /*
         * Check Nonces
         */
        if ( !$this->metabox()->wpVerifyNonce( __FUNCTION__ ) ) {

            return false;
        }

        $message = $this->MESSAGE_RESET_ALL_SUCCESS;

        /*
         * Delete all the settings
         */

        global $wpdb;
        $query = 'delete from wp_options where option_name = \'' . $this->plugin()->getSlug() . '_options\'';
        $dbresult = $wpdb->query( $query );

        /* if no rows affected, that means the defaults havent been changed yet and stored in the database */
        if ( $dbresult === 0 ) {
            $message = $this->MESSAGE_RESET_ALL_NOCHANGES;
        } elseif ( $dbresult === false ) {//returns false on error
            $message = $this->MESSAGE_RESET_ALL_FAILED;
            ;
        }

        $this->plugin()->saveUserOptions();

        $this->metabox()->showResponseMessage(
                $this->plugin()->getDirectory() . '/admin/templates/ajax_message_admin_panel.php', //string $template The path to the template to be used
                $message, // string $message The html or text message to be displayed to the user
                array(), //$errors Any error messages to display
                false, //boolean $logout Whether to force a logout after the message is displayed
                true //boolean $reload Whether to force a page reload after the message is displayed
        );
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
     * Called when adding a menu and sub menu. its purpose is to capture the
     * identifier hook name returned by the add_menu_page and add_submenu_page WordPress function.
     * @param string $menu_page_hook_name
     * @return object $this
     */
    public function setMenuPageHookName( $menu_page_hook_name ) {
        $this->_menu_page_hook_name = $menu_page_hook_name;
        return $this;
    }

    /**
     * Get Screen Id
     *
     * Simply returns the current screen id from the screen object
     * Used by : do_metaboxes calls in templates
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
     * Returns an array of the meta box ids that are closed for use in setting the default positions
     * @param array $closed_metaboxes
     * @return array $closed_metaboxes
     *
     */
    public function hookCloseMetaboxesOld( $closed_metaboxes ) {
        if ( !$this->pageCheckMenu() ) {
            return($closed_metaboxes);
        }

        /* Check whether any metabox positions have been saved and if not, consider
         * this a 'first visit'  and ensure the initial argument type is an array
         * ensure that data type is array to avoid errors when empty
         */
        if ( !is_array( $closed_metaboxes ) ) {
            $first_visit = true;
            $closed_metaboxes = array();
        } else {
            $first_visit = false;
        }




        $metaboxDefaultStates = $this->getMetaboxOpenStates();


        /*
         * exit the filter if no default states have been set
         */
        if ( !is_array( $metaboxDefaultStates ) ) {
            return $closed_metaboxes;
        }


        /*
         * iterate through each of the default states and add the metabox
         * id to the filter if the metabox is to be closed
         */

        foreach ( $metaboxDefaultStates as $metabox_id => $preferences ) {

            if ( $preferences[ 'open' ] === false ) {
                /*
                 * if this is the first visit, and user wanted to apply defaults only to first visit
                 * or if this is not the first visit, and the user wanted to apply them always
                 * then apply the preference
                 */
                if ( ($first_visit) || (!$first_visit && $preferences[ 'persist' ]) ) {
                    if ( array_search( $metabox_id, $closed_metaboxes ) === false ) { //if the closed array didnt contain the metabox
                        $closed_metaboxes[] = $metabox_id;
                    }
                } else {
                    
                }
            } else {

                if ( ($first_visit && $preferences[ 'first' ]) || (!$first_visit && $preferences[ 'persist' ]) ) {

                    $key = array_search( $metabox_id, $closed_metaboxes );
                    if ( $key !== false ) {
                        $closed_metaboxes[ $key ] = '';
                    }
                }
            }
        }



        return $closed_metaboxes;
    }

    /*
     * Page Check Editor
     *
     * Indicates whether the current page is
     * an editor of any post type
     *
     * @var $_page_check_editor boolean
     *
     */

    protected $_page_check_editor = null;

    /**
     * Page Check Editor
     *
     * Use for hook functions. Checks to see if we are on an Edit page before we take any hook actions.
     * @param none
     * @return boolean
     */
    public function pageCheckEditor() {
        $this->debug()->t();



        if ( is_null( $this->_page_check_editor ) ) {

            if ( !is_admin() ) {
                $this->_page_check_editor = false;
            } else {

                $this->_page_check_editor = $this->plugin()->tools()->isScreen( array( 'edit', 'add' ), null, false );
                if ( !$this->_page_check_editor ) {
                    /*
                     * if pageCheck failed, check to see if we are on a custom edit or add screen
                     */
                    $this->debug()->log( 'Not a standard edit or add page, checking to see if its a CustomEdit or CustomAdd screen' );
                    $this->_page_check_editor = $this->plugin()->tools()->isScreen( array( 'custom_edit', 'custom_add' ), null, false );
                }
            }
        }



        /*
         * check to see if we are either on the edit or add screen
         *
         */



        $this->debug()->logVar( '$this->_page_check_editor  = ', $this->_page_check_editor );

        return ($this->_page_check_editor);
    }

}
