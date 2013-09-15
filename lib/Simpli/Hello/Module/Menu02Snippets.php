<?php

/**
 * Top Level (Main) Admin Menu
 *
 *
 * @author Andrew Druffner
 * @package SimpliFramework
 * @subpackage SimpliHello
 */
class Simpli_Hello_Module_Menu02Snippets extends Simpli_Basev1c0_Plugin_PostType {



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
 * add the post type
 */

$this->registerPostType(
        $this->getPlugin()->getSlug() . '_snippet',
                array(
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
        ));

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

        $this->setConfig('CUSTOM_POST_EDITOR_ENABLED', false);



        /*
         * add the custom post editor
         */

        $this->addCustomPostEditor(
                $page_title = $this->getPlugin()->getName() . ' - Editor'
                , $menu_title = 'Edit My Custom Post Type'
                , $capability = 'edit_published_posts'
                , $icon_url = $this->getPlugin()->getUrl() . '/admin/images/menu.png'
        );


        $post = $this->getPlugin()->tools()->getPost();
        $this->debug()->logVar('$post = ', $post);
        if ($this->CUSTOM_POST_EDITOR_ENABLED) {


            $this->debug()->log('Adding a metabox to a custom  Editing Screen');

            $this->metabox()->addMetaBox(
                    $this->getSlug() . '_' . 'metabox_post_options'  //Meta Box DOM ID
                    , __('Custom Editor Metabox added from within ' . $this->getName(), $this->getPlugin()->getTextDomain()) //title of the metabox.
                    , array($this->metabox(), 'renderMetaBoxTemplate') //function that prints the html
                    , $screen_id = null// must be null so WordPress uses current screen id as default. mistakenly called $post_type in the codex. See Source Code.
                    , 'normal' //normal advanced or side The part of the page where the metabox should show
                    , 'default' // 'high' , 'core','default', 'low' The priority within the context where the box should show
                    , null //$metabox['args'] in callback function
            );
        } else {

            /*
             * add metaboxes to the regular editor
             */
            $this->debug()->log('Adding a metabox to the regular WordPress Editing Screen');

            $this->metabox()->addMetaBox(
                    $this->getSlug() . '_' . 'metabox_post_options'  //Meta Box DOM ID
                    , __('Regular Editor Metabox added by ' . $this->getName(), $this->getPlugin()->getTextDomain()) //title of the metabox.
                    , array($this->metabox(), 'renderMetaBoxTemplate') //function that prints the html
                    , $screen_id = null// must be null so WordPress uses current screen id as default. mistakenly called $post_type in the codex. See Source Code.
                    , 'normal' //normal advanced or side The part of the page where the metabox should show
                    , 'default' // 'high' , 'core','default', 'low' The priority within the context where the box should show
                    , null //$metabox['args'] in callback function
            );
        }

        $this->metabox()->setMetaboxOpenState('post_user_options_metabox_ajax_options', false, true);
    }



}
?>