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
class Simpli_Hello_Modules_Menu10General extends Simpli_Hello_Basev1c2_Plugin_Menu {

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
         * Set the Meta Box Initial Open/Close state
         */
        $this->metabox()->setMetaboxOpenState
                ($this->getSlug() . '_metabox_about'
                , false
                , true
        );

        /*
         * Add the Menu Page
         */

        $this->addMenuPage
                (
                $page_title = $this->plugin()->getName() . ' - General Settings'
                , $menu_title = array('menu' => $this->plugin()->getName(), 'sub_menu' => 'General Settings')
                , $capability = 'manage_options'
                , $icon_url = $this->plugin()->getUrl() . '/admin/images/menu.png'
                , $position = null
        );


        $this->metabox()->addMetaBox(
                $this->getSlug() . '_' . 'metabox_about'  //Meta Box DOM ID
                , __('About Simpli Hello and the Simpli Framework', $this->plugin()->getTextDomain()) //title of the metabox.
                , array($this->metabox(), 'renderMetaBoxTemplate') //function that prints the html
                , $screen_id = null// must be null so WordPress uses current screen id as default. mistakenly called $post_type in the codex. See Source Code.
                , 'normal' //normal advanced or side The part of the page where the metabox should show
                , 'default' // 'high' , 'core','default', 'low' The priority within the context where the box should show
                , null //$metabox['args'] in callback function
        );
////
////
//
//


        $this->metabox()->addMetaBox(
                $this->getSlug() . '_' . 'metabox_hellosettings'  //Meta Box DOM ID
                , __('Simpli Hello Plugin Settings', $this->plugin()->getTextDomain()) //title of the metabox.
                , array($this->metabox(), 'renderMetaBoxTemplate') //function that prints the html
                , $screen_id = null// must be null so WordPress uses current screen id as default. mistakenly called $post_type in the codex. See Source Code.
                , 'normal' //normal advanced or side The part of the page where the metabox should show
                , 'default' // 'high' , 'core','default', 'low' The priority within the context where the box should show
                , null //$metabox['args'] in callback function
        );


        $this->metabox()->addMetaBox(
                $this->getSlug() . '_' . 'metabox_example'  //Meta Box DOM ID
                , __('Example Metabox with different input types', $this->plugin()->getTextDomain()) //title of the metabox.
                , array($this->metabox(), 'renderMetaBoxTemplate') //function that prints the html
                , $screen_id = null// must be null so WordPress uses current screen id as default. mistakenly called $post_type in the codex. See Source Code.
                , 'normal' //normal advanced or side The part of the page where the metabox should show
                , 'default' // 'high' , 'core','default', 'low' The priority within the context where the box should show
                , null //$metabox['args'] in callback function
        );

        /*
         * Ajax Meta Box Examples
         *
         * Examples of Meta Boxes that are rendered by pulling remote content using an ajax request
         * Do *not* include these in your final plugin without changing the url to a remote site that you control.
         *
         */

        $this->metabox()->addMetaBox(
                $this->getSlug() . '_' . 'metabox_updates'  //Meta Box DOM ID
                , __('Plugin Updates', $this->plugin()->getTextDomain()) //title of the metabox
                , array($this->metabox(), 'renderMetaBoxAjax') //function that prints the html
                , $screen_id// Current Screen ID . This is mistakenly called $post_type in the codex. See source.
                , 'side'//normal advanced or side The part of the page where the metabox should show
                , 'default' // 'high' , 'core','default', 'low' The priority within the context where the box should show
                , array('url' => $this->plugin()->getUrl() . '/admin/plugin-updates-example.html') // replace this with a url that points to your plugin's website
        );


        $this->metabox()->addMetaBox(
                $this->getSlug() . '_' . 'metabox_support'  //Meta Box DOM ID
                , __('Support', $this->plugin()->getTextDomain()) //title of the metabox
                , array($this->metabox(), 'renderMetaBoxAjax') //function that prints the html
                , $screen_id// Current Screen ID . This is mistakenly called $post_type in the codex. See source.
                , 'side'//normal advanced or side The part of the page where the metabox should show
                , 'default' // 'high' , 'core','default', 'low' The priority within the context where the box should show
                , array('url' => $this->plugin()->getUrl() . '/admin/plugin-support-example.html') // replace this with a url that points to your plugin's website
        );
//
        $this->metabox()->addMetaBox(
                $this->getSlug() . '_' . 'metabox_feedback'  //Meta Box DOM ID
                , __('Feedback', $this->plugin()->getTextDomain()) //title of the metabox
                , array($this->metabox(), 'renderMetaBoxAjax') //function that prints the html
                , $screen_id// Current Screen ID . This is mistakenly called $post_type in the codex. See source.
                , 'side'//normal advanced or side The part of the page where the metabox should show
                , 'default' // 'high' , 'core','default', 'low' The priority within the context where the box should show
                , array('url' => $this->plugin()->getUrl() . '/admin/plugin-feedback-example.html') // replace this with a url that points to your plugin's website
        );

        $this->metabox()->addMetaBox(
                $this->getSlug() . '_' . 'metabox_donate'  //Meta Box DOM ID
                , __('Donate', $this->plugin()->getTextDomain()) //title of the metabox
                , array($this->metabox(), 'renderMetaBoxAjax') //function that prints the html
                , $screen_id// Current Screen ID . This is mistakenly called $post_type in the codex. See source.
                , 'side'//normal advanced or side The part of the page where the metabox should show
                , 'default' // 'high' , 'core','default', 'low' The priority within the context where the box should show
                , array('url' => $this->plugin()->getUrl() . '/admin/plugin-donate-example.html') // replace this with a url that points to your plugin's website
        );


        /*
         * Lets use unique nonces for extra security :)
         */
        $this->setConfig('UNIQUE_ACTION_NONCES', false);
    }

    /**
     * Shows a disabled message if the plugin is disabled via the settings
     * This will only appear when first switching to the general settings page. Its assumed that the settings that trigger
     * it are set on a different (advanced) menu page.
     *
     */
    public function showDisabledMessage() {
        $this->debug()->t();

        if (!$this->pageCheckMenu()) {
            return;
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

}
?>