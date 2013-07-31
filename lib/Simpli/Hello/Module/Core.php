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
     * Configure Module
     *
     * @param none
     * @return void
     */
    public function config() {

    }

    /**
     * Add Hooks
     *
     * Adds WordPress Hooks, triggered during module initialization
     * @param none
     * @return void
     */
    public function addHooks() {




        /*
         * add scripts
         *  */

        add_action('wp_enqueue_scripts', array(&$this, 'enqueue_scripts'));

        //__START_EXAMPLE_CODE__
        /*
         * Add filter for content
         */

        add_filter('the_content', array(&$this, 'say_hello'), 10);
        //__END_EXAMPLE_CODE__


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
        // Example
//       wp_enqueue_style($this->getPlugin()->getSlug() . '-admin-page', $this->getPlugin()->getUrl() . '/admin/css/settings.css', array(), $this->getPlugin()->getVersion());
//        wp_enqueue_script('jquery');
//        wp_enqueue_script('jquery-form');
//        wp_enqueue_script('post');
//
//        if (function_exists('add_thickbox')) {
//            add_thickbox();
//        }
        /* Example
          $handle = $this->getPlugin()->getSlug() . '_core.js';
          $src = $this->getPlugin()->getUrl() . '/js/' . $this->getPlugin()->getSlug() . '_core.js';
          $deps = 'jquery';
          $ver = '1.0';
          $in_footer = false;
          wp_enqueue_script($handle, $src, $deps, $ver, $in_footer);
         *
         */
    }

//__START_EXAMPLE_CODE__
    /**
     * Say Hello - Adds Text at the start or end of a post
     * WordPress Hook Filter Function for 'content'
     *
     * @uses is_single()
     */
    public function say_hello($content) {
        global $post;



        /*
         * If the global setting is configured for disabled, then dont
         * add the hello text
         */
        $enabled_globally = $this->getPlugin()->getSetting('hello_global_default_enabled');
        if ($enabled_globally != 'enabled') {
            return($content);
        }




        /*
         *  Get the Post Settings
         *
         *  */

        $enabled = $this->getPlugin()->getModule('Post')->getPostOption('enabled');
        $placement = $this->getPlugin()->getModule('Post')->getPostOption('placement');
        $text = $this->getPlugin()->getModule('Post')->getPostOption('text');
        $use_global_text = $this->getPlugin()->getModule('Post')->getPostOption('use_global_text');
        /*
         * if the post is configured to use the defaults, then use the defaults from the global settings
         */

        if ($placement == 'default') {
            $placement = $this->getPlugin()->getSetting('hello_global_default_placement');
        }
        if ($use_global_text == 'true') {
            $text = $this->getPlugin()->getSetting('hello_global_default_text');
        }


        if (is_single() && ($enabled == 'true'))
        // welcome message
//        $content .= sprintf(
//            '<img class="post-icon" src="%s/images/post_icon.png" alt="Post icon" title=""/>%s',
//            get_bloginfo( 'stylesheet_directory' ),
//            $content
//        );
            if ($placement == 'before') {
                $content = $text . $content;
            } else {

                $content = $content . $text;
            }

        // Returns the content.
        return $content;
    }

//__END_EXAMPLE_CODE__
}