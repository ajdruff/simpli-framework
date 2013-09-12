<?php

/**
 * Top Level (Main) Admin Menu
 *
 *
 * @author Andrew Druffner
 * @package SimpliFramework
 * @subpackage SimpliHello
 * @property boolean $CUSTOM_POST_EDITOR_ENABLED Whether you want to redirect to a custom post editor
 */
class Simpli_Hello_Module_Menu01CustomPostType extends Simpli_Basev1c0_Plugin_Menu {

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
            add_action('current_screen', array($this, 'hookCreateNewPost')); //Checks to see if user wants to create a new post, and creates it.
        }
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
         * Enable/Disable Custom Post Editor
         *
         * Configure a custom Post Editor to use for your post type
         * This can be a completly custom editor without relying on any
         * core WordPress code. If you use the postEditor element from the Simpli_Forms
         * plugin, you can even add WordPress's editor easily but reposition it anywhere in your form.
         * You need to build it by editing the menu01_custom_post_type template
         * Activated  whenever the edit or add screen is displayed.
         * If false, you'll get the regular editor.
         */

        $this->setConfig('CUSTOM_POST_EDITOR_ENABLED', true);



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
     * Hook to Register Post Types
     *
     * Called by 'init' to register any post types
     * Ref:http://codex.wordpress.org/Function_Reference/register_post_type
     * @param none
     * @return void
     */
    public function hookRegisterPostTypes() {





        /*
         * Register Simpli Hello Forms
         */



        if ($this->isTopLevel()) {
            $parent_slug = true;
        } else {
            $parent_slug = $this->getTopLevelMenuSlug();
        }
        $this->debug()->logVar('$parent_slug = ', $parent_slug);
        $args = array(
            'label' => 'Simpli Hello Snippets' //A plural descriptive name for the post type marked for translation.
            , 'labels' => array(
                'name' => null // general name for the post type, usually plural. The same as, and overridden by $post_type_object->label
                , 'singular_name' => 'Simpli Hello Snippet' // name for one object of this post type. Defaults to value of name
                , 'menu_name' => 'Simpli Hello' // the menu name text. This string is the name to give menu items. Defaults to value of name
                , 'all_items' => 'Snippets' //the all items text used in the menu. Default is the Name label
                , 'add_new' => 'Add New Snippet' // 'Add New' text used in the menu title, and the button in the post editor. The default is Add New for both hierarchical and non-hierarchical types. When internationalizing this string, please use a gettext context matching your post type. Example: _x('Add New', 'product');
                , 'add_new_item' => 'Add New Snippet' // the add new item text displayed on the menu page itself (not the menu text). Default is Add New Post/Add New Page
                , 'new_item' => 'New Item' // the new item text. Default is New Post/New Page
                , 'view_item' => 'View Snippet' // the view item text. This appears on the normally labeled 'View Item' button in the post editor page. Default is View Post/View Page
                , 'search_items' => 'Search snippets' // the search items text. Default is Search Posts/Search Pages
                , 'not_found' => 'No snippets Found' //the not found text. Default is No posts found/No pages found
                , 'not_found_in_trash' => 'No snippets found in trash' // the not found in trash text. Default is No posts found in Trash/No pages found in Trash
                , 'parent_item_colon' => null // the parent text. This string isn't used on non-hierarchical types. In hierarchical ones the default is
            ) //
            , 'description' => 'A snippet of text or html that can be inserted into any post using the Simpli Hello option' // A short descriptive summary of what the post type is
            , 'public' => true //Whether a post type is intended to be used publicly either via the admin interface or by front-end users.
            , 'exclude_from_search' => false //Whether to exclude posts with this post type from front end search results.
            , 'publicly_queryable' => true //If you set this to FALSE, you will find that you cannot preview/see your custom post (return 404)
            , 'show_ui' => true //
            , 'show_in_nav_menus' => true //
            , 'show_in_menu' => $parent_slug //Where to show the post type in the admin menu. show_ui must be true. false, does not show. true , top level, string -parent menu slug
            , 'show_in_admin_bar' => true //make same as show_in_menu
            , 'menu_position' => $this->getPlugin()->getModule('Admin')->getMenuPosition() // The position in the menu order the post type should appear. show_in_menu must be true.
            , 'menu_icon' => $this->getPlugin()->getUrl() . '/admin/images/menu.png' //The url to the icon to be used for this menu.
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

        $this->_register_post_type($this->getPlugin()->getSlug() . '_snippet', $args);



        //add_post_type_support( $this->getPlugin()->getSlug() . '_snippet', array('title', 'editor') );
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
    private function _register_post_type($post_type, $args) {


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
            //   , 'rewrite' => true  //Triggers the handling of rewrites for this post type. To prevent rewrites, set to false. Default: true and use $post_type as slug
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

        $args = $this->getPlugin()->getTools()->screenDefaults($arg_defaults, $args);

        /*
         * Use array_filter to remove null values so that the wordpress builtin function supplies defaults
         */

        $args['labels'] = array_filter($args['labels']);
        $args = array_filter($args);


        register_post_type($post_type, $args);

        /*
         * set the post type added to the post type for the module
         */
        $this->setPostType($post_type);

        /*
         * update the menu tracker
         */
        $this->updateMenuTracker($this->getMenuSlug(), array('top_level_slug' => 'edit.php?post_type=' . $post_type));



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

        if (!$this->CUSTOM_POST_EDITOR_ENABLED) {
            return;
        }

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
     * Hook Add Meta Boxes
     *
     * Hook Function to add meta boxes to the current post
     * @param none
     * @return void
     */
    public function hookAddMetaBoxes() {
        $this->debug()->t();

        /*
         * add the custom post editor metabox only if we
         * it is enabled
         */

        if ($this->CUSTOM_POST_EDITOR_ENABLED) {


            if (!$this->pageCheck()) {
                return;
            }

//        /*
//         * if it passes the page check, you still want to check if the page matches, since
//         * admin menus will
//         */
//        $pageQueryVarCheck = (isset($_GET['page']) && $_GET['page'] === $this->getMenuSlug()); //does page match our page?
//
//        if (!$pageQueryVarCheck) {
//            return;
//        }

            add_meta_box(
                    $this->getSlug() . '_' . 'metabox_test'  //Meta Box DOM ID
                    , __('Custom Editor Metabox added from within ' . $this->getName(), $this->getPlugin()->getTextDomain()) //title of the metabox.
                    , array($this, 'renderMetaBoxTemplate') //function that prints the html
                    , $screen_id = null// must be null so WordPress uses current screen id as default. mistakenly called $post_type in the codex. See Source Code.
                    , 'normal' //normal advanced or side The part of the page where the metabox should show
                    , 'default' // 'high' , 'core','default', 'low' The priority within the context where the box should show
                    , null //$metabox['args'] in callback function
            );
        } else {
            if (!$this->pageCheck()) {
                return;
            }

            add_meta_box(
                    $this->getSlug() . '_' . 'metabox_test'  //Meta Box DOM ID
                    , __('Regular Editor Metabox added by ' . $this->getName(), $this->getPlugin()->getTextDomain()) //title of the metabox.
                    , array($this, 'renderMetaBoxTemplate') //function that prints the html
                    , $screen_id = null// must be null so WordPress uses current screen id as default. mistakenly called $post_type in the codex. See Source Code.
                    , 'normal' //normal advanced or side The part of the page where the metabox should show
                    , 'default' // 'high' , 'core','default', 'low' The priority within the context where the box should show
                    , null //$metabox['args'] in callback function
            );
        }



//
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



        //  $settings_page_slug = $this->getPlugin()->getSlug() . '_' . $this->getSlug(); //$this->getMenuSlug(); still empty


        $isScreenAdd = ($this->getPlugin()->getTools()->isScreen('add', $this->getPostType()));
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

            $redirect_url = admin_url() . $menu_tracker[$top_menu]['top_level_slug'] . '&' . $this->getPlugin()->QUERY_VAR . '=' . $this->getPlugin()->QV_ADD_POST . '&page=' . $this->getMenuSlug();

            //works too:         $redirect_url=(admin_url() . 'edit.php?' . $this->getPlugin()->QUERY_VAR . '=' . $this->getPlugin()->QV_ADD_POST . '&post_type='.$this->getPostType().'&page=' . $this->getMenuSlug());


            $this->debug()->logVar('$redirect_url = ', $redirect_url);

            wp_redirect($redirect_url);
        } else {

            /*
             * Otherwise, redirect using admin.php and the known post type previously set when we registered it.
             */


            $redirect_url = admin_url() . 'admin.php?' . $this->getPlugin()->QUERY_VAR . '=' . $this->getPlugin()->QV_ADD_POST . '&post_type=' . $this->getPlugin()->getTools()->getPostTypeQueryVar($this->getPostType()) . '&page=' . $this->getMenuSlug();


            $this->debug()->logVar('$redirect_url = ', $redirect_url);
            wp_redirect($redirect_url);
        }


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
         * paramaters if they exist.
         */
        if (is_null($post)) {
            if (isset($_GET['post'])) {
                $post = get_post($_GET['post']);

                $this->debug()->log('Used $_GET Paramater \'post\' to create post object of type \'' . $post->post_type . '\'');
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

        $isScreenEdit = $this->getPlugin()->getTools()->isScreen('edit-add', $this->getPostType());

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
            $redirect_url = admin_url() . $menu_tracker[$top_menu]['top_level_slug'] . '&' . $this->getPlugin()->QUERY_VAR . '=' . $this->getPlugin()->QV_EDIT_POST . '&post=' . $post->ID . '&page=' . $this->getMenuSlug();
            $this->debug()->logVar('$redirect_url = ', $redirect_url);

            wp_redirect($redirect_url);
            die();
        } else {
            $redirect_url = admin_url() . 'admin.php?' . $this->getPlugin()->QUERY_VAR . '=' . $this->getPlugin()->QV_EDIT_POST . '&post=' . $post->ID . '&page=' . $this->getMenuSlug();
            $this->debug()->logVar('$redirect_url = ', $redirect_url);

            wp_redirect($redirect_url);
              die();
        }


//'admin.php?'.$this->getPlugin()->QUERY_VAR.'='. $this->getPlugin()->QV_ADD_POST.'&
        // wp_redirect(admin_url() . 'admin.php?'.$this->getPlugin()->QUERY_VAR.'='. $this->getPlugin()->QV_EDIT_POST .'&post=' . $post->ID . '&page=' . $this->getMenuSlug());

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
        if (!$this->pageCheck()) {
            return;
        }



//dont show if the plugin is enabled
        if (($this->getPlugin()->getUserOption('plugin_enabled') == 'enabled')) {
            return;
        }
        ?>



        <div class="error">
            <p><strong>You have disabled <?php echo $this->getPlugin()->getName() ?> functionality.</strong> To re-enable <?php echo $this->getPlugin()->getName() ?> , set  'Maintenance -> Enable Plugin' to 'Yes'.</p>
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

    /**
     * Hook - Create New Post
     *
     * Creates a new post if the GET query variable has 'add_post' . This will occur when the 'add_new' link is clicked when there is a custom post type editor configured.
     *
     * @param none
     * @return void
     */
    public function hookCreateNewPost() {

        $tools = $this->getPlugin()->getTools();

        /*
         * Check if our query variable is set to 'add_post'. if it is, we are on the 'add_post' page and
         * we create an auto-draft of a post.
         */

        if (($tools->getQueryVar($this->getPlugin()->QUERY_VAR) === $this->getPlugin()->QV_ADD_POST)) {
            // Create post object
            /*
             * get post type,
             * We use the getPostTypeQueryVar() because it will be able to retrieve
             * the post_type even if its been obfuscated due to using a custom post editor.
             * post_type value will be obfuscated so WordPress doesnt recognize it as a valid registered post type.
             * if it did, it would throw an error (cannot load page) since it doesnt recognize it as a valid edit page.
             */

            $post_type = $this->getPlugin()->getTools()->getPostTypeQueryVar();

            global $post;
            $new_post_values = array(
                'post_status' => 'auto-draft',
                'post_author' => 1,
                'post_type' => $post_type
            );

// Insert the post into the database
            $id = wp_insert_post($new_post_values);
            $this->debug()->log('Created New Post with id = ' . $id);
            $post = get_post($id);
            $this->debug()->logVar('$post = ', $post);
        }
    }



}
?>