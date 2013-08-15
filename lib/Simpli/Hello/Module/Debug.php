<?php

/**
 * Debug Module
 *
 * Debug Methods
 *
 * @author Andrew Druffner
 * @package SimpliFramework
 * @subpackage SimpliHello
 *
 */
class Simpli_Hello_Module_Debug extends Simpli_Basev1c0_Plugin_Module {

    /**
     * is Focused
     *
     * Checks to see if the trace has a focus on it ( if the focus link has been clicked)
     *
     * @param none
     * @return boolean
     */
    private function _isFocused() {
        if (isset($_GET[$this->QUERY_VAR_FOCUS_CLASS]) || isset($_GET[$this->QUERY_VAR_FOCUS_METHOD])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get Focused Class
     *
     * Returns the name of the focused class. If not in focus, returns null. If focused class is not set, returns an empty string.
     *
     * @param none
     * @return string The value of the $_GET variable that holds the focused class
     */
    private function _getFocusedClass() {

        if ($this->_isFocused()) {
            $result = (isset($_GET[$this->QUERY_VAR_FOCUS_CLASS]) ) ? ($_GET[$this->QUERY_VAR_FOCUS_CLASS]) : '';
        } else {
            $result = null;
        }
        return $result;
    }

    /**
     * Get Focused Method
     *
     * Returns the name of the focused method If not in focus, returns null.If focused method is not set, returns an empty string.
     *
     * @param none
     * @return string The value of the $_GET variable that holds the focused method
     */
    private function _getFocusedMethod() {
        if ($this->_isFocused()) {
            $result = (isset($_GET[$this->QUERY_VAR_FOCUS_METHOD]) ) ? ($_GET[$this->QUERY_VAR_FOCUS_METHOD]) : '';
        } else {
            $result = null;
        }
        return $result;
    }

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

        $this->setOption('FilterBypass', false); //true will bypasses all filters

        $this->debug()->setFilter('Simpli_Hello_Module_Admin', false);
        $this->debug()->setFilter('Simpli_Hello_Module_Core', false);
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

        $this->debug()->setOption('graphviz', true);





        /*
         * Browser Output
         * Where you want the output
         * 'footer' to output to footer, 'inline' to output at time of error , false for all output off
         */
        $this->setOption('browser_output', 'inline');

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


        register_shutdown_function(array($this, 'hookPrintMasterTraceOnShutdown'));
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
                'QUERY_VAR_FOCUS_CLASS' => 'simpli_debug_fc'
                , 'QUERY_VAR_FOCUS_METHOD' => 'simpli_debug_fm'
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
        $function = $properties['function'];
        $file = $properties['file'];

        if (!$this->_inFilters($class, $function, $always_debug)) {
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
     * Echos out a debug message
     *
     * Prints a Formatted Array of variables
     *
     * @param none
     * @return void
     */
    public function e($message, $always_debug = false) {

        $arr_btrace = $this->_debug_backtrace(false);

        $properties = $this->_getDebugStatementProperties(debug_backtrace());
        $line = $properties['line'];
        $class = $properties['class'];
        $function = $properties['function'];
        $file = $properties['file'];



        /*
         * check filters and debug state
         */
        if (!$this->_inFilters($class, $function, $always_debug)) {
            return;
        }


        $content = $message;


        $this->_sendToOutput($line, $class, $function, $file, $content);
    }

    /**
     * Get Reflection
     *
     * Uses reflection to return the class name, file, function name, and arg_names from __METHJOD__
     *
     * @param string $method The magic constant __METHOD__ passed from within a class method
     * @return array
     */
    private function _getReflection_old($method) {

        $method_parts = explode('::', $method);

        $class = $method_parts[0];
        $function = $method_parts[1];
        $r = new ReflectionClass($class);
        $arg_params = $r->getMethod($function)->getParameters();
//        echo '<br>' . __LINE__ .
        $arg_names = array();
        foreach ($arg_params as $arg_param) {
            $arg_names[] = $arg_param->name;
        }

//        echo '<br>' . __LINE__;
        $file = ($r->getFileName());
//        echo '<br>' . __LINE__;
//         $method_parts = explode('::', __METHOD__);
//
//        $class = $method_parts[0];
//        $function = $method_parts[1];
//        $r = new ReflectionClass($class);
//
//        $file = ($r->getFileName());
//        echo '<br> Class =  ' . $class;
//        echo '<br> Method =  ' . $function;
//        echo '<br> $file =  ' . $file;



        return(array('class' => $class, 'file' => $file, 'function' => $function, 'arg_names' => $arg_names));
    }

    /**
     * Variables
     *
     * Intended to be used with get_defined_vars() as the argument, but will work with any array. Takes an array, and outputs each of its indexes as if it were a separate variable, almost like extract, but wont impact the symbol table and is for display purposes only.
     *
     * @param none
     * @return void
     */
    public function vars($defined_vars, $always_debug = false) {

        /*
         * gets the properties of the debug statement for filtering and output
         */
        $arr_btrace = $this->_debug_backtrace(false);

        $properties = $this->_getDebugStatementProperties(debug_backtrace());
        $line = $properties['line'];
        $class = $properties['class'];
        $function = $properties['function'];
        $file = $properties['file'];

        /*
         * check filters and debug state
         */
        if (!$this->_inFilters($class, $function, $always_debug)) {
            return;
        }

        /*
         * Check each element of the array, and output in the format $<index_name> = $value , using
         * the normal $this->_v() method.
         */
        foreach ($defined_vars as $var_name => $var_value) {

            $content = $this->_v('$' . $var_name . ' = ', $var_value, $always_debug, true, false);
            $this->_sendToOutput($line, $class, $function, $file, $content);
        }
    }

    /**
     * Variable Wrapper
     *
     * Prints a Formatted Variable
     *
     * @param mixed
     * @return void
     */
    public function v($message, $var, $always_debug = false) {


        $same_line = true;
        $arr_btrace = $this->_debug_backtrace(false);

        $properties = $this->_getDebugStatementProperties(debug_backtrace());


        $line = $properties['line'];
        $class = $properties['class'];
        $function = $properties['function'];
        $file = $properties['file'];


        /*
         * check filters and debug state
         */
        if (!$this->_inFilters($class, $function, $always_debug)) {
            return;
        }


        /*
         * bump output to next line from the label if an array or object so the $message is easier to read
         */
        if (is_array($var) || is_object($var)) {
            $same_line = false;
        }
        $content = $this->_v($message, $var, $always_debug, true, true);


        $this->_sendToOutput($line, $class, $function, $file, $content, $same_line);
    }

    /**
     * Variable
     *
     * Prints a Formatted Variable
     *
     * @param mixed
     * @return void
     */
    private function _v($message, $var, $always_debug, $allow_arrays = false, $allow_objects = false) {

        #init

        /*
         * If an array, format as an array
         */
        if (is_array($var) || is_object($var)) {
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
            $var = (array) $var; //cast objects as an array for the purpose of iterating through them for display. without this, objects will give you errors when when setting $var[$key]
            foreach ($var as $key => $value) {
                if (is_array($value) || is_object($value)) {

                    $same_line = false;
                    $type = ucwords(gettype($value)); //e.g.: 'Array'
                    if (is_object($value)) {
                        $type = get_class($value) . ' ' . $type;
                    }
                    //  $value_string = '<pre>' . trim(htmlspecialchars(print_r($value, true))) . '</pre>';



                    if ((!$allow_objects) && (is_object($value))) {
                        $value_string = 'Objects are not expandable. To debug an object, use $this->debug()->v()';
                    } elseif ((!$allow_arrays) && (is_array($value))) {
                        $value_string = 'Arrays are not expandable. To debug an array, use $this->debug()->v()';
                    } else {
                        try {
                            //$value_string = trim(htmlspecialchars(print_r($value, true)));
                            $value_string = trim(htmlspecialchars(print_r($value, true)));
                        } catch (Exception $exc) {
                            $this->e($exc->getMessage());
                            $value_string = 'Error while attempting to display value : ' . $this->e($exc->getMessage());
                        }


                        // $value_string = '<pre>' . trim(htmlspecialchars(print_r($value, true))) . '</pre>';
                    }
                    $tags = array(
                        '{TYPE}' => $type,
                        '{KEY_NAME}' => $key,
                        '{VALUE}' => $value_string,
                    );

                    $var[$key] = str_replace(array_keys($tags), array_values($tags), $template);
                } else {
                    $var[$key] = htmlspecialchars($value);
                }
            }



//$this->getPlugin()->getSlug() . '_' . $this->getSlug()


            $content = $message . '<pre>' . print_r($var, true) . '</pre>';
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
            'function' => '',
            'args' => array(),
        );
        /*
         * get the backtrace
         */

        $arr_btrace = $this->_debug_backtrace(debug_backtrace()); //get the backtrace

        /*
         * get where the debug statement was located
         */



        $ds_line = (isset($arr_btrace[0]['line']) ? $arr_btrace[0]['line'] : '');
        $ds_file = (isset($arr_btrace[0]['file']) ? $arr_btrace[0]['file'] : '');
        $ds_class = (isset($arr_btrace[1]['class']) ? $arr_btrace[1]['class'] : '');
        $ds_function = (isset($arr_btrace[1]['function']) ? $arr_btrace[1]['function'] : '');


        /*
         * iterate through the loop so we can simplify each trace
         */

        foreach ($arr_btrace as $key => $trace_properties) {

            $trace_properties = array_intersect_key(array_merge($defaults, $trace_properties), $defaults); //make sure the indexes we need are there or use their defaults
            $traces[] = $trace_properties;
        }
        $content = 'Simplified debug_backtrace() <pre>' . print_r($traces, true) . '</pre>';
        $this->_sendToOutput($ds_line, $ds_class, $ds_function, $ds_file, $content, true);
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
        $ds_function = (isset($arr_btrace[0]['function']) ? $arr_btrace[0]['function'] : '');

        /*
         * check if in filters
         */
        if (!$this->_inFilters($ds_class, $ds_function, $always_debug)) {

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
        $content = '<div>' . implode('<br/><br/>', $traces) . '</div>';

        $this->_sendToOutput($line, $class, $function, $file, $content);
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
        $props['function'] = (isset($array_backtrace[1]['function']) ? $array_backtrace[1]['function'] : '');
        return $props;
    }

    /**
     * Master Trace
     *
     * Holds methods and classes in the order that they were executed.
     * structure {'classes'=array($class1,$class2),'methods'=array(
     *            'line' => null,
      'file' => null,
      'class' => null,
      'method' => null,
      'args' => array(),
     * )}
     * @var array
     *      */
    protected $_master_trace = null;

    /**
     * Add to Master Trace
     *
     * Adds a method to the master trace. The master trace array tracks each method as it is called.
     *
     * @param array $trace_array An array containing a trace in the form compact('line','file','class','method','args');
     * @return void
     */
    public function addToMasterTrace($props) {


        /*
         * define prop_id, which is the property id that we'll use to find the correct property transient
         * All properties are contained in transietns for each method
         */
        $prop_id = (isset($this->_master_trace['methods'])) ? count($this->_master_trace['methods']) : 0;

        /*
         * add the new class to the existing traces class array
         * then remove duplicate values
         */
        $classes = $this->_master_trace['classes'];
        $classes[] = $props['class'];
        $this->_master_trace['classes'] = array_unique($classes);
        $this->_master_trace['methods'][] =
                array('method' => $props['method'],
                    'class' => $props['class'],
                    'prop_id' => $prop_id, //place holder for property id
                    'defined_vars_available' => false //indicate defined variables are saved to the property transient. will flip to true when they are
        );

        /*
         * save the properties to a wordpress transient
         * Transients are necessary to temporarily store properties because of their size to prevent memory problems
         */
        set_transient($this->getPlugin()->getSlug() . '_' . $this->getSlug() . 'trace_prop' . '_' . $prop_id, $props, 120);
        return;
    }

    /**
     * Get Master Trace
     *
     * @param none
     * @return array $this->_master_trace
     */
    public function getMasterTrace() {

        if (is_null($this->_master_trace)) {
            $this->_master_trace = array();
        }

        return $this->_master_trace;
    }

    /**
     * Defined Variables
     *
     * Adds the Defined Variables to the Master Trace
     *
     * @param none
     * @return void
     */
    public function definedVars($arr_defined_vars = array(), $always_debug = false) {

        /*
         * Check each element of the array, and output in the format $<index_name> = $value , using
         * the normal $this->_v() method.
         */
        $string_defined_vars = ''; //holds the defined vars html
        foreach ($arr_defined_vars as $var_name => $var_value) {

            $content = $this->_v('$' . $var_name . ' = ', $var_value, $always_debug, true, false);



            $string_defined_vars.= '<br/>' . $content;
        }
        $arr_btrace = $this->_debug_backtrace(false);


        $props = $this->_getDebugStatementProperties(debug_backtrace());



        if (!$this->_inFilters($props['class'], $props['function'], $always_debug)) {
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

        // echo $html;
        $this->_sendToOutput($props['line'], $props['class'], $props['function'], $props['file'], $html, $same_line = false, false);




//
//        $ds_properties = $this->_getDebugStatementProperties(debug_backtrace(), false);
//
//        if (!$this->_inFilters($ds_properties['class'], $ds_properties['function'], $always_debug)) {
//            return;
//        }
//
//
//
//        /*
//         * get backtrace information
//         */
//        $array_backtrace = $this->_debug_backtrace(debug_backtrace());
//
//
//        /*
//         * send backtrace data to get Method so we can get its properties
//         */
//
//        $props = $this->_getMethodProperties($array_backtrace, $defined_vars);
//
//
//
//        $master_trace = $this->getMasterTrace();
//
//
//        /*
//         * Return if Master Trace is Empty
//         * This can happen if no debug statements are being used (unlikely)
//         * or for some reason the hook gets called prior to shutdown ( which has happened but not sure why...)
//         *
//         */
//        if (empty($master_trace)) {
//            return;
//        }
//        //  $this->v($master_trace, true);
////        $this->v('$master_trace = ', $master_trace, true);
////         echo '<pre>', print_r($master_trace['methods'], true), '</pre>';
//        $traces = $master_trace['methods'];
//
//        $updated = false;
//
//        /*
//         * Find a trace item with the same class and method but is missing the defined variables
//         * Add the defined variables to it by replacing its trace properties
//         *
//         */
//
//
//        foreach ($traces as $key => $trace) {
//
//
//            if ($props['method'] === $trace['method'] && $props['class'] === $trace['class'] && !$updated) { //find the first trace item that matches the class and method
//                if ($trace['defined_vars_available'] === false) { //if defined vars hasnt been updated yet, update it with this one
//                    set_transient($this->getPlugin()->getSlug() . '_' . $this->getSlug() . 'trace_prop' . '_' . $trace['prop_id'], $props);
//                    $traces[$key]['defined_vars_available'] = true; //flag that defined vars is now available
//                    $updated = true; //end loop by flagging that the update is done
//                }
//            }
//        }
//
//        /*
//         * update the master trace array
//         */
//        $master_trace['methods'] = $traces;
//        $this->_master_trace = $master_trace;
    }

    /**
     * Trace Wrapper
     *
     * Long Description
     *
     * @param none
     * @return void
     */
    public function t($always_debug = false, $levels = 0) {
        $arr_btrace = $this->_debug_backtrace();
        $ds_properties = $this->_getDebugStatementProperties(debug_backtrace());

        if (!$this->_inFilters($ds_properties['class'], $ds_properties['function'], $always_debug)) {
            return;
        }

        /*
         * Get one level as a header
         *
         */

        $arr_trace = $this->_trace($ds_properties, $always_debug, 1);
        $method_header_html = $arr_trace['backtrace'];

        /*
         * Get the backtrace, hiding it under a link to expand/collapse it
         */

        $arr_trace = $this->_trace($ds_properties, $always_debug, 99);

        $tags = array(
            // '{CLASS}' => ($props['class'] !== '') ? $props['class'] : $not_available_text,
            '{BACKGROUND_COLOR}' => '#E3DDB2',
            // '{METHOD}' => ($props['function'] !== '') ? $props['function'] : $not_available_text,
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
         * Now get the visualbacktrace html
         */
        $arr_trace = $this->_trace($ds_properties, $always_debug, 99);

        $tags = array(
            // '{CLASS}' => ($props['class'] !== '') ? $props['class'] : $not_available_text,
            '{BACKGROUND_COLOR}' => '#E3DDB2',
            // '{METHOD}' => ($props['function'] !== '') ? $props['function'] : $not_available_text,
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


        $content = $non_visual_trace_html . $visual_trace_html;

        $this->_sendToOutput($ds_properties['line'], $ds_properties['class'], $ds_properties['function'], $ds_properties['file'], $content, false);
    }

    /**
     * Trace
     *
     * Trace internal function , intended to be called from a wrapper. Outputs the callstack of a method
     *
     * @param array $ds_properties The debug statement properties from _getDebugStatementProperties
     * @param boolean $always_debug Whether to always display regardless of filter settings
     * @param int $levels The number of levels of the call stack to show. 0 to show all
     * @return array , 'backtrace' is html output , 'visual_backtrace' is the output from graphviz if enabled.
     */
    private function _trace($ds_properties, $always_debug = false, $levels = 1) {


        $arr_btrace = $this->_debug_backtrace(true);
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
         * if a mask is set, then use all levels
         */

        if ($this->_isFocused()) {
            $levels = 0; //this will provide a complete drilldown for the focused method
        }


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

                $content = $this->_v('$' . $var_name . ' = ', $var_value, $always_debug, true, false);

                $current_expanded_args.= '<br/>' . $content;
            }




            $debug_trace_html_template = '<div style="padding:0px;margin:0px;"><div style="background-color:{BACKGROUND_COLOR};border:1px solid grey;padding:5px;text-align:left;display: inline-block;margin-left:{MARGIN}px;margin-top:5px;">
                <strong>{CLASS}::{METHOD_SIG}</strong>


                <a class="simpli_debug_citem simpli_debug_get_debug_info" href="#"><span>Info</span><span style="visibility:hidden;display:none">Collapse</span></a>



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
                '{CALLING_METHOD}' => $current_calling_method
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
         * add a border if its a backtrace
         */
        if ($levels > 1 || $levels === 0) {
            $style = 'border:groove 5px green';
            $title = '<h3 style="text-align:center">' . $ds_properties['function'] . '() Backtrace</h3>';
        } else {
            $style = '';
            $title = '';
        }
        $content = '<div style="' . $style . '">' . $title . '<pre>' . implode('', $traces_html) . '</pre></div>';
        $result['backtrace'] = $content;
        $result['visual_backtrace'] = $visual_backtrace;

        return $result;
    }

    /**
     * Trace
     *
     * Long Description
     *
     * @param none
     * @return void
     */
    public function t2($always_debug = false, $levels = 1) {



        $ds_properties = $this->_getDebugStatementProperties(debug_backtrace(), false);

        if (!$this->_inFilters($ds_properties['class'], $ds_properties['function'], $always_debug)) {
            return;
        }



        /*
         * get backtrace information
         */
        $array_backtrace = $this->_debug_backtrace(debug_backtrace());


        /*
         * send backtrace data to get Method so we can get its properties
         */
        $defined_vars = array(); // defined variables arent available yet
        $props = $this->_getMethodProperties($array_backtrace, $defined_vars);



        /*
         * save this method's properties to the master trace array
         */
        $this->addToMasterTrace($props);
    }

    /**
     * Trace
     *
     * Generates a simple description of the current method or a full backtrace if you set levels >1
     * You will also get a visual backtrace if it is enabled
     * Usage:
     *    $this->debug()->t();
     *     full backtrace with all levels:
      debug()->t(false,99)
     *
     * @param boolean $always_debug Whether you want to override the filters to always show debug output
     * @param int $levels The number of levels you want to display. default is 1, showing the current function. 0 will show all.
     * @return void
     */
    public function t_original($always_debug = false, $levels = 1, $defined_vars = array()) {
        $properties = $this->_getDebugStatementProperties(debug_backtrace(), false);




        $ds_line = $properties['line'];
        $ds_class = $properties['class'];
        $ds_function = $properties['function'];
        $ds_file = $properties['file'];

        /*
         * save this method's properties to the master trace array
         */
        addToMasterTrace($properties);

        /*
         *
         */

        /*
         * Check each element of the array, and output in the format $<index_name> = $value , using
         * the normal $this->_v() method.
         */
        $ds_function_defined_vars = ''; //holds the defined vars html
        foreach ($defined_vars as $var_name => $var_value) {

            $content = $this->_v('$' . $var_name . ' = ', $var_value, $always_debug);
            //        $defined_var = $this->_sendToOutput($ds_line, $ds_class, $ds_function, $ds_file, $content, false);
            $ds_function_defined_vars.= '<br/>' . $content;
        }



        $arr_btrace = $this->_debug_backtrace();


        //  array_shift($arr_btrace);
        /*
         * Show trace only if in current filters
         * if class is set to an empty string, use the backtrace class and function
         */


//        if ($class === '') {
//            if (!$this->_inFilters($arr_btrace[0]['class'], $arr_btrace[0]['function'], $always_debug)) {
//                return;
//            }
//        } else {
//
//            if (!$this->_inFilters($class, $function, $always_debug)) {
//                return;
//            }
//        }

        if (!$this->_inFilters($ds_class, $ds_function, $always_debug)) {
            return;
        }


        /*
         *  initialize variables
         */


        $traces = array(); // the visualize traces array
        $traces_html = array(); //initialize traces html array
        $counter = -1; //keep track of number of branches so we can add appropriate formatting
        $shift_counter = 0;
        $previous_function = ''; //tracks the previous method for use to determine branch to new method so we can change formatting
        $current_function = ''; //tracks the method being processed
        $current_class = ''; //tracks the class being processed
        $previous_class = ''; //tracks the previous class for use to determine branch to new method so we can change formatting
        $margin = 5; //the number of pixels the margin should shift during a trace when the trace branches to a new class
        $bg_color1 = '#E3DDB2'; //first background color to display
        $bg_color2 = '#ECC084'; //second background color to display
        $background_color = $bg_color1; // the initial color of the trace background. colors will shift between 1 and 2 when classes shift
        $link_text_expansion = 'More';
        $link_text_expansion_visual = 'Visual Backtrace';
        $trace_location = ''; // either the line and path where the debug statement is located, or the file path to the file that the class where the method being traced resides in



        /*
         * if a mask is set, then use all levels
         */

        if ($this->_isFocused()) {
            $levels = 0; //this will provide a complete drilldown for the focused method
        }

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
         * Iterate through backtrace and extrace what we need, filling in the template as we go
         */
        $loop_length = count($backtrace_array);
        foreach ($backtrace_array as $key => $functions) {
            $counter++;

            $defaults = array(
                'file' => null,
                'line' => null,
                'class' => null,
                'function' => null,
                'args' => null,
                'function_comment' => null
            );


            /*
             * Validate and define $current_ variables
             */
            $functions = array_merge($defaults, $functions);
            $current_line = $functions['line'];
            $current_function = $functions['function'];
            $current_class = $functions['class'];
            $current_file = $functions['file'];
            $current_args = $functions['args'];
            $current_function_comment = $functions['function_comment'];

            /*
             * Change formatting paramaters when the class changes or ( in the case of a plain function call) when the
             * function changes
             * We want all methods that occur in the same class to be displayed in the same column
             */
            if (
                    (($counter !== 0) && ($current_class !== $previous_class)) //the first iteration will always have different current and previous since previous is set to null
                    ||
                    (($current_class === '') && ($current_function != $previous_function)) //to account for standalone functions
            ) {

                if (!($this->_inExcludedFilter($current_function))) { //only change formatting if not in excluded filter
                    static $toggle = true; //tracks current state of toggle.
                    $toggle = !$toggle && true; //flips toggle state each time this line is executed


                    $shift_counter++;  //tracks how many times we shift so we can calculate the margin
                    $margin = $shift_counter * 50; //shifts the formatting of the next method block to the right
                    $background_color = ($toggle) ? $bg_color1 : $bg_color2; //alternates colors when there is a change
                }
            }

            /*
             * This may not be necessary , its a holdover from original code
             * its intent was to create a human readable function sig for do_action
             */

            //         if (($functions['function'] === 'do_action')) {
//
            //             $functions['function'] = $functions['function'] . '( \'' . current_filter() . '\' )';
            //        }




            /*
             * modify the function string so its human readable, adding arguments if available
             */

            $fsig = $this->_getFunctionSignature($current_class, $current_function, $current_args, true);

            $current_function_sig = $fsig['function_sig_simple'];

            /*
             * create the expanded args string from the $args array returned from the _getFunctionSignature function
             * Expanded args just means if the args are an array , they will formatted vertically for easier reading
             */
            $expanded_args = '<pre>' . htmlspecialchars(print_r($fsig['args'], true)) . '</pre>';
            $expanded_args = '';
            foreach ($fsig['args'] as $var_name => $var_value) {

                $content = $this->_v('$' . $var_name . ' = ', $var_value, $always_debug);

                $expanded_args.= '<br/>' . $content;
            }




            //   $class_path = $fsig['class_path'];
            //check file
            if (is_null($current_file)) {

                $current_file = 'unknown';
                $trace_file_path = 'unknown';
            } else {
                $trace_file_path = $this->getPlugin()->getTools()->makePathRelative($this->getPlugin()->getDirectory(), $current_file);
            }

            if (is_null($current_line)) {

                $current_line = 'unknown';
            }
            /*
             * Debug Statement Location
             */
            /*
             * For the last trace item,show the line number and file path that the debug statement is located in
             */



            if ($counter === ($loop_length - 1)) {
                $trace_location = 'Line ' . $ds_line . ' in file ' . $ds_file;
            } else { //if not the last trace item, show the function location returned by _getFunctionSignature
                $trace_location = $fsig['function_location'];
            }

            /*
             * Method Comment
             */

            $current_function_comment = $fsig['function_comment'];

            /* Link Text
             */


            if ($counter !== (count($backtrace_array) ) - 1) {//for all but the last level use 'more'
                $link_text = $link_text_expansion;
                $visual_backtrace = ''; // remove the visual backtrace tag if the current trace is not the last
            } else {//the last level should use the visual backtrace text if enabled. do not add if only one level
                if ($counter != 0 && $this->debug()->getOption('graphviz')) {
                    $link_text = $link_text_expansion_visual; //e.g.: Visual Backtrace"
                    $visual_backtrace = '{VISUAL_BACKTRACE}'; //keep the visual backtrace tag so it remains in the last trace
                } else {
                    $link_text = $link_text_expansion; //e.g.: "More"
                    $visual_backtrace = ''; // if visual backtradce is not enabled, remove it from the last trace also
                }
            }

            /*
             * create explore_url
             * @todo: need to add the get params in a more robust fashion - maybe use getTools() that would check if there is a ? using url_parts.
             */
            $explore_url = $_SERVER['REQUEST_URI'] . '&' . $this->QUERY_VAR_FOCUS_CLASS . '=' . $current_class . '&' . $this->QUERY_VAR_FOCUS_METHOD . '=' . $current_function;

            $new_get_params = array(
                $this->QUERY_VAR_FOCUS_CLASS => $current_class,
                $this->QUERY_VAR_FOCUS_METHOD => $current_function,
            );
            $explore_url = $this->getPlugin()->getTools()->rebuildURL($new_get_params); //takes current url and uses the new get paramaters


            $debug_trace_html_template = '<div style="padding:0px;margin:0px;"><div style="background-color:{BACKGROUND_COLOR};border:1px solid grey;padding:5px;text-align:left;display: inline-block;margin-left:{MARGIN}px;margin-top:5px;">
                <strong>{CLASS}::{FUNCTION_SIG}</strong>


                    <a class="simpli_debug_citem" href="#"><span>Info</span><span style="visibility:hidden;display:none">Collapse</span></a>
<a  href="{EXPLORE_URL}">Focus</a>



                    <div  class="simpli_debug_toggle" style="display:none;visibility:hidden;padding:0px;margin:0px;"><!-- Collapsable -->
                        <div style="margin:10px 0px 10px 0px;"> <strong style="font-size:xx-large">Description</strong></div>
                        <p> {FUNCTION_COMMENT}</p>



                        <div style="margin:10px 0px 10px 0px;"> <strong style="font-size:xx-large">Location</strong></div>
                        <p> {TRACE_LOCATION}</p>

                        <div style="margin:10px 0px 10px 0px;">  <strong style="font-size:xx-large">Called From</strong></div>
                        <p > Line {LINE} in  {FILE_PATH}</p>
                        <div style="margin:10px 0px 10px 0px;"> <strong style="font-size:xx-large">Arguments</strong></div>
                        {EXPANDED_ARGS}
                        <div style="margin:10px 0px 10px 0px;">  <strong style="font-size:xx-large">Defined Variables</strong></div>

                        {DEFINED_VARS}




                        {VISUAL_BACKTRACE}

                    </div>

            </div>
        </div>';


            $debug_trace_html_template = $this->getPlugin()->getTools()->getHtmlWithoutWhitespace($debug_trace_html_template); //this is necessary since there are pre tags in the source . You could just remove it manually using a macro in a text editor , like the 'remove unnecessary whitespace' utility in notepad++ , but using getHtmlWithoutWhitespace allows us to retain the whitespace in our source file so its human readable, while still removing it when its displayed.
            /*
             * Now populate the html template
             */
            $search = array('{LINE}', '{FILE_PATH}', '{CLASS}', '{FUNCTION_SIG}', '{MARGIN}', '{BACKGROUND_COLOR}', '{TRACE_LOCATION}', '{EXPANDED_ARGS}', '{VISUAL_BACKTRACE}', '{LINK_TEXT}', '{FUNCTION_COMMENT}', '{DEFINED_VARS}', '{EXPLORE_URL}');
            $replacements = array($current_line, $trace_file_path, $current_class, $current_function_sig, $margin, $background_color, $trace_location, $expanded_args, $visual_backtrace, $link_text, $current_function_comment, $ds_function_defined_vars, $explore_url);
            $current_trace_html = str_replace($search, $replacements, $debug_trace_html_template);



            /*
             * Exclude uninteresting internal  functions from trace so as to
             * make the trace cleaner
             */
            if ($this->_inExcludedFilter($current_function)) {

                continue; /* dont show the functions in $excluded_functions_filter array since they are internal and not interesting */
            }

            /*
             * Build Visual Backtrace Array
             * This builds a traces array that is used by the getVisualBacktrace method
             * Classes are saved as keys to ensure uniqueness
             * and to preserve order
             * Methods are saved as class and function elements
             */
            if (!isset($traces['classes'][$current_class])) {
                $traces['classes'][$current_class] = '';
            }


            $traces['methods'][] = array(
                'class' => $current_class,
                'function' => $current_function . '()'
            );


            /*
             * Wrap up the loop
             * Assemble the final trace output
             * Update the previous variables
             */
            $traces_html[] = $current_trace_html;
            $previous_function = $current_function;
            $previous_class = $current_class;
        } //Backtrace loop complete

        /*
         * clean up the traces array
         * Since the classes we want are keys, but the
         * visual backtrace function expects an array of values,
         * convert the array to one that consists of just its former keys
         */

        $traces['classes'] = array_keys($traces['classes']); //use only the keys of the classes

        /*
         * Get the visual backtrace. If visual backtrace is not enabled,
         * it will return an empty string
         */

        //   die('$traces_html[count($traces_html) - 1] = ' . $traces_html[count($traces_html) - 1]);
        if ($counter > 1) {//only add backtrace for levels greater than 1
            $header = '<div style = "margin:10px 0px 10px 0px;"> <strong style = "font-size:xx-large">Visual Backtrace</strong></div>';
            //add header only if visual backtrace is not empty
            $visual_backtrace = $this->getVisualBacktrace($traces);
            $visual_backtrace = ($visual_backtrace === '') ? '' : $header . $visual_backtrace;
            $traces_html[count($traces_html) - 1] = str_replace('{VISUAL_BACKTRACE}', $visual_backtrace, $traces_html[count($traces_html) - 1]);
        } else {
            $traces_html[count($traces_html) - 1] = str_replace('{VISUAL_BACKTRACE}', '', $traces_html[count($traces_html) - 1]);
        }


        /*
         * Assemble teh final content for the output
         */
        $content = '<div><pre>' . implode('', $traces_html) . '</pre></div>';


        $this->_sendToOutput($ds_line, $ds_class, $ds_function, $ds_file, $content);
    }

    /**
     * Trace
     *
     * Generates a simple description of the current method or a full backtrace if you set levels >1
     * You will also get a visual backtrace if it is enabled
     * Usage:
     *    $this->debug()->t();
     *     full backtrace with all levels:
      debug()->t(false,99)
     *
     * @param boolean $always_debug Whether you want to override the filters to always show debug output
     * @param int $levels The number of levels you want to display. default is 1, showing the current function. 0 will show all.
     * @return void
     */
    public function t_old($always_debug = false, $levels = 1, $defined_vars = array()) {
        $properties = $this->_getDebugStatementProperties(debug_backtrace(), false);




        $ds_line = $properties['line'];
        $ds_class = $properties['class'];
        $ds_function = $properties['function'];
        $ds_file = $properties['file'];

        /*
         * save this method's properties to the master trace array
         */
        addToMasterTrace($properties);

        /*
         *
         */

        /*
         * Check each element of the array, and output in the format $<index_name> = $value , using
         * the normal $this->_v() method.
         */
        $ds_function_defined_vars = ''; //holds the defined vars html
        foreach ($defined_vars as $var_name => $var_value) {

            $content = $this->_v('$' . $var_name . ' = ', $var_value, $always_debug);
            //        $defined_var = $this->_sendToOutput($ds_line, $ds_class, $ds_function, $ds_file, $content, false);
            $ds_function_defined_vars.= '<br/>' . $content;
        }



        $arr_btrace = $this->_debug_backtrace();


        //  array_shift($arr_btrace);
        /*
         * Show trace only if in current filters
         * if class is set to an empty string, use the backtrace class and function
         */


//        if ($class === '') {
//            if (!$this->_inFilters($arr_btrace[0]['class'], $arr_btrace[0]['function'], $always_debug)) {
//                return;
//            }
//        } else {
//
//            if (!$this->_inFilters($class, $function, $always_debug)) {
//                return;
//            }
//        }

        if (!$this->_inFilters($ds_class, $ds_function, $always_debug)) {
            return;
        }


        /*
         *  initialize variables
         */


        $traces = array(); // the visualize traces array
        $traces_html = array(); //initialize traces html array
        $counter = -1; //keep track of number of branches so we can add appropriate formatting
        $shift_counter = 0;
        $previous_function = ''; //tracks the previous method for use to determine branch to new method so we can change formatting
        $current_function = ''; //tracks the method being processed
        $current_class = ''; //tracks the class being processed
        $previous_class = ''; //tracks the previous class for use to determine branch to new method so we can change formatting
        $margin = 5; //the number of pixels the margin should shift during a trace when the trace branches to a new class
        $bg_color1 = '#E3DDB2'; //first background color to display
        $bg_color2 = '#ECC084'; //second background color to display
        $background_color = $bg_color1; // the initial color of the trace background. colors will shift between 1 and 2 when classes shift
        $link_text_expansion = 'More';
        $link_text_expansion_visual = 'Visual Backtrace';
        $trace_location = ''; // either the line and path where the debug statement is located, or the file path to the file that the class where the method being traced resides in



        /*
         * if a mask is set, then use all levels
         */

        if ($this->_isFocused()) {
            $levels = 0; //this will provide a complete drilldown for the focused method
        }

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
         * Iterate through backtrace and extrace what we need, filling in the template as we go
         */
        $loop_length = count($backtrace_array);
        foreach ($backtrace_array as $key => $functions) {
            $counter++;

            $defaults = array(
                'file' => null,
                'line' => null,
                'class' => null,
                'function' => null,
                'args' => null,
                'function_comment' => null
            );


            /*
             * Validate and define $current_ variables
             */
            $functions = array_merge($defaults, $functions);
            $current_line = $functions['line'];
            $current_function = $functions['function'];
            $current_class = $functions['class'];
            $current_file = $functions['file'];
            $current_args = $functions['args'];
            $current_function_comment = $functions['function_comment'];

            /*
             * Change formatting paramaters when the class changes or ( in the case of a plain function call) when the
             * function changes
             * We want all methods that occur in the same class to be displayed in the same column
             */
            if (
                    (($counter !== 0) && ($current_class !== $previous_class)) //the first iteration will always have different current and previous since previous is set to null
                    ||
                    (($current_class === '') && ($current_function != $previous_function)) //to account for standalone functions
            ) {

                if (!($this->_inExcludedFilter($current_function))) { //only change formatting if not in excluded filter
                    static $toggle = true; //tracks current state of toggle.
                    $toggle = !$toggle && true; //flips toggle state each time this line is executed


                    $shift_counter++;  //tracks how many times we shift so we can calculate the margin
                    $margin = $shift_counter * 50; //shifts the formatting of the next method block to the right
                    $background_color = ($toggle) ? $bg_color1 : $bg_color2; //alternates colors when there is a change
                }
            }

            /*
             * This may not be necessary , its a holdover from original code
             * its intent was to create a human readable function sig for do_action
             */

            //         if (($functions['function'] === 'do_action')) {
//
            //             $functions['function'] = $functions['function'] . '( \'' . current_filter() . '\' )';
            //        }




            /*
             * modify the function string so its human readable, adding arguments if available
             */

            $fsig = $this->_getFunctionSignature($current_class, $current_function, $current_args, true);

            $current_function_sig = $fsig['function_sig_simple'];

            /*
             * create the expanded args string from the $args array returned from the _getFunctionSignature function
             * Expanded args just means if the args are an array , they will formatted vertically for easier reading
             */
            $expanded_args = '<pre>' . htmlspecialchars(print_r($fsig['args'], true)) . '</pre>';
            $expanded_args = '';
            foreach ($fsig['args'] as $var_name => $var_value) {

                $content = $this->_v('$' . $var_name . ' = ', $var_value, $always_debug);

                $expanded_args.= '<br/>' . $content;
            }




            //   $class_path = $fsig['class_path'];
            //check file
            if (is_null($current_file)) {

                $current_file = 'unknown';
                $trace_file_path = 'unknown';
            } else {
                $trace_file_path = $this->getPlugin()->getTools()->makePathRelative($this->getPlugin()->getDirectory(), $current_file);
            }

            if (is_null($current_line)) {

                $current_line = 'unknown';
            }
            /*
             * Debug Statement Location
             */
            /*
             * For the last trace item,show the line number and file path that the debug statement is located in
             */



            if ($counter === ($loop_length - 1)) {
                $trace_location = 'Line ' . $ds_line . ' in file ' . $ds_file;
            } else { //if not the last trace item, show the function location returned by _getFunctionSignature
                $trace_location = $fsig['function_location'];
            }

            /*
             * Method Comment
             */

            $current_function_comment = $fsig['function_comment'];

            /* Link Text
             */


            if ($counter !== (count($backtrace_array) ) - 1) {//for all but the last level use 'more'
                $link_text = $link_text_expansion;
                $visual_backtrace = ''; // remove the visual backtrace tag if the current trace is not the last
            } else {//the last level should use the visual backtrace text if enabled. do not add if only one level
                if ($counter != 0 && $this->debug()->getOption('graphviz')) {
                    $link_text = $link_text_expansion_visual; //e.g.: Visual Backtrace"
                    $visual_backtrace = '{VISUAL_BACKTRACE}'; //keep the visual backtrace tag so it remains in the last trace
                } else {
                    $link_text = $link_text_expansion; //e.g.: "More"
                    $visual_backtrace = ''; // if visual backtradce is not enabled, remove it from the last trace also
                }
            }

            /*
             * create explore_url
             * @todo: need to add the get params in a more robust fashion - maybe use getTools() that would check if there is a ? using url_parts.
             */
            $explore_url = $_SERVER['REQUEST_URI'] . '&' . $this->QUERY_VAR_FOCUS_CLASS . '=' . $current_class . '&' . $this->QUERY_VAR_FOCUS_METHOD . '=' . $current_function;

            $new_get_params = array(
                $this->QUERY_VAR_FOCUS_CLASS => $current_class,
                $this->QUERY_VAR_FOCUS_METHOD => $current_function,
            );
            $explore_url = $this->getPlugin()->getTools()->rebuildURL($new_get_params); //takes current url and uses the new get paramaters


            $debug_trace_html_template = '<div style="padding:0px;margin:0px;"><div style="background-color:{BACKGROUND_COLOR};border:1px solid grey;padding:5px;text-align:left;display: inline-block;margin-left:{MARGIN}px;margin-top:5px;">
                <strong>{CLASS}::{FUNCTION_SIG}</strong>


                    <a class="simpli_debug_citem" href="#"><span>Info</span><span style="visibility:hidden;display:none">Collapse</span></a>
<a  href="{EXPLORE_URL}">Focus</a>



                    <div  class="simpli_debug_toggle" style="display:none;visibility:hidden;padding:0px;margin:0px;"><!-- Collapsable -->
                        <div style="margin:10px 0px 10px 0px;"> <strong style="font-size:xx-large">Description</strong></div>
                        <p> {FUNCTION_COMMENT}</p>



                        <div style="margin:10px 0px 10px 0px;"> <strong style="font-size:xx-large">Location</strong></div>
                        <p> {TRACE_LOCATION}</p>

                        <div style="margin:10px 0px 10px 0px;">  <strong style="font-size:xx-large">Called From</strong></div>
                        <p > Line {LINE} in  {FILE_PATH}</p>
                        <div style="margin:10px 0px 10px 0px;"> <strong style="font-size:xx-large">Arguments</strong></div>
                        {EXPANDED_ARGS}
                        <div style="margin:10px 0px 10px 0px;">  <strong style="font-size:xx-large">Defined Variables</strong></div>

                        {DEFINED_VARS}




                        {VISUAL_BACKTRACE}

                    </div>

            </div>
        </div>';


            $debug_trace_html_template = $this->getPlugin()->getTools()->getHtmlWithoutWhitespace($debug_trace_html_template); //this is necessary since there are pre tags in the source . You could just remove it manually using a macro in a text editor , like the 'remove unnecessary whitespace' utility in notepad++ , but using getHtmlWithoutWhitespace allows us to retain the whitespace in our source file so its human readable, while still removing it when its displayed.
            /*
             * Now populate the html template
             */
            $search = array('{LINE}', '{FILE_PATH}', '{CLASS}', '{FUNCTION_SIG}', '{MARGIN}', '{BACKGROUND_COLOR}', '{TRACE_LOCATION}', '{EXPANDED_ARGS}', '{VISUAL_BACKTRACE}', '{LINK_TEXT}', '{FUNCTION_COMMENT}', '{DEFINED_VARS}', '{EXPLORE_URL}');
            $replacements = array($current_line, $trace_file_path, $current_class, $current_function_sig, $margin, $background_color, $trace_location, $expanded_args, $visual_backtrace, $link_text, $current_function_comment, $ds_function_defined_vars, $explore_url);
            $current_trace_html = str_replace($search, $replacements, $debug_trace_html_template);



            /*
             * Exclude uninteresting internal  functions from trace so as to
             * make the trace cleaner
             */
            if ($this->_inExcludedFilter($current_function)) {

                continue; /* dont show the functions in $excluded_functions_filter array since they are internal and not interesting */
            }

            /*
             * Build Visual Backtrace Array
             * This builds a traces array that is used by the getVisualBacktrace method
             * Classes are saved as keys to ensure uniqueness
             * and to preserve order
             * Methods are saved as class and function elements
             */
            if (!isset($traces['classes'][$current_class])) {
                $traces['classes'][$current_class] = '';
            }


            $traces['methods'][] = array(
                'class' => $current_class,
                'function' => $current_function . '()'
            );


            /*
             * Wrap up the loop
             * Assemble the final trace output
             * Update the previous variables
             */
            $traces_html[] = $current_trace_html;
            $previous_function = $current_function;
            $previous_class = $current_class;
        } //Backtrace loop complete

        /*
         * clean up the traces array
         * Since the classes we want are keys, but the
         * visual backtrace function expects an array of values,
         * convert the array to one that consists of just its former keys
         */

        $traces['classes'] = array_keys($traces['classes']); //use only the keys of the classes

        /*
         * Get the visual backtrace. If visual backtrace is not enabled,
         * it will return an empty string
         */

        //   die('$traces_html[count($traces_html) - 1] = ' . $traces_html[count($traces_html) - 1]);
        if ($counter > 1) {//only add backtrace for levels greater than 1
            $header = '<div style = "margin:10px 0px 10px 0px;"> <strong style = "font-size:xx-large">Visual Backtrace</strong></div>';
            //add header only if visual backtrace is not empty
            $visual_backtrace = $this->getVisualBacktrace($traces);
            $visual_backtrace = ($visual_backtrace === '') ? '' : $header . $visual_backtrace;
            $traces_html[count($traces_html) - 1] = str_replace('{VISUAL_BACKTRACE}', $visual_backtrace, $traces_html[count($traces_html) - 1]);
        } else {
            $traces_html[count($traces_html) - 1] = str_replace('{VISUAL_BACKTRACE}', '', $traces_html[count($traces_html) - 1]);
        }


        /*
         * Assemble teh final content for the output
         */
        $content = '<div><pre>' . implode('', $traces_html) . '</pre></div>';


        $this->_sendToOutput($ds_line, $ds_class, $ds_function, $ds_file, $content);
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
     * Get Function Signature
     *
     * Create a human readable function signature from function name and an argument array
     * Useful for debug methods
     *
     * @param string $function The name of the function 'my_function'
     * @param array $arg_names A non-associative array with the names of the arguments
     * @param array $arg_values A non-associative array with the values of the arguments
     * @param boolean $arg_expansion True/False as to whether you want args to be formatted as a cascading array
     * @return array with 2 elements,
     * ['sig'],  A string in the form my_function(arg1='arg value',arg2='arg value')
     * ['args'], An array that contains an associative array of the arguments
     */
    private function _getFunctionSignature($class, $function, $arg_values, $arg_expansion = false) {

        #init
        $arg_string = '';
        $args = array();
        $class_path = '';
        $function_path = '';
        $function_sig = '';
        $function_sig_simple;
        $function_sig_super_simple;
        $function_line = '';
        $function_location = '';
        $function_comment = '';
        /*
         * if a class was given, derive class path and arg names using
         * reflection. otherwise, set to null
         * this also avoids an 'class does not exist' error when doing
         * reflection on a class that isnt available
         */
        if ($class === null) {

            $arg_names = null;
            $class_path = '';
        } else {
            $reflected_vars = ($this->_getReflection($class . '::' . $function));
            $class_path = $this->getPlugin()->getTools()->makePathRelative($this->getPlugin()->getDirectory(), $reflected_vars['file']);


            $arg_names = $reflected_vars['arg_names'];
        }

        if ((!is_null($arg_values))) {

            /* If there are arguments, create an argument array of name value pairs
             * if arg names are available, use them by combining them with arguments.
             */
            if (is_array($arg_names) && is_array($arg_values) && (count($arg_names) > 0) && count($arg_names) === count($arg_values)) {
                $args = array_combine($arg_names, $arg_values);
            } else {
                $args = $arg_values;
            }

            /*
             * add a dollar sign in front of each of the argument names and surround in parens
             */
            $arg_string = '$' . urldecode(http_build_query($args, null, ',$'));
            $arg_string = ($arg_string === '$') ? '' : $arg_string; //remove the $ if thats the only thing left in the string
        }



        /*
         * if class path is not defined, its a function (not a method) , so set its line and function from function reflection
         */
        if ($class_path === '') {

            try {
                $reflFunc = new ReflectionFunction($function);
                $function_line = $reflFunc->getStartLine();
                $function_path = $reflFunc->getFileName();
                $function_location = "Line $function_line in $function_path";
            } catch (Exception $exc) {
                /*
                 * need to catch fatal exceptions caused by language constructs that cant be found by php
                 */
                $error_message = $exc->getMessage();

                if (stripos($error_message, 'does not exist') !== false) {


                    $function_location = 'PHP built-in function or language construct';
                }
                $this->debug()->e('Exception Message = ' . $error_message);
            }
        } else {
            $function_location = $class_path;
            /*
             * Get the method comment
             */
            $method = new ReflectionMethod($class, $function);

            $function_comment = '<p>' . str_replace('*', '<br/>*', $method->getDocComment()) . '</p>';
        }

        $function_sig = htmlspecialchars($function . '(' . $arg_string . ')'); //function name with arg names and values. convert html in arg values so you can view code.
        $function_sig_simple = (is_array($arg_names)) ? $function . '(' . implode(',', $arg_names) . ')' : $function . '()'; //function name with argument names
        $function_sig_super_simple = $function . '()'; //just the function name with parens, no arguments

        $fsig['function_sig'] = $function_sig;
        $fsig['function_sig_simple'] = $function_sig_simple;
        $fsig['function_sig_super_simple'] = $function_sig_super_simple;
        $fsig['args'] = $args;
        $fsig['class_path'] = $class_path;
        $fsig['function_path'] = $function_path;
        $fsig['function_line'] = $function_line;
        $fsig['function_location'] = $function_location;
        $fsig['function_comment'] = $function_comment;

        return $fsig;
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
    protected function _getGroupLabel($class, $function, $file) {
        /*
         * create groups for js console expand/collapse groups
         */



        $class = ($class === '') ? '' : $class . '::';
        $function = ($function === '') ? '' : $function . '()';
        $file = ($file === '') ? '<unknown>' . ' /' : $file . ' /';
//        if ($function == '') {
//            $group_label = basename($file);
//        } else {
//            $group_label = basename($file) . '/' . $class . $function;
//        }
//
        $group_label = basename($file) . $class . $function;
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
    private function _getLineNumbersPrefix($line, $class, $function, $file) {

        $prefix = '';
        $line_number_template = $this->getOption('line_numbers_prefix_template');


        if ($this->getOption('line_numbers_prefix_enabled')) {

            $search = array('{LINE}', '{CLASS}', '{FUNCTION}', '{FILE}');
            $replacements = array($line, $class, $function, $file);
            $prefix = str_replace($search, $replacements, $line_number_template);
        }

        return $prefix;
    }

    /**
     * Send to Output
     *
     * Formats and sends a debug message to browser output if configured for inline debugging, and makes it
     * available for the footer or console output
     *
     * @param none
     * @return void
     */
    protected function _sendToOutput($line, $class, $function, $file, $content, $same_line = true, $usePrefix = true) {

        if ($usePrefix === true) {


            $prefix = $this->_getLineNumbersPrefix($line, $class, $function, $file);


            if ($same_line) {
                $content = $prefix . $content;
            } else {
                $content = $prefix . '<br>' . $content;
            }
        }





        $group_label = $this->_getGroupLabel($class, $function, $file);
        //$this->stop(true, true);
        //    $this->_logToQueue($content, $group_label, null, true);
        $this->_echoInline($group_label, $content);

        $result['group_label'] = $group_label;
        $result['content'] = $content;

        return $result;
    }

    /**
     * Get Messages
     *
     * Returns and the array of debug messages added to queue
     *
     * @param none
     * @return void
     * @todo: replace with proper get/set debug_messages .
     */
    protected function _getMessages() {

        return ($this->_logToQueue());
    }

    /**
     * Echo Debug Message Inline
     *
     * Outputs the debug message inlin
     *
     * @param none
     * @return void
     */
    protected function _echoInline($group_label, $content) {


        static $last_group_label;


        if ('inline' === $this->getOption('browser_output')) {

            if ($last_group_label == $group_label) { //dont repeat the group if exactly the same
                // echo '<div>' . '<pre style="margin:20px;background-color:#E7E7E7;">' . $content . '</pre></div>';
                echo '<div>' . '<div style="background-color:#E7E7E7;">' . $content . '</div></div>';
            } else {

                //echo '<div style="background-color:#F1F1DA;">' . $group_label . '</div><div>' . '<pre style="margin:20px;background-color:#E7E7E7;">' . $content . '</pre></div>';
                echo '<div style="padding:5px;margin:5px 0 5px 0;background-color:#F1F1DA;"><strong>' . $group_label . '</strong></div><div>' . '<div style="background-color:#E7E7E7;">' . $content . '</div></div>';
            }
        }
        $last_group_label = $group_label;
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
    private function _inExcludedFilter($function) {

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

        if (in_array($function, $excluded_functions_filter)) {
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
    protected function _inFilters($class, $function, $always_debug) {

        //$args = func_get_args();

        /*
         * dont debug if debugging is off
         */

        if ($this->isOff()) {

            return false;
        }

        /*
         * Check for focus
         * A focus blocks all other debug output and shows only a single requested method
         * as determined by the $_GET variables
         */
//wpdev.com/wp-admin/admin.php?page=simpli_hello_menu30_test&simpli_debug_mask_class=Simpli_Addons_Simpli_Forms_Module_Form&simpli_debug_mask_method=renderElement
        //   $_GET['simpli_debug_mask_class'] = 'Simpli_Addons_Simpli_Forms_Module_Form';
        //   $_GET['simpli_debug_mask_method'] = 'renderElement';

        if ($this->_isFocused()) {
            if ($class === $this->_getFocusedClass() || ($class === get_class($this))) {

                if ($function === $this->_getFocusedMethod()) {
                    return true; //masks the class/method
                } else {
                    if ($class === get_class($this)) {
                        return true;
                    } else {
                        return false; //returns false if a method is set, but does not match the focus
                    }
                }
            } else {
                return false; //return false if class is set, but does not match the focus
            }
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

        if (in_array($function, array_keys($filters['enabled']))) {
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


//        $gv->addEdge(array('wake up' => 'visit bathroom'));
//        $gv->addEdge(array('visit bathroom' => 'make coffee'));
        //  echo $gv->image('jpg');



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
             * Browser Output
             * Where you want the output
             * 'footer' to output to footer, 'inline' to output at time of error , false for all output off
             */
            'browser_output' => 'inline',
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
     * hookAjaxGetProps
     *
     * Long Description
     *
     * @param none
     * @return void
     */
    public function hookAjaxGetProps() {
        $template = '
<div style = "margin:10px 0px 10px 0px;"> <strong style = "font-size:xx-large">Description</strong></div>
<p> {FUNCTION_COMMENT}</p>



<div style = "margin:10px 0px 10px 0px;"> <strong style = "font-size:xx-large">Location</strong></div>
<p> {TRACE_LOCATION}</p>

<div style = "margin:10px 0px 10px 0px;"> <strong style = "font-size:xx-large">Called From</strong></div>
<p > Line {LINE} in {FILE_PATH}</p>
<div style = "margin:10px 0px 10px 0px;"> <strong style = "font-size:xx-large">Arguments</strong></div>
{EXPANDED_ARGS}
<div style = "margin:10px 0px 10px 0px;"> <strong style = "font-size:xx-large">Defined Variables</strong></div>

{DEFINED_VARS}




{VISUAL_BACKTRACE}

';
        $search = array('{LINE}', '{FILE_PATH}', '{CLASS}', '{FUNCTION_SIG}', '{MARGIN}', '{BACKGROUND_COLOR}', '{EXPANDED_ARGS}', '{VISUAL_BACKTRACE}', '{LINK_TEXT}', '{FUNCTION_COMMENT}', '{DEFINED_VARS}', '{EXPLORE_URL}');
        $replacements = array($current_line, $trace_file_path, $current_class, $current_function_sig, $margin, $background_color, $trace_location, $expanded_args, $visual_backtrace, $link_text, $current_function_comment, $ds_function_defined_vars, $explore_url);
        $current_trace_html = str_replace($search, $replacements, $debug_trace_html_template);
    }

    /**
     * Print Trace Item
     *
     * Prints an item in the Master Trace
     *
     * @param array $props The properties of the trace item
     * @return void
     */
    private function _printTraceItem($props) {


        $class = $props['class'];
        $method_sig = $props['signature_simple'];
        $background_color = '';
        $margin = 0;


        $template = '<div style="padding:0px;margin:0px;"><div style="background-color:{BACKGROUND_COLOR};border:1px solid grey;padding:5px;text-align:left;display: inline-block;margin-left:5px;margin-top:5px;">
                <strong>{CLASS}::{METHOD_SIG}</strong>


                    <a class="simpli_debug_citem simpli_debug_getprops" href="#"><span>Info</span><span style="visibility:hidden;display:none">Collapse</span></a>


                    <div  style="display:none;visibility:hidden;padding:0px;margin:0px;"><!-- Collapsable -->


                    </div>

            </div>
        </div>';


        $template = $this->getPlugin()->getTools()->getHtmlWithoutWhitespace($template); //this is necessary since there are pre tags in the source . You could just remove it manually using a macro in a text editor , like the 'remove unnecessary whitespace' utility in notepad++ , but using getHtmlWithoutWhitespace allows us to retain the whitespace in our source file so its human readable, while still removing it when its displayed.
        /*
         * Now populate the html template
         */
        $search = array('{CLASS}', '{METHOD_SIG}', '{BACKGROUND_COLOR}', '{MARGIN}');
        $replacements = array($class, $method_sig, $background_color, $margin);
        $trace_html = str_replace($search, $replacements, $template);

        echo $trace_html;
    }

    /**
     * Hook Print Master Trace On Shutdown
     *
     * Long Description
     *
     * @param none
     * @return void
     */
    public function hookPrintMasterTraceOnShutdown() {
        $master_trace = $this->getMasterTrace();


        /*
         * Return if Master Trace is Empty
         * This can happen if no debug statements are being used (unlikely)
         * or for some reason the hook gets called prior to shutdown ( which has happened but not sure why...)
         *
         */
        if (empty($master_trace)) {
            return;
        }
        //  $this->v($master_trace, true);
//        $this->v('$master_trace = ', $master_trace, true);
//         echo '<pre>', print_r($master_trace['methods'], true), '</pre>';
        $methods = $master_trace['methods'];


        foreach ($methods as $method) {


            $prop_id = $method['prop_id'];

            $props = get_transient($this->getPlugin()->getSlug() . '_' . $this->getSlug() . 'trace_prop' . '_' . $prop_id);
            $this->_printTraceItem($props);
        }

//get_transient($this->getPlugin()->getSlug() . '_' . $this->getSlug() . 'trace_prop' . '_' . $prop_id, $props);
        //        echo $this->getVisualBacktrace($master_trace);
    }

    /**
     * Hook Print Log to Footer
     *
     * Outputs the debug log to the browser in the footer
     * @param string $content The shortcode content
     * @return string The parsed output of the form body tag
     */
    function hookPrintLogToFooter() {

        $vars = json_encode($this->getLocalVars());
        ?>
                <script type='text/javascript'>

                            var <?php echo $this->getSlug(); ?> = <?php echo $vars; ?>

                        </script>

                <?php
            }

            /**
             * Hook - Print Log to Console
             *
             * Prints Local Vars to the Javascript Console
             * @param string $content The shortcode content
             * @return string The parsed output of the form body tag
             */
            function hookPrintLogToConsole() {

                $vars = json_encode($this->getLocalVars());
                ?>
                <script type='text/javascript'>

                            var <?php echo $this->getSlug(); ?> = <?php echo $vars; ?>

                        </script>

                <?php
            }

            protected $_log_queue = null;

            /**
             * Log to Queue
             *
             *  Collects the debug messages for later printout by javascript or php.
             * @param string $message The debug message to add to the $debug_messages array
             * @param string $group_name (optional) If specified, can be used as a flag to indent or group the message.
             * @param bool $console_output Whether Messages will be written to the javascript console. This helps prevent firebug from reaching its log limit.
             * @return array $debug_messages All the debug messages
             */
            protected function _logToQueue($log_entry = null, $group_name = null, $subgroup_name = null, $console_output = true) {

                //   return $this->_addMessageToQueue($log_entry, $group_name, $subgroup_name, $console_output);
                // static $debug_messages = array();

                if (is_null($this->_log_queue)) {
                    $this->_log_queue = array();
                }


                if (is_null($log_entry)) {
                    return ($this->_log_queue);
                } else {
                    if (is_null($group_name)) {
                        $group_name = 'root'; /* tells the printer to show no indent */
                    }


                    $arr_member = array('text' => $log_entry, 'group_name' => $group_name, 'subgroup_name' => $subgroup_name, 'console_output' => $console_output);


                    $this->_log_queue[] = $arr_member;

                    //$debug_messages[]=array('text' => $message, 'group_name' => $group_name, 'subgroup_name' => $subgroup_name,'console_output'=>$console_output);
                }
            }

            /**
     * Add Message To Queue
     *
     *  Collects the debug messages for later printout by javascript or php.
     * @param string $message The debug message to add to the $debug_messages array
     * @param string $group_name (optional) If specified, can be used as a flag to indent or group the message.
     * @param bool $console_output Whether Messages will be written to the javascript console. This helps prevent firebug from reaching its log limit.
     * @return array $debug_messages All the debug messages
     */
    protected function _addMessageToQueue($message = null, $group_name = null, $subgroup_name = null, $console_output = true) {


        static $debug_messages = array();




        if (is_null($message)) {
            return ($debug_messages);
        } else {
            if (is_null($group_name)) {
                $group_name = 'root'; // tells the printer to show no indent
            }


            $arr_member = array('text' => $message, 'group_name' => $group_name, 'subgroup_name' => $subgroup_name, 'console_output' => $console_output);
            // array_push($debug_messages, $arr_member);
            $debug_messages[] = $arr_member;
            //$debug_messages[]=array('text' => $message, 'group_name' => $group_name, 'subgroup_name' => $subgroup_name,'console_output'=>$console_output);
        }
    }

}

