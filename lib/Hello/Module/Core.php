<?php

/**
 * Core Module
 *
 * Plugin's core functionality
 *
 * @author Andrew Druffner
 * @package Hello
 *
 */
class Hello_Module_Core extends Simpliv1c0_Plugin_Module {

    private $moduleName;
    private $moduleSlug;

    /**
     * Initialize Module
     *
     * @param none
     * @return void
     */
    public function init() {


        /*
         *
         * Set the Module Name based on the name of this file
         * for MyModule.php , moduleName=MyModule
         *
         */


        $this->moduleName = basename(__FILE__, ".php");

        /*
         * Create an equivilent 'module slug' that is the module name but lower cased and separated by underscores
         * for for MyModule.php , moduleName=MyModule , moduleSlug='my_module'
         * http://stackoverflow.com/q/8611617
         */
        $regex = '/(?<!^)((?<![[:upper:]])[[:upper:]]|[[:upper:]](?![[:upper:]]))/';
        $this->moduleSlug = strtolower(preg_replace($regex, '_$1', $this->moduleName));








        /*
         * add scripts
         *  */

        add_action('wp_enqueue_scripts', array(&$this, 'enqueue_scripts'));

        /*
         *  add custom ajax handlers
         *
         * adding a wp_ajax hook in this format will execute the specified class method whenever the ajax request specifies an action = to $this->getPlugin()->getSlug() . '_xxxx'
          // see http://codex.wordpress.org/Plugin_API/Action_Reference/wp_ajax_%28action%29
         *
         */
//this is where you map any form actions with the php function that handles the ajax request
//  add_action('wp_ajax_' . $this->getPlugin()->getSlug() . '_settings_save', array(&$this, 'save'));
// Add any other hooks you need to support this module






    }

    /**
     * Adds javascript and stylesheets
     * WordPress Hook - enqueue_scripts
     *
     * @param none
     * @return void
     */
    public function enqueue_scripts() {
//       wp_enqueue_style($this->getPlugin()->getSlug() . '-admin-page', $this->getPlugin()->getPluginUrl() . '/admin/css/settings.css', array(), $this->getPlugin()->getVersion());
//        wp_enqueue_script('jquery');
//        wp_enqueue_script('jquery-form');
//        wp_enqueue_script('post');
//
//        if (function_exists('add_thickbox')) {
//            add_thickbox();
//        }
        $handle = SIMPLI_HELLO_SLUG . '_core.js';
        $src = $this->getPlugin()->getPluginUrl() . '/lib/'.SIMPLI_HELLO_SHORTNAME.'/js/' . SIMPLI_HELLO_SLUG . '_core.js';
        $deps = 'jquery';
        $ver = '1.0';
        $in_footer = false;
        wp_enqueue_script($handle, $src, $deps, $ver, $in_footer);
    }







}