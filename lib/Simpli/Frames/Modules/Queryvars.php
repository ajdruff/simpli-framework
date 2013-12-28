<?php

/**
 * Query Module
 *
 * This module enables template redirection or can trigger methods actions based on query variables passed within a url or via pretty urls.
 *
 * @author Andrew Druffner
 * @package SimpliFramework
 *
 *
 */
/*
 * What does this module do ?
 * it allows you to 1) access any page or script in  your templates folder or perform any action executed by a method in this class using a request url in the form http://example.com?myvar=myval or http://example.com/my/pretty/url/
 *
 * How does it work?
 * It first 'white lists' a number of query variables, all beginning with a prefix you define. The default prefix is the slug of your plugin
 * The last part of the query variable is anything you want. Some predefined ones are:
 * 'action' so with the prefix, the url would look like http://example.com?simpli_frames_action=value  : This query variable calls any method in this class by passing the appropriate value defined by your add_action () function. For example, If you passed ?simpli_frames_action=sayHello, you could create a method called sayHello that would echo 'Hello'
 * 'page , so with the prefix, the url would look like http://example.com?simpli_frames_page=value  : In this case, the url would redirect the page to a template that is mapped to the value
 *
 * Note: query variable redirects work from the root of your site, not from a subdirectory:
 * Example: http://wpdev.com/?simpli_frames_action=phpInfo
 * They will also work within admin, but admin uses  a different way of checking for them ( it checks the $_GET parameters instead of wp_query_vars).
 *
 * How to Use this Module
 *
 * 1) Configure by editing the configure() method
 * 2) For every template you want to have access to, do this : $this->_pages = array('value'=>'relative_file_path.php') where 'value' is the value passed to ?simpli_frames_page
 * 3) For every action you want to trigger, add an 'add_action' just like the examples given , and write a function that maps to it.
 * For example, I want to trigger wordpress do return a database query when I pass request ?simpli_frames_action=latest-products
 * So I would , add the action:
 * add_action($this->_query_var_prefix . '_action' . '_latest-products', array($this, 'latestProducts'));
 * and then write a method called 'latestProducts' to return the database result
 * 4) To access these 'ugly urls' , define a pretty_url_pattern and its target ugly_url_pattern and add these to the $this->$_rewrite_rules array as in the examples.
 */



class Simpli_Frames_Modules_QueryVars extends Simpli_Frames_Base_v1c2_Plugin_Module {

    public $_query_var_prefix;
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
        $this->debug()->t();





        // Add global admin scripts
        //  add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));

        /*
         *
         * Register query variables
         *
         */
        add_filter('query_vars', array($this, 'registerQueryVar'));

        /*
         * check query variables for front end requests
         */

        add_filter('template_redirect', array($this, 'checkQueryVars'));

        /*
         * Check query variables for backend requests ( within admin)
         */
        add_action('admin_init', array($this, 'checkQueryVars'));


        /*
         *
         * Use the 'add_action' function to Map Methods to Query Variable Actions
         *
         *
         *
         * Several Examples Follow (need to uncomment to use), but you'll need to add your own
         * Format:
         * add_action($this->_query_var . '_action' . '<ActionValuePassedInQuery>', array($this, '<Method>'));


          add_action($this->_query_var_prefix . '_action' . '_sayHello', array($this, 'sayHello')); // Example 1: ?simpli_frames_action=sayHello
          add_action($this->_query_var_prefix . '_action' . '_sayGoodbye', array($this, 'sayGoodbye')); //Example 2: ?simpli_frames_action=sayGoodbye
          add_action($this->_query_var_prefix . '_action' . '_phpInfo', array($this, 'phpInfo')); // Example 3: ?simpli_frames_action=phpInfo
          add_action($this->_query_var_prefix . '_action' . '_test', array($this, 'test')); // Example 3: ?simpli_frames_action=phpInfo

         */

        //      add_action($this->_query_var_prefix . '_action' . '_upload_addon', array($this->plugin()->getModule('Menu20DevManage'), 'hookFormActionUploadAddon')); // ?simpli_frames_action=upload_addon




        /*
         *
         * Add Pretty Url Hook
         *
         */

        add_filter('rewrite_rules_array', array($this, 'rewriteRules'));

        /*
         * Flush Rewrite Rules Upon plugin activation
         *
         */



        //$this->plugin()->addActivateAction(array($this, 'flushRewriteRules'));
    }

    /**
     * Configure Module
     *
     * @param none
     * @return void
     */
    function config() {
        $this->debug()->t();


        /*
         * Query Variables
         *
         */

        $this->_query_var_prefix = $this->plugin()->QUERY_VAR;
        $this->_query_var_suffixes = array('action', 'page');

        /*
         *
         * Path to the Templates
         *
         */

        $this->_template_directory = $this->plugin()->getAdminDirectory() . '/templates'; // no ending slash


        /*
         *
         * Page mappings
         * The index is the value of the simpli_frames_page query variable
         * The value should be the path to the template from the template directory
         *
         */

        $this->_pages = array(
            '1' => 'query_vars_template_example1.php'   //e.g.: call this template by going to http://example.com?simpli_frames_page=1
            , '2' => 'query_vars_template_example2.php' //e.g.: call this template by going to http://example.com?simpli_frames_page=2
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
                , 'ugly_url_pattern' => 'index.php?' . $this->plugin()->getSlug() . '_action=' . '$matches[1]'
            )
            , array(//  http://example.com/mytemplate/1/
                'pretty_url_pattern' => 'mytemplate/(.+)/?$'
                , 'ugly_url_pattern' => 'index.php?' . $this->plugin()->getSlug() . '_page=' . '$matches[1]'
            )
            , array(//  http://example.com/mytemplate1/
                'pretty_url_pattern' => 'mytemplate1/?$'
                , 'ugly_url_pattern' => 'index.php' . $this->plugin()->getSlug() . '_page=1'
            )
            , array(//  http://example.com/awesome/
                'pretty_url_pattern' => 'awesome/?$'
                , 'ugly_url_pattern' => 'index.php?' . $this->plugin()->getSlug() . '_page=2'
            )
            , array(//  http://example.com/phpinfo/
                'pretty_url_pattern' => 'phpinfo/?$'
                , 'ugly_url_pattern' => 'index.php?' . $this->plugin()->getSlug() . '_action=phpinfo'
            )
        );
    }

    /**
     * Action Example 2 - Method
     *
     * Long Description
     * * @param none
     * @return void
     */
    function sayHello() {
        $this->debug()->t();

        echo '<br> Hello';
    }

    /**
     * Test
     *
     * Code anything you want here. for testing
     *
     * @param none
     * @return void
     */
    public function test() {

        echo $this->plugin()->tools()->url2dir(admin_url());
    }

    /**
     * Action Example 2 - Step 2 - Add the function
     *
     * This is the method that will fire when http://example.com/?simpli_frames_action=sayGoodbye is called.
     * * @param none
     * @return void
     */
    function sayGoodbye() {
        $this->debug()->t();

        echo '<br> Goodbye';
    }

    /**
     * Action Example 3 - Method
     *
     * Long Description
     * * @param none
     * @return void
     */
    function phpInfo() {
        $this->debug()->t();

        phpinfo();
    }

    /**
     * Register A Query Variable
     *
     * Filter Hook Function for action query_vars
     * White lists a query variable so we can have our plugin respond to query parameters
     *
     * @param string $query_vars
     * @return string $query_vars
     */
    function registerQueryVar($query_vars) {
        $this->debug()->t();

        /*
         * register the Plugin's Query Var
         */
        array_push($query_vars, $this->_query_var_prefix);

        /*
         * Register query variables in the form:
         * Example: simpli_frames_action  , simpli_frames_page
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
        $this->debug()->t();


        global $wp_query;


        $this->debug()->logVar('$_GET = ', $_GET);
        $this->debug()->logVar('$this->_query_var_suffixes = ', $this->_query_var_suffixes);
        $this->debug()->logVar('$wp_query->query_vars = ', $wp_query->query_vars);


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
        } else {  //admin requests dont care so just check if one of our variables are on $_GET
            foreach ($this->_query_var_suffixes as $suffix) {

                if (isset($_GET[$this->_query_var_prefix . '_' . $suffix])) {

                    $query_var = $this->_query_var_prefix . '_' . $suffix;
                    $query_var_value = $_GET[$this->_query_var_prefix . '_' . $suffix];
                    $return = false;
                }
            }
        }

        /*
         * if our query variables are not detected, return
         */


        if ($return) {
            return;
        }

        /*
         * If there is a match to a page , include it.
         */
        if (stripos($query_var, 'page') !== false) {

            require($this->_template_directory . '/' . $this->_pages[$query_var_value]);
            exit();
        } else {



            /*
             *
             * Execute any actions added for that query variable value
             *
             * For example, ?simpli-framework-action=reactivate would trigger any functions that were mapped using:
             * add_action('simpli_framework_action_reactivate','my_function);
             * $query_vars['simpli-framework-action']

             */

            do_action($query_var . '_' . $query_var_value);
        }
    }

    /**
     * Rewrite Rules
     *  Filter Hook Function for adding a wordpress rewrite rule
     *
     *
     * Note that this does not actually write anything to htaccess. The rule
     * is instead stored in the WordPress database
     * * @param none
     * @return void
     */
    function rewriteRules($rules) {
        $this->debug()->t();

        $newrules['action/(.+)/?$'] = 'index.php?' . $this->plugin()->getSlug() . '_action=' . '$matches[1]';

        foreach ($this->_rewrite_rules as $new_rule) {

            $newrules[$new_rule['pretty_url_pattern']] = $new_rule['ugly_url_pattern'];
        }


        return $newrules + $rules;
    }

}

