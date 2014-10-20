<?php

/**
 * Post Type Module
 *
 *
 * @author Andrew Druffner
 * @package SimpliFramework
 *
 */
class Nomstock_Com_Modules_Menu05UserTemplates extends Nomstock_Com_Base_v1c2_Plugin_PostType {


/**
 * Config
 *
 * Long Description
 ** @param none
 * @return void
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
$this->plugin()->tools()->shortenSlugMore($this->plugin()->getSlug()) . ('_template')
, array(
'label' => 'Templates' //A plural descriptive name for the post type marked for translation.
, 'labels' => array(
'name' => null // general name for the post type, usually plural. The same as, and overridden by $post_type_object->label
, 'singular_name' => 'Template' // name for one object of this post type. Defaults to value of name
, 'menu_name' => 'Templates' // the menu name text. This string is the name to give menu items. Defaults to value of name
, 'all_items' => 'Templates' //the all items text used in the menu. Default is the Name label
, 'add_new' => 'Add New Template' // 'Add New' text used in the menu title, and the button in the post editor. The default is Add New for both hierarchical and non-hierarchical types. When internationalizing this string, please use a gettext context matching your post type. Example: _x('Add New', 'product');
, 'add_new_item' => 'Add New Template' // the add new item text displayed on the menu page itself (not the menu text). Default is Add New Post/Add New Page
, 'new_item' => 'New Item' // the new item text. Default is New Post/New Page
, 'view_item' => 'View Template' // the view item text. This appears on the normally labeled 'View Item' button in the post editor page. Default is View Post/View Page
, 'search_items' => 'Search templates' // the search items text. Default is Search Posts/Search Pages
, 'not_found' => 'No templates Found' //the not found text. Default is No posts found/No pages found
, 'not_found_in_trash' => 'No templates found in trash' // the not found in trash text. Default is No posts found in Trash/No pages found in Trash
, 'parent_item_colon' => null // the parent text. This string isn't used on non-hierarchical types. In hierarchical ones the default is
) //
, 'description' => 'Templates to be used for sales landing pages' // A short descriptive summary of what the post type is
, 'public' => false //Whether a post type is intended to be used publicly either via the admin interface or by front-end users.
, 'exclude_from_search' => true //Whether to exclude posts with this post type from front end search results.
, 'publicly_queryable' => false //If you set this to FALSE, you will find that you cannot preview/see your custom post (return 404)
, 'show_ui' => true //
, 'show_in_nav_menus' => true //
, 'menu_position' => $this->plugin()->getModule('Admin')->getMenuPosition() // The position in the menu order the post type should appear. show_in_menu must be true.
, 'menu_icon' => $this->plugin()->getUrl() . '/admin/images/menu.png' //The url to the icon to be used for this menu.
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
 * This can be a completely customized editor without relying on any
 * core WordPress code. If you use the postEditor element from the Simpli_Forms
 * plugin, you can even add WordPress's editor easily but reposition it anywhere in your form.
 * You need to build it by editing the menu01_custom_post_type template
 * Activated  whenever the edit or add screen is displayed.
 * If false, you'll get the regular editor.
 */

$this->setConfig('CUSTOM_POST_EDITOR_ENABLED', false);








}

}
?>