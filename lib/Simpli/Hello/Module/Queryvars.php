<?php

/**
 * Query Module
 *
 * This module enables template redirection or can trigger methods actions based on query variables passed within a url or via pretty urls.
 *
 * @author Andrew Druffner
 * @package SimpliFramework
 * @subpackage SimpliHello
 *
 */
/*
 * What does this module do ?
 * it allows you to 1) access any page or script in  your templates folder or perform any action executed by a method in this class using a request url in the form http://example.com?myvar=myval or http://example.com/my/pretty/url/
 *
 * How does it work?
 * It first 'white lists' a number of query variables, all beginning with a prefix you define. The default prefix is the slug of your plugin
 * The last part of the query variable is anything you want. Some predefined ones are:
 * 'action' so with the prefix, the url would look like http://example.com?simpli_hello_action=value  : This query variable calls any method in this class by passing the appropriate value defined by your add_action () function. For example, If you passed ?simpli_hello_action=sayHello, you could create a method called sayHello that would echo 'Hello'
 * 'page , so with the prefix, the url would look like http://example.com?simpli_hello_page=value  : In this case, the url would redirect the page to a template that is mapped to the value
 *
 *
 * How to Use this Module
 *
 * 1) Configure by editing the configure() method
 * 2) For every template you want to have access to, do this : $this->_pages = array('value'=>'relative_file_path.php') where 'value' is the value passed to ?simpli_hello_page
 * 3) For every action you want to trigger, add an 'add_action' just like the examples given , and write a function that maps to it.
 * For example, I want to trigger wordpress do return a database query when I pass request ?simpli_hello_action=latest-products
 * So I would , add the action:
 * add_action($this->_query_var_prefix . '_action' . '_latest-products', array(&$this, 'latestProducts'));
 * and then write a method called 'latestProducts' to return the database result
 * 4) To access these 'ugly urls' , define a pretty_url_pattern and its target ugly_url_pattern and add these to the $this->$_rewrite_rules array as in the examples.
 */



class Simpli_Hello_Module_Queryvars extends Simpli_Basev1c0_Plugin_Module {

    private $_query_var_prefix;
    private $_query_var_suffixes = array();
    private $_pages = array();
    private $_rewrite_rules = array();



    /**
     * Add Hooks
     *
     * Adds WordPress Hooks, triggered during module initialization
     * @param none
     * @return void
     */
    public function addHooks() {





        // Add global admin scripts
        //  add_action('admin_enqueue_scripts', array(&$this, 'admin_enqueue_scripts'));

        /*
         *
         * Register query variables
         *
         */
        add_filter('query_vars', array(&$this, 'registerQueryVar'));

        /*
         * check query variables for front end requests
         */

        add_filter('template_redirect', array(&$this, 'checkQueryVars'));

        /*
         * Check query variables for backend requests ( within admin)
         */
        add_action('admin_init', array(&$this, 'checkQueryVars'));


        /*
         *
         * Use the 'add_action' function to Map Methods to Query Variable Actions
         *
         *
         *
         * Several Examples Follow, but you'll need to add your own
         * Format:
         * add_action($this->_query_var . '_action' . '<ActionValuePassedInQuery>', array(&$this, '<Method>'));
         */

        add_action($this->_query_var_prefix . '_action' . '_sayHello', array(&$this, 'sayHello')); // Example 1: ?simpli_hello_action=sayHello
        add_action($this->_query_var_prefix . '_action' . '_sayGoodbye', array(&$this, 'sayGoodbye')); //Example 2: ?simpli_hello_action=sayGoodbye
        add_action($this->_query_var_prefix . '_action' . '_phpinfo', array(&$this, 'phpInfo')); // Example 3: ?simpli_hello_action=phpInfo



        /*
         *
         * Add Pretty Url Hook
         *
         */

        add_filter('rewrite_rules_array', array(&$this, 'rewriteRules'));

        /*
         * Flush Rewrite Rules Upon plugin activation
         *
         */



        $this->getPlugin()->addActivateAction(array(&$this, 'flushRewriteRules'));

    }


    /**
     * Configure Module
     *
     * @param none
     * @return void
     */
    function config() {

        /*
         * Query Variables
         *
         */

        $this->_query_var_prefix = $this->getPlugin()->getSlug();
        $this->_query_var_suffixes = array('action', 'page');

        /*
         *
         * Path to the Templates
         *
         */

        $this->_template_directory = $this->getPlugin()->getDirectory() . '/templates'; // no ending slash


        /*
         *
         * Page mappings
         * The index is the value of the simpli_hello_page query variable
         * The value should be the path to the template from the template directory
         *
         */

        $this->_pages = array(
            '1' => 'template1.php'   //e.g.: call this template by going to http://example.com?simpli_hello_page=1
            , '2' => 'template2.php' //e.g.: call this template by going to http://example.com?simpli_hello_page=2
        );

        /*
         * Pretty Url Patterns
         * Enables access to
         */

        $this->_rewrite_rules = array(
            /*
             * Examples , Add or edit your own
             */
            array(//  http://example.com/action/sayHello/
                'pretty_url_pattern' => 'action/(.+)/?$'
                , 'ugly_url_pattern' => 'index.php?simpli_hello_action=' . '$matches[1]'
            )
            , array(//  http://example.com/mytemplate/1/
                'pretty_url_pattern' => 'mytemplate/(.+)/?$'
                , 'ugly_url_pattern' => 'index.php?simpli_hello_page=' . '$matches[1]'
            )
            , array(//  http://example.com/mytemplate1/
                'pretty_url_pattern' => 'mytemplate1/?$'
                , 'ugly_url_pattern' => 'index.php?simpli_hello_page=1'
            )
            , array(//  http://example.com/awesome/
                'pretty_url_pattern' => 'awesome/?$'
                , 'ugly_url_pattern' => 'index.php?simpli_hello_page=2'
            )
            , array(//  http://example.com/phpinfo/
                'pretty_url_pattern' => 'phpinfo/?$'
                , 'ugly_url_pattern' => 'index.php?simpli_hello_action=phpinfo'
            )
        );
    }

    /**
     * Action Example 2 - Method
     *
     * Long Description
     * @param string $content The shortcode content
     * @return string The parsed output of the form body tag
     */
    function sayHello() {
        echo '<br> Hello';
    }

    /**
     * Action Example 2 - Step 2 - Add the function
     *
     * This is the method that will fire when http://example.com/?simpli_hello_action=sayGoodbye is called.
     * @param string $content The shortcode content
     * @return string The parsed output of the form body tag
     */
    function sayGoodbye() {
        echo '<br> Goodbye';
    }

    /**
     * Action Example 3 - Method
     *
     * Long Description
     * @param string $content The shortcode content
     * @return string The parsed output of the form body tag
     */
    function phpInfo() {
        phpinfo();
    }

    /**
     * Register A Query Variable
     *
     * Filter Hook Function for action query_vars
     * White lists a query variable so we can have our plugin respond to query paramaters
     *
     * @param string $query_vars
     * @return string $query_vars
     */
    function registerQueryVar($query_vars) {

        /*
         * Register query variables in the form:
         * Example: simpli_hello_action  , simpli_hello_page
         */

        foreach ($this->_query_var_suffixes as $suffix) {
            array_push($query_vars, $this->_query_var_prefix . '_' . $suffix);
        }




        return $query_vars;
    }

    /**
     * Check for Query Variables
     * Filter Hook Function for action redirect_template
     *
     * Checks for any query variables for the framework, and then executes the actions associated with them .
     *
     * @param none
     * @return void
     */
    function checkQueryVars() {

        global $wp_query;






        $return = true;
        /*
         * If none of our query variables were passed, return.
         */

        if (!is_admin()) { //front end requests must be within the $wp_query->query_vars array
            foreach ($this->_query_var_suffixes as $suffix) {
                if (isset($wp_query->query_vars[$this->_query_var_prefix . '_' . $suffix])) {
                    $query_var = $this->_query_var_prefix . '_' . $suffix;
                    $query_var_value = $wp_query->query_vars[$this->_query_var_prefix . '_' . $suffix];
                    $return = false;
                }
            }
        } elseIf (!is_admin()) {  //admin requests dont care so just check if one of our variables are on $_GET
            foreach ($this->_query_var_suffixes as $suffix) {
                if (isset($_GET[$this->_query_var_prefix . '_' . $suffix])) {
                    $query_var = $this->_query_var_prefix . '_' . $suffix;
                    $query_var_value = $_GET[$this->_query_var_prefix . '_' . $suffix];
                    $return = false;
                }
            }
        }




        if ($return) {
            return;
        }

        /*
         * If there is a match to a page , include it.
         */
        if (stripos($query_var, 'page') !== false) {

            require($this->_template_directory . '/' . $this->_pages[$query_var_value]);
            die();
        } else {



            /*
             *
             * Execute any actions added for that query variable value
             *
             * For example, ?simpli-framework-action=reactivate would trigger any functions that were mapped using:
             * add_action('simpli_framework_action_reactivate','my_function);
             * $query_vars['simpli-framework-action']
             *             echo '<br>' . $query_var; echo '<br>query var value=' . $query_var_value;
              echo '<pre>';
              print_r($_GET);
              echo '</pre>';
              echo '<pre>';
              print_r($wp_query->query_vars);
              echo '</pre>';
             */

            do_action($query_var . '_' . $query_var_value); // instead of get? what happens in admin ?
        }
    }

    /**
     * Rewrite Rules
     *  Filter Hook Function for adding a wordpress rewrite rule
     *
     *
     * Note that this does not actually write anything to htaccess. The rule
     * is instead stored in the WordPress database
     * @param string $content The shortcode content
     * @return string The parsed output of the form body tag
     */
    function rewriteRules($rules) {
        $newrules['action/(.+)/?$'] = 'index.php?simpli_hello_action=' . '$matches[1]';

        foreach ($this->_rewrite_rules as $new_rule) {

            $newrules[$new_rule['pretty_url_pattern']] = $new_rule['ugly_url_pattern'];
        }


        return $newrules + $rules;
    }

    /**
     * Flush Rewrite Rules
     *  Filter Hook Function for activation flushing rewrite rules
     *
     *
     * @global type $pagenow
     * @global type $wp_rewrite
     */
    function flushRewriteRules() {
        global $wp_rewrite;

        $wp_rewrite->flush_rules();

        if ($this->getPlugin()->getLogger()->getLoggingState() === true) {
            echo '<br> Logger: activated flush rewrite rules from ' . __METHOD__;
        }
    }

}