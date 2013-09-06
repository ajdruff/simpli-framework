<?php

/**
 * Top Level (Main) Admin Menu
 *
 *
 * @author Andrew Druffner
 * @package SimpliFramework
 * @subpackage SimpliHello
 *
 */
class Simpli_Hello_Module_Menu15CustomPostType extends Simpli_Basev1c0_Plugin_Menu {

    /**
     * a simple stop so we can examine actions
     *
     * Long Description
     *
     * @param none
     * @return void
     */
    public function test_action() {
        $this->debug()->logVar('$_POST = ', $_POST);
        $this->debug()->logVar('$_GET = ', $_GET);
        $this->debug()->stop();
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

        parent::addHooks();


        add_action('init', array($this, 'createPostTypes'));
        //add_action($this->getPlugin()->getSlug() . '_menuPageAdded', array($this, 'createPostTypes'));

/*
 * Redirect to custom editor if adding or editing
 */

        add_action('current_screen', array($this, 'redirectEdit'));

        add_action('current_screen', array($this, 'redirectAdd')); //pre_get_posts doesnt fire when adding a post, but current screen does

        /*
         *  Add Custom Ajax Handlers
         *
         * adding a wp_ajax hook in this format will execute the specified class method whenever the ajax request specifies an action = to $this->getPlugin()->getSlug() . '_xxxx'
          see http://codex.wordpress.org/Plugin_API/Action_Reference/wp_ajax_%28action%29

          Example ( this is included in base class so no need to add it here
          //add_action('wp_ajax_' . $this->getPlugin()->getSlug() . '_settings_save', array($this, 'save'));
         *
         *
         *
         */


        /*
         * Add any other hooks you need - see base class for examples
         *
         */


        /*
         * Add an admin notice if disabled
         *
         */
        add_action('admin_notices', array($this, 'showDisabledMessage'));
    }

    /**
     * Config
     *
     * Long Description
     * @param string $content The shortcode content
     * @return string The parsed output of the form body tag
     */
    public function config() {
        $this->debug()->t();


        /*
         * call parent configuration first
         * this is required or menus wont load
         */
        parent::config();











        /*
         * Set default metabox states - must place this after parent::init to get access to the module's slug
         */
        $this->setMetaboxDefaultStates(
                array(
                    //set the about metabox to stay closed
                    $this->getSlug() . '_metabox_about' => array('state' => 'closed', 'persist' => true
                    )
        ));
    }

    /**
     * Create Post Types
     *
     * Long Description
     *
     * @param none
     * @return void
     */
    public function createPostTypes() {


        /*
         * Register Simpli Hello Forms
         */

        $parent_slug = $this->getTopLevelMenuSlug();

        $args = array(
            'label' => 'Simpli Hello Forms' //A plural descriptive name for the post type marked for translation.
            , 'labels' => array(
                'name' => null // general name for the post type, usually plural. The same as, and overridden by $post_type_object->label
                , 'singular_name' => 'Simpli Hello Form' // name for one object of this post type. Defaults to value of name
                , 'menu_name' => 'Simpli Hello Forms' // the menu name text. This string is the name to give menu items. Defaults to value of name
                , 'all_items' => null //the all items text used in the menu. Default is the Name label
                , 'add_new' => null // the add new text. The default is Add New for both hierarchical and non-hierarchical types. When internationalizing this string, please use a gettext context matching your post type. Example: _x('Add New', 'product');
                , 'add_new_item' => null // the add new item text. Default is Add New Post/Add New Page
                , 'new_item' => null // the new item text. Default is New Post/New Page
                , 'view_item' => null // the view item text. Default is View Post/View Page
                , 'search_items' => null // the search items text. Default is Search Posts/Search Pages
                , 'not_found' => null //the not found text. Default is No posts found/No pages found
                , 'not_found_in_trash' => null // the not found in trash text. Default is No posts found in Trash/No pages found in Trash
                , 'parent_item_colon' => null // the parent text. This string isn't used on non-hierarchical types. In hierarchical ones the default is
            ) //
            , 'description' => null // A short descriptive summary of what the post type is
            , 'public' => true //Whether a post type is intended to be used publicly either via the admin interface or by front-end users.
            , 'exclude_from_search' => false //Whether to exclude posts with this post type from front end search results.
            , 'publicly_queryable' => true //If you set this to FALSE, you will find that you cannot preview/see your custom post (return 404)
            , 'show_ui' => true //
            , 'show_in_nav_menus' => true //
            , 'show_in_menu' => $parent_slug //Where to show the post type in the admin menu. show_ui must be true. false, does not show. true , top level, string -parent menu slug
            , 'show_in_admin_bar' => true //make same as show_in_menu
            , 'menu_position' => null //
            , 'menu_icon' => null //The url to the icon to be used for this menu.
            , 'capability_type' => null //The string to use to build the read, edit, and delete capabilities.
            , 'capabilities' => null //An array of the capabilities for this post type.
            , 'map_meta_cap' => false //Whether to use the internal default meta capability handling.
            , 'hierarchical' => false //Whether the post type is hierarchical (e.g. page). Allows Parent to be specified.
            , 'supports' => array('title', 'editor') //, '___' => null //
            , 'register_meta_box_cb' => null //callback function that will be called just before adding metaboxes. place your add_metabox and remove metabox calls here
            , 'taxonomies' => array() //An array of registered taxonomies like category or post_tag that will be used with this post type.
            , 'has_archive' => false //Enables post type archives. Will use $post_type as archive slug by default.
            //   , 'permalink_epmask' => EP_PERMALINK  //The default rewrite endpoint bitmasks
            //   , 'rewrite' => true  //Triggers the handling of rewrites for this post type. To prevent rewrites, set to false. Default: true and use $post_type as slug
            , 'query_var' => true //Sets the query_var key for this post type.Default: true - set to $post_type
            , 'can_export' => true //Can this post_type be exported.
        );

        $this->register_post_type($this->getPlugin()->getSlug() . '_form', $args);
    }

    /**
     * Register Post Type (Wrapper for wordpress method of the same name)
     *
     * Creates a custom post type
     * Ref:http://codex.wordpress.org/Function_Reference/register_post_type
     *
     * @param string $post_type Post type. (max. 20 characters, can not contain capital letters or spaces
     * @param array An array of arguments as described below
     * @return void
     */
    function register_post_type($post_type, $args) {


        $arg_defaults = array(
            'label' => null //A plural descriptive name for the post type marked for translation.
            , 'labels' => array(
                'name' => null // general name for the post type, usually plural. The same as, and overridden by $post_type_object->label
                , 'singular_name' => null // name for one object of this post type. Defaults to value of name
                , 'menu_name' => null // the menu name text. This string is the name to give menu items. Defaults to value of name
                , 'all_items' => null //the all items text used in the menu. Default is the Name label
                , 'add_new' => null // the add new text. The default is Add New for both hierarchical and non-hierarchical types. When internationalizing this string, please use a gettext context matching your post type. Example: _x('Add New', 'product');
                , 'add_new_item' => null // the add new item text. Default is Add New Post/Add New Page
                , 'new_item' => null // the new item text. Default is New Post/New Page
                , 'view_item' => null // the view item text. Default is View Post/View Page
                , 'search_items' => null // the search items text. Default is Search Posts/Search Pages
                , 'not_found' => null //the not found text. Default is No posts found/No pages found
                , 'not_found_in_trash' => null // the not found in trash text. Default is No posts found in Trash/No pages found in Trash
                , 'parent_item_colon' => null // the parent text. This string isn't used on non-hierarchical types. In hierarchical ones the default is
            ) //
            , 'description' => null // A short descriptive summary of what the post type is
            , 'public' => true //Whether a post type is intended to be used publicly either via the admin interface or by front-end users.
            , 'exclude_from_search' => false //Whether to exclude posts with this post type from front end search results.
            , 'publicly_queryable' => true //If you set this to FALSE, you will find that you cannot preview/see your custom post (return 404)
            , 'show_ui' => true //
            , 'show_in_nav_menus' => true //
            , 'show_in_menu' => true //Where to show the post type in the admin menu. show_ui must be true. false, does not show. true , top level, string -parent menu slug
            , 'show_in_admin_bar' => true //make same as show_in_menu
            , 'menu_position' => null //
            , 'menu_icon' => null //The url to the icon to be used for this menu.
            , 'capability_type' => null //The string to use to build the read, edit, and delete capabilities.
            , 'capabilities' => null //An array of the capabilities for this post type.
            , 'map_meta_cap' => false //Whether to use the internal default meta capability handling.
            , 'hierarchical' => false //Whether the post type is hierarchical (e.g. page). Allows Parent to be specified.
            , 'supports' => array('title', 'editor') //, '___' => null //
            , 'register_meta_box_cb' => null //callback function that will be called just before adding metaboxes. place your add_metabox and remove metabox calls here
            , 'taxonomies' => array() //An array of registered taxonomies like category or post_tag that will be used with this post type.
            , 'has_archive' => false //Enables post type archives. Will use $post_type as archive slug by default.
            //   , 'permalink_epmask' => EP_PERMALINK  //The default rewrite endpoint bitmasks
            //   , 'rewrite' => true  //Triggers the handling of rewrites for this post type. To prevent rewrites, set to false. Default: true and use $post_type as slug
            , 'query_var' => true //Sets the query_var key for this post type.Default: true - set to $post_type
            , 'can_export' => true //Can this post_type be exported.
                ///the remaining arguments are fyi only and should not be used
                //
          //  , '_builtin' => false //Whether this post type is a native or "built-in" post_type.
                //  , '_edit_link' => null //Link to edit an entry with this post type. Note: this Codex entry is for documentation '-' core developers recommend you don't use this when registering your own post type
        );


//$arg_defaults=array_filter($arg_defaults);

        $args = $this->getPlugin()->getTools()->screenDefaults($arg_defaults, $args);
        $args['labels'] = array_filter($args['labels']);
        $args = array_filter($args);
//echo '<pre>', print_r($args, true), '</pre>';


        register_post_type($post_type, $args);

        /*
         * set the post type added to the post type for the module
         */
        $this->setPostType($post_type);



//die(__FILE__);
    }

    /**
     * Admin Menu (Optional)
     * Adds the post editor as a menu item, then removes it, so its still available as
     * a redirect template.
     * WordPress Hook - admin_menu . Fired by base class addHooks method
     *
     * @param none
     * @return void
     */
    public function hookAdminMenu() {
        $this->debug()->t();


        /*
         * add the post editor
         */
        $page_title = $this->getPlugin()->getName() . ' - Editor';

        $menu_title = 'Edit My Custom Post Type';
        $capability = 'edit_published_posts';
        $icon_url = $this->getPlugin()->getUrl() . '/admin/images/menu.png';


        /*
         * add the custom post editor
         */

        $this->addCustomPostEditor(
                $page_title = $this->getPlugin()->getName() . ' - Editor', $menu_title = 'Edit My Custom Post Type', $capability, $icon_url = $this->getPlugin()->getUrl() . '/admin/images/menu.png'
        );
    }

    /**
     * Add meta boxes
     *
     * @param none
     * @return void
     */
    public function add_meta_boxes() {
        $this->debug()->t();




        add_meta_box(
                $this->getSlug() . '_' . 'metabox_test'  //Meta Box DOM ID
                , __('Your Custom Post Editor', $this->getPlugin()->getTextDomain()) //title of the metabox.
                , array($this, 'renderMetaBoxTemplate') //function that prints the html
                , $screen_id = null// must be null so WordPress uses current screen id as default. mistakenly called $post_type in the codex. See Source Code.
                , 'normal' //normal advanced or side The part of the page where the metabox should show
                , 'default' // 'high' , 'core','default', 'low' The priority within the context where the box should show
                , null //$metabox['args'] in callback function
        );

        $this->debug()->logVar('$this->getSlug() = ', $this->getSlug());
//
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

    /**
     * Redirect Add Page
     *
     * Checks to see if the current query is requesting an add action for
     * the configured post type. If it is, then it redirects to the post editor
     *
     * @param none
     * @return void
     */
    public function redirectAdd() {

        $this->debug()->t();
        /*
         * Return if not in admin
         */
        if (!is_admin()) {

            $this->debug()->logVars(get_defined_vars());
            return;
        }



      //  $settings_page_slug = $this->getPlugin()->getSlug() . '_' . $this->getSlug(); //$this->getMenuSlug(); still empty


  $isScreenAdd = ($this->getPlugin()->getModule('Tools')->isScreen('add', $this->getPostType()));
        /*
         * Return if not the edit screen
         */
        if (!$isScreenAdd) {
            $this->debug()->log('Returning, not the add screen');
            return;
        }

        $this->debug()->logVar('Redirecting to page= ', $this->getMenuSlug());


        wp_redirect(admin_url() . 'admin.php?page=' . $this->getMenuSlug());

die();
    }

    /**
     * Redirect to Custom Editor
     *
     * Checks to see if the current query is requesting an edit action for
     * the configured post type. If it is, then it redirects to the post editor
     *
     * @param none
     * @return void
     */
    public function redirectEdit() {

        $this->debug()->t();
        /*
         * Return if not in admin
         */
        if (!is_admin()) {

            $this->debug()->logVars(get_defined_vars());
            return;
        }
        global $post;
        /*
         * if no post object, create one from the get
         * paramaters if they exist.
         */
        if (is_null($post)) {
            if (isset($_GET['post'])) {
                $post = get_post($_GET['post']);

                $this->debug()->log('Used $_GET Paramater \'post\' to create post object of type \''. $post->post_type  .'\'');
                $this->debug()->logVar('$post = ', $post);
            } else {
                $post = null;
                            $this->debug()->log('Returning from ' . __FUNCTION__ . ' since no post object set');
            $this->debug()->logVars(get_defined_vars());
            return;
            }
        }


        $this->debug()->logVar('Current Screen = ', get_current_screen());


        $this->debug()->logVar('$this->getPostType() = ', $this->getPostType());

        /*
         * Return if not the right post type
         */
            if ($post->post_type !== $this->getPostType()) {
            $this->debug()->log('Returning from ' . __FUNCTION__ . ' since not the right post type');
            $this->debug()->logVars(get_defined_vars());
            return;
        }


     //   $settings_page_slug = $this->getPlugin()->getSlug() . '_' . $this->getSlug(); //$this->getMenuSlug(); still empty

        $isScreenEdit = $this->getPlugin()->getModule('Tools')->isScreen('edit-add', $this->getPostType());

        /*
         * Return if not the edit screen
         */
        if (!$isScreenEdit) {
            $this->debug()->log('Returning, not the edit screen');
            return;
        }
        $this->debug()->logVar('Redirecting to page= ', $this->getMenuSlug());


 wp_redirect(admin_url() . 'admin.php?post=' . $post->ID . '&page=' . $this->getMenuSlug());

die();
        return;

    }

    /**
     * Shows a disabled message if the plugin is disabled via the settings
     * This will only appear when first switching to the general settings page. Its assumed that the settings that trigger
     * it are set on a different (advanced) menu page.
     *
     */
    public function showDisabledMessage() {
        $this->debug()->t();



        //dont show if you are not on the main menu ( general settings )
        if (isset($_GET['page']) && $_GET['page'] !== $this->getPlugin()->getSlug() . '_' . $this->getSlug()) {
            return;
        }

//dont show if the plugin is enabled
        if (($this->getPlugin()->getSetting('plugin_enabled') == 'enabled')) {
            return;
        }
        ?>



        <div class="error">
            <p><strong>You have disabled <?php echo $this->getPlugin()->getName() ?> functionality.</strong> To re-enable <?php echo $this->getPlugin()->getName() ?> , set  'Maintenance -> Enable Plugin' to 'Yes'.</p>
        </div>

        <?php
    }

}
?>