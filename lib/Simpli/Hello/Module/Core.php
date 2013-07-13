<?php

/**
 * Core Module
 *
 * Plugin's core functionality
 *
 * @author Andrew Druffner
 * @package SimpliFramework
 * @subpackage SimpliHello
 *
 */
class Simpli_Hello_Module_Core extends Simpli_Basev1c0_Plugin_Module {


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
//       wp_enqueue_style($this->getPlugin()->getSlug() . '-admin-page', $this->getPlugin()->getPluginUrl() . '/admin/css/settings.css', array(), $this->getPlugin()->getVersion());
//        wp_enqueue_script('jquery');
//        wp_enqueue_script('jquery-form');
//        wp_enqueue_script('post');
//
//        if (function_exists('add_thickbox')) {
//            add_thickbox();
//        }
        $handle = $this->getPlugin()->getSlug() . '_core.js';
        $src = $this->getPlugin()->getPluginUrl() . '/js/' . $this->getPlugin()->getSlug() . '_core.js';
        $deps = 'jquery';
        $ver = '1.0';
        $in_footer = false;
        wp_enqueue_script($handle, $src, $deps, $ver, $in_footer);
    }







}