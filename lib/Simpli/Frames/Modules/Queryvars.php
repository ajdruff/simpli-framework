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
    protected $_page_templates = array();
    protected $_external_redirects = array();


    /*
     * WordPress Rewrite Rules Array
     * For rewrite rules that are not written to .htaccess
     */
    private $_wp_rewrite_rules = array();

    /**
     * Add Hooks
     *
     * Adds WordPress Hooks, triggered during module initialization
     * @param none
     * @return void
     */
    public function addHooks() {
        $this->debug()->t();
        /*
         * flush the rewrite rules if just activted
         */




        add_action( 'init', array( $this, 'hookAddHtAccessRewriteRules' ) );
        /*
         * disable in production since it flushes rules with every refresh
         */


        if ( $this->plugin()->DEBUG ) {


            add_action( 'init', 'flush_rewrite_rules' );

}


        /*
         *
         * Register query variables
         *
         */
        add_filter( 'query_vars', array( $this, 'hookRegisterQueryVars' ) );

        /*
         * check query variables for front end requests
         */

        add_filter( 'template_redirect', array( $this, 'hookCheckQueryVars' ) );

        /*
         * Check query variables for backend requests ( within admin)
         */
        add_action( 'admin_init', array( $this, 'hookCheckQueryVars' ) );

        /*
         * External Redirect
         */


        add_filter( 'template_redirect', array( $this, 'hookCheckForExternalRedirects' ) );


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



        /*
         *
         * Add Actions for Page Redirects
         *
         */




        /*
         * Add Action for Template Rendering - Permalink Action Show Template
         * 
         * Renders a Template
         * 
         * 
         */
        add_action( $this->_query_var_prefix . '_action' . '_permalinkActionShowTemplate', array( $this->plugin()->getModule( 'Core' ), 'permalinkActionShowTemplate' ) );








        /*
         *
         * Add Pretty Url Hook
         *
         */

        add_filter( 'rewrite_rules_array', array( $this, 'hookAddWPRewriteRules' ) );

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



//echo $this->plugin()->tools()->getRelativePath(ABSPATH,$this->plugin()->getDirectory() . '/lib/txt2imgr/$1');
        /*
         * Query Variables
         *
         */

        $this->_query_var_prefix = $this->plugin()->QUERY_VAR;
        $this->_query_var_suffixes = array( 'action', 'page' );


        /*
         * Set White Listed Query Variables
         *
         * Add other query variables here that you need white listed, meaning that WordPress
         * will return them when using get_query_var() method and are accessible within actions
         * that are called using a wp rewrite rules redirect
         * 
         * 
         * Example:
         *         $this->setConfig(
                'QUERY_VARS'
                , array(
            'domain_name',
            'user',
            'next_tickerid',
            'nstock_tag_callback'
            , 'nstock_template'
                )
        );
         * 
         * 
         * 
         */



        $this->setConfig(
                'QUERY_VARS'
                , array(
            ''
                )
        );
        /*
         * Template Directory
         *
         * Set the path that holds the pages that a request from ?page='mytemplate' displays
         */
        $this->setConfig(
                'TEMPLATE_DIRECTORY'
                , dirname( dirname( $this->plugin()->getDirectory() ) ) . '/content/published/_jekyll-output/templates' // no ending slash
        );




        /*
         * Add a Page Template (Example)
         * 
         * Map the name of the template to the file name
         */


        $this->addPageTemplate( 'mytemplate', 'query_vars_template_example1.php' );













        /*
         * Action Pretty Url
         * will call an action using the pretty url /action/myaction/
         * 
         * http://example.com/action/sayHello/
        
        $this->addWPRewriteRule(
                'action/(.+)/?$' //$match_pattern - 
                , 'index.php?' . $this->plugin()->getSlug() . '_action=' . '$matches[1]'   //$target_pattern - 
        );
 */
        
        
        /*
         * PhpInfo Pretty Url (Example)
         * 
         * Calls PhpInfo ( comment out in production ) 
         * 
         * http://example.com/phpinfo/
         
        $this->addWPRewriteRule(
                'phpinfo/{0,1}(.*)$' //$match_pattern - 
                , 'index.php?' . $this->plugin()->getSlug() . '_action=phpinfo'   //$target_pattern - 
        );
*/
        
        


        /*
         * Example of an external redirect
         * 
         * $this->addExternalRedirect( '^/google', 'http://google.com' ); #target must begin with a slash
         */

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
     * Hook Registers Query Variables
     *
     * Filter Hook Function for action query_vars
     * White lists the configured query variables so we can have our plugin respond to query parameters
     *
     * @param string $query_vars
     * @return string $query_vars
     */
    function hookRegisterQueryVars( $query_vars ) {
        $this->debug()->t();

        /*
         * Set default for QUERY_VARS
         *
         * Long Description
         */
        $this->setConfigDefault(
                'QUERY_VARS'
                , array()
        );

        /*
         * register the Plugin's Query Var
         */
        array_push( $query_vars, $this->_query_var_prefix );

        /*
         * Register query variables in the form:
         * Example: simpli_frames_action  , simpli_frames_page
         */


        foreach ( $this->_query_var_suffixes as $suffix ) {
            array_push( $query_vars, $this->_query_var_prefix . '_' . $suffix );
}

        /*
         * Now add any additional query variables that were configured
         *
         */
        foreach ( $this->QUERY_VARS as $query_var ) {
            array_push( $query_vars, $query_var );
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
    function hookCheckQueryVars() {
        $this->debug()->t();



        global $wp_query;


        $this->debug()->logVar( '$_GET = ', $_GET );
        $this->debug()->logVar( '$this->_query_var_suffixes = ', $this->_query_var_suffixes );
        $this->debug()->logVar( '$wp_query->query_vars = ', $wp_query->query_vars );


        $return = true;
        /*
         * If none of our query variables were passed, return.
         */

        if ( !is_admin() ) { //front end requests must be within the $wp_query->query_va rs array
            foreach ( $this->_query_var_suffixes as $suffix ) {
                if ( isset( $wp_query->query_vars[ $this->_query_var_prefix . '_' . $suffix ] ) ) {
                    $query_var = $this->_query_var_prefix . '_' . $suffix;
                    $query_var_value = $wp_query->query_vars[ $this->_query_var_prefix
                            . '_' . $suffix ];
                    $return = false;
}
}
} else {  //admin requests dont care so just check if one of our variables are on $_GET
            foreach ( $this->_query_var_suffixes

            as $suffix ) {

                if ( isset( $_GET[ $this->
                                _query_var_prefix . '_' . $suffix ] ) ) {

                    $query_var = $this->_query_var_prefix
                            . '_' . $suffix;
                    $query_var_value = $_GET[ $this->_query_var_prefix
                            . '_' . $suffix ];
                    $return = false;
}
}
}

        /*
         * if our query variables are not detected, return
         */


        if ( $return ) {
            return;
}

        $this->debug()->logVar( '$query_var_value = ', $query_var_value );

        /*
         * If there is a match to a page , include it.
         */
        if ( stripos( $query_var
                        , 'page' ) !== false ) {

            require($this->TEMPLATE_DIRECTORY . '/' . $this->_page_templates[ $query_var_value ] );
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

            do_action( $query_var . '_' . $query_var_value );
}
    }

    /**
     * Add WP Rewrite Rule
     *
     * Adds a WordPress Rewrite Rule to the rewrite rules array.
     * It will not write to htacces and will not execute any redirects except in the form
     * 'index.php?myvar=myvalue'
     * If you pass query variables this way, they will only be accessible if they are white listed first.

     *
     * @param none
     * @return void
     */
    public function addWPRewriteRule( $match_pattern, $target_pattern ) {
        $new_rule[ 'match_pattern' ] = $match_pattern;
        $new_rule[ 'target_pattern' ] = $target_pattern;

        $this->_wp_rewrite_rules[] = $new_rule;
    }

    /**
     * Hook Add WP Rewrite Rule
     *  Filter Hook Function for adding a wordpress rewrite rule that does not use htaccess.
     * These rules are stored in the WordPress database, not in htaccess.
     * You cannot redirect to just any url. It will ignore any valid redirect url except in the form 'index.php?myvar=myvalue' and recognizes
     * matches . 
     *
     * * @param none
     * @return void
     */
    function hookAddWPRewriteRules( $rules ) {

        $this->debug()->t();

        $new_rules[ 'action/(.+)/?$' ] = 'index.php?' . $this->plugin()->getSlug() . '_action=' . '$matches[1]';

        $this->debug()->logVar( '$this->_wp_rewrite_rules = ', $this->_wp_rewrite_rules );

        foreach ( $this->_wp_rewrite_rules as $new_rule ) {

            $new_rules[ $new_rule[ 'match_pattern' ] ] = $new_rule[ 'target_pattern' ];
}

        $this->debug()->logVar( 'New rules = ', $new_rules + $rules );

        return $new_rules + $rules;
    }

    /**
     * Add External Redirect
     *
     * Adds an External Redirect. This will redirect the browser using PHP's
     * Header functions, and as such, will always result in the browser
     * address bar changing to the destination url, hence, we call it an external redirect.
     * It works great for a true external redirect, but can also be used for internal redirect 
     * if you dont want to mess with flushing rules and dont care if the browser address bar changes.
     *
     * @param none
     * @return void
     */
    public function addExternalRedirect( $match_pattern, $target_url ) {

        $this->_external_redirects[ $match_pattern ] = $target_url;


    }

    /**
     * Hook - Check for External Redirects
     *
     * Does a Permanent Redirect ( Browser Address Bar will change)
     *
     * @param none
     * @return void
     */
    public function hookCheckForExternalRedirects() {
        $redirects = $this->_external_redirects;
        $this->debug()->logVar( '$redirects = ', $redirects );



        foreach ( $redirects as $match_pattern => $target_url ) {


            $this->debug()->logVar( '$target_url = ', $target_url );
            $this->debug()->logVar( '$match_pattern = ', $match_pattern );
            if ( (preg_match( '|' . $match_pattern . '|', $_SERVER[ 'REQUEST_URI' ], $matches ) === 0 ) ) {

                continue;
}
            $this->debug()->logVar( '$match_pattern = ', $match_pattern );
            $this->debug()->logVar( '$matches = ', $matches );

            global $wp_query;
            $wp_query->is_404 = false;
            status_header( '200' );

            $this->debug()->logVar( '$matches = ', $matches );

            /*
             * need to remove the part of the uri that matched, or will get an endless loop
             */
            $redirect_url = $target_url . str_replace( $matches[ 0 ], '', $_SERVER[ 'REQUEST_URI' ] );
            $this->debug()->logVar( '$redirect_url = ', $redirect_url );


            header( "HTTP/1.1 302 Moved Temporarily" );

            header( "Location: " . $redirect_url );
            exit();




}
    }

    protected $_htaccess_rewrite_rules = null;

    /**
     * Add .htaccess Rule
     *
     * Add a re-write rule to the .htaccess file. Use this method from within config()
     * The hookAddHtAccessRewriteRules will be the one that actually adds them.
     *
     * @param none
     * @return void
     */
    public function addHtAccessRewriteRule( $match_pattern, $target_pattern, $position = 'top' ) {

        $new_rule[ 'match_pattern' ] = $match_pattern;
        $new_rule[ 'target_pattern' ] = $target_pattern;
        $new_rule[ 'position' ] = $position;
        $this->_htaccess_rewrite_rules[] = $new_rule;
    }

    /**
     * Hook - Add Ht Access Rules
     *
     * Fired by init, adds all the configured  .htaccess rules to the .htaccess file
     *
     * @param none
     * @return void
     */
    public function hookAddHtAccessRewriteRules() {
        if ( is_null( $this->_htaccess_rewrite_rules ) ) {
            return;
}
        foreach ( $this->_htaccess_rewrite_rules as $htaccess_rewrite_rule ) {
            add_rewrite_rule(
                    $htaccess_rewrite_rule[ 'match_pattern' ], // The regex to match the incoming URL
                    $htaccess_rewrite_rule[ 'target_pattern' ], // the url
                    $htaccess_rewrite_rule[ 'position' ] //whether to place it on the top or bottom relative to the other rules.
            );

}

        $this->plugin()->doPersistentAction( $this->plugin()->getSlug() . '_flush_rewrite_rules' );
    }

    /**
     * Add Page Template
     *
     * Maps the query variable {slug}_page value to a template file.
     * Usage: $this->addPageTemplate('domains','domains.tpl.php');
     * 
     * @param string $query_value The value of the {slug}_page query variable identifying the template
     * @param string $template_file_base_name The file name of the template 
     * @return void
     */
    public function addPageTemplate( $query_value, $template_file_base_name )
    {

        //     $new_page_template[ $query_value ] = $template_file_base_name;


        $this->_page_templates[ $query_value ] = $template_file_base_name;


}



}
