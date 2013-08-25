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
        $this->debug()->t(); //trace provides a information about the method and arguments, and provides a backtrace in an expandable box. A visual trace is also provided if graphiviz is enabled.

        $this->debug()->log('loga gets logged to browser, javascript console, and file');
        $this->debug()->logb('logb gets logged only to the browser');
        $this->debug()->logc('logc gets logged only to the javascript console');
        $this->debug()->logf('logf to the log file');
        $this->debug()->logcError('logcError logs an error in red to the javascript console');

        $my_array = array(
            'element1' => 1,
            'element2' => 2,
            'element3' => 3,
        );


        $this->debug()->logExtract($my_array); //logExtract logs each element of an array as its own variable and value pair. Output is to the browser

        $this->debug()->logVar('$my_array = ', $my_array); //logVar logs variables or arrays to the browser. Variables are nicely formatted in a vertical format
        $this->debug()->logVars(get_defined_vars()); //logVars is designed to format the output of get_defined_vars
    }

    /**
     * Demo Debug
     *
     * Provides a few examples of how to use the functions in the debug module.
     * To see this output, you must configure the debug module to setMethodFilter('_demoDebug') or setMethodFilter('Simpli_Hello_Module_Core');
     *
     * @param none
     * @return void
     */
    private function _demoDebug() {

         /*
          * debug()->t() or $this->debug()->logTrace()
          * Both methods are aliases for the same functionality. Their purpose is to provide information about the current method, and provide links to a backtrace (showing all methods within the current call stack, in the order that they were called) , and a visual backtrace (if enabled, requiring the graphviz Pear library), showing a visual representation of the call stack.
          */
        $this->debug()->t();

         $this->debug()->log('<br><strong>$this->debug()->t() or $this->debug()->logTrace()</strong><br>  <p>Both methods are aliases for the same functionality. Their purpose is to provide information about the current method, and provide links to a backtrace (showing all methods within the current call stack, in the order that they were called) , and a visual backtrace (if enabled, requring the graphviz Pear library), showing a visual representation of the call stack.</p>');

        $this->debug()->log('apple array: <pre>' . print_r(array('a'=>'apple','b'=>'bananna'),true) . '</pre>');
        $my_array = array(
            'apple' => 'red', 'orange' => 'orange'
        );

        $my_name = 'Jones';

        /*
         * Normally, we would just use logVar() for any variable we want to log
         */
        $this->debug()->log('<br><strong>logVar() Example</strong><br> Works with both single value variable and arrays:');
        $this->debug()->logVar('$my_name=', $my_name); //works with single variables

        $this->debug()->logVar('$my_array=', $my_array); //and with arrays



        /*
         * logVars()  - Log all Defined Variables
         *
         * logVars() shows all the variables
         * that have been defined within the method (including arguments), up
         * to the location where the get_defined_vars() statement is located
         * It wraps its  output in a clickable div that you
         *
         */
        $this->debug()->log('<br><strong>logVars() Example</strong><br> Shows all the variables that have been defined within the method (including arguments), upto the
            location where the get_defined_vars() statement is located');
        $this->debug()->logVars(get_defined_vars());


        /*
         * Use logExtract() if you want to see each of the elements of an array as its own separate variable
         * This method is similar to the php function extract, but doesnt actually create variables
         * but displays them as if they were
         * e.g.: $apple='red'
         */
        $this->debug()->log('<br><strong>logExtract() Example</strong><br> Shows each element of an array as its own separate variable');
        $this->debug()->logExtract($my_array);
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
//

        /*
         * a good place to demonstrate debug.
         */
        if ($this->debug()->isOn() && $this->debug()->getOption('demo_enabled')) {
            $this->_demoDebug();
        }


        /*
         * add scripts
         *  */
//something
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
        $this->debug()->t();
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



        $this->debug()->t();
        global $post;




        /*
         * If the global setting is configured for disabled, then dont
         * add the hello text
         */
        $enabled_globally = $this->getPlugin()->getSetting('hello_global_default_enabled');

        if ($enabled_globally !== 'enabled') {

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