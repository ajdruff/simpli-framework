<?php

/**
 * Theme Module
 *
 * Loads Custom Stylesheets and Javascript required for styling
 *
 * @author Andrew Druffner
 * @package SimpliFramework
 *
 *
 */
class Nomstock_Com_Modules_Theme extends Nomstock_Com_Base_v1c2_Plugin_Module {

    /**
     * Configure Module
     *
     * @param none
     * @return void
     */
    public function config() {
        $this->debug()->t(); //trace provides a information about the method and arguments, and provides a backtrace in an expandable box. A visual trace is also provided if graphiviz is enabled.
    }

    /**
     * Add Hooks
     *
     * Adds WordPress Hooks, triggered during module initialization
     * @param none
     * @return void
     */
    public function addHooks() {
        $this->debug()->t();



        /*
         * add scripts
         *  */

        add_action( 'wp_enqueue_scripts', array( $this, 'hookEnqueueScripts' ) );


        /*
         *  add custom ajax handlers
         * this is where you map any form actions with the class method that handles the ajax request
         *
         * adding a wp_ajax hook in this format will execute the specified class method whenever the ajax request specifies an action = to $this->plugin()->getSlug() . '_xxxx'
          see http://codex.wordpress.org/Plugin_API/Action_Reference/wp_ajax_%28action%29
         *
         * Example:
         * add_action('wp_ajax_' . $this->plugin()->getSlug() . '_settings_save', array($this, 'save'));
         *
         */
    }

    /**
     * Adds javascript and stylesheets
     * WordPress Hook - hookEnqueueScripts
     *
     * @param none
     * @return void
     */
    public function hookEnqueueScripts() {
        $this->debug()->t();
        /* Example
          wp_enqueue_style($this->plugin()->getSlug() . '-admin-page', $this->plugin()->getAdminUrl() . '/css/settings.css', array(), $this->plugin()->getVersion());
          wp_enqueue_script('jquery');
          wp_enqueue_script('jquery-form');
          wp_enqueue_script('post');
         *
         */

        /* Example
          $handle = $this->plugin()->getSlug() . '_core.js';
          $src = $this->plugin()->getUrl() . '/js/' . $this->plugin()->getSlug() . '_core.js';
          $deps = 'jquery';
          $ver = '1.0';
          $in_footer = false;
          wp_enqueue_script($handle, $src, $deps, $ver, $in_footer);
         *
         */

        wp_enqueue_style(
                $this->plugin()->getSlug() . '-google-fonts', "//fonts.googleapis.com/css?family=Crete+Round:400italic,400", array(), //dependents
                ''
        );

        wp_enqueue_style(
                $this->plugin()->getSlug() . '-google-font-faces', $this->plugin()->URL_CSS . '/stylesheet.css', array(), //dependents
                ''
        );



        wp_enqueue_style(
                $this->plugin()->getSlug() . '-bootstrap', $this->plugin()->URL_CSS . '/bootstrap-3.0.1/css/bootstrap.min.css', array(), //dependents
                '3.0.1'
        );

        wp_enqueue_script(
                $this->plugin()->getSlug() . '-bootstrap', //$handle 
                $this->plugin()->URL_CSS . '/bootstrap-3.0.1/js/bootstrap.min.js', // $src
                array( 'jquery' ), //$deps
                '3.0.1', //$ver
                false  // $in_footer 
        );



    }

}
