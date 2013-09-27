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
 * 5) if all else fails, set all filters to off, and go to the function that you need, and set $force_output to true for those log statements that you need.
 * 7) setOption('show_objects',false) ( this is the default)
 *  7) setOption('show_arrays',false) This will simply not display arrays when using logVars to display defined variables.
 * 8) do not increase timeout or memory , as this just invites abuse and can be a security hazard.
 *
 *
 * @example
 *         $this->debug()->t(); //trace provides a information about the method and arguments, and provides a backtrace in an expandable box. A visual trace is also provided if graphiviz is enabled.

  $this->debug()->log('log gets logged to browser, javascript console, and file');
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
 * @author Andrew Druffner
 * @package SimpliFramework
 * @subpackage SimpliHello
 *
 */
//class Simpli_Basev1c0_Plugin_Debug {//extends Simpli_Basev1c0_Plugin_Helper{
class Simpli_Basev1c0_Plugin_Debug extends Simpli_Basev1c0_Plugin_Helper {
//    function __construct($plugin) {
//        $this->_plugin = $plugin;
//    }
//
//    protected $_plugin;
    /**
     * Short Description
     *
     * Long Description
     *
     * @param none
     * @return void
     */
    // public function plugin() {
    //        return $this->_plugin;
    // }

    /**
     * Debug
     *
     * Returns the debug() method from the calling plugin object
     *
     * @param none
     * @return void
     */
    //  public function debug() {
    //      return $this;
    //  }

    /**
     * Configure Module
     *
     * @param none
     * @return void
     */
    public function config() {
        /* this method intentionally left empty since it should be implemented using the child class */
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
            add_action('admin_enqueue_scripts', array($this, 'hookEnqueueStyles'));
        } else {
            add_action('wp_enqueue_scripts', array($this, 'hookEnqueueScripts'));
            add_action('wp_enqueue_scripts', array($this, 'hookEnqueueStyles'));
        }



        if ($this->getOption('log_all_actions')) {
            add_action('all', array($this, 'hookLogAllActions'));
        }

        /*
         * Register Shutdown functinos are called in the order they are registered.
         * You must therefore register the hookPrintTraceStack before the hookPrintLogToFooter
         * or you'll receive invalid resource errors because the trace will print to a non-existent handle
         */

        if ($this->getOption('output_to_footer')) {
            register_shutdown_function(array($this, 'hookPrintLogToFooter'));
        }




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
                    ();
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


        $handle = $this->plugin()->getSlug() . '_' . 'debug-trace.js';
        $path = $this->plugin()->getDirectory() . '/admin/js/debug-trace.js';

        $inline_deps = array(); //cannot rely on namespaces since namespaces must be loaded in footer for them to work.
        $external_deps = array('jquery');
        $footer = false; //must load in head in case there is a fatal error that prevents foot scripts from loading
        $this->plugin()->enqueueInlineScript($handle, $path, $inline_deps, $external_deps, $footer);



        /*
         * add javascript for multiselect box
         * ref:http://www.senamion.com/blog/jmultiselect2side.html
         * Not used, but left here as an exaple

          $handle = 'jquery.multiselect2side.js';
          $src = $this->plugin()->getUrl() . '/js/jquery.multiselect2side.js';
          $deps = array('jquery');
          $ver = null;
          $in_footer = false;
          // wp_enqueue_script($handle, $src, $deps, $ver, $in_footer);

         */
    }

    /**
     * Enqueue Styles
     *
     * Enqueues Styles needed for debugging
     *
     * @param none
     * @return void
     */
    public function hookEnqueueStyles() {

        /*
         * add style for multiselect box
         * ref:http://www.senamion.com/blog/jmultiselect2side.html
         * Not used, but left here as an exaple


          $handle = 'jquery.multiselect2side.css';
          $src = $this->plugin()->getUrl() . '/css/jquery.multiselect2side.css';
          $deps = array();
          $ver = null;
          $media=null;
          //   wp_enqueue_style($handle, $src, $deps, $ver,$media);
         */
    }

    /**
     * Stop
     *
     * Exits PHP directly or upon optional condition
     *
     * @param none
     * @return void
     */
    public function stop($force_output = false) {


        $props = $this->_getMethodProperties();

        $line = $props['line'];
        $file = $props['file'];
        $ds_line = $props['ds_line'];
        if (!$this->_inFilters($props, $type = 'info', $force_output)) {
            return;
        }
        $template = ' <div style="color:red">Debug Stop - to continue script, remove the $this->debug()->stop() call on line {DS_LINE} in file {BASENAME_FILE} <br/><span style="color:black;">( {FILE} )</span></div>';
        $basename_file = basename($file);
        $tags = (compact('ds_line', 'line', 'file', 'basename_file'));
        $stop_message = $this->plugin()->tools()->crunchTpl($tags, $template);

        $this->_log($stop_message, $props, false, 'all', 'info');
        die();
    }

    /**
     * Log to Browser
     *
     * Log content to browser
     *
     * @param $content Content to be logged
     * @param $force_output Whether to override filters and force output
     * @return void
     */
    public function logb($content, $force_output = false) {

        $props = $this->_getMethodProperties();



        /*
         * check filters and debug state
         */
        if (!$this->_inFilters($props, $type = 'info', $force_output)) {
            return;
        }
        $this->_log($content, $props, $use_prefix = true, $target = 'browser');
    }

    /**
     * Log  to Console
     *
     * Log content to console
     *
     * @param $content Content to be logged
     * @param $force_output Whether to override filters and force output
     * @return void
     */
    public function logc($content, $force_output = false) {

        $props = $this->_getMethodProperties();



        /*
         * check filters and debug state
         */
        if (!$this->_inFilters($props, $type = 'info', $force_output)) {
            return;
        }
        $this->_log($content, $props, $use_prefix = true, $target = 'console', $type = 'info');
    }

    /**
     * Log error to console
     *
     * Log error to console
     *
     * @param $content Content to be logged
     * @param $force_output Whether to override filters and force output
     * @return void
     */
    public function logcError($content, $force_output = false) {

        $props = $this->_getMethodProperties();



        /*
         * check filters and debug state
         */
        if (!$this->_inFilters($props, $type = 'error', $force_output)) {
            return;
        }
        $this->_log($content, $props, $use_prefix = true, $target = 'console', $type = 'error');
    }

    /**
     * Log to File
     *
     * Log content to file
     *
     * @param $content Content to be logged
     * @param $force_output Whether to override filters and force output
     * @return void
     */
    public function logf($content, $force_output = false) {




        $props = $this->_getMethodProperties();



        /*
         * check filters and debug state
         */
        if (!$this->_inFilters($props, $type = 'info', $force_output)) {
            return;
        }
        $this->_log($content, $props, $use_prefix = true, $target = 'file', $type = 'info');
    }

    /**
     * Log
     *
     * Log content to all targets
     *
     * @param string $content The html or plain text to be logged
     * @param boolean $force_output True to override any blocking filters so as to force output to the log.
     * @return void
     */
    public function log($content, $force_output = false) {





        $props = $this->_getMethodProperties();


//        if ($props['method']==='filter') {
//            echo '<br> class =|' . $props['class'].'|';
//        }
        /*
         * check filters and debug state
         */
        if (!$this->_inFilters($props, $type = 'info', $force_output)) {
            return;
        }


        $this->_log($content, $props, $use_prefix = true, $target = 'all', $type = 'info');
    }

    /**
     * Log
     *
     * Log content to all targets
     *
     * @param string $content The html or plain text to be logged
     * @param boolean $force_output True to override any blocking filters so as to force output to the log.
     * @return void
     */
    public function logError($content, $force_output = false) {





        $props = $this->_getMethodProperties();


//        if ($props['method']==='filter') {
//            echo '<br> class =|' . $props['class'].'|';
//        }
        /*
         * check filters and debug state
         */
        if (!$this->_inFilters($props, $type = 'error', $force_output)) {
            return;
        }

        $this->_log($content, $props, $use_prefix = true, $target = array('browser', 'file', 'console'), $type = 'error');
    }

    /**
     * Log Extract
     *
     * Takes an array, and outputs each of its indexes as if it were a separate variable,  like extract(), but wont impact the symbol table and is for display purposes only.
     * Output is logged only to browser due to potential for a large amount of output and expected usage as a tracing tool.
     * example: logExtract(array('fruit1'=>'apple','fruit2'=>'orange'))
     * will output:
     * $fruit1='apple'
     * $fruit2='orange'
     *
     * @param array $array_vars Any associative array in which you'd like each index to be shown as its own variable with value equal to the element's value
     * @param boolean $force_output True Overrides filter settings
     * @return void
     */
    public function logExtract($array_vars, $force_output = false) {

        /*
         * gets the properties of the debug statement for filtering and output
         */

        $props = $this->_getMethodProperties();


        /*
         * check filters and debug state
         */
        if (!$this->_inFilters($props, $type = 'info', $force_output)) {
            return;
        }

        /*
         * Check each element of the array, and output in the format $<index_name> = $value , using
         * the normal $this->_logVar() method.
         */
        foreach ($array_vars as $var_name => $var_value) {

            $content = $this->_logVar('$' . $var_name . ' = ', $var_value, $force_output);



            $this->_log($content, $props, $use_prefix = true, $target = 'browser', $type = 'info');
        }
    }

    /**
     * Logs a Variable
     *
     * Logs a variable to the browser as the output target. Will format arrays and objects vertically for
     * easier reading.
     *
     * @param string $var_name A short name or  description of the variable
     * @param $var The variable to be logged
     * @param boolean $force_output True Overrides filter settings
     * @return void
     */
    public function logVar($var_name, $var, $force_output = false, $show_arrays = true, $show_objects = true, $expandable = null) {




        $props = $this->_getMethodProperties();


        /*
         * check filters and debug state
         */
        if (!$this->_inFilters($props, $type = 'info', $force_output)) {
            return;
        }





        /*
         * bump output to next line from the label if an array or object so the $var_name is easier to read
         */
        if (is_array($var) || is_object($var)) {
            $var_name = '<br/> ' . $var_name;
        }

        $content = $this->_logVar($var_name, $var, $force_output, $show_arrays, $show_objects, $expandable);

        $this->_log($content, $props, $use_prefix = true, $target = 'browser', $type = 'info');
    }

    /**
     * Log Variable
     *
     * Returns the name and value of a variable. Arrays and objects are formatted vertically.
     *
     * @param string $var_name A short name or  description of the variable
     * @param $var The variable to be logged
     * @param boolean $force_output True Overrides filter settings
     * @param boolean $show_arrays Whether to iterate through arrays. Used to help reduce the amount of memory used. If not provided, it will take its value from the main settings.
     * @param boolean $show_objects Whether to iterate through objects. Used to help reduce the amount of memory used. If not provided, it will take its value from the main settings.
     * @param boolean $expandable True will create a clickable div around content that initially hides the content, but then displays it on click. False will show the content without being surrounded by the hidden div
     *
     * @return void
     */
    private function _logVar($var_name, $var, $force_output, $show_arrays = null, $show_objects = null, $expandable = null) {

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
            $show_objects = $this->getOption('show_objects');
        }

        if (is_null($expandable)) {
            $expandable = $this->getOption('expand_on_click');
        }
#init



        /*
         * If an array, format as an array
         */
        if (is_array($var) || is_object($var)) {
            //cast to an array. if you dont, the object will appear as an empty array in the final output
            $var = (array) $var;
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

            /*
             * hide arrays behind a 'more' link that when clicked, will expand to display the values
             * if false, will show them without the link. this may be necessary since sometimes the more link
             * breaks (depending if you use a stop() or die() statement or if fatal errors occur in the code you are
             * troubleshooting)
             */
            if ($expandable === true) {
                $template = '
        <div style="display:inline-block;">
            {TYPE}&nbsp;<a class="simpli_debug_citem" href="#"><span>More</span><span style="visibility:hidden;display:none">Less</span></a>
            <div style="visibility:hidden;display:none;background-color:#E7DFB5;">
                [{KEY_NAME}]=> {VALUE}
            </div>
        </div>

';
            } else {
                $template = '
        <div style="display:block;">
            {TYPE}&nbsp;
            <div style="visibility:visible;display:block;background-color:#E7DFB5;">
                [{KEY_NAME}]=> {VALUE}
            </div>
        </div>

';
            }

            $template = $this->plugin()->tools()->scrubHtmlWhitespace($template);


            foreach ($var as $key => $value) {
                if (is_array($value) || is_object($value)) {

                    $same_line = false;
                    $type = ucwords(gettype($value)); //e.g.: 'Array'
                    if (is_object($value)) {
                        $type = get_class($value) . ' ' . $type;
                    }




                    if ((!$show_objects) && (is_object($value))) {
                        $value_string = 'Objects are not expandable. To debug an object, use $this->debug()->logVar()';
                    } elseif ((!$show_arrays) && (is_array($value))) {
                        $value_string = 'Arrays are not expandable. To debug an array, use $this->debug()->logVar()';
                    } else {
                        try {


                            $value_string = trim(htmlspecialchars(print_r($value, true)));
                        } catch (Exception $exc) {

                            $this->log($exc->getMessage());
                            $value_string = 'Error while attempting to display value : ' . $this->log($exc->getMessage());
                        }
                    }
                    $tags = array(
                        '{TYPE}' => $type,
                        '{KEY_NAME}' => $key,
                        '{VALUE}' => $value_string,
                    );

                    $arr_result[$key] = str_replace(array_keys($tags), array_values($tags), $template);
                } else {
                    if (is_bool($value)) {
                        $value = ($value) ? $value . '(true)' : $value . '(false)';
                    }

                    /*
                     * spell out 'null' if the string is null,
                     * otherwise
                     */

                    if (is_null($value)) {
                        $value = '(null)';
                    }
                    if ($value === '') {
                        $value = '(empty string)';
                    }
                    $arr_result[$key] = htmlspecialchars($value);
                }
            }






            $content = $var_name . '<pre>' . print_r($arr_result, true) . '</pre>';
        } else {

            /*
             * spell out 'true' or 'false' if
             * variable value is a boolean, so we can differentiate from an empty string
             */
            if (is_bool($var)) {
                $var = ($var === true) ? $var . '(true)' : $var . '(false)';
            }

            /*
             * spell out 'null' if the string is null,
             * otherwise
             */

            if (is_null($var)) {
                $var = (string) '(null)';
            }
            if ($var === '') {
                $var = (string) '(empty string)';
            }
            $content = $var_name . htmlspecialchars($var);
        }

        return $content;
    }

    /**
     * Debug Backtrace
     *
     * Provides the array from the php function debug_backtrace. Useful since
     * it requires no paramaters
     *
     * @param boolean $wrapper True if called by another *private* method that is in turn called by a puble method.
     * This helps determine to remove another layer from the backtrace
     * @return array The
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
     * This helps to reduce the amount of output for easier viewing.
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
            'function' => '',
            'args' => array(),
        );
        /*
         * get the backtrace
         */

        $arr_btrace = $this->_debug_backtrace(false); //get the backtrace

        /*
         * get where the debug statement was located
         */

        $props = $this->_getMethodProperties($arr_btrace);


        /*
         * iterate through the loop so we can simplify each trace
         */

        foreach ($arr_btrace as $key => $trace_properties) {

            $trace_properties = array_intersect_key(array_merge($defaults, $trace_properties), $defaults); //make sure the indexes we need are there or use their defaults
            $traces[] = $trace_properties;
        }
        $content = 'Simplified debug_backtrace() <pre>' . print_r($traces, true) . '</pre>';



        $this->_log($content, $props, true, 'browser', 'info');
    }

    /**
     * Simple Trace
     *
     * Provides a very simple trace without all the theatrics. Useful within the debug module itself, since using t() would cause unending loop and memory errors
     *
     * @param array $arr_btrace The backtrace array produced by debug_backtrace()
     * @return void
     */
    public function st($force_output = false) {

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

        $arr_btrace = $this->_debug_backtrace(false); //get the backtrace

        /*
         * get where the debug statement was located
         */

        $props = $this->_getMethodProperties($arr_btrace);

        /*
         * check if in filters
         */
        if (!$this->_inFilters($props, $type = 'info', $force_output)) {

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
            $trace_template = '{line}/{file}/{class}->{function}<br>(<br>{args}<br>)';
            $search = array('{file}', '{line}', '{class}', '{function}', '{args}');
            $args_formatted_string = '<pre style="padding-left:20px">' . print_r($args, true) . '</pre>';
            $replacements = array(basename($file), $line, $class, $function, $args_formatted_string);
            $trace_string = str_ireplace($search, $replacements, $trace_template);





            if ($counter > 0) {
                $previous_string = $trace_string;
            }
            $margin = $counter * 10;

            $trace_string = '<span style="border:solid 1px grey;display:inline-block;margin-left:' . $margin . 'px">' . $trace_string . '</span>';


            $traces[] = $trace_string;
        }
        $content = '<em>Simple Trace : </em><div>' . implode('<br/><br/>', $traces) . '</div>';
        $this->_log($content, $props, true, 'browser', 'info'); //info so as not to trigger the special handling that trace gets
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
        $props['args'] = (isset($array_backtrace[1]['args']) ? $array_backtrace[1]['args'] : '');

        return $props;
    }

    protected $_defined_vars_stack;

    /**
     * Log Variables
     *
     * Logs the variables returned by get_defined_vars in a nicely formatted , expandable div
     * Output is logged only to browser due to potential for a large amount of output and expected usage as a tracing tool.
     *
     * Usage:
     *
     * @example   $this->debug()->logVars(get_defined_vars());
     *
     * @param array $arr_defined_vars Must be get_defined_vars()
     * @return void
     */
    public function logVars($arr_defined_vars = array(), $force_output = false) {
        /*
         * dont bother if defined variables is not enabled
         */
        if (!$this->debug()->getOption('defined_vars_enabled')) {
            return;
        }


        /*
         * Check each element of the array, and output in the format $<index_name> = $value , using
         * the normal $this->_logVar() method.
         */



        $props = $this->_getMethodProperties();



        $string_defined_vars = ''; //holds the defined vars html
        foreach ($arr_defined_vars as $var_name => $var_value) {

            $content = $this->_logVar('$' . $var_name . ' = ', $var_value, $force_output);



            $string_defined_vars.= '<br/>' . $content;
        }

        /*
         * updated defined vars stack
         */
        $this->_defined_vars_stack[$props['class']][$props['method']][] = $string_defined_vars;


        if (!$this->_inFilters($props, $type = 'info', $force_output)) {
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
            '{METHOD}' => $props['method'],
            '{CLASS}' => $props['class']
        );





        $template = '

<div style=";padding:5px;text-align:left;display: inline-block;">
<a class="simpli_debug_citem" href="#"><span><em>Variables</em></span><span style="visibility:hidden;display:none">Collapse Variables</span></a>



                <div  class="simpli_debug_toggle" style="display:none;visibility:hidden;padding:0px;margin:0px;"><!-- Collapsable --><p >{DEFINED_VARS}</p>


</div>
</div>

';
        $template = $this->plugin()->tools()->scrubHtmlWhitespace($template);


        $content = str_replace(array_keys($tags), array_values($tags), $template);




        $this->_log($content, $props, $use_prefix = true, $target = 'browser');
    }

    /**
     * Get Simple Trace String
     *
     * Returns the trace string displayed for each method
     *
     * @param array $props The properties of the method that generated the debug call. You can get this using _getMethodProperties()
     * @param string type 'normal','simple','text'
     * @return string $trace_string The string that will be displayed for the method
     */
    private function _getMethodLabel($props) {
        /*
         * Available tags for $ds_props are:
         * {LINE}
         * {CLASS}
         * {METHOD}
         * {FILE}
         * {ARGS}
         *
         */
#init
        $not_available_text = "not available";


        $template_type = $this->getOption('trace_output_format');
        $method_label_template = $this->getOption('method_label_template_' . $template_type);
        /*
         * use a simple trace format to reduce html and speed things up.
         * this just provides a simple label
         */
        $prop_args = $props['args'];
        if ($template_type === 'text') {
            $props['args'] = htmlspecialchars(@json_encode($prop_args)); // the '@' is to suppress spurious 'recursion' notices for json_encode.






            /*
             * populate the template
             */
            $method_label_text = $this->plugin()->tools()->crunchTpl($props, $method_label_template);
            $method_label_text = str_ireplace('[[', '', $method_label_text); //removes double brackets that appear after json encode
            $method_label_text = str_ireplace(']]', '', $method_label_text); // ditto
            return $method_label_text;
        }




        $method_label_template = $this->plugin()->tools()->scrubHtmlWhitespace($method_label_template); //this is necessary since there are pre tags in the source . You could just remove it manually using a macro in a text editor , like the 'remove unnecessary whitespace' utility in notepad++ , but using scrubHtmlWhitespace allows us to retain the whitespace in our source file so its human readable, while still removing it when its displayed.
        /*
         * Now populate the html template
         */
        /*
         * Expanded Args
         */

        $current_expanded_args = '';
        foreach ($props['args'] as $var_name => $var_value) {

            $current_expanded_args .= '<br/>' . $this->_logVar('$' . $var_name . ' = ', $var_value, true);
        }
        $tags = array(
            '{LOCATION}' => ($props['line'] !== '' && $props['file'] !== '') ? ' Line ' . $props['line'] . ' in ' . $props['file'] : 'Not Available',
            '{CALLING_LOCATION}' => ($props['calling_line'] !== '' && $props['calling_file'] !== '') ? ' Line ' . $props['calling_line'] . ' in ' . $props['calling_file'] : 'Not Available',
            '{LINE}' => ( $props['line'] !== '') ? $props['line'] : $not_available_text,
            '{FILE}' => ( $props['file'] !== '') ? $props['file'] : $not_available_text,
            '{CLASS}' => ( $props['class'] !== '') ? $props['class'] : $not_available_text,
            '{METHOD_SIG}' => ( $props['signature'] !== '') ? $props['signature'] : $not_available_text,
            '{METHOD_SIG_SIMPLE}' => ( $props['signature_simple'] !== '') ? $props['signature_simple'] : $not_available_text,
            '{METHOD}' => ( $props['method'] !== '') ? $props['method'] : $not_available_text,
            '{EXPANDED_ARGS}' => ($current_expanded_args !== '') ? $current_expanded_args : $not_available_text,
            '{DESCRIPTION}' => ( $props['comment'] !== '') ? '<p>' . str_replace("\n", '<br/>', $props['comment']) . '</p>' : $not_available_text,
            '{CALLING_LINE}' => $props['calling_line'],
            '{CALLING_FILE}' => $props['calling_file'],
            '{CALLING_CLASS}' => $props['calling_class'],
            '{CALLING_METHOD}' => $props['calling_method'],
            '{SOURCE}' => $this->_getSource($props['file'], $props['line'], $props['end_line'], $props['comment']),
        );


        $current_trace_html = str_replace(array_keys($tags), array_values($tags), $method_label_template);



        return $current_trace_html;
    }

    protected $_trace_stack;
    protected $_trace_indents;
    protected $_last_method_label = null; //used to track the last method label used
    protected $_trace_props_previous = null;

    /**
     * t
     *
     * Same as logTrace - shorter form for convienance and backward compability
     *
     * @param none
     * @return void
     */
    public function t($force_output = false, $levels = 0) {

        /*
         * dont bother if trace is not enabled
         */
        if (!$this->debug()->getOption('trace_enabled')) {
            return;
        }


        $arr_btrace = $this->_debug_backtrace();


        $props = $this->_getMethodProperties();



        if (!$this->_inFilters($props, $type = 'trace', $force_output)) {
            return;
        }

        $content = $this->_formatTraceForBrowser($props, $arr_btrace, 0);

        $this->_log($content, $props, $use_prefix = false, $target = 'browser', $type = 'trace');



        return;
    }

    /**
     * Log Trace Wrapper
     *
     * Wrapper around _logTrace to provide a more human readable method
     * Because of huge amount of ouput, target is browser only
     *
     * @param none
     * @return void
     */
    public function logTrace($force_output = false, $levels = 0) {
        /*
         * dont bother if trace is not enabled
         */
        if (!$this->debug()->getOption('trace_enabled')) {
            return;
        }


        $arr_btrace = $this->_debug_backtrace();


        $props = $this->_getMethodProperties();



        if (!$this->_inFilters($props, $type = 'trace', $force_output)) {
            return;
        }

        $content = $this->_formatTraceForBrowser($props, $arr_btrace, 0);

        $this->_log($content, $props, $use_prefix = false, $target = 'browser', $type = 'trace');



        return;
    }

    /**
     * Format Trace for Browser
     *
     * Wraps the backtrace and visual backtrace from _getBacktrace() in the html template in options.
     *
     * @param array $props The properties from _getMethodProperties
     * @param array $arr_btrace The debug backtrace from debug_backtrace()
     * @param $levels The number of levels you want to backtrace
     * @return void
     */
    private function _formatTraceForBrowser($props, $arr_btrace, $levels) {

        $non_visual_backtrace_html = '';
        $visual_backtrace_html = '';

        /*
         * Get the full backtrace, hiding it under a link to expand/collapse it
         */

        $arr_trace = $this->_getBacktrace($arr_btrace, $levels);



        if ($this->getOption('expand_on_click')) {
            $template = $this->getOption('backtrace_template_expand_on_click');
        } else {

            $template = $this->getOption('backtrace_template_show_without_click');
        }

        if ($this->getOption('backtrace_enabled')) {
            /*
             * Build Non-Visual Trace Html
             */
            $tags = array(
// '{CLASS}' => ($props['class'] !== '') ? $props['class'] : $not_available_text,
                // '{METHOD}' => ($props['method'] !== '') ? $props['method'] : $not_available_text,
                '{TRACE}' => $arr_trace['backtrace'],
                '{EXPAND_TEXT}' => 'Backtrace',
                '{COLLAPSE_TEXT}' => 'Hide Backtrace',
                '{METHOD}' => $props['method']
            );
            $template = $this->plugin()->tools()->scrubHtmlWhitespace($template);
            $non_visual_backtrace_html = str_replace(array_keys($tags), array_values($tags), $template);
        }


        /*
         * Now get the visual backtrace html
         */

        if ($this->getOption('visual_backtrace_enabled')) {

            /*
             * Build Visual Trace Html
             */
            $tags = array(
// '{CLASS}' => ($props['class'] !== '') ? $props['class'] : $not_available_text,
                // '{METHOD}' => ($props['method'] !== '') ? $props['method'] : $not_available_text,
                '{TRACE}' => $arr_trace['visual_backtrace'],
                '{EXPAND_TEXT}' => 'Visual Backtrace',
                '{COLLAPSE_TEXT}' => 'Hide Visual Backtrace',
                '{METHOD}' => $props['method']
            );
            $template = $this->plugin()->tools()->scrubHtmlWhitespace($template);
            $visual_backtrace_html = str_replace(array_keys($tags), array_values($tags), $template);
        }
        $content = $non_visual_backtrace_html . $visual_backtrace_html;

        return $content;

        //  $this->_log($content, $props, $use_prefix = false, $target = 'browser', $type = 'trace');
    }

    /**
     * Get Backtrace
     *
     * Trace internal function , intended to be called from a wrapper. Outputs the callstack of a method
     *
     * @param array $ds_properties The returned properties from _getDebugStatementProperties
     * @param boolean $force_output Whether to always display regardless of filter settings
     * @param int $levels The number of levels of the call stack to show. 0 to show all
     * @return array , 'backtrace' is html output , 'visual_backtrace' is the output from graphviz if enabled.
     */
    private function _getBacktrace($arr_btrace, $levels = 1) {








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

            /*
             * get properties of the current method
             *
             */
            $props = $this->_getMethodProperties(array_slice($backtrace_array, $key, 2), $reverse = true);

            /*
             * shift to the right for each level of the trace
             */
            static $toggle = true; //tracks current state of toggle.
            $toggle = !$toggle && true; //flips toggle state each time this line is executed


            $shift_counter++;  //tracks how many times we shift so we can calculate the margin

            $margin = $shift_counter * 5;






            $debug_trace_html_template = '<div style="margin-top:5px;margin-left:{MARGIN}px">

{METHOD_HEADER}

            </div>';


            $debug_trace_html_template = $this->plugin()->tools()->scrubHtmlWhitespace($debug_trace_html_template); //this is necessary since there are pre tags in the source . You could just remove it manually using a macro in a text editor , like the 'remove unnecessary whitespace' utility in notepad++ , but using scrubHtmlWhitespace allows us to retain the whitespace in our source file so its human readable, while still removing it when its displayed.
            /*
             * Now populate the html template
             */

            $tags = array(
                '{MARGIN}' => $margin,
                '{BACKGROUND_COLOR}' => $background_color,
                '{COUNTER}' => $counter,
                '{METHOD_HEADER}' => $this->_getMethodLabel($props)
            );


            $current_trace_html = str_replace(array_keys($tags), array_values($tags), $debug_trace_html_template);


            /*
             * Exclude uninteresting internal  functions from trace so as to
             * make the trace cleaner
             */
            if ($this->_inExcludedFilter($props['method'])) {

                continue; /* dont show the functions in $function_exclusion_filter array since they are internal and not interesting */
            }

            /*
             * Build Visual Backtrace Array
             * This builds a traces array that is used by the getVisualBacktrace method
             * Classes are saved as keys to ensure uniqueness
             * and to preserve order
             * Methods are saved as class and function elements
             */

            $traces['classes'][] = $props['class'];

            $traces['methods'][] = array(
                'class' => $props['class'],
                'function' => $props['method']
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

        $backtrace = implode('', $traces_html);
        $result['backtrace'] = $backtrace;
        $result['visual_backtrace'] = $visual_backtrace;

        return $result;
    }

    /**
     * Get Method Properties
     *
     * Returns a list of properties describing the method or standalone function.
     * @param array $array_backtrace A backtrace array as returned by debug_backtrace or $this->debug_backtrace()
     * @param $reverse Whether the array_backtrace is a reverse backtrace so we know which way to traverse the backtrace array
     * @return void
     */
    private function _getMethodProperties($array_backtrace = null, $reverse = false) {

        if ($reverse) {
            $start = 0;
            $caller = -1;
        } else {
            $start = 0;
            $caller = 1;
        }
        $props_backtrace = array();

        if (is_null($array_backtrace)) {
            $array_backtrace = debug_backtrace();
            array_shift($array_backtrace); //removes the current method
            $props_backtrace['ds_line'] = (isset($array_backtrace[$start]['line']) ? $array_backtrace[$start]['line'] : null);

            /*
             * removes the debug statement
             */
            array_shift($array_backtrace);
        } else {

            $props_backtrace['ds_line'] = null;
        }



        //
#init
        $props = array();

        $arg_string = '';
        $expanded_args = '';

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
        } else {
            $props['args'] = array();
        }


        /*
         * Build the argument string
         */

        foreach ($props['args'] as $var_name => $var_value) {

            $expanded_args .= '<br/>' . $this->_logVar('$' . $var_name . ' = ', $var_value, true);
        }
        $props['signature'] = $props_refl['method'] . '<br>(' . $expanded_args . '<br>)';

        $props['signature_simple'] = (is_array($props_refl['arg_names']) && !empty($props_refl['arg_names'])) ? $props_refl['method'] . '($' . implode(',$', $props_refl['arg_names']) . ')' : $props_refl['method'] . '()'; //function name with argument names

        /*
         * Set defaults for returned array
         */
        $defaults = array(
            'native' => null,
            'file' => null, //the file that contains the method
            'ds_line' => null,
            'line' => null, //the line at which the method declaration appears
            'end_line' => null,
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
        $props['file'] = $props_refl['file'];
        $props['end_line'] = $props_refl['end_line'];
        $props['line'] = (string) $props_refl['line']; //casting necessary so trim works on string test
        $props['class'] = $props_refl['class'];
        $props['ds_line'] = $props_backtrace['ds_line'];
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
        $props = $this->plugin()->tools()->screenDefaults($defaults, $props);


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
                $comment = $reflFunc->getDocComment();
                $line = $reflFunc->getStartLine();
                $file = $reflFunc->getFileName();
                $end_line = $reflFunc->getEndLine();
            } catch (Exception $exc) {
                /*
                 * need to catch fatal exceptions caused by language constructs that cant be found by php
                 */
                $error_message = $exc->getMessage();

                if (stripos($error_message, 'does not exist') !== false) {

                    $line = null;
                    $end_line = null;
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


            $comment = $reflMethod->getDocComment();
            /*
             * Method $line and $end_line
             */
            $line = $reflMethod->getStartLine();
            $end_line = $reflMethod->getEndLine();


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
            'line' => null, //the line number where the function is located
            'end_line' => null, //the line number where the function ends
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
        $props['end_line'] = (string) $end_line; //casting necessary so trim works on string test
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
        $props = $this->plugin()->tools()->screenDefaults($defaults, $props);

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
     * Get Prefix
     *
     * Gets the log output prefix as defined by the options
     *
     * @param none
     * @return void
     */
    private function _getPrefix($props, $target) {

        $prefix = '';




        if ($this->getOption($target . '_prefix_enabled')) {
            $template = $this->getOption($target . '_prefix_template');
            $tags = $props;
            $tags['time'] = date($this->getOption('prefix_time_format'));
            $tags['plugin_slug'] = $this->plugin()->getSlug();

            $prefix = $this->plugin()->tools()->crunchTpl($tags, $template); //str_ireplace(array_keys($tags), array_values($tags), $template);
        }

        return $prefix;
    }

    protected $_footer_log_handle = null;

    /**
     * Print to Browser
     *
     * Prints a log entry inline and to the footer file
     *
     * @param array $log_entry The log entry
     * @return void
     */
    private function _printToBrowser($log_entry) {

        /*
         * Print to Browser
         */

        /*
         * format first
         */
        if ($this->getOption('output_to_inline') || $this->getOption('output_to_footer')) {
            $browser_output = $this->_formatForBrowser($log_entry);
        }

        /*
         * Output Inline
         */
        if ($this->getOption('output_to_inline')) {
            /* must wrap in div or layout will look like garbage  */
            echo '<div>' . $browser_output . '</div>';
        }
        /*
         * Output to footer using a temporary file which saves memory by not having to save to an array
         */

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

            fwrite($footer_log_handle, '<br>' . $browser_output);
        }
    }

    protected $_console_log;

    /**
     * Print to Console
     *
     * Prints a log entry inline and to the console file
     *
     * @param array $log_entry The log entry
     * @return void
     */
    private function _printToConsole($log_entry) {

        /*
         * Print to Console File
         */

        $props = $log_entry['props'];


        if ($this->getOption('output_to_console')) {


            if ($log_entry['use_prefix']) {

                /*
                 * add prefix
                 */

                $prefix = $this->_getPrefix($props, 'console');
                $log_entry['content'] = $prefix . $log_entry['content'];
            }



            if ($log_entry['type'] === 'error') {

                $error_template = $this->getOption('error_template');

                $tags['PLUGIN_SLUG'] = $this->plugin()->getSlug();
                $tags['ERROR_MESSAGE'] = $log_entry['content'];
                $tags = array_merge($tags, $props);
                $log_entry['content'] = $this->plugin()->tools()->crunchTPL($tags, $error_template);
            }

            /*
             * First, convert any new lines introduced by the whitespaces in the source code to <br/> tags
             */

            $log_entry['content'] = $this->plugin()->tools()->nl2br(($log_entry['content'])); //need to do this to protect against the source code introducing new lines to output strings, which breaks console.log


            /*
             * Then Convert to text, preserve newlines as a tag {NEW_LINE} that will be replaced later when printed to console.
             */
            $log_entry['content'] = $this->plugin()->tools()->html2text(($log_entry['content']), '{NEW_LINE}');




            $this->_console_log[] = $log_entry;



//            /*
//             * take same output that was echoed and save it to a temporary file that will be included at
//             * upon script shutdown
//             */
//            if (is_null($this->_console_log_handle)) {
//                $this->_console_log_handle = tmpfile(); //create a temporary file
//            }
//            $footer_log_handle = $this->_console_log_handle;
//            /*
//             * write the string to it
//             */
//
//            fwrite($this->_footer_log_handle, '<br>' . $browser_output);
        }
    }

    /**
     * Print to File
     *
     * Prints a log entry inline and to the console file
     *
     * @param array $log_entry The log entry
     * @return void
     */
    private function _printToFile($log_entry) {

        static $append = false;

        $props = $log_entry['props'];

        if ($this->getOption('output_to_file')) {

            $log_file_path = $this->getOption('log_file_path');

            if ($log_entry['use_prefix']) {

                /*
                 * add prefix
                 */

                $prefix = $this->_getPrefix($props, 'file');
                $log_entry['content'] = $prefix . ' ' . $log_entry['content'];
            }
            if ($log_entry['type'] === 'error') {

                $error_template = $this->getOption('error_template');

                $tags['PLUGIN_SLUG'] = $this->plugin()->getSlug();
                $tags['ERROR_MESSAGE'] = $log_entry['content'];
                $tags = array_merge($tags, $props);
                $log_entry['content'] = $this->plugin()->tools()->crunchTPL($tags, $error_template);
            }


            /*
             * Convert to text to strip out html tags but preserve new lines
             */
            $log_entry['content'] = $this->plugin()->tools()->html2text(($log_entry['content']));




            /*
             * write the string to the file
             * Create a new file the first time this method is executed,
             *  as tracked by the static var $append
             */
            if (!$append) {
                file_put_contents($log_file_path, $log_entry['content']); //, FILE_APPEND);
                $append = true;
            } else {
                file_put_contents($log_file_path, "\n" . $log_entry['content'], FILE_APPEND);
            }
        }
    }

    /**
     * log
     *
     * Adds a log entry to the log or outputs it to browser if inline debugging is on
     *
     * @param string $line Line of the debug statement
     * @param string $class Class containing the debug statement
     * @param string $method Method or function containing the debug statement
     * @param string $file File path containing the debug statement
     * @param boolean $use_prefix Whether to use a prefix ( a line number template)
     * @param string $target  'all','browser','console','file'
     * @return void
     */
    protected function _log($content, $props, $use_prefix = true, $target = 'browser', $type = 'info') {





        /*
         * If logging disabled, then let only errors and traces through
         */
        if (!$this->getOption('logging_enabled')) {
            if ($type !== 'trace' || 'error' !== $type) {
                return;
            }
        }


        $log_entry = compact('props', 'content', 'use_prefix', 'target', 'type');

        /*
         * turn the target into an array if not already
         */
        if (!is_array($log_entry['target'])) {
            $log_entry['target'] = array($log_entry['target']);
        }

        foreach ($log_entry['target'] as $target) {


            switch ($target) {

                case 'browser':

                    if ($this->getOption('output_to_inline') || $this->getOption('output_to_footer')) {
                        $this->_printToBrowser($log_entry);
                    }
                    break;
                case 'file':
                    if ($this->getOption('output_to_file')) {
                        $this->_printToFile($log_entry);
                    }
                    break;
                case 'console':
                    if ($this->getOption('output_to_console')) {

                        $this->_printToConsole($log_entry);
                    }
                    break;

                case 'all':
                    if ($this->getOption('output_to_inline') || $this->getOption('output_to_footer')) {
                        $this->_printToBrowser($log_entry);
                    }
                    if ($this->getOption('output_to_file')) {
                        $this->_printToFile($log_entry);
                    }
                    if ($this->getOption('output_to_console')) {

                        $this->_printToConsole($log_entry);
                    }
                    break;
            }
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
            'content' => null,
            'props' => null,
            'use_prefix' => true,
            'target' => null,
            'type' => null
        );

        $log_entry = $this->plugin()->tools()->screenDefaults($defaults, $log_entry);

        $props = $log_entry['props'];
        $prefix = '';


        if ($log_entry['use_prefix'] === true) {


            $prefix = $this->_getPrefix($props, 'browser');
        }


        if ($log_entry['type'] === 'error') {

            $error_template = $this->getOption('error_template');

            $tags['PLUGIN_SLUG'] = $this->plugin()->getSlug();
            $tags['ERROR_MESSAGE'] = $log_entry['content'];
            $tags = array_merge($tags, $props);
            $log_entry['content'] = $this->plugin()->tools()->crunchTPL($tags, $error_template);
        }







        $indent = $this->_getTraceLevel($props, $this->_trace_props_previous);


        $margin = $indent * 50;





        /*
         * Trace previous properties so
         * we can compare to see if we need to
         * adjust margins
         */
        $this->_trace_props_previous = $props;

        /*
         * Here, we are returning either the trace block,
         * or the log entry, depending on what kind of log we are processing
         * The {TRACE} is provided by the _getBacktrace() method which logs its output.
         */

        $trace_block_template = '<div style="background-color:{BACKGROUND_COLOR};display:inline-block;border: 1px solid grey;margin:5px 0 5px;margin-left:{MARGIN}px">
                <div>{METHOD_LABEL}</div><div>{TRACE}</div>

            </div>';


        $log_content_template = '<div  style="padding:5px;background-color:{BACKGROUND_COLOR};display:inline-block;border;border: 1px solid grey;margin:5px 0 5px;margin-left:{MARGIN}px">
            {PREFIX}{CONTENT}
        </div>';

        $tags = array(
            'BACKGROUND_COLOR' => $this->_getTraceColor($indent),
            'MARGIN' => $margin,
            'PREFIX' => $prefix,
            'CONTENT' => $log_entry['content'],
            'TRACE' => ($log_entry['type'] === 'trace') ? $log_entry['content'] : '',
            'METHOD_LABEL' => $this->_getMethodLabel($props),
        );

        if ($log_entry['type'] === 'trace') {
            $result = $this->plugin()->tools()->crunchTpl($tags, $trace_block_template);
        } else {

            $result = $this->plugin()->tools()->crunchTpl($tags, $log_content_template);
        }

        return $result;
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
    public function setMethodFilter($name, $enabled) {
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
        if (!$this->getOption('function_exclusion_filter_enabled')) {
            return false;
        }
        /*
         * otherwise, check whether its in the filter
         */
        $function_exclusion_filter = $this->getOption('function_exclusion_filter');

        if (in_array($method, $function_exclusion_filter)) {
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
    protected function _inFilters($props, $log_type, $force_output) {

//$args = func_get_args();
        #init
        /*
         * combine class and method so we can search on the combined namespace
         */
        $class_method = $props['class'] . '::' . $props['method'];

        /*
         * dont debug if debugging is off
         */

        if ($this->isOff()) {

            return false;
        }

        /*
         * Show only posted form debugging if enabled
         */
        if ($this->getOption('debug_post_only') === true) {

            if (!isset($_POST) || empty($_POST)) {

                return false;
            }
        }

        /*
         * Do not do any logging if ajax request
         * AJAX check
         */
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') { //if isAjax()
            if (!$this->getOption('ajax_debugging_enabled')) { //then if ajax is not enabled, return false
                return false;
            };
            /* special ajax here */
        } else {

            if ($this->getOption('ajax_debugging_only')) { //if not ajax, but ajax_debugging_only is true, return false.
                return false;
            };
        }
        /*
         * Always Show Errors
         */
        if ($this->getOption('always_show_errors')) {
            if ($log_type === 'error') {
                return true;
            }
        }

        /*
         * If Method Filters are disabled,
         * then we can return true since all module output is allowed
         *
         *
         */
        if (!$this->getOption('method_filters_enabled')) {
            return true;
        }

        /*
         * Debug if always_debug is set to true
         */
        if ($force_output === true) {
            return true;
        }

        /*
         * Debug if in filters, otherwise, dont
         */
        $filters = $this->getFilters();

        if (in_array($props['class'], array_keys($filters['enabled']))) {
            return(true);
        }

        if (in_array($props['method'], array_keys($filters['enabled']))) {
            return(true);
        }


        if (in_array($class_method, array_keys($filters['enabled']))) {
            return(true);
        }


        /*
         * Regex Filter Check
         *
         * Because we allo regex patterns, we'll
         * do a final search to see if any of the filters represents a
         * regex that matches either the class or the method.
         *
         *
         */
        //  echo '<pre>', print_r($filters, true), '</pre>';
        foreach (array_keys($filters['enabled']) as $filter_regex_pattern) {


            if (preg_match("/" . $filter_regex_pattern . "/", $props['class']) === 1) {
                return true;
            }

            if (preg_match("/" . $filter_regex_pattern . "/", $props['method']) === 1) {
                return true;
            }
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

        $is_graphviz_enabled = $this->debug()->getOption('visual_backtrace_enabled');

        if ($is_graphviz_enabled) {

            $graphviz_include_path = $this->getOption('graphviz_include_path');

            /*
             * check if file is in include path before attempting to include it
             */
//if not include path, return a message to use
            if ($this->plugin()->tools()->inIncludePath($graphviz_include_path)) {
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
     * @param mixed $option_name
     * @return mixed The default value of the option
     */
    private function _getDefaultOption($option_name) {

        if (is_null($this->_default_options)) {
            $this->_setDefaultOptions();
        }
        if (!isset($this->_default_options[$option_name])) {
            return null;
        }
        return $this->_default_options[$option_name];
    }

    /**
     * Set Default Option
     *
     * Sets a default option value
     *
     * @param string $option_name The name of the option
     * @param string $option_value The value of the the option
     * @return void
     */
    private function _setDefaultOption($option_name, $option_value) {
        $this->_default_options[$option_name] = $option_value;
    }

    private $_default_options = null;

    /**
     * Set Default Options
     *
     * Sets all the default options
     *
     * @param none
     * @return void
     */
    private function _setDefaultOptions() {




        /*
         * Trace Enabled
         *
         * Outputs information about each called method or function, including a link
         * to view a backtrace and a visual backtrace.
         * A method will show only if within the filter and if the $this->debug()->t() method is called.
         * Because trace can produce a lot of output, you can disable it if all you
         * are interested in is logs.
         */
        $this->_setDefaultOption('trace_enabled', true);

        /*
         * Trace Output Format
         *
         * Indicates which trace template to use
         * options are 'normal'(default), 'text' and 'simple'
         */

        $this->_setDefaultOption('trace_output_format', 'normal');

        /*
         * Backtrace Enabled
         * Whether to provide a backtrace within
         * the trace html
         */

        $this->debug()->_setDefaultOption('backtrace_enabled', true);


        /*
         * Visual Backtrace Enabled
         *
         *  Graphiviz Visualization of the debug trace
         * Requires both the PEAR Image_GraphViz module
         * and graphviz binary to be installed using either
         * a php extension or the windows installation package
         */

        $this->_setDefaultOption('visual_backtrace_enabled', false);

        /*
         * Logging Enabled
         *
         * Enabled Logging
         */

        $this->_setDefaultOption('logging_enabled', true);



        /*
         * Defined Variables Enabled
         *
         * Outputs the variables defined in a function for each method that has a logVars() call
         */


        $this->_setDefaultOption('defined_vars_enabled', true);



        /*
         *
         * Log All Actions - will output every hook action - generates a ton of output.
         * Can be filtered using the action_inclusion_filter and action_exclusion_filter
         * true/false
         */


        $this->_setDefaultOption('log_all_actions', false);

        /*
         * Method Filters Enabled
         * False ignores all setMethodFilter settings, allowing
         * all debug statements to be sent to output
         */

        $this->_setDefaultOption('method_filters_enabled', true);


        /*
         * Output To Inline
         * Whether to echo debug output to the browser as it is logged
         */

        $this->_setDefaultOption('output_to_inline', false);


        /*
         * Always Show LogErrors
         *  regardless of filtering
         */

        $this->_setDefaultOption('always_show_errors', true);

        /*
         * Error Template
         *
         */

        $this->_setDefaultOption('error_template', '<div ><em style="color:red;"> Error ( Plugin {PLUGIN_SLUG} ) </em> {ERROR_MESSAGE}  <p>Calling method : {CALLING_CLASS}::{CALLING_METHOD}() </p>on Line {CALLING_LINE} in file {CALLING_FILE}</div>');




        /*
         * Output To Footer
         * Whether to echo debug output to the browser's
         *  footer at the script's shutdown
         */

        $this->_setDefaultOption('output_to_footer', true);



        /*
         * Output To File
         * Whether to echo debug output to a file
         */

        $this->_setDefaultOption('output_to_file', false);


        /*
         * Output to Console
         * Whether to echo debug output to the javascript console
         */

        $this->_setDefaultOption('output_to_console', false);

        /*
         * Enable or disable the Excluded Functions Filter
         */
        /*
         * Expand On Click
         *
         * When enabled, hides certain debug output in an attempt to make the output easier to read.
         * The outout is hidden behind links , that when clicked, will show it.
         * Typical debug output thatis hidden are: arrays, backtraces, visual traces, and objects.
         * Occassionally, you may be troubleshooting code that breaks javascript or breaks before
         * the javascript is loaded. IN this case, the expansion wont work, and you'll need to set
         * this paramater to false while you troubleshoot. With it set to false, all output is shown on
         * initial display, without requiring a click.
         *
         */
        $this->_setDefaultOption('expand_on_click', true);

        $this->_setDefaultOption('function_exclusion_filter_enabled', true);

        /*
         *
         * Excluded Functions
         * Exclude these functions from traces
         */


        $this->_setDefaultOption('function_exclusion_filter', array(
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
        ));




        /*
         * Enable or disable the Actions Exclusion Filter
         */

        $this->_setDefaultOption('action_exclusion_filter_enabled', true);

        /*
         * Action Exclusion Filter
         * Excluded Actions
         * Exclude these actions from log all actions
         */

        $this->_setDefaultOption('action_exclusion_filter', array(
            'gettext',
            'gettext_with_context',
            'sanitize_key',
            'attribute_escape',
            'get_user_metadata',
            'no_texturize_tags',
            'no_texturize_shortcodes',
            'user_has_cap',
            'clean_url',
            'wp_parse_str',
            'sanitize_html_class',
            'map_meta_cap',
            'load_textdomain',
            'load_textdomain_mofile',
            'override_load_textdomain'

                /*
                 *
                 * load-mint-forms_page_mint-forms-edit
                 * admin_print_styles-mint-forms_page_mint-forms-edit
                 * admin_print_scripts-mint-forms_page_mint-forms-edit
                 * admin_head-mint-forms_page_mint-forms-edit
                 * mint-forms_page_mint-forms-edit
                 * screen_settings
                 * screen_options_show_screen (WP_Screen Object)
                 * current_screen
                 */
                )
        );


        /*
         * Enable or disable the Actions Inclusion Filter
         */

        $this->_setDefaultOption('action_inclusion_filter_enabled', false);

        /*
         * Action Inclusion Filter
         * Log only those actions listed in the filter when enabled
         * Default is empty
         */


        $this->_setDefaultOption('action_inclusion_filter', array(
        ));






        /*
         * Log File Location
         */


        $this->_setDefaultOption('log_file_path', $this->plugin()->getDirectory() . '/debug.log.txt');


        /* Ajax Debugging
         * whether to allow for debugging during an ajax call
         * Ajax output may interfere with javascript and cause errors, so
         * we keep this false by default
         */
        $this->_setDefaultOption('ajax_debugging_enabled', false);

        /* Ajax Debugging Only
         * Whether to filter all output except if the request is an ajax request.
         */
        $this->_setDefaultOption('ajax_debugging_only', false);

        /* Debug Posted Forms Only
         *
         * Whether to filter all output except if a form has been posted
         */
        $this->_setDefaultOption('debug_post_only', false);

        /*
         * Demo Enabled
         */
        $this->_setDefaultOption('demo_enabled', true);


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


        $this->_setDefaultOption('show_arrays', true);
        $this->_setDefaultOption('show_objects', false);

        /*
         * Graphiviz Include Path
         */

        $this->_setDefaultOption('graphviz_include_path', 'Image/GraphViz.php');

        /*
         * Templates
         */


        /*
         * Text Method Templates can use the following tags
         *   {NATIVE} Whether a method is native to PHP
          {FILE} //the file that contains the method
          {LINE} //the line at which the method declaration appears
          {CLASS} //the class that contains the method
          {METHOD}//the method name
          {COMMENT}//the method comment from source
          {ARGS}/ //an array of the arguments. must contain values passed to the function. may or may not contain the argument names.
          {CALLING_METHOD} //the method that preceeds this method in the call stack
          {CALLING_CLASS} // the class of the calling method
          {CALLING_FILE}  // the file of the calling method
          {CALLING_LINE}  // the line at which the calling method called this function
          {SIGNATURE}  // A string that includes the Method name, followed by parens which include the name *and* value pairs of each of the arguments, separated by a comma
          {SIGNATURE_SIMPLE} // same as signature but doesnt include vales of the arguments
         *
         */
        $this->_setDefaultOption('method_label_template_text', '{CLASS}::{METHOD}([{ARGS}])<div style="font-size:xx-small"><em>called from:{CALLING_CLASS}::{CALLING_METHOD}</em></div>');



        /*
         * Simple and Normal Templates Can use the following tags:
         * {LOCATION}
         * {CALLING_LOCATION}
         * {LINE}
         * {FILE}
         * {CLASS}
         * {METHOD_SIG}
         * {METHOD_SIG_SIMPLE}
         * {METHOD}
         * {EXPANDED_ARGS}
         * {COMMENT}
         * {CALLING_LINE}
         * {CALLING_FILE}
         * {CALLING_CLASS}
         * {CALLING_METHOD}
         *
         */

        $this->_setDefaultOption('method_label_template_normal', '<div style="padding:5px;border:1px solid grey;display: inline-block;">
                <strong>{CLASS}::{METHOD_SIG}</strong>
<span><em style="font-size:x-small">called from: {CALLING_CLASS}::{CALLING_METHOD}</em><br/></span>

                <a  class="simpli_debug_citem " href="#"><span>More</span><span style="visibility:hidden;display:none">Less</span></a>



                <div  class="simpli_debug_toggle" style="display:none;visibility:hidden;padding:0px;margin:0px;"><!-- Collapsable -->

                    <div style = "display:block;margin:10px 0px 10px 0px;"> <strong style = "font-size:x-large">Class </strong></div>
                    <div style = "margin:10px 0px 10px 20px;"> <p style = "font-size:medium"><em>{CLASS}</em></p></div>
                    <div style = "margin:10px 0px 10px 0px;"> <strong style = "font-size:x-large">Method</strong></div>

<div style = "margin:10px 0px 10px 20px;"> <p style = "font-size:medium"><em>{METHOD_SIG_SIMPLE}</em></p></div>
 <div style = "margin:10px 0px 10px 0px;"> <strong style = "font-size:x-large">Description</strong></div>
                    <div style = "margin:10px 0px 10px 20px;"> <p style = "font-size:medium">{DESCRIPTION}</p></div>
 <div style = "margin:10px 0px 10px 0px;"> <strong style = "font-size:x-large">Location</strong></div>
                  <div style = "margin:10px 0px 10px 20px;"> <p style = "font-size:medium">{LOCATION}</p></div>

<div style = "margin:10px 0px 10px 0px;"> <strong style = "font-size:x-large">Called From</strong></div>
<div style = "margin:10px 0px 10px 20px;"> <p style = "font-size:medium">{CALLING_LOCATION}</p></div>




                </div>

       <!-- Source Follows -->
       <div style="padding:5px;display:inline;">


                <a  class="simpli_debug_citem " href="#"><span>Show Source</span><span style="visibility:hidden;display:none">Hide Source</span></a>



                <div  class="simpli_debug_toggle" style="background-color:#EFEBEF;display:none;visibility:hidden;padding:0px;margin:0px;"><!-- Collapsable -->

                    {SOURCE}




                </div>
            </div>
 </div>


');


        /*
         * simple trace template.
         * Can use the following tags:
         *
         */

        $this->_setDefaultOption('method_label_template_simple', '<div style="padding:5px;border:1px solid grey;display: inline-block;">
                <strong>{CLASS}::{METHOD_SIG}</strong>

            </div>');


        /*
         * Backtrace Template
         */

        $template = $this->_setDefaultOption('backtrace_template_expand_on_click', '<div style="padding:5px;text-align:left;display: inline-block;">
 <strong style = "font-size:medium"></strong> <em "font-size:small"></em>
<a class="simpli_debug_citem" href="#"><span><em>{EXPAND_TEXT}</em></span><span style="visibility:hidden;display:none"><em>{COLLAPSE_TEXT}</em></span></a>



                <div  class="simpli_debug_toggle" style="display:none;visibility:hidden;padding:0px;margin:0px;"><!-- Collapsable -->




                    <p > {TRACE}</p>


</div>
</div>
'
        );
        $template = $this->_setDefaultOption('backtrace_template_show_without_click', '<div style="padding:5px;text-align:left;display: inline-block;">
 <strong style = "font-size:medium"></strong> <em "font-size:small"></em>



                <div  class="simpli_debug_toggle" style="padding:0px;margin:0px;">




                    <p > {TRACE}</p>


</div>
</div>'
        );

        /*
         * Trace Colors
         * Colors used for each level of the trace. colors for levels above 9 will repeat from 0-9
         * Ref:http://www.wingsnw.com/colorbb.html
         */
        $this->_setDefaultOption('trace_colors', array(
            0 => '#D8E4F1',
            1 => '#F1C68C',
            2 => '#f1e7d0',
            3 => '#eeffcc',
            4 => '#88ffbb',
            5 => '#00ffcc',
            6 => '#ccbb66',
            7 => '#eebb77',
            8 => '#aa9900',
            9 => '#ffaa00'
                )
        );

        /*
         * Prefix Templates
         *
         * available tags:
         * {PLUGIN_SLUG}
         * {TIME}
         * any item in the $props array

         */
        $this->_setDefaultOption('browser_prefix_enabled', true);
        $this->_setDefaultOption('console_prefix_enabled', true);
        $this->_setDefaultOption('file_prefix_enabled', true);
        $this->_setDefaultOption('browser_prefix_template', '<div style="font-size:small">{CLASS}::{METHOD}()/{DS_LINE}&nbsp;     </div><div style="font-size:x-small"><em>(called from : {CALLING_CLASS}::{CALLING_METHOD}(), line {CALLING_LINE}</em>)</div><br/>');
        $this->_setDefaultOption('console_prefix_template', '{TIME} | {CLASS}->{METHOD}() | {PLUGIN_SLUG} : ');
        $this->_setDefaultOption('file_prefix_template', '{TIME} {METHOD}/{LINE}');
        $this->_setDefaultOption('prefix_time_format', 'Y-m-d H:i:s');
    }

    /**
     * Get Trace Color
     *
     * Returns the color associated with the current trace level
     *
     * @param int Level of trace, as returned by _getTraceLevel()
     * @return string Hexadecimal color code
     */
    private function _getTraceColor($trace_level) {


        $colors = $this->getOption('trace_colors');

        /*
         * Normalize to 0-9 if level is above 9
         * this will use colors 0-9 in order,
         * for each level 10 and above
         */
        if ($trace_level > 9) {

            $trace_level = (int) substr($trace_level, -1, 1);
        }

        return $colors[$trace_level];
    }

    /**
     * Get Stack Trace Level
     *
     * Returns the current trace level, starting from 0 and incrementing by 1
     *
     * @param string $props The current properties associated with the method as returned by getMethodProperties()
     * @param string $props_previous The previous method's properties in the trace
     *
     * @return void
     */
    private function _getTraceLevel($props, $props_previous) {


        /*
         * if this is the root caller, the indent is 0
         */
        if ($props_previous === null) {
            $indent = 0;
            $this->_trace_indents[$props['class']][$props['method']] = $indent;
            $props_previous = $props;
            return $indent;
        }
//
//        if (isset($this->_trace_indents[$props['calling_class']][$props['calling_method']])) {
//            $indent = $this->trace_indents[$props['calling_class']][$props['calling_method']] + 1;
//         //   $indent=1;
//           // $last_indent = $this->_trace_indents[$props['class']][$props['method']];
////            if ($last_indent['calling_class'] === $props['calling_class'] && $last_indent['calling_method'] === $props['calling_method']) {
////                $indent = $last_indent['indent'];
////            } else {
////                $indent = $this->trace_indents[$last_indent['calling_class']][$last_indent['calling_method']]['indent'] + 1;
////
////                $this->_trace_indents[$props['class']][$props['method']]['indent'] = $indent;
////                $this->_trace_indents[$props['class']][$props['method']]['calling_class'] = $props['calling_class'];
////                $this->_trace_indents[$props['class']][$props['method']]['calling_method'] = $props['calling_method'];
////            }
////            $this->_trace_indents[$props['class']][$props['method']] = array(
////                'calling_class' => $props['calling_class'],
////                'calling_method' => $props['calling_method'],
////                'indent' => $indent0);
//            $this->_trace_indents[$props['class']][$props['method']]=$indent;
//
//        } else {
////            $this->_trace_indents[$props['class']][$props['method']] = array(
////                'calling_class' => $props['calling_class'],
////                'calling_method' => $props['calling_method'],
////                'indent' => 0);
//             $indent = 1;
//            $this->_trace_indents[$props['class']][$props['method']]=$indent;
//
//
//        }
//echo '<br><br><br>Indents<pre>'.print_r($this->_trace_indents,true).'</pre>';
//        return $indent;
//        $this->_trace_indents[$props['class']][$props['method']] = array(
//            'calling_class' => $props['calling_class'],
//            'calling_method' => $props['calling_method'],
//            'indent' => $indent,
//        );



        $caller_is_previous_method = ($props['calling_class'] === $props_previous['class']) && (($props['calling_method'] === $props_previous['method']));
        $caller_is_previous_caller = ($props['calling_class'] === $props_previous['calling_class']) && (($props['method'] === $props_previous['method']));

        $previous_method_indent = (isset($this->_trace_indents[$props_previous['class']][$props_previous['method']]) ? $this->_trace_indents[$props_previous['class']][$props_previous['method']] : 0);
        $calling_method_indent = (isset($this->_trace_indents[$props['calling_class']][$props['calling_method']]) ? $this->_trace_indents[$props['calling_class']][$props['calling_method']] : 0);


        /*
         * if calling method is the same as the previous method, indent is the calling method's indent + 1
         *
         */



        if ($caller_is_previous_method) { //yes
            $indent = $calling_method_indent + 1;
        } else { //no
            if ($caller_is_previous_caller) { //yes
                $indent = $previous_method_indent;
            } else { //no
                if ($props['method'] === 'loadModule') {

                }
                $indent = $calling_method_indent + 1;
            }
        }

        if ($indent < 0) {
            $indent = 0;
        }

        /*
         * update indents
         */
        $this->_trace_indents[$props['class']][$props['method']] = $indent;
        $props_previous = $props;
        return ($indent);
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

        if (is_null($this->_footer_log_handle)) {
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
     * Get Console Log
     *
     * @param none
     * @return string
     */
    private function _getConsoleLog() {

        if (is_null($this->_console_log)) {
            return array();
        }

        return $this->_console_log;
    }

    /**
     * Hook - Print Log to Console
     *
     * Prints Local Vars to the Javascript Console
     * @param string $content The shortcode content
     * @return string The parsed output of the form body tag
     */
    function hookPrintLogToConsole() {

        /*
         * Some browsers will crash if you use console.log in your code and not have the developer tools open at the same time. Use the below code snippet at the top of your code:

          if(!window.console){ window.console = {log: function(){} }; }
         * ref:http://stackoverflow.com/questions/4743730/what-is-console-log-and-how-do-i-use-it
         * http://blog.patspam.com/2009/the-curse-of-consolelog
         *
         *
         */




        $log = $this->_getConsoleLog();
        if (empty($log)) {
            return;
        }
        $console_log_statements = '';
        foreach ($log as $log_entry) {
            $log_text = $log_entry['content'];

            $log_text = "'" . addslashes($log_text) . "'";


            if ($log_entry['type'] === 'info') {

                $console_log_statements .= "\t\tconsole.log(" . $log_text . ");\n";
            } elseif ($log_entry['type'] === 'error') {

                $console_log_statements .= "\t\tconsole.error(" . $log_text . ");\n";
            }
        }


        $template = '
            <script id="simpli_debug_console_log" type="text/javascript">




        {

{CONSOLE_LOG_STATEMENTS}
}
    </script>
';
        /*
         * New lines are tricky - most reliably, substitute at last minute, and only with single quotes, not double or yo will
         * receive syntax errors. instead of using <br> or \n\r in strings since to _logToConsole, instead use {NEW_LINE} and
         * this function will replace them with proper line breaks
         */
        $tags = array(
            '{CONSOLE_LOG_STATEMENTS}' => $console_log_statements,
            '{NEW_LINE}' => '\n\r'
        );

        $script = str_ireplace(array_keys($tags), array_values($tags), $template);
        echo $script;
        // echo htmlspecialchars($script);
    }

    /**
     * Debug - simply logs the current wordpress hook
     *
     * Logs the current wordpress hook to the debug output




     * - Attempt to determine if action or filter function by scraping admam brown's pages, and then output the proper function prototype
     * - create link direct to the version source
     * - extract the variable names from source and output them in the function prototype
     *
     *
     */
    public function hookLogAllActions() {
        $props = $this->_getMethodProperties();
        static $hook_count;
        $hook_count++;

        $message = '';
        $current_filter = current_filter();



        /*
         * dont log the actions that are in the exclusion filter
         * This allows us to ignore actions that are not of interest
         */
        if ($this->getOption('action_exclusion_filter_enabled')) {
            if (in_array($current_filter, $this->getOption('action_exclusion_filter'))) {

                return;
            }
        }


        /*
         * if an inclusion filter is enabled, only show those actions that are included
         * in the filter
         */
        if ($this->getOption('action_inclusion_filter_enabled')) {

            if (!in_array($current_filter, $this->getOption('action_inclusion_filter'))) {
                /* if didnt find it as a direct match,
                 * now check if pregmatch matches anything.
                 */
                $matches_inclusion_filter_regex_pattern = false;
                foreach ($this->getOption('action_inclusion_filter') as $filter_regex) {

                    if (preg_match("/" . $filter_regex . "/", $current_filter) === 1) {
                        $matches_inclusion_filter_regex_pattern = true;
                    }
                }

                if (!$matches_inclusion_filter_regex_pattern) {
                    return;
                }
            }
        }


        /*
         * dont debug if debugging is off
         */

        if ($this->isOff()) {

            return;
        }

        /*
         * Do not do any logging if ajax request
         * AJAX check
         */
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            /* special ajax here */
            return;
        }




        /*
         * Get an array of all the arguments sent to this function by the hook
         *
         */
        $args = func_get_args();


        /*
         * Now format the arguments passed by the hook
         */

        $args_list = '';

        foreach ($args as $key => $value) {
            $priority = '';
            $return = '';
            if ($key === 0) {
                continue;
            } else if ($key === 1) {
                $args_list.= '$arg' . $key;
            }
            else
                $args_list.= ',' . '$arg' . $key;
        }

        if (count($args) > 2) {

            $priority = ',10,' . (count($args) - 1);
            $return = 'return($arg1)';
        }

        /*
         * Define and populate the output template
         */
        $template = '<div style="padding:5px;background-color:#D8E4F1;border:thin solid grey">

<span>Hook #{HOOK_COUNT} = {CURRENT_FILTER} </span>
<p>
<a target="_top" href="{CODEX_URL}">codex</a> | <a href="{HOOK_SOURCE_URL}">source</a>
</p>
<p style="padding-left:5px">
<em>Add Hook Example: </em><br/>add_filter(\'{CURRENT_FILTER}\',\'my_function\'{PRIORITY});</p>
<p style="padding-left:5px">
<em>Hook Function Example: </em><br/>function my_function ({ARGS_LIST})<br/>{<br/>{RETURN}<br/>}</p>

        </div>

        <div style="background-color:#F1C68C;">
             <div style="color:green!important;border:thin grey solid;"> Arguments </div>
        {ARGUMENTS}
        </div>';

        $template = $this->plugin()->tools()->scrubHtmlWhitespace($template);
        $tags = array(
            '{HOOK_COUNT}' => $hook_count,
            '{CURRENT_FILTER}' => $current_filter,
            '{CODEX_URL}' => 'http://codex.wordpress.org/Plugin_API/Action_Reference/' . $args[0],
            '{HOOK_SOURCE_URL}' => 'http://adambrown.info/p/wp_hooks/hook/' . $args[0],
            '{CURRENT_FILTER}' => $current_filter,
            '{PRIORITY}' => $priority,
            '{ARGS_LIST}' => $args_list,
            '{RETURN}' => ($return !== '') ? '//your code here <br>' . $return . ';' : '//your code here',
            '{ARGUMENTS}' => '<pre>' . htmlspecialchars(print_r($args, true)) . '</pre>',
        );


        $content = str_ireplace(array_keys($tags), array_values($tags), $template);



        //  $this->_log($content, null, null, null, current_filter(), true, 'browser', 'info');
        $this->_log($content, $props, $use_prefix = true, $target = 'browser', $type = 'info');
    }

    /**
     * Get Method Source
     *
     * Get a method's source code
     *
     * @param string $class_name The name of the class
     * @param string $method_name The method or function name
     * @return void
     */
    private function _getSource($file_name, $start_line, $end_line, $comment = '') {



        /*
         * you must back up $start by 1 to get the proper location and length
         */
        $start_line = $start_line - 1;

        $length = $end_line - $start_line;


        $file_contents = file($file_name);

        /*
         * Verify file_contents is an array, otherwise we'll get errors
         */
        if (!is_array($file_contents)) {
            return '';
        }

        /*
         * create a template for the source code.
         */
        $template = '<?php {BREAK} {COMMENT} {BREAK} {METHOD} {BREAK} ?>';

        /*
         * first parse the template and populate the comment and source
         */
        $tags = array(
            'COMMENT' => $comment,
            'METHOD' => implode("", array_slice($file_contents, $start_line, $length))
        );
        $highlighted_source = highlight_string($this->plugin()->tools()->crunchTpl($tags, $template), true);
        /*
         * then insert the breaks to separate the comment from the source and the php tags
         */
        $tags = array(
            'BREAK' => '<br/>',
        );
        $highlighted_source = $this->plugin()->tools()->crunchTpl($tags, $highlighted_source);

        /*
         * return the highlighted code
         */
        return $highlighted_source;
    }

}

