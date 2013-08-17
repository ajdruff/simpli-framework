<?php

/**
 * Debug Module
 *
 * Debug Methods
 *
 * A word about maximum execution time and memory exhausted errors. Every attempt has been made to limit the memory and execution footprint of this module. However,it is not too difficult to reach the limits of a typical WordPress/Php installation. Some hints: filter to only the modules you really need for execution (bypass_filter produces a lot of output and will frequently timeout). Try both inline and footer output to see if either completes without timing out. Do not use inline and footer output at the same time unless you have filter to only a few modules. Use graphviz sparingly - it works most reliabily when you have a few modules turned on and is a frequent cause of timeouts.
 *
 *
 * To get rid of timeout and memory errors, in this order:
 * 1) turn off graphviz
 * 2) log to either inline or footer, but not both
 * 3) if filter bypass is set to true, set it to false
 * 4) set the module filters to true only for those modules that you really need.
 * 5) if all else fails, set all filters to off, and go to the function that you need, and set $always_debug to true for those log statements that you need.
  * 7) setOption('show_objects',false) ( this is the default)
 *  7) setOption('show_arrays',false) This will simply not display arrays when using logVars to display defined variables.
 * 8) do not increase timeout or memory , as this just invites abuse and can be a security hazard.
 *
 *
 *
 * @author Andrew Druffner
 * @package SimpliFramework
 * @subpackage SimpliHello
 *
 */
class Simpli_Hello_Module_Debug extends Simpli_Basev1c0_Plugin_Module {

    /**
     * Configure Module
     *
     * @param none
     * @return void
     */
    public function config() {


        /*
         * turn debugging on/off
         */
        $this->debug()->turnOn();

        //  $this->debug()->turnOff();

        /*
         * set options . if modules set their own options, they will override these
         */

        $this->setOption('FilterBypass', true); //true will bypasses all filters, logging everything

        $this->debug()->setFilter('Simpli_Hello_Module_Admin', false);
        $this->debug()->setFilter('Simpli_Hello_Module_Core', true);
        $this->debug()->setFilter('Simpli_Hello_Module_Debug', false);


        $this->debug()->setFilter('Simpli_Hello_Module_ExampleModule', false);
        $this->debug()->setFilter('Simpli_Hello_Module_Menu10Settings', false);
        $this->debug()->setFilter('Simpli_Hello_Module_Menu20Settings', false);
        $this->debug()->setFilter('Simpli_Hello_Module_Menu30Test', false);


        $this->debug()->setFilter('Simpli_Hello_Module_Post', false);
        $this->debug()->setFilter('Simpli_Hello_Module_Queryvars', false);
        $this->debug()->setFilter('Simpli_Hello_Module_Shortcodes', false);
        $this->debug()->setFilter('Simpli_Hello_Module_Tools', false);
        $this->debug()->setFilter('Simpli_Addons_Forms_Module_Elements', false);
        $this->debug()->setFilter('Simpli_Addons_Simpli_Forms_Module_Filter', false);
        $this->debug()->setFilter('Simpli_Addons_Simpli_Forms_Module_Form', false);
        $this->debug()->setFilter('Simpli_Addons_Simpli_Forms_Module_Elements', false);
        $this->debug()->setFilter('Simpli_Addons_Simpli_Forms_Module_Theme', false);






        /*
         * Graphiviz Visualization of the debug trace
         * Requires both the PEAR Image_GraphViz module
         * and graphviz binary to be installed using either
         * a php extension or the windows installation package
         * ref:
         *
         *
         */

        $this->debug()->setOption('graphviz', false);


        /*
         * Memory and Execution Time Tweaks
         *
         * Show Arrays
         * Show Objects
         *
         * Will allow logTrace and logVars to recursively
         * show members that are arrays and objects, allowing you to click
         * the 'more' link to expand them further. Setting to true
         * is very memory and cpu intensive, and may
         * drive execution time and memory past their limits.
         * Recommend arrays to true and objects to false.
         *
         * Setting to false does not mean you can never view objects and arrays.
         * It only impacts the logVars() and the 'Info' (when displaying attributes)
         *  behavior. You can always show arrays
         * and variables using logVar() directly, which will always show them.
         *
         */
        $this->debug()->setOption('show_arrays', false);
        $this->debug()->setOption('show_objects', false);


        /*
         * Debug Outpt
         * Where you want to send the log output
         */
        $this->setOption('output_to_inline', true);
        $this->setOption('output_to_footer', false);
        $this->setOption('output_to_file', false);
        $this->setOption('output_to_console', false);






        /*
         *
         * Log All Actions - will output every hook action - generates a ton of output. do you really want to do this ?
         * true/false
         */
        $this->setOption('log_all_actions', false);



        /*
         * Output to the Javascript Console
         * true/false
         */
        $this->setOption('console', false);



        /*
         * Excluded Functions Filter Enabled
         * Default: true
         * this filter removes unwanted functions from trace output
         */

        $this->setOption('excluded_functions_filter_enabled', true);
        $this->setOption('excluded_functions_filter', array_merge(
                        $this->getOption('excluded_functions_filter'), array()
                )
        );


        /* example of how you would add a new function to the existing default filter . if you want to remove filters, either remove them from the default array (contained in the _getDefaultOption method , or redefine an entire new array. you can also just disable the filter by setting excluded_functions_filter_enabled to false

          $this->setOption('excluded_functions_filter', array_merge(
          $this->getOption('excluded_functions_filter'), array('init','config') // this will exclude any functions or method 'init' and 'config' as well as all the default filters
          )
          );
         */


        /*
         * Line Numbers
         * You can also change the template with line_numbers_prefix_template like this:
         * $this->setOption('line_numbers_prefix_template','<em>{FUNCTION}/{LINE}</em>&nbsp;')
         */
        $this->setOption('line_numbers_prefix_enabled', true);
        $this->setOption('line_numbers_prefix_template', '<em>{FUNCTION}/{LINE}</em>&nbsp;');
    }

    /**
     * Add Hooks
     *
     * Adds WordPress Hooks, triggered during module initialization
     * @param none
     * @return void
     */
    public function addHooks() {



        if (is_admin()) {
            add_action('admin_enqueue_scripts', array($this, 'hookEnqueueScripts'));
        } else {
            add_action('wp_enqueue_scripts', array($this, 'hookEnqueueScripts'));
        }


        register_shutdown_function(array($this, 'hookPrintLogToFooter'));
        register_shutdown_function(array($this, 'hookPrintLogToConsole'));
    }

    private $_ro_properties = null;

    /**
     * Get Read Only Properties
     *
     * Define any configuration data here that needs to be accessible to all modules and derived classes and objects.
     * usage from Plugin module $this->NAME_OF_PROPERTY without quotes.
     * Returns read-only properties using a magic method __get
     * ref: http://stackoverflow.com/questions/2343790/how-to-implement-a-read-only-member-variable-in-php
     * @param none
     * @return void
     */
    public function __get($name) {


        if (is_null($this->_ro_properties)) {
            $this->_ro_properties = array
                    (
            );
        }

        if (isset($this->_ro_properties[$name])) {
            return $this->_ro_properties[$name];
        } else {
            return null;
        }
    }

    /**
     * Enqueue Scripts
     *
     * Enqueues scripts needed for debugging
     *
     * @param none
     * @return void
     */
    public function hookEnqueueScripts() {


        $handle = $this->getPlugin()->getSlug() . '_' . 'debug-trace.js';
        $path = $this->getPlugin()->getDirectory() . '/admin/js/debug-trace.js';

        $inline_deps = array(); //cannot rely on namespaces since namespaces must be loaded in footer for them to work.
        $external_deps = array('jquery');
        $footer = false; //must load in head in case there is a fatal error that prevents foot scripts from loading
        $this->getPlugin()->enqueueInlineScript($handle, $path, $inline_deps, $external_deps, $footer);
        $this->getPlugin()->getLogger()->log('Added debug javascript ' . $path);
    }

    /**
     * Stop
     *
     * Exits PHP directly or upon optional condition
     *
     * @param none
     * @return void
     */
    public function stop($always_debug = false, $condition = true, $condition_message = '') {

        $properties = $this->_getDebugStatementProperties(debug_backtrace());
        $line = $properties['line'];
        $class = $properties['class'];
        $method = $properties['method'];
        $file = $properties['file'];

        if (!$this->_inFilters($class, $method, $always_debug)) {
            return;
        }
        $stop_message = ' <div style="color:red">Debug Stop - to continue script, remove the stop() function or edit its condition on line ' . $line . ' in file ' . basename($file) . ' <br/><span style="color:black;">(' . $file . ' )' . ' </span></div>';

        if ($condition_message !== '') {
            $stop_message = ' <div style="color:red">Debug Stop - ' . $condition_message . '<br/> To continue script, remove the stop() function or edit its condition on line ' . $line . ' in file ' . basename($file) . ' <br/><span style="color:black;">(' . $file . ' )' . ' </span></div>';
        }

        if ($condition) {
            die($stop_message);
        }

        //eval(($condition) ? "die ('$stop_message');" : 'echo (\'\');');
    }

    /**
     * Logs a message
     *
     * Prints a Formatted Array of variables
     *
     * @param none
     * @return void
     */
    public function log($browser_html, $console_text = null, $always_debug = false) {

        /*
         * if you want to log only to console, leave $html blank and add $text.
         * if you want to log only to browser,
         */
        if (is_null($console_text)) {
            $console_text = $browser_html;
        }


        $properties = $this->_getDebugStatementProperties(debug_backtrace());
        $line = $properties['line'];
        $class = $properties['class'];
        $method = $properties['method'];
        $file = $properties['file'];



        /*
         * check filters and debug state
         */
        if (!$this->_inFilters($class, $method, $always_debug)) {
            return;
        }






        $this->_log($line, $class, $method, $file, $browser_html, $console_text, true);
    }

    /**
     * Log Extract
     *
     * Takes an array, and outputs each of its indexes as if it were a separate variable,  like extract(), but wont impact the symbol table and is for display purposes only.
     *
     * @param none
     * @return void
     */
    public function logExtract($array_vars, $always_debug = false) {

        /*
         * gets the properties of the debug statement for filtering and output
         */
        $arr_btrace = $this->_debug_backtrace(false);

        $properties = $this->_getDebugStatementProperties(debug_backtrace());
        $line = $properties['line'];
        $class = $properties['class'];
        $method = $properties['method'];
        $file = $properties['file'];

        /*
         * check filters and debug state
         */
        if (!$this->_inFilters($class, $method, $always_debug)) {
            return;
        }

        /*
         * Check each element of the array, and output in the format $<index_name> = $value , using
         * the normal $this->_logVar() method.
         */
        foreach ($array_vars as $var_name => $var_value) {

            $html = $this->_logVar('$' . $var_name . ' = ', $var_value, $always_debug);
            $text = '';
            $this->_log($line, $class, $method, $file, $html, $text, true);
        }
    }

    /**
     * Logs a Variable
     *
     * Prints a Formatted Variable
     *
     * @param mixed
     * @return void
     */
    public function logVar($message, $var, $always_debug = false) {


        $same_line = true;
        $arr_btrace = $this->_debug_backtrace(false);

        $properties = $this->_getDebugStatementProperties(debug_backtrace());


        $line = $properties['line'];
        $class = $properties['class'];
        $method = $properties['method'];
        $file = $properties['file'];


        /*
         * check filters and debug state
         */
        if (!$this->_inFilters($class, $method, $always_debug)) {
            return;
        }


        /*
         * bump output to next line from the label if an array or object so the $message is easier to read
         */
        if (is_array($var) || is_object($var)) {
            $message = '<br/> ' . $message;
        }
        $html = $this->_logVar($message, $var, $always_debug, true, true);
        $text = '';


        $this->_log($line, $class, $method, $file, $html, $text, true);
    }

    /**
     * Variable
     *
     * Prints a Formatted Variable
     *
     * @param mixed
     * @return void
     */
    private function _logVar($message, $var, $always_debug, $show_arrays = null, $show_objects = null) {

        /*
         * If allow_arrays is not set explicitly, use the configured value
         * this pattern allows the logVar public function to override configuration so
         * a user may still show objects and arrays by using logVar, but other log functions
         * will abide by the configured settings
         */
        if (is_null($show_arrays)) {
            $show_arrays = $this->getOption('show_arrays');
        }
        if (is_null($show_objects)) {
            $show_arrays = $this->getOption('show_objects');
        }


        #init



        /*
         * If an array, format as an array
         */
        if (is_array($var) || is_object($var)) {

            /*
             * if the variable is an array or object, build another array with
             * results. do not attempt to update $var, since there are times when $var is an object passed by reference , which
             * will break this function with very difficult to debug 'call_user_function' errors
             */
            $arr_result = array();
            /*
             * Surround the element with a collapsible div, controlled by the debug-trace.js javascript
             * The javascript requires a specific format (see javascript comments), so be careful when editing.
             */
            $template = '
        <div style="display:inline-block;">
            {TYPE}&nbsp;<a class="simpli_debug_citem" href="#"><span>More</span><span style="visibility:hidden;display:none">Less</span></a>
            <div style="visibility:hidden;display:none;background-color:#E7DFB5;">
                [{KEY_NAME}]=> {VALUE}
            </div>
        </div>

';
            $template = $this->getPlugin()->getTools()->getHtmlWithoutWhitespace($template);


            foreach ($var as $key => $value) {
                if (is_array($value) || is_object($value)) {

                    $same_line = false;
                    $type = ucwords(gettype($value)); //e.g.: 'Array'
                    if (is_object($value)) {
                        $type = get_class($value) . ' ' . $type;
                    }
                    //  $value_string = '<pre>' . trim(htmlspecialchars(print_r($value, true))) . '</pre>';



                    if ((!$show_objects) && (is_object($value))) {
                        $value_string = 'Objects are not expandable. To debug an object, use $this->debug()->logVar()';
                    } elseif ((!$show_arrays) && (is_array($value))) {
                        $value_string = 'Arrays are not expandable. To debug an array, use $this->debug()->logVar()';
                    } else {
                        try {

                            //$value_string = trim(htmlspecialchars(print_r($value, true)));
                            $value_string = trim(htmlspecialchars(print_r($value, true)));
                        } catch (Exception $exc) {

                            $this->log($exc->getMessage());
                            $value_string = 'Error while attempting to display value : ' . $this->log($exc->getMessage());
                        }


                        // $value_string = '<pre>' . trim(htmlspecialchars(print_r($value, true))) . '</pre>';
                    }
                    $tags = array(
                        '{TYPE}' => $type,
                        '{KEY_NAME}' => $key,
                        '{VALUE}' => $value_string,
                    );

                    $arr_result[$key] = str_replace(array_keys($tags), array_values($tags), $template);
                } else {
                    $arr_result[$key] = htmlspecialchars($value);
                }
            }



//$this->getPlugin()->getSlug() . '_' . $this->getSlug()


            $content = $message . '<pre>' . print_r($arr_result, true) . '</pre>';
        } else {

            $same_line = true;
            $content = "$message " . $var;
        }

        return $content;
    }

    /**
     * Debug Backtrace
     *
     * Removes the current function from the backtrace. Useful for debug functions that
     * analyze the traces of the calling function
     *
     * @param none
     * @return void
     */
    private function _debug_backtrace($wrapper = false) {

        $arr_backtrace = debug_backtrace();
        array_shift($arr_backtrace); //removes the current method

        if ($wrapper) {
            array_shift($arr_backtrace); //removes the wrapper
        }

        array_shift($arr_backtrace); //removes the calling method
        return $arr_backtrace;
    }

    /**
     * Super Simple Trace
     *
     * Provides a straight dump of debug_backtrace but retaining only line,class,function,file and args
     * Does not rely on any functions within the Debug module so can be used anywhere without runaway recursion
     * Does not respect filtering and is not reversed as in st() and t()
     *
     * @param none
     * @return void
     */
    public function sst() {

        /*
         * dont bother if debug is off
         */
        if ($this->isOff()) {
            return;
        }


# init


        $defaults = array(
            'file' => '',
            'line' => '',
            'class' => '',
            'method' => '',
            'args' => array(),
        );
        /*
         * get the backtrace
         */

        $arr_btrace = $this->_debug_backtrace(false); //get the backtrace

        /*
         * get where the debug statement was located
         */



        $ds_line = (isset($arr_btrace[0]['line']) ? $arr_btrace[0]['line'] : '');
        $ds_file = (isset($arr_btrace[0]['file']) ? $arr_btrace[0]['file'] : '');
        $ds_class = (isset($arr_btrace[1]['class']) ? $arr_btrace[1]['class'] : '');
        $ds_method = (isset($arr_btrace[1]['function']) ? $arr_btrace[1]['function'] : '');


        /*
         * iterate through the loop so we can simplify each trace
         */

        foreach ($arr_btrace as $key => $trace_properties) {

            $trace_properties = array_intersect_key(array_merge($defaults, $trace_properties), $defaults); //make sure the indexes we need are there or use their defaults
            $traces[] = $trace_properties;
        }
        $html = 'Simplified debug_backtrace() <pre>' . print_r($traces, true) . '</pre>';
        $text = $html;

        $this->_log($ds_line, $ds_class, $ds_method, $ds_file, $html, $text, true);
    }

    /**
     * Simple Trace
     *
     * Provides a very simple trace without all the theatrics. Useful within the debug module itself, since using t() would cause unending loop and memory errors
     *
     * @param array $arr_btrace The backtrace array produced by debug_backtrace()
     * @return void
     */
    public function st($always_debug = false, $levels = 1) {

# init
        $previous_string = '';
        $lt = '&lt;';
        $gt = '&gt;';
        $defaults = array(
            'file' => "$lt file? $gt",
            'line' => "$lt #? $gt",
            'class' => '::',
            'function' => "$lt fn? $gt",
            'args' => array(),
        );




        /*
         * get the backtrace
         */

        $arr_btrace = $this->_debug_backtrace(); //get the backtrace

        /*
         * get where the debug statement was located
         */



        $ds_class = (isset($arr_btrace[0]['class']) ? $arr_btrace[0]['class'] : '');
        $ds_method = (isset($arr_btrace[0]['function']) ? $arr_btrace[0]['function'] : '');

        /*
         * check if in filters
         */
        if (!$this->_inFilters($ds_class, $ds_method, $always_debug)) {

            return;
        }

        /*
         * iterate through the backtrace in reverse
         */

        $arr_btrace = array_reverse($arr_btrace); //reverse it
        $counter = -1;
        foreach ($arr_btrace as $key => $trace_properties) {
            $counter++;
            /*
             * get only the properties shown in
             * the defaults and overwrite the defaults if the property exists
             */


            $trace_properties = array_intersect_key(array_merge($defaults, $trace_properties), $defaults); //make sure the indexes we need are there or use their defaults

            extract($trace_properties); //extract for the template

            /*
             * create a trace template laying out the properties to be human readable
             */
            $trace_template = '{file}/{line}/{class}->{function}(){args}';
            $search = array('{file}', '{line}', '{class}', '{function}', '{args}');
            $args_formatted_string = '<pre style="padding-left:20px">' . print_r($args, true) . '</pre>';
            $replacements = array(basename($file), $line, $class, $function, $args_formatted_string);
            $trace_string = str_ireplace($search, $replacements, $trace_template);
            if ($counter > 0) {
                $previous_string = $trace_string;
            }
            $margin = $counter * 10;
            // $margin = 0;
            $trace_string = '<span style="border:solid 1px grey;display:inline-block;margin-left:' . $margin . 'px">' . $trace_string . '</span>';


            $traces[] = $trace_string;
        }
        $html = '<div>' . implode('<br/><br/>', $traces) . '</div>';
        $text = implode('\n', $traces) . '\n';
        $this->_log($line, $class, $function, $file, $html, $text, true);


        //   return $output;
    }

    /**
     * Get Debug Statement Properties
     *
     * Returns an array of properties describing where the debug statement withint the calling function of the debug class's public method is located
     *
     * @param array $array_backtrace The output of debug_backtrace() from within the calling function
     * @return array
     */
    private function _getDebugStatementProperties($array_backtrace) {

        $props['line'] = (isset($array_backtrace[0]['line']) ? $array_backtrace[0]['line'] : '');
        $props['file'] = (isset($array_backtrace[0]['file']) ? $array_backtrace[0]['file'] : '');
        $props['class'] = (isset($array_backtrace[1]['class']) ? $array_backtrace[1]['class'] : '');
        $props['method'] = (isset($array_backtrace[1]['function']) ? $array_backtrace[1]['function'] : '');
        return $props;
    }

    /**
     * Log Variables
     *
     * Logs the variables returned by get_defined_vars
     *
     * @param none
     * @return void
     */
    public function logVars($arr_defined_vars = array(), $always_debug = false) {

        /*
         * Check each element of the array, and output in the format $<index_name> = $value , using
         * the normal $this->_logVar() method.
         */
        $string_defined_vars = ''; //holds the defined vars html
        foreach ($arr_defined_vars as $var_name => $var_value) {

            $content = $this->_logVar('$' . $var_name . ' = ', $var_value, $always_debug);



            $string_defined_vars.= '<br/>' . $content;
        }
        $arr_btrace = $this->_debug_backtrace(false);


        $props = $this->_getDebugStatementProperties(debug_backtrace());



        if (!$this->_inFilters($props['class'], $props['method'], $always_debug)) {
            return;
        }

        // $bg_color1 = '#E3DDB2'; //first background color to display
        //   $bg_color2 = '#ECC084'; //second background color to display

        $not_available_text = 'Not Available';
        $tags = array(
            //   '{CLASS}' => ($props['class'] !== '') ? $props['class'] : $not_available_text,
            '{BACKGROUND_COLOR}' => '#E3DDB2',
            //   '{METHOD}' => ($props['function'] !== '') ? $props['function'] : $not_available_text,
            '{DEFINED_VARS}' => ($string_defined_vars !== '') ? $string_defined_vars : $not_available_text,
        );





        $template = '<div style="padding:0px;margin:0px;">

<div style="background-color:{BACKGROUND_COLOR};border:1px solid grey;padding:5px;text-align:left;display: inline-block;margin-left:5px;margin-top:5px;">
<a class="simpli_debug_citem" href="#"><span><em>Variables</em></span><span style="visibility:hidden;display:none">Hide</span></a>



                <div  class="simpli_debug_toggle" style="display:none;visibility:hidden;padding:0px;margin:0px;"><!-- Collapsable --><p > {DEFINED_VARS}</p>


</div>
</div>
';
        $template = $this->getPlugin()->getTools()->getHtmlWithoutWhitespace($template);


        $html = str_replace(array_keys($tags), array_values($tags), $template);
        $text = 'Defined Vars = ' . ($string_defined_vars !== '') ? $string_defined_vars : $not_available_text;


        $this->_log($props['line'], $props['class'], $props['method'], $props['file'], $html, $text, false);
    }

    /**
     * t
     *
     * Same as logTrace - shorter form for convienance and backward compability
     *
     * @param none
     * @return void
     */
    public function t($always_debug = false, $levels = 0) {
        $arr_btrace = $this->_debug_backtrace();
        $ds_properties = $this->_getDebugStatementProperties(debug_backtrace());

        if (!$this->_inFilters($ds_properties['class'], $ds_properties['method'], $always_debug)) {
            return;
        }
        $this->_logTrace($ds_properties, $arr_btrace);
    }

    /**
     * Trace Wrapper
     *
     * Wrapper around _logTrace to provide a more human readable method
     *
     * @param none
     * @return void
     */
    public function trace($always_debug = false, $levels = 0) {
        $arr_btrace = $this->_debug_backtrace();
        $ds_properties = $this->_getDebugStatementProperties(debug_backtrace());

        if (!$this->_inFilters($ds_properties['class'], $ds_properties['method'], $always_debug)) {
            return;
        }
        $this->_logTrace($ds_properties, $arr_btrace);
    }

    /**
     * Log Trace
     *
     * Logs a backtrace  and if available, a visual backtrace for a method. Is really a wrapper around _trace which is the workhorse, while this function provides mainly formatting of _trace output.
     *
     * @param array $ds_properties The debug statement properties as returned by $this->_getDebugStatementProperties(debug_backtrace().
     * @return void
     */
    private function _logTrace($ds_properties, $arr_btrace) {


        /*
         * Get one level as a header
         *
         */

        $arr_trace = $this->_trace($ds_properties, $arr_btrace, 1);
        $method_header_html = $arr_trace['backtrace'];

        /*
         * Get the full backtrace, hiding it under a link to expand/collapse it
         */

        $arr_trace = $this->_trace($ds_properties, $arr_btrace, $levels);

        $tags = array(
            // '{CLASS}' => ($props['class'] !== '') ? $props['class'] : $not_available_text,
            '{BACKGROUND_COLOR}' => '#E3DDB2',
            // '{METHOD}' => ($props['method'] !== '') ? $props['method'] : $not_available_text,
            '{TRACE}' => $arr_trace['backtrace'],
        );




        $template = '<div style="padding:0px;margin:0px;"><div style="background-color:{BACKGROUND_COLOR};border:1px solid grey;padding:5px;text-align:left;display: inline-block;margin-left:5px;margin-top:5px;">
 <strong style = "font-size:medium"></strong> <em "font-size:small"></em>
<a class="simpli_debug_citem" href="#"><span><em>Backtrace</em></span><span style="visibility:hidden;display:none">Hide</span></a>



                <div  class="simpli_debug_toggle" style="display:none;visibility:hidden;padding:0px;margin:0px;"><!-- Collapsable -->




                    <p > {TRACE}</p>


</div>
</div>
';

        /*
         * get the final output of the non-visual traces
         */
        $template = $this->getPlugin()->getTools()->getHtmlWithoutWhitespace($template);
        $non_visual_trace_html = $method_header_html . str_replace(array_keys($tags), array_values($tags), $template);

        /*
         * Now get the visual backtrace html
         */

        if ($this->getOption('graphviz')) {



            $tags = array(
                // '{CLASS}' => ($props['class'] !== '') ? $props['class'] : $not_available_text,
                '{BACKGROUND_COLOR}' => '#E3DDB2',
                // '{METHOD}' => ($props['method'] !== '') ? $props['method'] : $not_available_text,
                '{VISUAL_BACKTRACE}' => $arr_trace['visual_backtrace'],
            );




            $template = '<div style="padding:0px;margin:0px;"><div style="background-color:{BACKGROUND_COLOR};border:1px solid grey;padding:5px;text-align:left;display: inline-block;margin-left:5px;margin-top:5px;">
 <strong style = "font-size:medium"></strong> <em "font-size:small"></em>
<a class="simpli_debug_citem" href="#"><span><em>Visual Backtrace</em></span><span style="visibility:hidden;display:none">Hide</span></a>



                <div  class="simpli_debug_toggle" style="display:none;visibility:hidden;padding:0px;margin:0px;"><!-- Collapsable -->




                    <p > {VISUAL_BACKTRACE}</p>


</div>
</div>
';
            $template = $this->getPlugin()->getTools()->getHtmlWithoutWhitespace($template);

            $visual_trace_html = str_replace(array_keys($tags), array_values($tags), $template);
            $html = $non_visual_trace_html . $visual_trace_html;
        } else {
            $html = $non_visual_trace_html;
        }

        $text = 'not-defined';

        $this->_log($ds_properties['line'], $ds_properties['class'], $ds_properties['method'], $ds_properties['file'], $html, $text, false);
    }

    /**
     * Trace
     *
     * Trace internal function , intended to be called from a wrapper. Outputs the callstack of a method
     *
     * @param array $ds_properties The returned properties from _getDebugStatementProperties
     * @param boolean $always_debug Whether to always display regardless of filter settings
     * @param int $levels The number of levels of the call stack to show. 0 to show all
     * @return array , 'backtrace' is html output , 'visual_backtrace' is the output from graphviz if enabled.
     */
    private function _trace($ds_properties, $arr_btrace, $levels = 1) {



        //     $ds_properties = $this->_getDebugStatementProperties(debug_backtrace());




        /*
         *  initialize variables
         */







        $not_available_text = 'Not Available';

        $traces = array(); // the visualize traces array
        $traces_html = array(); //initialize traces html array
        $counter = -1; //loop counter
        $shift_counter = 0; //keep track of number of branches so we can add appropriate formatting

        $margin = 5; //the number of pixels the margin should shift during a trace when the trace branches to a new class
        $bg_color1 = '#E3DDB2'; //first background color to display
        $bg_color2 = '#ECC084'; //second background color to display
        $background_color = $bg_color1; // the initial color of the trace background. colors will shift between 1 and 2 when classes shift
        $trace_location = ''; // either the line and path where the debug statement is located, or the file path to the file that the class where the method being traced resides in




        /*
         * Slice the array for the number of levels we want
         * Reverse it so we get oldest first

         */

        /*
         * slice array only if level provided is sane
         * this means that 0 will always give you a full trace
         */
        $sliced_arr_btrace = ($levels > 0 && $levels < count($arr_btrace)) ? array_slice($arr_btrace, 0, $levels) : $arr_btrace;

        /*
         * Reverse
         */
        $backtrace_array = array_reverse($sliced_arr_btrace);




        /*
         * Iterate through backtrace and extract what we need, filling in the template as we go
         */
        $loop_length = count($backtrace_array);
        $counter = -1;

        foreach ($backtrace_array as $key => $backtrace) {
            $counter++;
            $props = $this->_getMethodProperties($backtrace_array, array(), $start = $key, $calling = -1);




            /*
             * Define $current variables
             */


            $current_line = ($props['line']);
            $current_method = $props['method'];
            $current_class = $props['class'];
            $current_file = $props['file'];
            $current_args = $props['args'];
            $current_comment = $props['comment'];
            $current_method_sig = $props['signature_simple'];
            $current_method_sig_simple = $props['signature_simple'];
            $current_calling_method = $props['calling_method'];
            $current_calling_class = $props['calling_class'];
            $current_calling_file = $props['calling_file'];
            $current_calling_line = $props['calling_line'];

            /*
             * shift to the right for each level of the trace
             */
            static $toggle = true; //tracks current state of toggle.
            $toggle = !$toggle && true; //flips toggle state each time this line is executed


            $shift_counter++;  //tracks how many times we shift so we can calculate the margin

            $margin = $shift_counter * 5;


            /*
             * create the expanded args string from the $args array returned from the _getFunctionSignature function
             * Expanded args just means if the args are an array , they will formatted vertically for easier reading
             */

            $current_expanded_args = '';
            foreach ($current_args as $var_name => $var_value) {

                $content = $this->_logVar('$' . $var_name . ' = ', $var_value, true);

                $current_expanded_args.= '<br/>' . $content;
            }




            $debug_trace_html_template = '<div style="padding:0px;margin:0px;"><div style="background-color:{BACKGROUND_COLOR};border:1px solid grey;padding:5px;text-align:left;display: inline-block;margin-left:{MARGIN}px;margin-top:5px;">
                <strong>{CLASS}::{METHOD_SIG}</strong>


                <a id="expand_info_{CLASS}{FUNCTION}_{COUNTER}" class="simpli_debug_citem simpli_debug_get_debug_info" href="#"><span>Info</span><span style="visibility:hidden;display:none">Collapse</span></a>



                <div  class="simpli_debug_toggle" style="display:none;visibility:hidden;padding:0px;margin:0px;"><!-- Collapsable -->

                    <div style = "margin:10px 0px 10px 0px;"> <strong style = "font-size:xx-large">Class </strong></div>
                    <div style = "margin:10px 0px 10px 0px;"> <strong style = "font-size:large">{CLASS}</strong></div>
                    <div style = "margin:10px 0px 10px 0px;"> <strong style = "font-size:xx-large">Method</strong></div>
                    <div style = "margin:10px 0px 10px 0px;"> <strong style = "font-size:large">{METHOD_SIG_SIMPLE}</strong></div>

                    <div style = "margin:10px 0px 10px 0px;"> <strong style = "font-size:xx-large">Description</strong></div><p>{COMMENT}</p><div style = "margin:10px 0px 10px 0px;"> <strong style = "font-size:xx-large">Location</strong></div>
                    <p>{LOCATION}</p>

                    <div style = "margin:10px 0px 10px 0px;"> <strong style = "font-size:xx-large">Called From</strong></div>
                    <p > {CALLING_LOCATION}</p>
                    <div style = "margin:10px 0px 10px 0px;"> <strong style = "font-size:xx-large">Arguments</strong></div>
                    {EXPANDED_ARGS}

                </div>
            </div>';


            $debug_trace_html_template = $this->getPlugin()->getTools()->getHtmlWithoutWhitespace($debug_trace_html_template); //this is necessary since there are pre tags in the source . You could just remove it manually using a macro in a text editor , like the 'remove unnecessary whitespace' utility in notepad++ , but using getHtmlWithoutWhitespace allows us to retain the whitespace in our source file so its human readable, while still removing it when its displayed.
            /*
             * Now populate the html template
             */

            $tags = array(
                '{LOCATION}' => ($current_line !== '' && $current_file !== '') ? ' Line ' . $current_line . ' in ' . $current_file : 'Not Available',
                '{CALLING_LOCATION}' => ($current_calling_line !== '' && $current_calling_file !== '') ? ' Line ' . $current_calling_line . ' in ' . $current_calling_file : 'Not Available',
                '{LINE}' => ($current_line !== '') ? $current_line : $not_available_text,
                '{FILE}' => ($current_file !== '') ? $current_file : $not_available_text,
                '{KEY}' => $key, //must come after DEFINED_VARIABLES
                '{CLASS}' => ($current_class !== '') ? $current_class : $not_available_text,
                '{METHOD_SIG}' => ($current_method_sig !== '') ? $current_method_sig : $not_available_text,
                '{METHOD_SIG_SIMPLE}' => ($current_method_sig_simple !== '') ? $current_method_sig_simple : $not_available_text,
                '{METHOD}' => ($current_method !== '') ? $current_method : $not_available_text,
                '{MARGIN}' => $margin,
                '{BACKGROUND_COLOR}' => $background_color,
                '{EXPANDED_ARGS}' => ($current_expanded_args !== '') ? $current_expanded_args : $not_available_text,
                '{COMMENT}' => ($current_comment !== '') ? $current_comment : $not_available_text,
                '{CALLING_LINE}' => $current_calling_line,
                '{CALLING_FILE}' => $current_calling_file,
                '{CALLING_CLASS}' => $current_calling_class,
                '{CALLING_METHOD}' => $current_calling_method,
                '{COUNTER}' => $counter
            );


            $current_trace_html = str_replace(array_keys($tags), array_values($tags), $debug_trace_html_template);


            /*
             * Exclude uninteresting internal  functions from trace so as to
             * make the trace cleaner
             */
            if ($this->_inExcludedFilter($current_method)) {

                continue; /* dont show the functions in $excluded_functions_filter array since they are internal and not interesting */
            }

            /*
             * Build Visual Backtrace Array
             * This builds a traces array that is used by the getVisualBacktrace method
             * Classes are saved as keys to ensure uniqueness
             * and to preserve order
             * Methods are saved as class and function elements
             */

            $traces['classes'][] = $current_class;

            $traces['methods'][] = array(
                'class' => $current_class,
                'function' => $current_method
            );


            /*
             * Wrap up the loop
             * Assemble the final trace output
             * Update the previous variables
             */
            $traces_html[] = $current_trace_html;
        } //Backtrace loop complete





        /*
         * Get the visual backtrace. If visual backtrace is not enabled,
         * it will return an empty string
         */
        $visual_backtrace = $this->getVisualBacktrace($traces);



        /*
         * Assemble the final content for the output

         */

        $content = '<div><pre>' . implode('', $traces_html) . '</pre></div>';
        $result['backtrace'] = $content;
        $result['visual_backtrace'] = $visual_backtrace;

        return $result;
    }

    /**
     * Get Method Properties
     *
     * Returns a list of properties describing the method or standalone function.
     * @todo: this should remove the need for getFunctionSignature, getReflection, and _getDebugStatementProperties_old
     * @param $array_
     * @return void
     */
    private function _getMethodProperties($array_backtrace, $defined_vars, $start = 0, $caller = 1) {


        #init
        $props = array();
        $props_backtrace = array();
        $arg_string = '';

//        if ($wrapper) { //if this method is being called through a wrapper (rather than directly as a public method, then have to remove one additional level from the backtrace
//            array_shift($array_backtrace);
//        }
        $props_backtrace['class'] = (isset($array_backtrace[$start]['class']) ? $array_backtrace[$start]['class'] : null);
        $props_backtrace['method'] = (isset($array_backtrace[$start]['function']) ? $array_backtrace[$start]['function'] : null);

        $props_backtrace['arg_values'] = (isset($array_backtrace[$start]['args']) ? $array_backtrace[$start]['args'] : null);
        $props_backtrace['calling_file'] = (isset($array_backtrace[$start]['file']) ? $array_backtrace[$start]['file'] : null);
        $props_backtrace['calling_line'] = (isset($array_backtrace[$start]['line']) ? $array_backtrace[$start]['line'] : null);



        $props_backtrace['calling_method'] = (isset($array_backtrace[$start + $caller]['function']) ? $array_backtrace[$start + $caller]['function'] : null);
        $props_backtrace['calling_class'] = (isset($array_backtrace[$start + $caller]['class']) ? $array_backtrace[$start + $caller]['class'] : null);




        $props_refl = $this->_getReflectedMethodProperties($props_backtrace['class'], $props_backtrace['method']);




        /* Arguments
         * Assign $props['args]
         */


        if ((!is_null($props_backtrace['arg_values']))) {

            /* If there are arguments, create an argument array of name value pairs
             * if arg names are available, use them by combining them with arguments.
             */
            if (is_array($props_refl['arg_names']) && is_array($props_backtrace['arg_values']) && (count($props_refl['arg_names']) > 0) && count($props_refl['arg_names']) === count($props_backtrace['arg_values'])) {
                $props['args'] = array_combine($props_refl['arg_names'], $props_backtrace['arg_values']);
            } else {
                $props['args'] = $props_backtrace['arg_values'];
            }

            /*
             * add a dollar sign in front of each of the argument names and surround in parens
             */
            $arg_string = '$' . urldecode(http_build_query($props['args'], null, ',$'));
            $arg_string = ($arg_string === '$') ? '' : $arg_string; //remove the $ if thats the only thing left in the string
        }


        /* Method Signature
         * Assign $props['signature'] and  $props['signature_simple']
         */
        $props['signature'] = htmlspecialchars($props_refl['method'] . '(' . $arg_string . ')'); //function name with arg names and values. convert html in arg values so you can view code.
        $props['signature_simple'] = (is_array($props_refl['arg_names']) && !empty($props_refl['arg_names'])) ? $props_refl['method'] . '($' . implode(',$', $props_refl['arg_names']) . ')' : $props_refl['method'] . '()'; //function name with argument names

        /*
         * Set defaults for returned array
         */
        $defaults = array(
            'native' => null,
            'defined_vars' => null, //the variables defined in the function up to the position where the debug statement was placed
            'file' => null, //the file that contains the method
            'line' => null, //the line at which the method declaration appears
            'class' => null, //the class that contains the method
            'method' => null, //the method name
            'comment' => null, //the method comment from source
            'args' => null, //an array of the arguments. must contain values passed to the function. may or may not contain the argument names.
            'calling_method' => null, //the method that preceeds this method in the call stack
            'calling_class' => null, // the class of the calling method
            'calling_file' => null, // the file of the calling method
            'calling_line' => null, // the line at which the calling method called this function
            'signature' => null, // A string that includes the Method name, followed by parens which include the name *and* value pairs of each of the arguments, separated by a comma
            'signature_simple' => null
        );
        /*
         * Assign Final Values
         */

        /*
         * Build Final Array using the
         * $props_backtrace array which contains runtime values
         * and the $props_refl array which contains compile time values
         * if the right side contains value from $props, it means it was assigned earlier
         */
        $props['native'] = $props_refl['native'];
        $props['defined_vars'] = $defined_vars; //defined in the arguments
        $props['file'] = $props_refl['file'];
        $props['line'] = (string) $props_refl['line']; //casting necessary so trim works on string test
        $props['class'] = $props_refl['class'];
        $props['method'] = $props_refl['method'];
        $props['comment'] = $props_refl['comment'];
        $props['args'] = $props['args'];
        $props['calling_method'] = $props_backtrace['calling_method'];
        $props['calling_class'] = $props_backtrace['calling_class'];
        $props['calling_file'] = $props_backtrace['calling_file'];
        $props['calling_line'] = (string) $props_backtrace['calling_line']; //casting necessary so trim works on string test
        $props['signature'] = $props['signature'];
        $props['signature_simple'] = $props['signature_simple'];



        /* Screen Defaults
         * Make sure that the returned array has exactly the same
         * element indexes as the defaults array, and that the values
         * match the defaults if they reached this point and are still null or dont exist
         */
        $props = $this->getPlugin()->getTools()->screenDefaults($defaults, $props);


        /*
         * trim strings
         */
        foreach ($props as $key => $value) {
            if (is_string($value)) {
                $props[$key] = trim($value);
            }
        }



        return $props;
    }

    /**
     * Get Reflected Method Properties
     *
     * Returns a list of properties describing the method or standalone function where the debug statement is located.
     * This method uses the reflection classes, so the values returned are compile time values
     *
     * @param $class
     * @param $method
     * @return void
     */
    private function _getReflectedMethodProperties($class, $method) {

        #init;
        $arg_names = '';
        $file = '';
        $error_message = '';
        $native = false;
        $reflMethod = null;
        $comment = '';

        $reflClass = null;
        $arg_params = array();
        $arg_names = array();

        /*
         * Function Properties $file, $arg_names, $line
         */
        if (is_null($class)) {
            /*
             * Function $file and $arg_names
             */
            $arg_names = null;
            $file = null;
            try {
                $reflFunc = new ReflectionFunction($method);
                $line = $reflFunc->getStartLine();
                $file = $reflFunc->getFileName();
            } catch (Exception $exc) {
                /*
                 * need to catch fatal exceptions caused by language constructs that cant be found by php
                 */
                $error_message = $exc->getMessage();

                if (stripos($error_message, 'does not exist') !== false) {

                    $line = null;
                    $file = null;
                    $native = true;
                }
            }
        }
        /*
         * Method Properties  $comment, $file, $arg_names, $line
         */

        if (!is_null($class)) {



            /*
             * Method $comment
             */
            $reflMethod = new ReflectionMethod($class, $method);

            $comment = '<p>' . str_replace('*', '<br/>*', $reflMethod->getDocComment()) . '</p>';
            /*
             * Method $line
             */
            $line = $reflMethod->getStartLine();
            /*
             * Method $file and $arg_names
             */
            $reflClass = new ReflectionClass($class);
            $file = ($reflClass->getFileName());
            $arg_params = $reflClass->getMethod($method)->getParameters();

            $arg_names = array();
            foreach ($arg_params as $arg_param) {
                $arg_names[] = $arg_param->name;
            }
        }

        /*
         * set defaults for returned array
         */
        $defaults = array(
            'native' => null, //whether the function is a native or built-in function or language construct
            'line' => null, //the line where the function is located
            'file' => null, //the file that contains the method
            'class' => null, //the class that contains the method
            'method' => null, //the method name
            'arg_names' => null, //an array of the argument names
            'comment' => null, //the comment docBlock
        );


        /*
         * Assign Values to Returned Array
         */


        $props['native'] = $native;
        $props['line'] = (string) $line; //casting necessary so trim works on string test
        $props['file'] = $file;
        $props['class'] = $class;
        $props['method'] = $method;
        $props['arg_names'] = $arg_names;
        $props['comment'] = $comment;



        /* Screen Defaults
         * Make sure that the returned array has exactly the same
         * element indexes as the defaults array, and that the values
         * match the defaults if they werent set previously
         */
        $props = $this->getPlugin()->getTools()->screenDefaults($defaults, $props);

        /*
         * trim strings
         */
        foreach ($props as $key => $value) {
            if (is_string($value)) {
                $props[$key] = trim($value);
            }
        }

        return $props;
    }

    /**
     * Turn On
     *
     * Turns Debugging On
     *
     * @param none
     * @return void
     */
    public function turnOn() {

        $this->_debug_state = true;
        return ($this->_debug_state);
    }

    /**
     * Turn Off
     *
     * Turns Debugging Off
     *
     * @param none
     * @return void
     */
    public function turnOff() {

        $this->_debug_state = false;
        return ($this->_debug_state);
    }

    /**
     * Is On
     *
     * Returns True if Debugging is on
     *
     * @param none
     * @return void
     */
    public function isOn() {

        return ($this->_debug_state && true);  //return true if true , false if false
    }

    /**
     * Is Off
     *
     * Returns True if Debugging is off
     *
     * @param none
     * @return void
     */
    public function isOff() {

        return (!$this->_debug_state && true);
    }

    protected $_options = null;

    /**
     * Get Option
     *
     * Returns the value from the _options array or the _getDefaultOption method
     * @param string $option_name
     * @return mixed
     */
    public function getOption($option_name) {
        $options = $this->getOptions();

        if (!isset($options[$option_name])) {

            $option_value = $this->_getDefaultOption($option_name);
        } else {
            $option_value = $options[$option_name];
        }

        return $option_value;
    }

    /**
     * Set Option
     *
     * @param string $option_name
     * @param string $option_value
     *
     * @return object $this
     */
    public function setOption($option_name, $option_value) {

        $this->_options[$option_name] = $option_value;
        return $this->_options;
    }

    /**
     * Get Options
     *
     * @param none
     * @return array
     */
    public function getOptions() {

        if (is_null($this->_options)) {
            $this->_options = array();
        }
        return $this->_options;
    }

    protected $_debug_state;

    /**
     * Get Group Label
     *
     * create group label for js console expand/collapse groups and browser output
     *
     * @param none
     * @return void
     */
    protected function _getGroupLabel($class, $method, $file) {
        /*
         * create groups for js console expand/collapse groups
         */



        $class = ($class === '') ? '' : $class . '::';
        $method = ($method === '') ? '' : $method . '()';
        $file = ($file === '') ? '<unknown>' . ' /' : $file . ' /';
//        if ($method == '') {
//            $group_label = basename($file);
//        } else {
//            $group_label = basename($file) . '/' . $class . $method;
//        }
//
        $group_label = basename($file) . $class . $method;
        return ($group_label);
    }

    /**
     * Get Line Numbers Prefix
     *
     * Gets the line number prefix as defined by the options
     *
     * @param none
     * @return void
     */
    private function _getLineNumbersPrefix($line, $class, $method, $file) {

        $prefix = '';
        $line_number_template = $this->getOption('line_numbers_prefix_template');


        if ($this->getOption('line_numbers_prefix_enabled')) {

            $search = array('{LINE}', '{CLASS}', '{FUNCTION}', '{FILE}');
            $replacements = array($line, $class, $method, $file);
            $prefix = str_replace($search, $replacements, $line_number_template);
        }

        return $prefix;
    }

    protected $_footer_log_handle = null;

    /**
     * log
     *
     * Adds a log entry to the log or outputs it to browser if inline debugging is on
     *
     * @param none
     * @return void
     */
    protected function _log($line, $class, $method, $file, $html, $text, $use_prefix = true) {


        $log_entry = compact('line', 'class', 'method', 'file', 'html', 'text', 'use_prefix');

//        if ($this->getOption('output_to_footer') || $this->getOption('output_to_console')) {
//            $this->_addToLog($line, $class, $method, $file, $html, $text, $use_prefix);
//
//
//        }

        $browser_output = $this->_formatForBrowser($log_entry);

        if ($this->getOption('output_to_inline')) {
            echo $browser_output;
        }


        if ($this->getOption('output_to_footer')) {
            /*
             * take same output that was echoed and save it to a temporary file that will be included at
             * upon script shutdown
             */
            if (is_null($this->_footer_log_handle)) {
                $this->_footer_log_handle = tmpfile(); //create a temporary file
            }
            $footer_log_handle = $this->_footer_log_handle;
            /*
             * write the string to it
             */
            //  fwrite($tempFileOutHandle, $example);
            /*
             * return its path
             */
            //     static $counter;
            //    $counter++;
            //    $metaDatas = stream_get_meta_data($this->_footer_log_handle);
            //    $footer_log_file_name = $metaDatas['uri'];




            fwrite($this->_footer_log_handle, '<br>' . $browser_output);
        }
    }

    /**
     * Format For Browser
     *
     * Returns a log entry that has been formatted for inline or footer html output
     *
     * @param array $log_entry A log array element
     * @return void
     */
    protected function _formatForBrowser($log_entry) {

        /*
         * Make sure that the $log_entry contains all the elements we need
         */
        $defaults = array(
            'html' => null,
            'text' => null,
            'line' => null,
            'file' => null,
            'class' => null,
            'method' => null,
            'use_prefix' => true
        );

        $this->getPlugin()->getTools()->screenDefaults($defaults, $log_entry);




        if ($log_entry['use_prefix'] === true) {


            $prefix = $this->_getLineNumbersPrefix($log_entry['line'], $log_entry['class'], $log_entry['method'], $log_entry['file']);
            $log_entry['html'] = $prefix . $log_entry['html'];
        }





        $group_label = $this->_getGroupLabel($log_entry['class'], $log_entry['method'], $log_entry['file']);





        static $last_group_label;


        ob_start();

        if ($last_group_label == $group_label) { //dont repeat the group if exactly the same
            // if (false) { //dont repeat the group if exactly the same
            // echo '<div>' . '<pre style="margin:20px;background-color:#E7E7E7;">' . $content . '</pre></div>';
            echo '<div>' . '<div style="background-color:#E7E7E7;">' . $log_entry['html'] . '</div></div>';
        } else {

            //echo '<div style="background-color:#F1F1DA;">' . $group_label . '</div><div>' . '<pre style="margin:20px;background-color:#E7E7E7;">' . $content . '</pre></div>';
            echo '<div style="padding:5px;margin:5px 0 5px 0;background-color:#F1F1DA;"><strong>' . $group_label . '</strong></div><div>' . '<div style="background-color:#E7E7E7;">' . $log_entry['html'] . '</div></div>';
        }

        $output_buffer = ob_get_clean();





        $last_group_label = $group_label;

        return $output_buffer;
    }

    /**
     * Set Filter
     *
     * Adds the name of a filter to the enabled or disabled filters array.
     *
     * @param $name The class, function, or breadcrumb name
     * @param $enabled True/false
     * @return $array, the current filters
     */
    public function setFilter($name, $enabled) {
        $filters = $this->getFilters();
        if ($enabled) {
            $filters['enabled'][$name] = count($filters['enabled']);
        } else {
            unset($filters['enabled'][$name]);


            $filters['disabled'][$name] = count($filters['disabled']);
        }
        $this->setOption('filters', $filters);
        return $filters;
    }

    /**
     * Short Description
     *
     * Long Description
     *
     * @param none
     * @return void
     */
    public function getFilters() {
        if (!isset($this->_options['filters'])) {
            $options = $this->getOptions();
            $options['filters']['enabled'] = array();
            $options['filters']['disabled'] = array();
            $this->setOption('filters', $options['filters']);
        }
        return($this->_options['filters']);
    }

    /**
     * In Excluded Filter
     *
     * Checks to see if the function is in the excluded filter and returns true or false. Will always return true if filters are disabled
     *
     * @param string $function_name The name of the excluded function
     * @return boolean True if the function should be excluded from output, false if otherwise
     */
    private function _inExcludedFilter($method) {

        /*
         * if the excluded function is not enabled, return false
         */
        if (!$this->getOption('excluded_functions_filter_enabled')) {
            return false;
        }
        /*
         * otherwise, check whether its in the filter
         */
        $excluded_functions_filter = $this->getOption('excluded_functions_filter');

        if (in_array($method, $excluded_functions_filter)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * In Filters
     *
     * Checks to see if the passed filters array from a debug method's arguments
     * are within the filters we want
     *
     * @param none
     * @return boolean
     */
    protected function _inFilters($class, $method, $always_debug) {

        //$args = func_get_args();

        /*
         * dont debug if debugging is off
         */

        if ($this->isOff()) {

            return false;
        }




        /*
         * If Filter Bypass is set to true,
         * ignore all filters so return true
         *
         */
        if ($this->getOption('FilterBypass')) {
            return true;
        }

        /*
         * Debug if always_debug is set to true
         */
        if ($always_debug === true) {
            return true;
        }

        /*
         * Debug if in filters, otherwise, dont
         */
        $filters = $this->getFilters();

        if (in_array($class, array_keys($filters['enabled']))) {
            return(true);
        }

        if (in_array($method, array_keys($filters['enabled']))) {
            return(true);
        }


        return false;
    }

    /**
     * Visualize Backtrace
     *
     * Returns a graphviz graphical representation of the backtrace
     *
     * @param array $traces
     * contains the classes and the methods in the format contained in the example in the comments within the function
     *
     * @return void
     */
    public function getVisualBacktrace($traces) {

        /*
         * check if enabled, if not, return an empty string
         * if enabled, but not configured properly, return an error message
         */

        $is_graphviz_enabled = $this->debug()->getOption('graphviz');

        if ($is_graphviz_enabled) {

            $graphviz_include_path = $this->getOption('graphviz_include_path');

            /*
             * check if file is in include path before attempting to include it
             */
            //if not include path, return a message to use
            if ($this->getPlugin()->getTools()->inIncludePath($graphviz_include_path)) {
                include_once $graphviz_include_path; //
            } else {
                die('<br> [Simpli Framework Debug Module] Error: Attempted to include \'' . $graphviz_include_path . '\' , but it could not be found. Graphviz is either not installed properly, or you need to set the include path for your installation. <br>You can set the Graphviz include path by adding the following line to the config() method in the Debug module: $this->debug()->setOption(\'graphviz_include_path\',\'path/to/GraphViz.php\' ( The default include path =\'Image/GraphViz.php\')<br> If you don\'t want to use graphviz to visually trace your debug backtraces, just add the following line to the config() method in the debug module; $this->setOption(\'graphviz\',false);');
            }
        } else {

            return ''; //if graphviz is not enabled, dont attempt to graph and return an empty string
        }
//ref:http://bebo.minka.name/k2thingz/doxydoc/html/d9/d49/class_image___graph_viz_e13b94d8b7b7e83d3c36f819110bf929.html#e13b94d8b7b7e83d3c36f819110bf929




        /*
         *
         * Example traces array
         * Demonstrates the format that the traces array expects
         */
//        $traces = array(
//            'classes' => array(
//                'class1'
//                , 'class2'
//                , 'class3'
//            ),
//            'methods' => array(array(
//                    'class' => 'class1'
//                    , 'function' => 'function3'
//                )
//                , array(
//                    'class' => 'class2'
//                    , 'function' => 'function1'
//                )
//                , array(
//                    'class' => 'class3'
//                    , 'function' => 'function1'
//                )
//                , array(
//                    'class' => 'class2'
//                    , 'function' => 'function2'
//                )
//                , array(
//                    'class' => 'class1'
//                    , 'function' => 'function2'
//                )
//                , array(
//                    'class' => 'class3'
//                    , 'function' => 'function1'
//                )
//                , array(
//                    'class' => 'class2'
//                    , 'function' => 'function1'
//                )
//            )
//        );
//
//

        /*
         * extract the classes and traces into different arrays
         */
        $classes = array_unique($traces['classes']);
        $trace_methods = $traces['methods'];

        /*
         * Define the cluster template
         */
        $cluster_template = ' subgraph cluster{CLASS} {
            node [style = filled, color = white];
            style = filled;
            color = lightgrey;
          {METHODS}
            label = "{CLASS}";
        }
        ';

        /*
         * Define the digraph template
         */

        $digraph_template = 'digraph G {
    {CLUSTERS}
    // Method Connections
    {CONNECTIONS}

    //splines=false; //forces straight edges
}';

        /*
         * Initialize
         */
        $clusters_dot_markup = '';

        $connections = '';

        /*
         * Iterate through the classes
         * With each new class, build connections for each of its methods
         */
        foreach ($classes as $class) {

            /*
             * Initialize Loop
             */
            $methods = '';
            $clusters_dot_markup_array = array(); //create an array to hold the dot markup for each of the class clusters
            $flow_sequence = 0; //keep track of where we are in the flow, so we can label the connections

            /*
             * iterate through each of the traces and add each to the appropriate
             * subgraph cluster that matches their class.
             * create a connections string that will be added to the main graph
             */
            foreach ($trace_methods as $trace) {


                /*
                 * when the trace's class matches the current cluster class, add it to the cluster
                 */
                if ($trace['class'] === $class) {


                    $method1 = '"' . $trace['class'] . "::" . $trace['function'] . '"'; //e.g.: MyClass::MyFunction to create a unique node name . Need to surround with double quotes so it wont break on special chars
                    $methods.= $method1 . '[label=' . '"' . $trace['function'] . '"' . '];'; //add it to a $methods string that we can add to the class cluster, give it a label that only includes its function name since the cluster will identify its class. Need to surround with double quotes so it wont break on special chars

                    /* Add a connection
                     * Connections track the trace from one point to another
                     * add a connection if there is at least one more trace that follows
                     */
                    if ($flow_sequence + 1 < count($trace_methods)) {
                        $method2 = '"' . $trace_methods[$flow_sequence + 1]['class'] . "::" . $trace_methods[$flow_sequence + 1]['function'] . '"';

                        $connections.=$method1 . '->' . $method2 . '[label="' . ($flow_sequence + 1) . '"];'; //';[label=' . $trace['function'] . ']';
                    }
                }
                $flow_sequence++;
            }

            /*
             * Now populate the cluster template
             */
            $search = array('{CLUSTER}', '{METHODS}', '{CLASS}');
            $replacements = array($flow_sequence, $methods, $class);
            $clusters_dot_markup_array[$class] = str_replace($search, $replacements, $cluster_template);

            /*
             * Concatenate the dot markup for each of the clusters
             * So we have one string we can insert into the graph template
             */
            $clusters_dot_markup.=$clusters_dot_markup_array[$class];
        }


        /*
         * Create the final Graph Markup string
         * Insert the cluster dot markup into the Graph Template
         * to create the final dot markup
         */

        $search = array('{CLUSTERS}', '{CONNECTIONS}');
        $replacements = array($clusters_dot_markup, $connections);
        $graph_dot_markup = str_replace($search, $replacements, $digraph_template);





//        echo '<pre>';
//        echo str_replace(';', ';<br>', $graph_dot_markup);
//        echo '</pre>';



        return $this->gvGraphString($graph_dot_markup);
        //  die('<br> ' . __METHOD__ . __LINE__ . ' exiting after printing digraph ');


        /*
         * Markup that works and was generated by this method
         * saved here for reference
         *
          digraph G {
          subgraph clusterclass1 {
          node [style = filled, color = white];

          style = filled;

          color = lightgrey;

          "class1::function3"[label=function3];
          "class1::function2"[label=function2];

          label = "class1";

          }
          subgraph clusterclass2 {
          node [style = filled, color = white];

          style = filled;

          color = lightgrey;

          "class2::function1"[label=function1];
          "class2::function2"[label=function2];
          "class2::function1"[label=function1];

          label = "class2";

          }
          subgraph clusterclass3 {
          node [style = filled, color = white];

          style = filled;

          color = lightgrey;

          "class3::function1"[label=function1];
          "class3::function1"[label=function1];

          label = "class3";

          }

          // Method Connections
          "class1::function3"->"class2::function1"[label="1"];
          "class1::function2"->"class3::function1"[label="5"];
          "class2::function1"->"class3::function1"[label="2"];
          "class2::function2"->"class1::function2"[label="4"];
          "class3::function1"->"class2::function2"[label="3"];
          "class3::function1"->"class2::function1"[label="6"];
          }
         *
         */
    }

    /**
     * Graph String
     *
     * Returns a graph image produced by graphviz, given a string containing the dot markup
     *
     * @param none
     * @return void
     */
    public function gvGraphString($graph) {

        error_reporting(E_ALL ^ E_NOTICE);
        $gv = new Image_GraphViz();


        /*
         * create a temporary file
         */
        $tempHandle = tmpfile();
        /*
         * write the string to it
         */
        fwrite($tempHandle, $graph);
        /*
         * return its path
         */
        $metaDatas = stream_get_meta_data($tempHandle);
        $tmpFilename = $metaDatas['uri'];

        $gv->load($tmpFilename);




        /*
         * create a temporary file
         */
        $tempFileOutHandle = tmpfile();
        /*
         * write the string to it
         */
        //  fwrite($tempFileOutHandle, $example);
        /*
         * return its path
         */
        $metaDatas = stream_get_meta_data($tempFileOutHandle);
        $tmpFileOutName = $metaDatas['uri'];




        $gv->renderDotFile($tmpFilename, $tmpFileOutName, 'svg');


        if (file_exists($tmpFileOutName)) {
            $img = file_get_contents($tmpFileOutName);
            $result = $img;
        } else {
            $result = '<br> The image could not be rendered.';
        }

        return $result;
    }

    /**
     * Get Default Option
     *
     * Provides a default option value if it wasnt set by the user
     *
     * @param none
     * @return void
     */
    private function _getDefaultOption($option_name) {

        $default_options = array(
            /*
             * Filter Bypass
             * True ignores all filters set by setFilter()
             */

            'FilterBypass' => false,
            /*
             * Graphiviz Include Path
             */
            'graphviz_include_path' => 'Image/GraphViz.php',
            /*
             * Whether graphviz is enabled
             * boolean
             */
            'graphviz' => false,
            /*
             * Output To Inline
             * Whether to echo debug output to the browser as it is logged
             */
            'output_to_inline' => false,
            /*
             * Output To Footer
             * Whether to echo debug output to the browser's
             *  footer at the script's shutdown
             */
            'output_to_footer' => false,
            /*
             * Output To File
             * Whether to echo debug output to a file
             */
            'output_to_file' => false,
            /*
             * Output to Console
             * Whether to echo debug output to the javascript console
             */
            'output_to_console' => false,
            /*
             * Memory and Execution Time Tweaks
             *
             * Show Arrays
             * Show Objects
             *
             * Will allow logTrace and logVars to
             * show arrays and objects. Setting to true
             * is very memory and cpu intensive, and may
             * drive execution time and memory past their limits.
             * Recommend arrays to true and objects to false.
             *
             *
             */
            'show_arrays' => true,
            'show_objects' => false,
            /*
             *
             * Log All Actions - will output every hook action - generates a ton of output. do you really want to do this ?
             * true/false
             */
            'log_all_actions' => false,
            /*
             *
             * Excluded Functions
             * Exclude these functions from traces
             */
            'excluded_functions_filter' =>
            array(
                'require',
                'require_once',
                'include',
                'include_once',
                'do_action',
                'do_meta_boxes',
                'do_shortcode',
                'load_template',
                'locate_template',
                'preg_replace_callback',
                'do_shortcode_tag',
                'call_user_func',
                'call_user_func_array',
                'apply_filters',
                'do_action_ref_array',
                'main'
            ),
            /*
             * Enable or disable the Excluded Functrions Filter
             */
            'excluded_functions_filter_enabled' => true,
            /*
             * Line Number Template
             */
            'line_numbers_prefix_enabled' => true,
            'line_numbers_prefix_template' => '<em>{FUNCTION}/{LINE}</em>&nbsp;'
        ); //end of options array

        if (isset($default_options[$option_name])) {
            return($default_options[$option_name]);
        } else {
            return null;
        }
    }

    /**
     * Hook Print Log to Footer
     *
     * Outputs Log to Browser after everything else.
     *
     * @param none
     * @return void
     */
    public function hookPrintLogToFooter() {



        if (!$this->getOption('output_to_footer')) {
            return;
        }



        echo '<div style="background-color:#E7E7E7">';
        echo ' <div style="text-align:center;font-size:large;"><strong>Debug Log</strong></div>';



        $metaDatas = stream_get_meta_data($this->_footer_log_handle);

        $footer_log_file_name = $metaDatas['uri'];

        include($footer_log_file_name);

        fclose($this->_footer_log_handle);


        echo '</div>';
    }

    /**
     * Hook - Print Log to Console
     *
     * Prints Local Vars to the Javascript Console
     * @param string $content The shortcode content
     * @return string The parsed output of the form body tag
     */
    function hookPrintLogToConsole() {

        if (!$this->getOption('output_to_console')) {
            return;
        }



        $vars = json_encode($this->getLocalVars());
        ?>
                <script type='text/javascript'>

                            var <?php echo $this->getSlug(); ?> = <?php echo $vars; ?>

                        </script>

                <?php
            }

//            protected $_log = null;
//
//            /**
//             * Get Log
//             *
//             * Returns the log array
//             *
//             * @param none
//             * @return void
//             */
//    public function getLog() {
//
//        if (is_null($this->_log)) {
//            $this->_log = array();
//        }
//
//        return $this->_log;
//    }
}

