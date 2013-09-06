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
class Simpli_Hello_Module_Menu10Settings extends Simpli_Basev1c0_Plugin_Menu {

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
     * Admin panel menu option
     * WordPress Hook - admin_menu
     *
     * @param none
     * @return void
     */
    public function hookAdminMenu() {
        $this->debug()->t();

        /*
         *
         * Add the main menu
         *
         */
        /*
         * Add menu
         */

        $page_title = $this->getPlugin()->getName() . ' - General Settings';
        $menu_title = array('menu' => $this->getPlugin()->getName(), 'sub_menu' => 'General Settings');
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
        $this->debug()->t();








        add_meta_box(
                $this->getSlug() . '_' . 'metabox_about'  //Meta Box DOM ID
                , __('About Simpli Hello and the Simpli Framework', $this->getPlugin()->getTextDomain()) //title of the metabox.
                , array($this, 'renderMetaBoxTemplate') //function that prints the html
                , $screen_id = null// must be null so WordPress uses current screen id as default. mistakenly called $post_type in the codex. See Source Code.
                , 'normal' //normal advanced or side The part of the page where the metabox should show
                , 'default' // 'high' , 'core','default', 'low' The priority within the context where the box should show
                , null //$metabox['args'] in callback function
        );
////
////
//
//
        add_meta_box(
                $this->getSlug() . '_' . 'metabox_hellosettings'  //Meta Box DOM ID
                , __('Simpli Hello Plugin Settings', $this->getPlugin()->getTextDomain()) //title of the metabox.
                , array($this, 'renderMetaBoxTemplate') //function that prints the html
                , $screen_id = null// must be null so WordPress uses current screen id as default. mistakenly called $post_type in the codex. See Source Code.
                , 'normal' //normal advanced or side The part of the page where the metabox should show
                , 'default' // 'high' , 'core','default', 'low' The priority within the context where the box should show
                , null //$metabox['args'] in callback function
        );

        add_meta_box(
                $this->getSlug() . '_' . 'metabox_example'  //Meta Box DOM ID
                , __('Example Metabox with different input types', $this->getPlugin()->getTextDomain()) //title of the metabox.
                , array($this, 'renderMetaBoxTemplate') //function that prints the html
                , $screen_id = null// must be null so WordPress uses current screen id as default. mistakenly called $post_type in the codex. See Source Code.
                , 'normal' //normal advanced or side The part of the page where the metabox should show
                , 'default' // 'high' , 'core','default', 'low' The priority within the context where the box should show
                , null //$metabox['args'] in callback function
        );
//


        add_meta_box(
                $this->getSlug() . '_' . 'metabox_updates'  //Meta Box DOM ID
                , __('Plugin Updates', $this->getPlugin()->getTextDomain()) //title of the metabox
                , array($this, 'renderMetaBoxAjax') //function that prints the html
                , $screen_id// Current Screen ID . This is mistakenly called $post_type in the codex. See source.
                , 'side'//normal advanced or side The part of the page where the metabox should show
                , 'default' // 'high' , 'core','default', 'low' The priority within the context where the box should show
                , array('url' => 'http://www.simpliwp.com/simpli-framework/metabox-updates-example/') //$metabox['args'] in callback function
        );
//
//
        add_meta_box(
                $this->getSlug() . '_' . 'metabox_support'  //Meta Box DOM ID
                , __('Support', $this->getPlugin()->getTextDomain()) //title of the metabox
                , array($this, 'renderMetaBoxAjax') //function that prints the html
                , $screen_id// Current Screen ID . This is mistakenly called $post_type in the codex. See source.
                , 'side'//normal advanced or side The part of the page where the metabox should show
                , 'default' // 'high' , 'core','default', 'low' The priority within the context where the box should show
                , array('url' => 'http://www.simpliwp.com/simpli-framework/metabox-support-example/') //$metabox['args'] in callback function
        );
//
        add_meta_box(
                $this->getSlug() . '_' . 'metabox_feedback'  //Meta Box DOM ID
                , __('Feedback', $this->getPlugin()->getTextDomain()) //title of the metabox
                , array($this, 'renderMetaBoxAjax') //function that prints the html
                , $screen_id// Current Screen ID . This is mistakenly called $post_type in the codex. See source.
                , 'side'//normal advanced or side The part of the page where the metabox should show
                , 'default' // 'high' , 'core','default', 'low' The priority within the context where the box should show
                , array('url' => 'http://www.simpliwp.com/simpli-framework/metabox-feedback-example/') //$metabox['args'] in callback function
        );

        add_meta_box(
                $this->getSlug() . '_' . 'metabox_donate'  //Meta Box DOM ID
                , __('Donate', $this->getPlugin()->getTextDomain()) //title of the metabox
                , array($this, 'renderMetaBoxAjax') //function that prints the html
                , $screen_id// Current Screen ID . This is mistakenly called $post_type in the codex. See source.
                , 'side'//normal advanced or side The part of the page where the metabox should show
                , 'default' // 'high' , 'core','default', 'low' The priority within the context where the box should show
                , array('url' => 'http://www.simpliwp.com/simpli-framework/metabox-donate-example/') //$metabox['args'] in callback function
        );
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