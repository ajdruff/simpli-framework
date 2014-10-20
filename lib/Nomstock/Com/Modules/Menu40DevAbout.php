<?php

/**
 * Admin Menu
 *
 *
 * @author Andrew Druffner
 * @package SimpliFramework
 *
 *
 */
class Simpli_Frames_Modules_Menu40DevAbout extends Simpli_Frames_Base_v1c2_Plugin_Menu {

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
    }

    /**
     * Config
     *
     * Long Description
     * * @param none
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
         * Add the Menu Page
         */

        $this->addMenuPage
                (
                $page_title = $this->plugin()->getName() . '- About'
                , $menu_title = 'About'
                , $capability = 'manage_options'
                , $icon_url = $this->plugin()->getUrl() . '/admin/images/menu.png'
                , $position = null
        );



        $this->metabox()->addMetaBox(
                'metabox_addons'  //Meta Box DOM ID
                , __('About Addons', $this->plugin()->getTextDomain()) //title of the metabox.
                , array($this->metabox(), 'renderMetaBoxTemplate') //function that prints the html
                , $screen_id = null// must be null so WordPress uses current screen id as default. mistakenly called $post_type in the codex. See Source Code.
                , 'normal' //normal advanced or side The part of the page where the metabox should show
                , 'default' // 'high' , 'core','default', 'low' The priority within the context where the box should show
                , null //$metabox['args'] in callback function
        );

        $this->metabox()->addMetaBox(
                'metabox_addons2'  //Meta Box DOM ID
                , __('About Addons2', $this->plugin()->getTextDomain()) //title of the metabox.
                , array($this->metabox(), 'renderMetaBoxTemplate') //function that prints the html
                , $screen_id = null// must be null so WordPress uses current screen id as default. mistakenly called $post_type in the codex. See Source Code.
                , 'normal' //normal advanced or side The part of the page where the metabox should show
                , 'default' // 'high' , 'core','default', 'low' The priority within the context where the box should show
                , null //$metabox['args'] in callback function
        );


        /*
         * Set the Meta Box Initial Open/Close state
         */
        $this->metabox()->setOpenState
                (
                'metabox_addons'
                , 'close' //
                , false  //persist?
        );
        $this->metabox()->setOpenState
                (
                'metabox_addons2'
                , 'close' //open
                , false  //but you can change it
        );

        /*
         * {META_BOX}
         * Add additional Metaboxes here
         *
         */
    }

}

?>