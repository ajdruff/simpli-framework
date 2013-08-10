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
     * Configure Module
     *
     * @param none
     * @return void
     */
    public function config() {


        /*
         * set defaults . if modules set their own options, they will override these
         */

        $this->setOption('FilterBypass', false); //bypasses all filters

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
        $this->debug()->setFilter('Simpli_Addons_Simpli_Forms_Module_Theme', true);






        /*
         * turn debugging on/off
         */
        $this->debug()->turnOn();
        //  $this->debug()->turnOff();

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

        $this->setOption('excluded_functions_filter_enabled', false);
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
    private function _getReflection($method) {

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
     * Variable (Function Version)
     *
     * Prints a Formatted Variable
     *
     * @param mixed
     * @return void
     */
    public function v($message, $var, $always_debug = false) {


        /**
         * Short Description
         *
         * Long Description
         *
         * @param none
         * @return void
         */
        #init
        $same_line = true;

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



        if (is_array($var) || is_object($var)) {
            $same_line = true;
            //  ob_start();
            $content = $message . '<pre>' . print_r($var, true) . '</pre>';
//            echo "$message<br>";

            //   $content = ob_get_clean();
        } else {
            $same_line = true;
            $content = "$message " . $var;
        }


        $this->_sendToOutput($line, $class, $function, $file, $content, $same_line);
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
    private function _debug_backtrace() {

        $arr_backtrace = debug_backtrace();
        array_shift($arr_backtrace); //removes the current function
        array_shift($arr_backtrace); //removes previous function
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
    private function _getDebugStatementProperties($array_backtrace, $wrapper = false) {
        if ($wrapper) { //if this function is being called through a wrapper, then have to remove one additional level
            array_shift($array_backtrace);
        }
        $props['line'] = (isset($array_backtrace[0]['line']) ? $array_backtrace[0]['line'] : '');
        $props['file'] = (isset($array_backtrace[0]['file']) ? $array_backtrace[0]['file'] : '');
        $props['class'] = (isset($array_backtrace[1]['class']) ? $array_backtrace[1]['class'] : '');
        $props['function'] = (isset($array_backtrace[1]['function']) ? $array_backtrace[1]['function'] : '');
        return $props;
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
    public function t($always_debug = false, $levels = 1) {
        $properties = $this->_getDebugStatementProperties(debug_backtrace(), false);




        $ds_line = $properties['line'];
        $ds_class = $properties['class'];
        $ds_function = $properties['function'];
        $ds_file = $properties['file'];




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
         * Slice the array for the number of levels we want
         * Reverse it so we get oldest first

         */

        /*
         * slice array only if level provided is sane
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
                'args' => null
            );
            /*
             * Remove Object Element
             * in case you want to dump the backtrace for troubleshooting this function,we remove
             * the object element in the backtrace array since we dont use it and it takes up
             * too much ouput
             */
            unset($functions['object']); //remove object since they take up too much space to print







            /*
             * Validate and define $current_ variables
             */
            $functions = array_merge($defaults, $functions);
            $current_line = $functions['line'];
            $current_function = $functions['function'];
            $current_class = $functions['class'];
            $current_file = $functions['file'];
            $current_args = $functions['args'];


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

            $current_function_sig = $fsig['sig'];

            /*
             * create the expanded args string from the $args array returned from the _getFunctionSignature function
             * Expanded args just means if the args are an array , they will formatted vertically for easier reading
             */
            $expanded_args = '<pre>' . print_r($fsig['args'], true) . '</pre>';


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


            $debug_trace_html_template = '<div style="padding:0px;margin:0px;"><div style="height:50px;background-color:{BACKGROUND_COLOR};border:1px solid grey;padding:5px;text-align:left;display: inline-block;margin-left:{MARGIN}px;margin-top:5px;">
                <strong>{CLASS}->{FUNCTION}</strong>

                <div >
                    <a class="simpli_debug_more" href="#"><em>{LINK_TEXT}</em></a>
                </div>

                <div >

                    <div  class="simpli_debug_toggle" style="display:none;visibility:hidden;padding:0px;margin:0px;">

                        <span><strong><em>Method Location:</em></strong>&nbsp;&nbsp;{TRACE_LOCATION}</span><br/>

                        <span><strong><em>Called From:</em></strong>&nbsp;&nbsp;Line {LINE} in  file {FILE_PATH} </span><br/>

                        <span ><strong><em>Arguments:</em></strong>&nbsp;&nbsp;{EXPANDED_ARGS}</span><br/>
                        {VISUAL_BACKTRACE}

</div>
                </div>
            </div>
        </div>';
            /*
             * Now populate the html template
             */
            $search = array('{LINE}', '{FILE_PATH}', '{CLASS}', '{FUNCTION}', '{MARGIN}', '{BACKGROUND_COLOR}', '{TRACE_LOCATION}', '{EXPANDED_ARGS}', '{VISUAL_BACKTRACE}', '{LINK_TEXT}');
            $replacements = array($current_line, $trace_file_path, $current_class, $current_function_sig, $margin, $background_color, $trace_location, $expanded_args, $visual_backtrace, $link_text);
            $current_trace_html = str_replace($search, $replacements, $debug_trace_html_template);
            $current_trace_html = $this->getPlugin()->getTools()->getHtmlWithoutWhitespace($current_trace_html); //this is necessary since for some reason, layout is impacted due to the whitespace in the source. You could remove it using the 'remove unnecessary whitespace' untility in notepad++ but this allows us to instead to retain the whitespace in our source so its human readable, while still removing it when its displayed.


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
            $visual_backtrace = $this->getVisualBacktrace($traces);
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
        $function_line = '';
        $function_location = '';
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
        }

        $fsig['sig'] = $function . '(' . $arg_string . ')';
        $fsig['args'] = $args;
        $fsig['class_path'] = $class_path;
        $fsig['function_path'] = $function_path;
        $fsig['function_line'] = $function_line;
        $fsig['function_location'] = $function_location;

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
     * Short Description
     *
     * Long Description
     *
     * @param none
     * @return void
     */
    protected function _sendToOutput($line, $class, $function, $file, $content, $same_line = true) {


        $prefix = $this->_getLineNumbersPrefix($line, $class, $function, $file);


        if ($same_line) {
            $content = $prefix . $content;
        } else {
            $content = $prefix . '<br>' . $content;
        }







        $group_label = $this->_getGroupLabel($class, $function, $file);
        $this->_addMessageToQueue($content, $group_label, null, true);
        $this->_echoInline($group_label, $content);
    }

    /**
     * Collects the debug messages for later printout by javascript or php.
     *
     * Long Description


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
                $group_name = 'root'; /* tells the printer to show no indent */
            }


            $arr_member = array('text' => $message, 'group_name' => $group_name, 'subgroup_name' => $subgroup_name, 'console_output' => $console_output);
            array_push($debug_messages, $arr_member);
            //$debug_messages[]=array('text' => $message, 'group_name' => $group_name, 'subgroup_name' => $subgroup_name,'console_output'=>$console_output);
        }
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

        return ($this->_addMessageToQueue());
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
                echo '<div>' . '<div style="margin:20px;background-color:#E7E7E7;">' . $content . '</div></div>';
            } else {

                //echo '<div style="background-color:#F1F1DA;">' . $group_label . '</div><div>' . '<pre style="margin:20px;background-color:#E7E7E7;">' . $content . '</pre></div>';
                echo '<div style="background-color:#F1F1DA;">' . $group_label . '</div><div>' . '<div style="margin:20px;background-color:#E7E7E7;">' . $content . '</div></div>';
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
        $classes = $traces['classes'];
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

    splines=false; //forces straight edges
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

}

