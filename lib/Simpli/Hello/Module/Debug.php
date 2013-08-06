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
         * set defaults . when modules set their options, they will override these
         */



        /*
         * turn debugging on/off
         */
        $this->debug()->turnOn();


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

        $this->visualizeBacktrace('');
        // die('<br>exiting after testing visualize backtrace');
        //require_once 'C:\cygwin\usr\share\pear\gv.php';

        /*
         * Argument Expansion
         * whether you want the arguments to expand to a formatted cascading array
         * in trace output
         */
        $this->debug()->setOption('argument_expansion', false);




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
        $inline_deps = array();
        $external_deps = array('jquery');
        $footer = false;
        $this->getPlugin()->enqueueInlineScript($handle, $path, $inline_deps, $external_deps, $footer);
        $this->getPlugin()->getLogger()->log('Added debug javascript ' . $path);
    }

    /**
     * Stop Wrapper ( Use within a Method )
     *
     * Wrapper around dstop() that provides a shorter signature when used within a method
     * Uses _getReflection which derives the class,function,and file from __METHOD__
     *
     * @param none
     * @return void
     */
    public function stop($line, $method, $always_debug, $condition, $condition_message) {

        extract($this->_getReflection($method));

        $this->dstop($line, $class, $function, $file, $always_debug, $condition, $condition_message);
    }

    /**
     * Stop  (Function Version)
     *
     * Exits PHP directly or upon optional condition
     *
     * @param none
     * @return void
     */
    public function dstop($line, $class, $function, $file, $always_debug, $condition = true, $condition_message = '') {

        if (!$this->_inFilters($class, $function, $always_debug)) {
            return;
        }
        $stop_message = ' <div style="color:red">Debug Stop - to continue script, remove the stop() function or edit its condition on line ' . $line . ' in file ' . basename($file) . ' <br/><span style="color:black;">(' . $file . ' )' . ' </span></div>';

        if ($condition_message !== '') {
            $stop_message = ' <div style="color:red">Debug Stop - ' . $condition_message . '<br/> To continue script, remove the stop() function or edit its condition on line ' . $line . ' in file ' . basename($file) . ' <br/><span style="color:black;">(' . $file . ' )' . ' </span></div>';
        }

        if ($condition) {
            die('$stop_message');
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
    public function e($line, $class, $function, $file, $always_debug = false, $message) {

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
     * Variable (Method Version)
     *
     * Shorter wrapper for use in methods.
     * Uses _getReflection which derives the class,function,and file from __METHOD__
     *
     * @param none
     * @return void
     */
    public function v($line, $method, $always_debug = false, $message, $var) {

        extract($this->_getReflection($method));

        $this->dv($line, $class, $function, $file, $always_debug, $message, $var);
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
        //    print_r($arg_names);


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
    public function dv($line, $class, $function, $file, $always_debug = false, $message, $var) {



        /*
         * check filters and debug state
         */
        if (!$this->_inFilters($class, $function, $always_debug)) {
            return;
        }



        if (is_array($var) || is_object($var)) {
            ob_start();
            echo "$line $message<br>";
            print_r($var);
            $content = ob_get_clean();
        } else {
            $content = "$line $message = $var<br>";
        }


        $this->_sendToOutput($line, $class, $function, $file, $content);
    }

    /**
     * Trace Wrapper ( Use within a Method )
     *
     * Wrapper around dt that provides a shorter signature when used within a method
     * Uses _getReflection which derives the class,function,and file from __METHOD__
     *
     * @param none
     * @return void
     */
    public function t($line, $method, $always_debug, $arr_btrace, $levels) {

        extract($this->_getReflection($method));


//                echo '<br> Class in Wrapper =  ' . $class;
//        echo '<br> Method in Wrapper  =  ' . $function;
//        echo '<br> $file in Wrapper  =  ' . $file;


        $this->dt($line, $class, $function, $file, $always_debug, $arr_btrace, $levels, $arg_names);
    }

    /**
     * Trace ( Use within a function )
     *
     * Generates a simple output of the current method or a full backtrace if you set levels >1
     * Usage:
     *    simple trace : $this->debug()->_t(__LINE__, get_class($this), __FUNCTION__, __FILE__, false, debug_backtrace(), false, 1);
     *     full backtrace with 5 levels:
      $this->debug()->_t(__LINE__, get_class($this), __FUNCTION__, __FILE__, false, debug_backtrace(), true, 5)
     * @param none
     * @return void
     */
    public function dt($line, $class, $function, $file, $always_debug, $arr_btrace, $levels = 5, $arg_names = null) {
        //  $classes = $traces['classes'];
        //  $trace_methods = $traces['methods'];
//  foreach ($arr_btrace as $key => $functions) {
//
//            unset($functions['object']); //remove object since they take up too much space to print
//            $ref_vars = ($this->_getReflection($functions['class'] . '::' . $functions['function']));
//         //   echo '<br>ref vars=<pre>', print_r($ref_vars, true), '</pre>';
//
//
//
//            echo '$arr_btrace<pre>', print_r($functions, true), '</pre>';
//        }
        //   die('stop');
        if (!$this->_inFilters($class, $function, $always_debug)) {
            return;
        }


        $output = '';
        $exclude_these_from_output = array(
            'do_shortcode',
            'load_template',
            'locate_template',
            'preg_replace_callback',
            'do_shortcode_tag',
            'call_user_func',
            'include',
            'call_user_func_array',
            'require_once',
            'apply_filters',
            'do_action_ref_array',
            'main',
            'require'
        );
        $debug_messages = ''; //initialize messages array
        $counter = 0; //keep track of number of branches so we can add appropriate formatting
        $shift_counter = 0;
        $previous_method = '';
        $current_method = '';
        $current_class = '';
        $previous_class = '';
        $padding = 5;
        $background_color = '#E3DDB2';
        /*
         * Slice the array for the number of levels we want
         * Reverse it so we get oldest first

          if ($levels > 1) {
          $backtrace_array = array_reverse(array_slice($arr_btrace, 0, $levels));
          } else {
          $backtrace_array = array_slice($arr_btrace, 0, $levels);
          }
         */

        //   $arr_btrace = array_slice($arr_btrace, 1, 1);
        $backtrace_array = array_reverse(array_slice($arr_btrace, 0, $levels));
        foreach ($backtrace_array as $key => $functions) {
            if (in_array($functions['function'], $exclude_these_from_output)) {
                continue; /* dont show the functions in $exclude_these_from_output array since they are internal and not interesting */
            }

//            $reflected_vars = ($this->_getReflection($functions['class'] . '::' . $functions['function']));
//            $functions['file'] = $reflected_vars['file'];
//            $functions['class'] = $reflected_vars['class'];
//            $functions['function'] = $reflected_vars['function'];
//            $functions['file'] = $reflected_vars['file'];
            /*
             * sometimes the file is not provided
             * this may be due to the way hooks are implemented
             * check for this and attempt to get it using the reflection class
              if (!isset($functions['file'])) {

              if (isset($functions['class'])) {
              $r = new ReflectionClass($class);
              $functions['file'] = $r->getFileName();
              } else {

              $functions['file'] = '[unknown_file]';
              }
              }
             *
             *  */




            $current_method = $functions['function'];



            if (isset($functions['class'])) {
                $current_class = $functions['class'];
            } else {
                $current_class = '';
            }
//            echo '<br> counter = ' . $counter;
//            echo '<br> $current_class = ' . $current_class;
//
//            echo '<br> $previous_class = ' . $previous_class;
//            echo '<br> $current_method = ' . $current_method;
//            echo '<br> $previous_method = ' . $previous_method;
//            echo '<br> 2';
            if ((($counter !== 0) && ($current_class !== $previous_class)) || (($current_class === '') && ($current_method != $previous_method))) {

                static $toggle = true;
                $toggle = !$toggle && true;

//                $previous_method = $current_method;
//                $previous_class = $current_class;
                $shift_counter++;  //shift the next method block to the right
                $padding = $shift_counter * 50;
                $background_color = ($toggle) ? '#E3DDB2' : '#ECC084'; //#CCCCCC'; //alternates colors when there is a change

            }

            /*
             * This may not be necessary , its a holdover from original code
             * its intent was to create a human readable function sig for do_action
             */

            //         if (($functions['function'] === 'do_action')) {
//
            //             $functions['function'] = $functions['function'] . '( \'' . current_filter() . '\' )';
            //        }
            //     public function _getFunctionSignature($function, $arg_names, $function_args, $arg_expansion = true) {
//            $function_args = $functions['args'];
//            $function = $functions['function'];
//            $fsig = $function;
//            $arg_expansion = $this->getOption('argument_expansion');


            /*
             * modify the function string so its human readable, adding arguments if available
             */
            $arg_expansion = $this->getOption('argument_expansion');
            $fsig = $this->_getFunctionSignature($functions['class'], $functions['function'], $arg_names, $functions['args'], $arg_expansion);
            $functions['function'] = $fsig['sig'];

            /*
             * create the expanded args string from the $args array returned from the _getFunctionSignature function
             */
            $expanded_args = '<pre>' . print_r($fsig['args'], true) . '</pre>';

            /*
             * get the file path of the class using reflection

              echo '<br>' . __LINE__ . $functions['class'] . '::' . $functions['function'] . '<br>';
              // $reflected_vars = $this->_getReflection($functions['class'], $functions['function']);
              $reflected_vars = $this->_getReflectionWrapper($functions['class'], $functions['function']);
              print_r($reflected_vars);
              echo '<br>' . __LINE__;
              $class_path = $this->getPlugin()->getTools()->makePathRelative($this->getPlugin()->getDirectory(), $reflected_vars['file']);
              echo '<br>' . __LINE__ . '<br>';
             */
            $class_path = $fsig['class_path'];

            unset($backtrace_array[$key]['object']); //remove object since they take up too much space to print
//            echo '<br> Class in dt =  ' . $class;
//            echo '<br> Method in dt  =  ' . $function;
//            echo '<br> $file in dt  =  ' . $functions['file'];

            $trace_file_path = $this->getPlugin()->getTools()->makePathRelative($this->getPlugin()->getDirectory(), $functions['file']);






        $debug_trace_html_template = '<div style="padding:0px;margin:0px;"><div style="height:50px;background-color:{BACKGROUND_COLOR};border:1px solid grey;padding:5px;text-align:left;display: inline-block;margin-left:{MARGIN}px;margin-top:5px;">
                <strong>{CLASS}->{FUNCTION}</strong>

                <div >
                    <a class="simpli_debug_more" href="#"><em>More</em></a>
                </div>

                <div >

                    <div  class="simpli_debug_toggle" style="display:none;visibility:hidden;padding:0px;margin:0px;">

                        <span><strong><em>Location:</em></strong>&nbsp;&nbsp;{CLASS_PATH}</span><br/>

                        <span><strong><em>Called From:</em></strong>&nbsp;&nbsp;Line {LINE} in  file {FILE_PATH} </span><br/>

                        <span ><strong><em>Expanded Args:</em></strong>&nbsp;&nbsp;{EXPANDED_ARGS}</span><br/>
                    </div>
                </div>
            </div>
        </div>';
            /*
             * Now populate the html template
             */
            $search = array('{LINE}', '{FILE_PATH}', '{CLASS}', '{FUNCTION}', '{MARGIN}', '{BACKGROUND_COLOR}', '{CLASS_PATH}', '{EXPANDED_ARGS}');
            $replacements = array($functions['line'], $trace_file_path, $current_class, $functions['function'], $padding, $background_color, $class_path, $expanded_args);
            $debug_message = str_replace($search, $replacements, $debug_trace_html_template);
           $debug_message = $this->getPlugin()->getTools()->getHtmlWithoutWhitespace($debug_message); //this is necessary since for some reason, layout is impacted due to the whitespace in the source. You could remove it using the 'remove unnecessary whitespace' untility in notepad++ but this allows us to instead to retain the whitespace in our source so its human readable, while still removing it when its displayed.

            $debug_messages .= $debug_message;
            $previous_method = $current_method;
            $previous_class = $current_class;
            $counter++;
        }


        $content = '<div>' . $debug_messages . '</div>';


        $this->_sendToOutput($line, $class, $function, $file, $content);
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
    public function _getFunctionSignature($class, $function, $arg_names, $arg_values, $arg_expansion = false) {



        $reflected_vars = ($this->_getReflection($class . '::' . $function));
        $class_path = $this->getPlugin()->getTools()->makePathRelative($this->getPlugin()->getDirectory(), $reflected_vars['file']);

        // $functions['class'] = $reflected_vars['class'];
        // $functions['function'] = $reflected_vars['function'];
        //  $functions['file'] = $reflected_vars['file'];


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



            $fsig['sig'] = $function . '(' . $arg_string . ')';
            $fsig['args'] = $args;
            $fsig['class_path'] = $class_path;
//            if (($arg_expansion === true) && (!empty($args))) {
//                $argument_expansion_html = '<pre>' . print_r($args, true) . '</pre>';
//            } else {
//                $argument_expansion_html = '';
//            }
//
//            $fsig = $fsig . $argument_expansion_html . '<div><em style="color:grey;font-size:medium;">' . '(Method ' . $class . '->' . $function . '() is located in ' . $class_path . ') </em></div>';
//        } else {
//            $fsig = $function . '()';
        }
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
        if ($function == '') {
            $group_label = basename($file);
        } else {
            $group_label = $class . '->' . $function . "() /" . basename($file);
        }
        return ($group_label);
    }

    /**
     * Short Description
     *
     * Long Description
     *
     * @param none
     * @return void
     */
    protected function _sendToOutput($line, $class, $function, $file, $content) {



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
                echo '<div>' . '<pre style="margin:20px;background-color:#E7E7E7;">' . $content . '</pre></div>';
            } else {

                echo '<div style="background-color:#F1F1DA;">' . $group_label . '</div><div>' . '<pre style="margin:20px;background-color:#E7E7E7;">' . $content . '</pre></div>';
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
        //  echo '<pre>', print_r($args, true), '</pre>';
        /*
         * dont debug if debugging is off
         */

        if ($this->isOff()) {
            return false;
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
     * Outputs a graphical representation of the backtrace
     *
     * @param array $traces
     * contains the classes and the methods in the format contained in the example in the comments within the function
     *
     * @return void
     */
    public function visualizeBacktrace($traces) {

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

            return; //if graphviz is not enabled, dont attempt to graph
        }
//ref:http://bebo.minka.name/k2thingz/doxydoc/html/d9/d49/class_image___graph_viz_e13b94d8b7b7e83d3c36f819110bf929.html#e13b94d8b7b7e83d3c36f819110bf929


        $traces = array(
            'classes' => array(
                'class1'
                , 'class2'
                , 'class3'
            ),
            'methods' => array(array(
                    'class' => 'class1'
                    , 'function' => 'function3'
                )
                , array(
                    'class' => 'class2'
                    , 'function' => 'function1'
                )
                , array(
                    'class' => 'class3'
                    , 'function' => 'function1'
                )
                , array(
                    'class' => 'class2'
                    , 'function' => 'function2'
                )
                , array(
                    'class' => 'class1'
                    , 'function' => 'function2'
                )
                , array(
                    'class' => 'class3'
                    , 'function' => 'function1'
                )
                , array(
                    'class' => 'class2'
                    , 'function' => 'function1'
                )
            )
        );



        /*
         * extrace the classes and traces into difference arrays
         */
        $classes = $traces['classes'];
        $trace_methods = $traces['methods'];


        $cluster_template = ' subgraph cluster{CLASS} {
            node [style = filled, color = white];
            style = filled;
            color = lightgrey;
          {METHODS}
            label = "{CLASS}";
        }
        ';
        $digraph_template = 'digraph G {
    {CLUSTERS}
    // Method Connections
    {CONNECTIONS}

    splines=false; //forces straight edges
}';
        $clusters_dot_markup = '';

        $connections = '';
        foreach ($classes as $class) {


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


                    $method1 = '"' . $trace['class'] . "::" . $trace['function'] . '"'; //e.g.: MyClass::MyFunction to create a unique node name
                    $methods.= $method1 . '[label=' . $trace['function'] . '];'; //add it to a $methods string that we can add to the class cluster, give it a label that only includes its function name since the cluster will identify its class

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




        echo '<pre>';
        echo str_replace(';', ';<br>', $graph_dot_markup);
        echo '</pre>';

        $this->gvGraphString($graph_dot_markup);

        die('exiting');



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
     * Long Description
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
            echo $img;
        } else {
            echo '<br> The image could not be rendered.';
        }
    }

    /**
     * Get Default Option
     *
     * Provides a default option value if it wasnt set by the user
     *
     * @param none
     * @return void
     */
    public function _getDefaultOption($option_name) {

        $default_options = array(
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
             * Argument Expansion
             * whether you want the arguments to expand to a formatted cascading array
             * in trace output
             */
            'argument_expansion' => false,
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
            'log_all_actions' => false
        );

        if (isset($default_options[$option_name])) {
            return($default_options[$option_name]);
        } else {
            return null;
        }
    }

}

