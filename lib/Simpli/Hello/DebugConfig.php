<?php

/**
 * Debug Helper Class
 *
 * This helper class provides the functionality of the debug() method within each of the modules and the plugin.
 * The only required method is config() , which turns on debugging, sets options, and sets filters.
 * You may also add your own methods that 'preconfigure' debugging filters such as some of the examples shown.
 * To disable debugging, go to the Plugin class and set the DEBUG property to false. You could also just rename this module
 * from DebugConfig to something else. To temporarily turn off debugging, just use $this->turnOff() ; within the config method.
 *
 *
 * For a more complete description of this module's methods, see the base class's comments.
 * @author Andrew Druffner
 * @package SimpliFramework
 * @subpackage SimpliHello
 *
 */
class Simpli_Hello_DebugConfig extends Simpli_Hello_Basev1c2_Plugin_Debug {

    /**
     * Configure Module
     *
     * Set any configuration option for the debug module here. You can switch on or off the entire module by using $this->turnOn or debug()->turnOff() , remove the module altogether ( which will not cause any errors since there is a 'phantom' class created that silently handles any debug requests, or comment out all debug() methods.
     * For a full explanation of all debug options, see the _setDefaultOptions() method in the base class, which explains all the options.
     *
     * @param none
     * @return void
     */
    public function config() {



        /*
         * turn debugging on/off
         * $this->turnOn();
         * $this->turnOff();
         * Off by default
         */
        $this->debug()->turnOn();



        /*
         * Call any Debug methods here.
         *
         * Examples:
         *
         * $this->debugHello();// a set of filters and options that provides debugging information for the core module
         *         $this->debugActivation(); //a set of filters and options that provides debugging information for plugin activation
         * $this->debugNonces();// a set of filters and options that provides debugging information about nonce creation and verification
         * $this->debugJavascriptLoading(); // a set of filters and options that provides debugging information on javascript loading
         * $this->debugSavePost(); // a set of filters and options that provides debugging information when saving a post
         *
         *
         *
         * Note: if you receive an error similar to: 'Fatal error: Maximum execution time of 30 seconds exceeded in ...'
         * It simply means your debug options are configured too liberally and you need to throttle down the amount of output
         * One option is to use the reduceExectionTime() option which drastically reduces the amount of output. You can then layer additional options
         * after you've added that like this :

          $this->setCommonOptions();
          $this->reduceExecutionTime();
          $this->setOption('method_filters_enabled', false);
         */

        $this->setCommonOptions();



        /*
         *
         * Add additional Filters
         *
         *
          Some Filter Examples

          $this->setMethodFilter('hookSavePost', true);//by method
          $this->setMethodFilter('Simpli_Addons_Simpli_Forms_Module_Form', true);//by class
          $this->setMethodFilter('Simpli_Hello_Module_Core::config', true);//by class and method
          $this->setMethodFilter('hook.*', true); //by regex

         *
         *
         */
    }

    /*
     * Debug Methods
     *
     * Group commonly used filters and options under a
     * single method call
     */

    /**
     * Debug Javascript Loading
     *
     * Shows debug messages for the loading of javascript
     *
     * @param none
     * @return void
     */
    public function debugJavascriptLoading($enabled = true) {
        if (!$enabled) {
            return;
        }
        $this->setCommonOptions();

        $this->setMethodFilter('hookEnqueue.*', true);
        $this->setMethodFilter('hookEdit.*', true);

        $this->setOption('debug_post_only', false); //block all but post , block all but ajax, block all but
    }

    /**
     * Debug Nonces
     *
     * Shows debug messages for nonce creation and verification
     *
     * @param none
     * @return void
     */
    public function debugNonces($enabled = true) {
        if (!$enabled) {
            return;
        }
        $this->setCommonOptions();
        $this->setMethodFilter('.*_createNonces.*', true);
        $this->setMethodFilter('wpVerifyNonce', true);


        $this->setMethodFilter('.*_jax.*', true);

        $this->setMethodFilter('.*once.*', true); //nonces

        $this->setMethodFilter('.*ave.*', true); //savePost,etc
        /*
         * Only output debugging information if a form has posted
         * @todo: add this to defaults as false
         */

        $this->setOption('debug_post_only', false); //block all but post , block all but ajax, block all but
    }

    /**
     * Debug Save Post
     *
     * Shows debug messages when saving post options
     *
     * @param none
     * @return void
     */
    public function debugSavePost($enabled = true) {
        if (!$enabled) {
            return;
        }
        $this->setCommonOptions();

        $this->setMethodFilter('hookAjaxSavePost', true);
        $this->setMethodFilter('hookSavePost', true);
        $this->setMethodFilter('setUserOption', true);
        $this->setMethodFilter('Simpli_Hello_Basev1c2_Plugin_Post', true);

        $this->setMethodFilter('_savePost', true);
        $this->setMethodFilter('saveUserOptions', true);
        $this->setOption('debug_post_only', true); //block all but post so it doesnt provide debug output until you submit
    }

    /**
     * Debug Hello
     *
     * Shows debug messages for posts that show the hello world message.
     *
     * @param none
     * @return void
     */
    public function debugHello($enabled = true) {
        if (!$enabled) {
            return;
        }
        $this->setCommonOptions();


        $this->setMethodFilter('addTextToPost', true);
        $this->setMethodFilter('Core', true);
        $this->setOption('trace_enabled', true);
        $this->setOption('defined_vars_enabled', true);
        $this->setOption('backtrace_enabled', true);
        $this->setOption('expand_on_click', true);
    }

    /**
     * Debug Activation
     *
     * Shows debug messages from activation
     *
     * @param none
     * @return void
     */
    public function debugActivation($enabled = true) {
        if (!$enabled) {
            return;
        }

        $this->setCommonOptions();
        /*
         * options
         */
        $this->setOption('log_all_actions', true);

        $this->setOption('method_filters_enabled', true);
        $this->setOption('trace_output_format', 'text');
        $this->setOption('expand_on_click', false);
        $this->setOption('trace_enabled', true);
        $this->setOption('output_to_inline', true);
        $this->setOption('output_to_file', true);
        $this->setOption('action_inclusion_filter', array_merge(
                        $this->getOption('action_inclusion_filter'), array(
//    'pre_get_posts',
//   'parse_query'
//  ,'send_headers'
//   ,'wp_headers'
//   ,'parse_request'
//  ,'query_vars'
//     'simpli_hello_simpli_hello_menu.*'
//     , 'current_screen'
            $this->plugin()->getSlug() . '_flush_rewrite_rules'
            , $this->plugin()->getSlug() . '_activated'
//   'wp_ajax.*'
                        )
        ));

        /*
         * filters
         */


        $this->setMethodFilter('flushRewriteRules', true);
        $this->setMethodFilter('Simpli_Hello_Plugin::shutdown', true);


        $this->setMethodFilter('Simpli_Hello_Plugin::__destruct', true);
        $this->setMethodFilter('doPersistentAction', true);
        $this->setMethodFilter('addPersistentAction', true);
        $this->setMethodFilter('toggleActivationStatus', true);
        $this->setMethodFilter('Simpli_Hello_Basev1c2_Plugin_PostType::config', true);

        $this->setMethodFilter('_register_post_type', true);

        $this->setMethodFilter('my_plugin.*', true);


        $this->setMethodFilter('hookFlushRewriteRules', true);
        $this->setMethodFilter('addAction.*', true);

        $this->setMethodFilter('.*flush.*', true);




        $this->setMethodFilter('activatePlugin', true);
        $this->setMethodFilter('addActivateAction', false);
    }

    /**
     * Set Common Options
     *
     * Sets most commonly used options (these are overrides of defaults. Edit as desired..)
     *
     * @param none
     * @return void
     */
    public function setCommonOptions() {

        /*
         * Enable Filters
         *
         * set to false to ignore all filters and print all module debug output
         *
         */
        $this->setOption('method_filters_enabled', true);


        $this->setOption('log_all_actions', false);

        $this->setOption('logging_enabled', true);

        $this->setOption('always_show_errors', true); /* always show errors, regardless of filtering */

        $this->setOption('error_template', '<div ><em style="color:red;"> Error ( Plugin {PLUGIN_SLUG} ) </em> {ERROR_MESSAGE}  <p>Calling method : {CALLING_CLASS}::{CALLING_METHOD}() </p>on Line {CALLING_LINE} in file {CALLING_FILE}</div>');


        $this->setOption('trace_enabled', true);

        $this->setOption('defined_vars_enabled', false);

        $this->setOption('backtrace_enabled', false);

        $this->setOption('visual_backtrace_enabled', false);


        $this->setOption('trace_output_format', 'normal');  //options are 'normal'(default), 'text' and 'simple'




        $this->setOption('show_arrays', false);
        $this->setOption('show_objects', false);

        $this->setOption('ajax_debugging_enabled', true);

        $this->setOption('ajax_debugging_only', false); // NOT ADDED TO DEFAULTS !only outputs debugging if the request is ajax. this helps in preventing debug output when you are only interested in the response during an ajax request (and not , for example, a page refresh).

        $this->setOption('expand_on_click', true);

        /*
         * Debug Output
         * Where you want to send the log output
         */
        $this->setOption('output_to_inline', true);

        $this->setOption('output_to_footer', false);
        $this->setOption('output_to_file', false);
        $this->setOption('output_to_console', false);
        $this->setOption('log_file_path', $this->plugin()->getDirectory() . '/debug.log.txt');

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
                        $this->getOption('action_inclusion_filter'), array(
//    'pre_get_posts',
//   'parse_query'
//  ,'send_headers'
//   ,'wp_headers'
//   ,'parse_request'
//  ,'query_vars'
//     'simpli_hello_simpli_hello_menu.*'
//     , 'current_screen'
// $this->plugin()->getSlug() . '_flush_rewrite_rules'
//   'wp_ajax.*'
                        )
                )
        );
    }

    /**
     * Debug Post Metaboxes
     *
     * Shows debug messages for displaying a post's metaboxes.
     *
     * @param none
     * @return void
     */
    public function debugPostMetaboxes($enabled = true) {
        if (!$enabled) {
            return;
        }

        $this->setCommonOptions();

        $this->setOption('method_filters_enabled', true);

        $this->setOption('trace_enabled', true);

        $this->setMethodFilter('renderMetaBox.*', true);
//$this->setMethodFilter('.*_Module_Metabox::addMetaBox', true);
        $this->setMethodFilter('.*addMetaBox', true);
        $this->setMethodFilter('.*hookAddMetaBoxes', true);
        $this->setMethodFilter('pageCheckEditor', true);
        $this->setMethodFilter('hookEditingScreen', true);

        $this->setMethodFilter('Simpli_Hello_Basev1c2_Plugin_Module_Post', true);



        $this->setMethodFilter('.*_Metabox::config', true);
        $this->setMethodFilter('Simpli_Hello_Modules_PostUserOptions::config', true);
        $this->setMethodFilter('getPost', true);
        $this->setMethodFilter('getEditPostID', true);

        $this->setMethodFilter('Simpli_Hello_Basev1c2_Plugin_Module_Post', true);
        $this->setMethodFilter('_hookNewPost', true);



        $this->setOption('log_all_actions', true);
        $this->setOption('action_inclusion_filter_enabled', true);
        $this->setOption('action_inclusion_filter', array_merge(
                        $this->getOption('action_inclusion_filter'), array(
            'wp_insert_post',
            'save_post'
                        )
                )
        );

        $this->setOption('trace_output_format', 'text');  //options are 'normal'(default), 'text' and 'simple'
    }

    /**
     * Reduce Execution Time
     *
     * Implements tweaks to reduce the risk of a max excution time error.
     * If this removes any options you want, simply add them back in after you call this option.
     *
     * @param none
     * @return void
     */
    public function reduceExecutionTime($enabled = true) {
        if (!$enabled) {
            return;
        }

        $this->setOption('defined_vars_enabled', false);
        $this->setOption('method_filters_enabled', true);

        $this->setOption('action_inclusion_filter_enabled', true);
        $this->setOption('show_arrays', false);
        $this->setOption('show_objects', false);


        $this->setOption('trace_enabled', true);


        $this->setOption('backtrace_enabled', false);

        $this->setOption('visual_backtrace_enabled', false);


        $this->setOption('trace_output_format', 'text');  //options are 'normal'(default), 'text' and 'simple'



        $this->setOption('expand_on_click', false);

        /*
         * Debug Output
         * Where you want to send the log output
         */
        $this->setOption('output_to_inline', true);

        $this->setOption('output_to_footer', false);
        $this->setOption('output_to_file', false);
        $this->setOption('output_to_console', false);
    }

    /**
     * Debug the Form Addon Shortcode
     *
     * Debugs the shortcode provoded by the simpli_forms addon.
     *
     * @param none
     * @return void
     */
    public function debugShortcode($enabled = true) {
        if (!$enabled) {
            return;
        }

        $this->setOption('method_filters_enabled', true);

        $this->setMethodFilter('renderMetaBoxTemplate', true);
        $this->setMethodFilter('renderMenuPage', true);





        $this->setMethodFilter('hookShortcodeElementWithoutContent', true);
        $this->setMethodFilter('hookShortcodeElementWithContent', true);
    }

    /**
     * Debug Post Type
     *
     * Debugs the Post Type method
     *
     * @param none
     * @return void
     */
    public function debugPostType($enabled = true) {
        if (!$enabled) {
            return;
        }
        $this->setMethodFilter('getPost', true);
        $this->setMethodFilter('getPostType', true);
        $this->setMethodFilter('_getEditorPostID', true);
        $this->setMethodFilter('getPostTypeRequestVar', true);
        $this->setMethodFilter('hookCreateNewPost', true);
    }

    /**
     * Short Description
     *
     * Filters debug messages for the rendering of the postEditor element (which provides the tinyMCE editor WordPress uses)
     *
     * @param none
     * @return void
     */
    public function debugPostEditor($enabled = true) {
        if (!$enabled) {
            return;
        }
//$this->setMethodFilter('.*_Addons_Simpli_Forms_Modules_Form::addHooks', true); //fix bug
        $this->setMethodFilter('filterPostEditor', true);
    }

    /**
     * Debug Simpli Forms Elements
     *
     * Filters debug messages for the rendering of form elements when using the Simpli Forms addon
     *
     * @param none
     * @return void
     */
    public function debugSimpliFormsElements($enabled = true) {
        if (!$enabled) {
            return;
        }
        $this->setMethodFilter('renderElement', true);
        $this->setMethodFilter('.*filter.*', true);
        $this->setMethodFilter('getTemplate', true);
        $this->setMethodFilter('_setCachedTemplate', true);
    }

    /**
     * Debug Redirects
     *
     * Long Description
     *
     * @param none
     * @return void
     */
    public function debugRedirect($enabled = true) {
        if (!$enabled) {
            return;
        }
        $this->setMethodFilter('hookRedirect.*', true);
    }

}

