<?php

/**
 * Shortcodes Module
 *
 * Adds Shortcodes
 *
 * @author Andrew Druffner
 * @package XXXX
 *
 */
class Hello_Module_Shortcodes extends Simpliv1c0_Plugin_Module {

    private $_moduleName;
    private $_moduleSlug;

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


        $this->_moduleName = basename(__FILE__, ".php");

        /*
         * Create an equivilent 'module slug' that is the module name but lower cased and separated by underscores
         * for for MyModule.php , moduleName=MyModule , moduleSlug='my_module'
         * http://stackoverflow.com/q/8611617
         */
        $regex = '/(?<!^)((?<![[:upper:]])[[:upper:]]|[[:upper:]](?![[:upper:]]))/';
        $this->_moduleSlug = strtolower(preg_replace($regex, '_$1', $this->_moduleName));




        add_shortcode(SIMPLI_HELLO_SLUG, array(&$this, 'sayHello'), 10);

        /**
         *
         *
         *  add scripts
         * example: add_action('wp_enqueue_scripts', array(&$this, 'enqueue_scripts'));
         *
         */
        /**
         *
         * Add custom ajax handlers
         *  Map Ajax Handlers to Ajax Actions passed to php by the ajax request
         * example: add_action('wp_ajax_' . $this->getPlugin()->getSlug() . '_my_action', array(&$this, 'my_function'));
         * see http://codex.wordpress.org/Plugin_API/Action_Reference/wp_ajax_%28action%29
         */
        /**
         *
         *
         *  Add any other hooks you need to support this module
         *
         *
         */
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
    }

    /**
     * Gets the module name
     * @return $_moduleName
     *
     *      */
    public function getModuleName() {

        return $this->_moduleName;
    }

    /**
     * Gets the module slug
     * @return $_moduleSlug
     *
     *      */
    public function getModuleSlug() {

        return $this->_moduleSlug;
    }

    /**
     *  Say Hello
     *
     *
     *  */
    public function sayHello() {



        $result='<div>Hello World! , says the ' . SIMPLI_HELLO_NAME . ' plugin</div>';

        return $result;
    }

}