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
class Simpli_Hello_Module_Menu10Settings extends Simpli_Basev1c0_Plugin_Menu {

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


        /*
         * Add an admin notice if disabled
         *
         */
                    add_action('admin_notices', array(&$this, 'showDisabledMessage'));

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
         * Add the main menu
         *
         */

        add_menu_page(
                $this->getPlugin()->getName() . ' - General Settings' // page title
                , $this->getPlugin()->getName() // menu title
                , 'manage_options'  // capability
                , $this->getPlugin()->getSlug() . '_' . $this->getSlug()  // menu slug
                // , array($this->getPlugin()->getModule($this->getName()), 'dispatch') //function
                , array($this, 'dispatch') //function to display the html
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
                $this->getPlugin()->getSlug() . '_menu10_settings' // parent slug
                , $this->getPlugin()->getName() . ' - General Settings' // page title
                , 'General Settings' // menu title
                , 'manage_options'  // capability
                , $this->getPlugin()->getSlug() . '_menu10_settings'  // make sure this is the same slug as the main menu so it overwrites the main menus submenu title
                , array($this, 'dispatch') //function to display the html
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
                , __('Example Settings Metabox', $this->getPlugin()->getSlug()) //title of the metabox.
                , array($this->getPlugin()->getModule('Admin'), 'meta_box_render') //function that prints the html
                , 'toplevel_page_' . $this->getPlugin()->getSlug() . '_' . $this->getSlug() . '_group1' //the post type to show the metabox
                , 'main' //normal advanced or side The part of the page where the metabox should show
                , 'high' // 'high' , 'core','default', 'low' The priority within the context where the box should show
                , array('metabox' => $this->getSlug() . '_metabox_general') //callback arguments.  'metabox' is the folder,  'settings_sub_menu_example_metabox1' is the template file
        );






        add_meta_box(
                $this->getPlugin()->getSlug() . '_updates' //HTML id attribute of metabox
                , __('Plugin Updates', $this->getPlugin()->getSlug()) //title of the metabox
                , array($this->getPlugin()->getModule('Admin'), 'meta_box_render') //function that prints the html
                , 'toplevel_page_' . $this->getPlugin()->getSlug() . '_' . $this->getSlug() . '_group2' //the post type to show the metabox
                , 'side'//normal advanced or side The part of the page where the metabox should show
                , 'low' // 'high' , 'core','default', 'low' The priority within the context where the box should show
                , array('metabox' => 'ajax', 'url' => 'http://www.simpliwp.com/simpli-framework/metabox-updates-example/') //callback arguments. file that contains the html for the metabox. metabox is the folder, 'settings-example' is the file in the folder
        );

        add_meta_box(
                $this->getPlugin()->getSlug() . '_support' //HTML id attribute of metabox
                , __('Support', $this->getPlugin()->getSlug()) //title of the metabox
                , array($this->getPlugin()->getModule('Admin'), 'meta_box_render') //function that prints the html
                , 'toplevel_page_' . $this->getPlugin()->getSlug() . '_' . $this->getSlug() . '_group2' //the post type to show the metabox
                , 'side'//normal advanced or side The part of the page where the metabox should show
                , 'low' // 'high' , 'core','default', 'low' The priority within the context where the box should show
                , array('metabox' => 'ajax', 'url' => 'http://www.simpliwp.com/simpli-framework/metabox-support-example/') //callback arguments. file that contains the html for the metabox. metabox is the folder, 'settings-example' is the file in the folder
        );

        add_meta_box(
                $this->getPlugin()->getSlug() . '_feedback' //HTML id attribute of metabox
                , __('Feedback', $this->getPlugin()->getSlug()) //title of the metabox
                , array($this->getPlugin()->getModule('Admin'), 'meta_box_render') //function that prints the html
                , 'toplevel_page_' . $this->getPlugin()->getSlug() . '_' . $this->getSlug() . '_group2' //the post type to show the metabox
                , 'side'//normal advanced or side The part of the page where the metabox should show
                , 'low' // 'high' , 'core','default', 'low' The priority within the context where the box should show
                , array('metabox' => 'ajax', 'url' => 'http://www.simpliwp.com/simpli-framework/metabox-feedback-example/') //callback arguments. file that contains the html for the metabox. metabox is the folder, 'settings-example' is the file in the folder
        );

        add_meta_box(
                $this->getPlugin()->getSlug() . '_donate' //HTML id attribute of metabox
                , __('Donate', $this->getPlugin()->getSlug()) //title of the metabox
                , array($this->getPlugin()->getModule('Admin'), 'meta_box_render') //function that prints the html
                , 'toplevel_page_' . $this->getPlugin()->getSlug() . '_' . $this->getSlug() . '_group2' //the post type to show the metabox
                , 'side'//normal advanced or side The part of the page where the metabox should show
                , 'low' // 'high' , 'core','default', 'low' The priority within the context where the box should show
                , array('metabox' => 'ajax', 'url' => 'http://www.simpliwp.com/simpli-framework/metabox-donate-example/') //callback arguments. file that contains the html for the metabox. metabox is the folder, 'settings-example' is the file in the folder
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
        wp_enqueue_style($this->getPlugin()->getSlug() . '-admin-page', $this->getPlugin()->getPluginUrl() . '/admin/css/settings.css', array(), $this->getPlugin()->getVersion());
        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-form');
        wp_enqueue_script('post');

        if (function_exists('add_thickbox')) {
            add_thickbox();
        }
    }


    /**
     * Shows a disabled message if the plugin is disabled via the settings
     * This will only appear when first switching to the general settings page. Its assumed that the settings that trigger
     * it are set on a different (advanced) menu page.
     *
     */
    public function showDisabledMessage() {


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
