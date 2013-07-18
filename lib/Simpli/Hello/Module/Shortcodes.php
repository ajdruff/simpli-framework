<?php

/**
 * Shortcodes Module
 *
 * Adds Shortcodes
 *
 * @author Andrew Druffner
 * @package SimpliFramework
 * @subpackage SimpliHello
 *
 */
class Simpli_Hello_Module_Shortcodes extends Simpli_Basev1c0_Plugin_Module {



    /**
     * Initialize Module
     *
     * @param none
     * @return void
     */
    public function init() {



        /*
         * Module base class requires
         * setting Name first, then slug
         */
        $this->setName();
        $this->setSlug();

        add_shortcode($this->getPlugin()->getSlug(), array(&$this, 'sayHello'), 10);

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

                  $this->getPlugin()->getLogger()->log($this->getPlugin()->getSlug() . ': initialized  module ' . $this->getName());
    }

    /**
     * Adds javascript and stylesheets
     * WordPress Hook - enqueue_scripts
     *
     * @param none
     * @return void
     */
    public function enqueue_scripts() {
//       wp_enqueue_style($this->getPlugin()->getSlug() . '-admin-page', $this->getPlugin()->getUrl() . '/admin/css/settings.css', array(), $this->getPlugin()->getVersion());
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



        $result='<div>Hello World! , says the ' . $this->getPlugin()->getName() . ' plugin</div>';

        return $result;
    }

}