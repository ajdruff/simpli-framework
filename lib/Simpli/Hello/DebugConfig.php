<?php

/**
 * Debug Module
 *
 * This module provides
 *
 * For a more complete description of this module's methods, see the base class's comments.
 * @author Andrew Druffner
 * @package SimpliFramework
 * @subpackage SimpliHello
 *
 */
class Simpli_Hello_DebugConfig extends Simpli_Basev1c0_Debug {

    /**
     * Configure Module
     *
     * Set any configuration option for the debug module here. You can switch on or off the entire module by using $this->debug()->turnOn or debug()->turnOff() , remove the module altogether ( which will not cause any errors since there is a 'phantom' class created that silently handles any debug requests, or comment out all debug() methods.
     * For a full explanation of all debug options, see the _setDefaultOptions() method in the base class, which explains all the options.
     *
     * @param none
     * @return void
     */

    public function config() {


        /*
         * turn debugging on/off
         * $this->debug()->turnOn();
         * $this->debug()->turnOff();
         * Off by default
         */
        $this->debug()->turnOn();

//  $this->debug()->turnOff();

        /*
         * set options . if modules set their own options, they will override these
         */

        $this->setOption('method_filters_enabled', true); //set to false to ignore all filters and print all module debug output
        //dont forget you can also just use a method   $this->debug()->setMethodFilter('config', false);
        //

//        $this->debug()->setMethodFilter('isScreen', true);
$this->debug()->setMethodFilter('redirectAdd', false);
$this->debug()->setMethodFilter('redirectEdit', false);
//
//$this->debug()->setMethodFilter('Simpli_Basev1c0_Plugin_Menu', true);

        //addPostEditor
//        $this->debug()->setMethodFilter('lines2array', false);
//        $this->debug()->setMethodFilter('el_sc', false);
//                $this->debug()->setMethodFilter('_sc', false);
//        $this->debug()->setMethodFilter('el_sc_options', false);
//        $this->debug()->setMethodFilter('renderElement', false);
//        $this->debug()->setMethodFilter('hookShortcodeElementWithContent', true);
//        $this->debug()->setMethodFilter('_getThemeDirectory', false);
//        $this->debug()->setMethodFilter('createForm', false);
//        $this->debug()->setMethodFilter('_filterOptions', false);
//        $this->debug()->setMethodFilter('Simpli_Addons_Simpli_Forms_Addon::loadModule', false);
//        $this->debug()->setMethodFilter('setTheme', false);
//$this->debug()->setMethodFilter('_setCachedTemplate', false);
//
//
//        $this->debug()->setMethodFilter('Simpli_Addons_Simpli_Forms_Addon', false);
//        $this->debug()->setMethodFilter('hookAdminMenu', false);
//
//        $this->debug()->setMethodFilter('Simpli_Basev1c0_Plugin', false);
//        $this->debug()->setMethodFilter('Simpli_Hello_Module_Admin', false);
//        $this->debug()->setMethodFilter('Simpli_Hello_Module_Core', false);
//        $this->debug()->setMethodFilter('Simpli_Hello_Module_Debug', false);
//
//
//        $this->debug()->setMethodFilter('Simpli_Hello_Module_Post', true);
//        $this->debug()->setMethodFilter('Simpli_Hello_Module_Menu10Settings', false);
//        $this->debug()->setMethodFilter('Simpli_Hello_Module_Menu20Settings', false);
//        $this->debug()->setMethodFilter('Simpli_Hello_Module_Menu30Test', false);
//
//
//
//        $this->debug()->setMethodFilter('Simpli_Addons_Simpli_Forms_Themes_Admin_Module_FilterSettings', false);
//        $this->debug()->setMethodFilter('Simpli_Addons_Simpli_Forms_Module_Filter', false);
//        $this->debug()->setMethodFilter('Simpli_Addons_Simpli_Forms_Themes_Admin_Module_FilterAdmin', false);
//        $this->debug()->setMethodFilter('Simpli_Addons_Simpli_Forms_Module_Form', false);
//        $this->debug()->setMethodFilter('Simpli_Addons_Simpli_Forms_Module_Elements', false);
//        $this->debug()->setMethodFilter('Simpli_Addons_Simpli_Forms_Module_Theme', false);


        /*
         * Filter Sets
         */
        /*
         * Templates
         */

    //    $this->debug()->setMethodFilter('renderElement', false);
     //   $this->debug()->setMethodFilter('_setCachedTemplate', false);
    //    $this->debug()->setMethodFilter('_getCachedTemplate', false);



        $this->setOption('logging_enabled', true);
        $this->debug()->setOption('trace_enabled', false);
        $this->debug()->setOption('defined_vars_enabled', true);
        $this->debug()->setOption('backtrace_enabled', true);
        $this->debug()->setOption('visual_backtrace_enabled', false);

        $this->setOption('trace_output_format', 'simple');  //options are 'normal'(default), 'text' and 'simple'


        $this->setOption('log_all_actions', false);

        $this->debug()->setOption('show_arrays', true);
        $this->debug()->setOption('show_objects', false);

$this->setOption('debug_ajax_enabled',true);

 $this->setOption('expand_on_click', true);

        /*
         * Debug Output
         * Where you want to send the log output
         */
        $this->setOption('output_to_inline', true);
        $this->setOption('output_to_footer', false);
        $this->setOption('output_to_file', false);
        $this->setOption('output_to_console', false);
        $this->setOption('log_file_path', $this->getPlugin()->getDirectory() . '/debug.log.txt');

        /*
         * Demo Enabled
         */
        $this->setOption('demo_enabled', false);

        /*
         * Excluded Functions Filter Enabled
         * Default: true
         * this filter removes unwanted functions from trace output
         */

        $this->setOption('function_exclusion_filter_enabled', true);
        $this->setOption('function_exclusion_filter', array_merge(
                        $this->getOption('function_exclusion_filter'), array()
                )
        );





        /* example of how you would add a new function to the existing default filter . if you want to remove filters, either remove them from the default array (contained in the _getDefaultOption method , or redefine an entire new array. you can also just disable the filter by setting function_exclusion_filter_enabled to false

          $this->setOption('function_exclusion_filter', array_merge(
          $this->getOption('function_exclusion_filter'), array('init','config') // this will exclude any functions or method 'init' and 'config' as well as all the default filters
          )
          );
         */


        /*
         * Action Exclusion Filter
         * Exclude these actions when 'log_all_actions' is enabled
         * Works the same way as the Excluded Functions filter above
         */

        $this->setOption('action_exclusion_filter_enabled', true);
        $this->setOption('action_exclusion_filter', array_merge(
                        $this->getOption('action_exclusion_filter'), array()
                )
        );


        /*
         * Action Inclusion Filter
         * Log only those actions listed in the filter when enabled
         */

        $this->setOption('action_inclusion_filter_enabled', true);
        $this->setOption('action_inclusion_filter', array_merge(
                        $this->getOption('action_inclusion_filter'), array('simpli_addons_simpli_forms_init'
                            ,'pre_get_posts',
                            'parse_query'
                            ,'send_headers'
                            ,'wp_headers'
                            ,'parse_request'
                            ,'query_vars'
                            ,'query'
                            ,'current_screen')
                )
        );


        /*
         * Javascript Source Code
         * Set to True to use the WordPress source versions
         * instead of the minified versions of the builtin javascript libraries, allowing
         * you to view the human readable versions of jquery,etc.
         */

        /* this wont work after 3.4 - must be defined before wp-settings loads */
        //    if (!defined('SCRIPT_DEBUG')) {
        //        if ($this->isOn()) {
        //           define('SCRIPT_DEBUG', true); //true to step through readable source code, false for minification,
        //       }
        //  }


        /*
         * Prefixes
         * Prefix Template Available tags:
         * {PLUGIN_SLUG}
         * {TIME}
         * {LINE}
         * {CLASS}
         * {METHOD}
         * {FILE}
         */
        //$this->setOption('browser_prefix_enabled', true);
        //  $this->setOption('browser_prefix_template', '<em>{METHOD}/{LINE}</em>&nbsp;');

//        $this->setOption('browser_prefix_template', true);
//        $this->setOption('console_prefix_enabled', true);
//        $this->setOption('file_prefix_enabled', true);
//        $this->setOption('browser_prefix_template', '<em>{METHOD}/{LINE}</em>&nbsp;');
//        $this->setOption('console_prefix_template', '{TIME} | {LINE} | {PLUGIN_SLUG} | {CLASS}->{METHOD}() |  : ');
//        $this->setOption('file_prefix_template', '{TIME} {METHOD}/{LINE}');
//        $this->setOption('prefix_time_format', 'Y-m-d H:i:s');
    }


}