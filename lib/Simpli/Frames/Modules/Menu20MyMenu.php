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
class Simpli_Frames_Modules_Menu20MyMenu extends Simpli_Frames_Base_v1c2_Plugin_Menu {

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

        $this->UNIQUE_ACTION_NONCES = true;

        /*
         * Add any other hooks you need - see base class for examples
         *
         */
        $this->metabox()->addFormAction('say_hello');
        $this->metabox()->addFormActionAjax('say_hello');
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
                $page_title = $this->plugin()->getName() . 'My Menu Settings'
                , $menu_title = array('menu' => $this->plugin()->getName(), 'sub_menu' => 'My Menu Settings')
                , $capability = 'manage_options'
                , $icon_url = $this->plugin()->getUrl() . '/admin/images/menu.png'
                , $position = null
        );



        $this->metabox()->addMetaBox(
                'metabox_settings'  //Meta Box DOM ID
                , __('Plugin Settings', $this->plugin()->getTextDomain()) //title of the metabox.
                , array($this->metabox(), 'renderMetaBoxTemplate') //function that prints the html
                , $screen_id = null// must be null so WordPress uses current screen id as default. mistakenly called $post_type in the codex. See Source Code.
                , 'normal' //normal advanced or side The part of the page where the metabox should show
                , 'default' // 'high' , 'core','default', 'low' The priority within the context where the box should show
                , null //$metabox['args'] in callback function
        );

        $this->metabox()->addMetaBox(
                'metabox_settings_box2'  //Meta Box DOM ID
                , __('Plugin Settings', $this->plugin()->getTextDomain()) //title of the metabox.
                , array($this->metabox(), 'renderMetaBoxTemplate') //function that prints the html
                , $screen_id = null// must be null so WordPress uses current screen id as default. mistakenly called $post_type in the codex. See Source Code.
                , 'normal' //normal advanced or side The part of the page where the metabox should show
                , 'default' // 'high' , 'core','default', 'low' The priority within the context where the box should show
                , null //$metabox['args'] in callback function
        );
        /*
         * {META_BOX}
         * Add additional Metaboxes here
         *
         */
    }

    public function hookFormActionSayHello() {



        $this->debug()->t();

        $logout = false;
        $reload = false;
        /*
         * Check Nonces
         */
        if (!$this->metabox()->wpVerifyNonce(__FUNCTION__)) {
            $message = "We\'re sorry, but your form submission failed because your session timed out. Please log out,log back in and try again.";

            $logout = true;
        } else {
            /*
             * do something
             */
            $logout = false;
            $message = 'Hello, world!';
        }

        $this->metabox()->showResponseMessage(
                //  $this->metabox()->setResponseMessage(
                $this->plugin()->getDirectory() . '/admin/templates/ajax_message_admin_panel.php', //string $template The path to the template to be used
                $message, // string $message The html or text message to be displayed to the user
                array(), //$errors Any error messages to display
                $logout, //boolean $logout Whether to force a logout after the message is displayed
                $reload //boolean $reload Whether to force a page reload after the message is displayed
        );
    }

}

?>