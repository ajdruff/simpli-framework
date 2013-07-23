<?php

/**
 * Sub Menu
 *
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
     * Admin panel menu option
     * WordPress Hook - admin_menu
     *
     * @param none
     * @return void
     */
    public function hookAdminMenu() {
        /*
         *
         * Add the main menu
         *
         */
        /*
         * Add menu
         */

        $page_title = $this->getPlugin()->getName() . ' - Advanced Settings';
        $menu_title = 'Advanced Settings';
        $capability = 'manage_options';
        $icon_url = $this->getPlugin()->getUrl() . '/admin/images/menu.png';

        $this->addMenuPage($page_title, $menu_title, $capability, $icon_url, null);
    }

    /**
     * Add meta boxes
     *
     * @param none
     * @return void
     */
    public function add_meta_boxes() {







        add_meta_box(
                $this->getSlug() . '_' . 'metabox_about'  //Meta Box DOM ID
                , __('About Simpli Hello and the Simpli Framework', $this->getPlugin()->getTextDomain()) //title of the metabox.
                , array($this, 'renderMetaBoxTemplate') //function that prints the html
                , $screen_id = null// must be null so WordPress uses current screen id as default. mistakenly called $post_type in the codex. See Source Code.
                , 'normal' //normal advanced or side The part of the page where the metabox should show
                , 'default' // 'high' , 'core','default', 'low' The priority within the context where the box should show
                , array('path' => $this->getPlugin()->getDirectory() . '/admin/templates/metabox/menu10_settings_metabox_about.php') //$metabox['args'] in callback function
        );
////
////
//
//
        add_meta_box(
                $this->getSlug() . '_' . 'metabox_maintain'  //Meta Box DOM ID
                , __('Maintenance', $this->getPlugin()->getTextDomain()) //title of the metabox.
                , array($this, 'renderMetaBoxTemplate') //function that prints the html
                , $screen_id = null// must be null so WordPress uses current screen id as default. mistakenly called $post_type in the codex. See Source Code.
                , 'normal' //normal advanced or side The part of the page where the metabox should show
                , 'default' // 'high' , 'core','default', 'low' The priority within the context where the box should show
                , null //$metabox['args'] in callback function
        );
    }

}

?>