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
        $this->debug()->turnOn();
        /*
         * Where you want the output
         * 'footer' to output to footer, 'inline' to output at time of error , false for all output off
         */
        $this->setOption('browser_output', 'inline');

        /*
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

    }

    public function stop($line, $class, $function, $file, $always_debug) {

        if (!$this->_inFilters($class, $function, $always_debug)) {
            return;
        }

        die("<br/>$line  " . basename($file) . " Debug Exit <br>");
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
     * Variable
     *
     * Prints a Formatted Variable
     *
     * @param mixed
     * @return void
     */
    public function v($line, $class, $function, $file, $always_debug = false, $message, $var) {




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
     * Trace
     *
     * Generates a simple output of the current method or a full backtrace if you set levels >1
     * Usage:
     *    simple trace : $this->debug()->_t(__LINE__, get_class($this), __FUNCTION__, __FILE__, false, debug_backtrace(), false, 1);
     *     full backtrace with 5 levels:
      $this->debug()->_t(__LINE__, get_class($this), __FUNCTION__, __FILE__, false, debug_backtrace(), true, 5)
     * @param none
     * @return void
     */
    public function t($line, $class, $function, $file, $always_debug, $arr_btrace, $arg_expansion = true, $levels = 5) {



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
        $previous_method = '';
        $current_method = '';
        $current_class = '';
        $previous_class = '';
        $padding = 5;
        $background_color = '#E3DDB2';
        /*
         * Slice the array for the number of levels we want
         * Reverse it so we get oldest first
         */
        $arr_btrace_rev = array_reverse(array_slice($arr_btrace, 0, $levels)); //we want to start from t
        foreach ($arr_btrace_rev as $key => $functions) {
            if (in_array($functions['function'], $exclude_these_from_output)) {
                continue; /* dont show the functions in $exclude_these_from_output array since they are internal and not interesting */
            }

            $current_method = $functions['function'];


            if (isset($functions['class'])) {
                $current_class = $functions['class'];
            } else {
                $current_class = '';
            }

            if (($counter != 0) && ($current_class != $previous_class) || (($current_class == '') && ($current_method != $previous_method))) {

                static $toggle = true;
                $toggle = !$toggle && true;

                $previous_method = $current_method;
                $previous_class = $current_class;
                $counter++;  //shift the next method block to the right
                $padding = $counter * 50;
                $background_color = ($toggle) ? '#E3DDB2' : '#ECC084'; //#CCCCCC'; //alternates colors when there is a change
            }



            if (($functions['function'] === 'do_action')) {

                $functions['function'] = $functions['function'] . '( \'' . current_filter() . '\' )';
            }
            /* add arguments to last called function */
            $fn = '';
            if ((!is_null($functions['args']))) {
                $functions['function'] = $functions['function'] . '(';
                $values = array_values($functions['args']);
                $fn = '$' . urldecode(http_build_query($values, null, ',$') . ')');
                if (($fn) == '$)') {
                    $fn = ')';
                }
                $functions['function'] = $functions['function'] . $fn;
            }

            if ($arg_expansion === true) {
                $argument_expansion = '<pre>' . print_r($functions['args'], true) . '</pre>';
            } else {
                $argument_expansion = '';
            }


            unset($arr_btrace[$key]['object']); //remove object since they take up too much space to print
            $debug_message = sprintf('<div><div style="background-color:%6$s;border:1px solid grey;display: inline-block;margin-left:%5$spx;margin-top:20px;"> %1$s: %4$s ->%2$s %3$s </div></div>', basename($functions['file']), $functions['function'], $argument_expansion, $current_class, $padding, $background_color
            );
            $debug_messages .= $debug_message;
        }

        //  echo '<div>' . $debug_messages . '</div>';
        $content = '<div>' . $debug_messages . '</div>';
        //$this->v(__LINE__, __CLASS__, __FUNCTION__, __FILE__, $always = false, '', print_r($debug_messages, true));
        //    $this->v(__LINE__, __CLASS__, __FUNCTION__, __FILE__, $always = false, '$arr_btrace', $arr_btrace);
        //   die('exiting on ' . __LINE__ . __METHOD__);


        $this->_sendToOutput($line, $class, $function, $file, $content);
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
     * @param string $option_name
     * @return mixed
     */
    public function getOption($option_name) {
        $options = $this->getOptions();
        return $options[$option_name];
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
            return array(
                'browser_output' => 'inline', /* 'footer' to output to footer, 'inline' to output at time of error , false for all output off */
                'log_all_actions' => false, // true/false,
                'console' => false /* true,false to output to the javascript console */
            );
        }
        return $this->_options;
    }

    /**
     * Adds javascript and stylesheets
     * WordPress Hook - enqueue_scripts
     *
     * @param none
     * @return void
     */
    public function enqueue_scripts() {

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
     * Long Description
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

}

