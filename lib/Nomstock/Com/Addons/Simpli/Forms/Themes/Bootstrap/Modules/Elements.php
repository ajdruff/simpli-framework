<?php

/**
 * Form Elements Module
 *
 * Provides Basic Elements
 *
 * @author Andrew Druffner
 * @package SimpliFramework
 * @subpackage SimpliAddonsForms
 *
 */
class Nomstock_Com_Addons_Simpli_Forms_Themes_Bootstrap_Modules_Elements extends Nomstock_Com_Addons_Simpli_Forms_Modules_Elements {

    /**
     * Add Hooks
     *
     * Adds WordPress Hooks, triggered during module initialization
     * @param none
     * @return void
     */
    public function addHooks() {
        $this->debug()->t();




        add_action( 'wp_enqueue_scripts', array( $this, 'hookEnqueueScripts' ) );




        /**
         *
         *
         *  add scripts
         * example: add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
         *
         */
        /**
         *
         * Add custom ajax handlers
         *  Map Ajax Handlers to Ajax Actions passed to php by the ajax request
         * example: add_action('wp_ajax_' . $this->plugin()->getSlug() . '_my_action', array($this, 'my_function'));
         * see http://codex.wordpress.org/Plugin_API/Action_Reference/wp_ajax_%28action%29
         */
       

   }

    /**
     * Adds javascript and stylesheets to admin panel
     * WordPress Hook - admin_enqueue_scripts
     *
     * @param none
     * @return void
     */
    public function hookEnqueueScripts() {
         /*
         * Add The Bootstrap CSS Style and Javascript
         * 
         */
        $theme = $this->addon()->getModule( 'Theme' );
        $relative_path = $this->plugin()->tools()->getRelativePath( $this->plugin()->getDirectory(), $theme->getThemeDirectory() );


        //   die('url is ' . $this->plugin()->getURL() . '/' . $relative_path . '/css/bootstrap-3.0.1/css/bootstrap.min.css' );



        wp_enqueue_style(
                $this->plugin()->getSlug() . '-bootstrap', //handle
                $this->plugin()->getURL() . '/' . $relative_path . '/css/bootstrap-3.0.1/css/bootstrap.min.css', //url
                array(), //dependents
                '3.0.1' //version
        );



        wp_enqueue_script(
                $this->plugin()->getSlug() . '-bootstrap', // $handle
                $this->plugin()->getURL() . '/' . $relative_path . '/css/bootstrap-3.0.1/js/bootstrap.min.js', // $src, 
                array( 'jquery' ), // $deps, 
                '3.0.1', // $ver, 
                true// $in_footer 
        );



        /*
         * Add Validation Script
         */


        wp_enqueue_script(
                $this->plugin()->getSlug() . '-jquery-validate', // $handle
                'http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.js', // $src, 
                array( 'jquery' ), // $deps, 
                '1.11.1', // $ver, 
                false// $in_footer 
        );
    }

}
