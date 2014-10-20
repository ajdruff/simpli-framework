<?php

/**
 * Starter Template - Addon Module
 *
 * Use this as a template to create your own Addon Module
 *
 * @author Andrew Druffner
 * @package SimpliFramework
 * @subpackage Addons
 *
 */
class Nomstock_Com_Addons_Mycompany_Myaddon_Modules_Mymodule extends Nomstock_Com__Base_v1c2_Plugin_Module {

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
         * Add Shortcodes
         *  e.g.: add_shortcode($this->plugin()->getSlug() . '_form', array($this, 'hookMyShortcodeMethod'), 10);
         */
    }

    /**
     * Configure Module
     *
     * @param none
     * @return void
     */
    public function config() {
        $this->debug()->t();
    }

    /**
     * Hello World
     *
     * A demonstration method to say hello
     *
     * @param none
     * @return void
     */
    public function helloWorld() {
        $this->debug()->t();
        echo '<br> Hello, world!';
    }

    /**
     * Who
     *
     * A demonstration method to display the Addon Module name
     *
     * @param none
     * @return void
     */
    public function who() {
        $this->debug()->t();
        echo '<br> Addon Module Name = ' . $this->getName();
        echo '<br> Addon Module Slug = ' . $this->getSlug();
    }

}

