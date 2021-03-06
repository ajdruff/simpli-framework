<?php

/**
 * Custom Post Type (Class extension of Menu)
 *
 * Manages the creation of a Custom Post Type, and ensure its menu items
 * integrate seamlessly with other plugin menus. Also allows the
 * creation of a custom editor.
 *
 * @author Andrew Druffner
 * @package SimpliFramework
 * @subpackage SimpliHello
 * @property boolean $CUSTOM_POST_EDITOR_ENABLED Whether you want to redirect to a custom post editor
 */
class Simpli_Hello_Basev1c2_Plugin_PostType extends Simpli_Hello_Basev1c2_Plugin_Menu {

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

        /*
         * Add the Custom Post Type
         */
        add_action('init', array($this, 'hookRegisterPostTypes'));

        /*
         * Add Metaboxes and scripts to the Editing Screen if we are on it
         */

        add_action('current_screen', array($this, 'hookEditingScreen'));


        /*
         * Redirect to custom editor if adding or editing
         */

        if ($this->CUSTOM_POST_EDITOR_ENABLED) {

            /*
             * Add an Edit Redirect if we are using a custom editor
             */
            add_action('current_screen', array($this, 'hookRedirectEdit'));
            /*
             * Add an Add Redirect if we are using a custom editor
             */
            add_action('current_screen', array($this, 'hookRedirectAdd'));
            /*
             * Create a new post object for the custom editor when adding a new post
             */
            // dont need to create new post object since we did that before redirecting.
            //  add_action('current_screen', array($this, 'hookCreateNewPost')); //Checks to see if user wants to create a new post, and creates it.
        }
        /*
         *  Add Custom Ajax Handlers
         *
         * adding a wp_ajax hook in this format will execute the specified class method whenever the ajax request specifies an action = to $this->plugin()->getSlug() . '_xxxx'
          see http://codex.wordpress.org/Plugin_API/Action_Reference/wp_ajax_%28action%29

          Example ( this is included in base class so no need to add it here
          //add_action('wp_ajax_' . $this->plugin()->getSlug() . '_settings_save', array($this, 'save'));
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
        $this->debug()->log('Adding Activate action for hookFlushRewriteRules');
        $this->plugin()->addActivateAction(array($this, 'hookFlushRewriteRules'));
    }

    protected $_register_post_type_args;

    /**
     * Register Post Type (Wrapper)
     *
     * Wrapper that saves parameter values to an array so that a hook
     * may add the register post type at a later time
     *
     * @param string $post_type Post type. (max. 20 characters, can not contain capital letters or spaces)
     * @args (optional)An array of arguments.
     * @return void
     */
    protected function registerPostType($post_type, $args = null) {

        $this->_register_post_type_args = compact('post_type', 'args');
    }

    /**
     * Hook - Flush Rewrite Rules
     *
     * Flushes the rewrite rules
     *
     * @param none
     * @return void
     */
    public function hookFlushRewriteRules() {
        $this->debug()->t();
        $this->debug()->log('Flushed Rules');
        flush_rewrite_rules();
    }

    /**
     * Hook to Register Post Types
     *
     * Called by 'init' to register any post types
     * Ref:http://codex.wordpress.org/Function_Reference/register_post_type
     * @param none
     * @return void
     */
    public function hookRegisterPostTypes() {


        /*
         * Retrieve the args that were saved earlier during config()
         */
        /*
         * dont process hook any further
         * if no post type was registered
         */
        if (is_null($this->_register_post_type_args)) {
            return;
        }


        extract($this->_register_post_type_args);






        if ($this->isTopLevel()) {
            $parent_slug = true;
        } else {
            $parent_slug = $this->getTopLevelMenuSlug();
        }
        /*
         * 'show_in_menu'  Where to show the post type in the admin menu.
         * show_ui must be true to show. False makes it not show.
         * To indicate a top_level menu for it to appear under, make it a string
         * that is equal to the top level menu slug.
         *
         * If 'show_in_menu' is not set, set it so that it forces the post type menus to
         * be added in the right place ( under the top level menu, or as the top level menu itself)
         */
        $args['show_in_menu'] = (isset($args['show_in_menu'])) ? $args['show_in_menu'] : $parent_slug;

        /*
         * 'show_in_admin_bar
         *
         * set the 'show_in_admin_bar' to be equal to 'show_in_menu' if it not already set
         */
        $args['show_in_admin_bar'] = (isset($args['show_in_admin_bar'])) ? $args['show_in_admin_bar'] : $args['show_in_menu']; //make same as show_in_menu

        /*
         * 'menu_position' The position in the menu order the post type should appear. show_in_menu must be true.
         *
         * Set Menu Position if not set, using the getMenuPosition method of the Admin method which
         * is designed to return a unique position , using the slug as a kind of salt

         */
        $args['menu_position'] = (isset($args['menu_position'])) ? $args['menu_position'] : $this->plugin()->getModule('Admin')->getMenuPosition(); //make same as show_in_menu


        /*
         * call the internal method that does all the work
         */
        $this->_register_post_type($post_type, $args);



        //add_post_type_support( $this->plugin()->getSlug() . '_snippet', array('title', 'editor') );
    }

    /**
     * Register Post Type (Wrapper for wordpress method of the same name)
     *
     * Creates a custom post type. This method should only be called from hookRegisterPostTypes()
     * Ref:http://codex.wordpress.org/Function_Reference/register_post_type
     *
     * @param string $post_type Post type. (max. 20 characters, can not contain capital letters or spaces
     * @param array An array of arguments as described below
     * @return void
     */
    protected function _register_post_type($post_type, $args) {


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
            , 'menu_position' => null //The position in the menu order the post type should appear. show_in_menu must be true.
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
            , 'rewrite' => null  //Triggers the handling of rewrites for this post type. To prevent rewrites, set to false. Default: true and use $post_type as slug
            , 'query_var' => true //Sets the query_var key for this post type.Default: true - set to $post_type
            , 'can_export' => true //Can this post_type be exported.
                ///the remaining arguments are fyi only and should not be used
                //
          //  , '_builtin' => false //Whether this post type is a native or "built-in" post_type.
                //  , '_edit_link' => null //Link to edit an entry with this post type. Note: this Codex entry is for documentation '-' core developers recommend you don't use this when registering your own post type
        );


        /*
         * Screen defaults, which only allows elements that are in the $arg_defaults array
         * and provides defaults for those elements that are not supplied.
         */

        $args = $this->plugin()->tools()->screenDefaults($arg_defaults, $args);

        /*
         * Use array_filter to remove null values so that the wordpress builtin function supplies defaults
         */

        $args['labels'] = array_filter($args['labels']);
        $args = array_filter($args);

        /*
         * Finally, register it using the WordPress register_post_type function
         */
        register_post_type($post_type, $args);
        $this->debug()->log('Registered Post Type ' . $post_type);
        /*
         * set the post type added to the post type for the module
         */
        $this->setPostType($post_type);

        /*
         * update the menu tracker
         */
        $this->updateMenuTracker($this->getMenuSlug(), array('top_level_slug' => 'edit.php?post_type=' . $post_type));

        /*
         * Flush the re-write rules
         *
         * A Custom Post type using permalinks must flush the re-write rules
         * before the the new urls will work, otherwise you'll get a 404 when
         * you try to browse to the custom post type's post.
         *
         * Use a persistent action to flush the re-write rules
         * Because the persistent action is only added during the Plugin::activatePlugin() method,
         * the flush will only occur once, during activation, not for every page request.
         *
         * Note that trying to use a 'do_action' here will *not* work, because the activation
         * occurs in a separate page request.
         */
        $this->plugin()->doPersistentAction($this->plugin()->getSlug() . '_flush_rewrite_rules');
    }

    /**
     * Hook - Redirect Add Page
     *
     * Checks to see if the current query is requesting an add action for
     * the configured post type. If it is, then it redirects to the post editor
     *
     * @param none
     * @return void
     */
    public function hookRedirectAdd() {

        $this->debug()->t();
        /*
         * Return if not in admin
         */
        if (!is_admin()) {


            return;
        }



        //  $settings_page_slug = $this->plugin()->getSlug() . '_' . $this->getSlug(); //$this->getMenuSlug(); still empty


        $isScreenAdd = ($this->plugin()->tools()->isScreen('add', $this->getPostType()));
        /*
         * Return if not the edit screen
         */
        if (!$isScreenAdd) {
            $this->debug()->log('Returning, not the add screen');
            return;
        }

        $this->debug()->logVar('Redirecting to page= ', $this->getMenuSlug());




        /*
         * Use a different redirect url depending on whether a custom post type is the top level menu.
         *
         */

        $menu_tracker = $this->getMenuTracker();
        $top_menu = key($menu_tracker);
        if (stripos($menu_tracker[$top_menu]['top_level_slug'], 'edit.php') !== false) {
            /*
             * if the custom post type is a top level menu page, redirect using its top level slug, which includes the post type
             */
            $post_type = $this->plugin()->tools()->getQueryVarFromUrl('post_type', $menu_tracker[$top_menu]['top_level_slug']); //extracts the post_type paramater from the top level slug, which includes the post type

            $redirect_url = admin_url() . $menu_tracker[$top_menu]['top_level_slug'] . '&' . $this->plugin()->QUERY_VAR . '=' . $this->plugin()->QV_ADD_POST . '&page=' . $this->getMenuSlug();

            //works too:         $redirect_url=(admin_url() . 'edit.php?' . $this->plugin()->QUERY_VAR . '=' . $this->plugin()->QV_ADD_POST . '&post_type='.$this->getPostType().'&page=' . $this->getMenuSlug());


            $this->debug()->logVar('$redirect_url = ', $redirect_url);
        } else {

            /*
             * Otherwise, redirect using admin.php
             * Obfuscate the post type since we are sending it in a url using a custom post editor page, and without obfuscation, WordPress will freak out (see getPostTypeRequestVar() for detauls
             */
            $post_type = $this->getPostType();
            $obfuscated_post_type = $this->plugin()->post()->getPostTypeRequestVar($this->getPostType());
            $this->debug()->logVar('$this->getPostType() = ', $this->getPostType());
            $this->debug()->logVar('$post_type = ', $post_type);



            $redirect_url = admin_url() . 'admin.php?' . $this->plugin()->QUERY_VAR . '=' . $this->plugin()->QV_ADD_POST . '&post_type=' . $obfuscated_post_type . '&page=' . $this->getMenuSlug();


            $this->debug()->logVar('$redirect_url = ', $redirect_url);
        }
        global $current_user;

        $new_post_values = array(
            'post_status' => 'auto-draft',
            'post_author' => $current_user->ID,
            'post_type' => $post_type
        );

// Insert the post into the database
        $post_id = wp_insert_post($new_post_values);
        $redirect_url = $redirect_url . '&post=' . $post_id;

        wp_redirect($redirect_url);
        die(); // to help wp_redirect exit cleanly
    }

    /**
     * Hook  - Redirect Edit
     *
     * Checks to see if the current query is requesting an edit action for
     * the configured post type. If it is, then it redirects to the post editor
     *
     * @param none
     * @return void
     */
    public function hookRedirectEdit() {

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
         * parameters if they exist.
         */
        if (is_null($post)) {
            if (isset($_GET['post'])) {
                $post = get_post($_GET['post']);

                $this->debug()->log('Used $_GET parameter \'post\' to create post object of type \'' . $post->post_type . '\'');
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


        //   $settings_page_slug = $this->plugin()->getSlug() . '_' . $this->getSlug(); //$this->getMenuSlug(); still empty

        $isScreenEdit = $this->plugin()->tools()->isScreen('edit-add', $this->getPostType());

        /*
         * Return if not the edit screen
         */
        if (!$isScreenEdit) {
            $this->debug()->log('Returning, not the edit screen');
            return;
        }


        $this->debug()->logVar('Redirecting to page= ', $this->getMenuSlug());
        $this->debug()->logVar('$this->getMenuTracker() = ', $this->getMenuTracker());

        $menu_tracker = $this->getMenuTracker();
        $top_menu = key($menu_tracker);
        if (stripos($menu_tracker[$top_menu]['top_level_slug'], 'edit.php') !== false) { //if a custom post type is the top level menu
            $redirect_url = admin_url() . $menu_tracker[$top_menu]['top_level_slug'] . '&' . $this->plugin()->QUERY_VAR . '=' . $this->plugin()->QV_EDIT_POST . '&post=' . $post->ID . '&page=' . $this->getMenuSlug();
            $this->debug()->logVar('$redirect_url = ', $redirect_url);

            wp_redirect($redirect_url);
            die();
        } else {
            $redirect_url = admin_url() . 'admin.php?' . $this->plugin()->QUERY_VAR . '=' . $this->plugin()->QV_EDIT_POST . '&post=' . $post->ID . '&page=' . $this->getMenuSlug();
            $this->debug()->logVar('$redirect_url = ', $redirect_url);

            wp_redirect($redirect_url);
            die();
        }


//'admin.php?'.$this->plugin()->QUERY_VAR.'='. $this->plugin()->QV_ADD_POST.'&
        // wp_redirect(admin_url() . 'admin.php?'.$this->plugin()->QUERY_VAR.'='. $this->plugin()->QV_EDIT_POST .'&post=' . $post->ID . '&page=' . $this->getMenuSlug());

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
        if ($this->CUSTOM_POST_EDITOR_ENABLED) {
            if (!$this->plugin()->tools()->isScreen(array('custom_edit', 'custom_add'), $this->getPostType())) {
                return;
            }
        } else {

            if (!$this->plugin()->tools()->isScreen(array('edit', 'add'), $this->getPostType())) {
                return;
            }
        }






//dont show if the plugin is enabled
        if (($this->plugin()->getUserOption('plugin_enabled') == 'enabled')) {
            return;
        }
        ?>



        <div class="error">
            <p><strong>You have disabled <?php echo $this->plugin()->getName() ?> functionality.</strong> To re-enable <?php echo $this->plugin()->getName() ?> , set  'Maintenance -> Enable Plugin' to 'Yes'.</p>
        </div>

        <?php
    }

    /**
     * Set Module Config Defaults
     *
     * Sets all the default configs . By adding a configuration here, you are defining it, but also allowing
     * later calls to setModuleConfig() to override these values.
     *
     * @param none
     * @return void
     */
    protected function setConfigDefaults() {
        /*
         * Custom Post Editor for your post type
         *
         * If you want to build a custom post editor, you can direct all edits and adds
         * to your post type by toggling 'custom_post_editor_enabled' to true.
         * You'll then need to build your own form  and add it to
         * the menu15_custom_post_type_metabox_editor.
         * This of course, means that you are responsible for managing metaboxes and
         * adding publish/preview,etc functions to your form.
         */
        $this->setConfigDefault('CUSTOM_POST_EDITOR_ENABLED', false);
    }

    protected $_add_new_post_ID = null;

    /**
     * Get Add New Post ID
     *
     * If a new post was created using 'Add New', retrieves the new post ID.
     *
     * @param none
     * @return void
     */
    public function getAddNewPostID() {

        return $this->_add_new_post_ID;
    }

    /**
     * Hook - Create New Post
     *
     * Creates a new post if the GET query variable has 'add_post' . This will occur when the 'add_new' link is clicked when there is a custom post type editor configured.
     *
     * @param none
     * @return void
     */
    public function hookCreateNewPost() {
        $this->debug()->t();
        $tools = $this->plugin()->tools();

        /*
         * Check if our query variable is set to 'add_post'. if it is, we are on the 'add_post' page and
         * we create an auto-draft of a post.
         */

        if (($tools->getRequestVar($this->plugin()->QUERY_VAR) === $this->plugin()->QV_ADD_POST)) {
            // Create post object
            /*
             * get post type,
             * We use the getPostTypeRequestVar() because it will be able to retrieve
             * the post_type even if its been obfuscated due to using a custom post editor.
             * post_type value will be obfuscated so WordPress doesnt recognize it as a valid registered post type.
             * if it did, it would throw an error (cannot load page) since it doesnt recognize it as a valid edit page.
             */

            $post_type = $this->plugin()->post()->getPostTypeRequestVar();

            $this->debug()->logVar('$post_type = ', $post_type);

            $this->debug()->stop(true);
            global $post;
            global $current_user;
            $new_post_values = array(
                'post_status' => 'auto-draft',
                'post_author' => $current_user,
                'post_type' => $post_type
            );

// Insert the post into the database
            $this->_add_new_post_ID = wp_insert_post($new_post_values);
            $this->debug()->log('Created New Post with id = ' . $this->getAddNewPostID());

            $post = get_post($this->getAddNewPostID());
            $this->debug()->logVar('$post = ', $post);
        }
    }

    /**
     * Hook - Editing Screen
     *
     * Hooks into the Editing Screen
     * Add method calls that should occur when the Editing or 'add new' screen
     * is displayed to someone logged into admin.
     * This is a good place to call addMetaBoxes, and to add any scripts or styles that
     * should only appear on the editor.
     * Checks the current screen object, and then builds the layout of the screen , adding metaboxes, scripts etc
     *
     * @param none
     * @return void
     */
    public function hookEditingScreen() {
        $this->debug()->t();
        if (!$this->pageCheckEditor()) {
            return;
        }

        //      add_action('current_screen', array($this, 'loadUserOptions')); //wp hook is not reliable on edit post page. admin_init cannot be used since a call to get_current_screen will return null see usage restrictions: http://codex.wordpress.org/Function_Reference/get_current_screen


        $this->debug()->log('Passed pageCheckEditor');
        /*
         * Add our meta boxes
         */

        $this->metabox()->hookAddMetaBoxes();
        //  add_action('current_screen', array($this, 'addMetaBoxes')); //action must be 'current_screen' so screen object can be accessed by the add_meta_boxes function
        // Add scripts
        add_action('admin_enqueue_scripts', array($this, 'hookEnqueueBasev1c2ClassScripts'));
    }

    /**
     *
     * @var string The post type created by registerCustomPostType()
     */
    protected $_post_type = null;

    /**
     * Set Post Type
     *
     * @param array $post_type
     * @return none
     */
    protected function setPostType($post_type) {
        $this->_post_type = $post_type;
    }

    /**
     * Get Post Type
     *
     * @param none
     * @return string
     */
    protected function getPostType() {
        return $this->_post_type;
    }

}
?>