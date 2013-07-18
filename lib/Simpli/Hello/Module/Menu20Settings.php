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
class Simpli_Hello_Module_Menu20Settings extends Simpli_Basev1c0_Plugin_Menu {

    /**
     * Initialize Module
     *
     * @param none
     * @return void
     */
    public function init() {


        /*
         *  Add Custom Ajax Handlers
         *
         * adding a wp_ajax hook in this format will execute the specified class method whenever the ajax request specifies an action = to $this->getPlugin()->getSlug() . '_xxxx'
        see http://codex.wordpress.org/Plugin_API/Action_Reference/wp_ajax_%28action%29

 Example ( this is included in base class so no need to add it here
        //add_action('wp_ajax_' . $this->getPlugin()->getSlug() . '_settings_save', array(&$this, 'save'));
         *
         *
         *
         */


        /*
         * Add any other hooks you need - see base class for examples
         *
         */


        parent::init();
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
         * Add a submenu that points to the same page as the main menu
         * This allows us to create a menu title that is different than the main heading
         *
         */

add_submenu_page(
                $this->getPlugin()->getSlug() .'_menu10_settings' // parent slug
                , $this->getPlugin()->getName() . ' - Settings Submenu' // page title
                , 'Advanced' // menu title
                , 'manage_options'  // capability
                , $this->getPlugin()->getSlug() . '_' .$this->getSlug()  // menu slug
                , array($this, 'dispatch') //function that provides the html. You will receive a 'Module not found' error if the name doesnt match any class names in the Module directory
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
                , 'toplevel_page_' . $this->getPlugin()->getSlug() . '_' . $this->getSlug() . '_group1' //the post type to show the metabox
                , 'main' //normal advanced or side The part of the page where the metabox should show
                , 'high' // 'high' , 'core','default', 'low' The priority within the context where the box should show
                , array('metabox' => $this->getSlug() . '_metabox_maintain') //callback arguments.  'metabox' is the folder,  'settings_sub_menu_example_metabox1' is the template file
        );





     }

    /**
     * Adds javascript and stylesheets to settings page in the admin panel.
     * WordPress Hook - enqueue_scripts
     *
     * @param none
     * @return void
     */
    public function admin_enqueue_scripts() {
        wp_enqueue_style($this->getPlugin()->getSlug() . '-admin-page', $this->getPlugin()->getUrl() . '/admin/css/settings.css', array(), $this->getPlugin()->getVersion());
        wp_enqueue_script('jquery-form');
        wp_enqueue_script('post');

        if (function_exists('add_thickbox')) {
            add_thickbox();
        }
    }

}
