<?php

/**
 * Nomstock Com World
 *
 * @author Andrew Druffner
 * @package SimpliFramework
 * @property string $SEDO_LINK_TEMPLATE Sedo.com link template to the domain listing

 * @property string $AFTERNIC_LINK_TEMPLATE Afternic.com link template to the domain listing
 * @property string $CONTACT_OWNER_LINK_TEMPLATE A template for the link to the owner's contact form
 *  @property string $EMAIL_FROM_DEFAULT The default from email address that will be used to send emails
 *  @property string $EMAIL_HOST The host that will be used for sending emails.
 *  @property string $EMAIL_AUTH Whether Email Authentication is required when sending Emails
 *  @property string $EMAIL_PORT The port to be used when sending emails.
 *  @property string $EMAIL_USERNAME The username used for authentication when sending Emails.
 * @property string $EMAIL_PASSWORD The password to be used when sending emails.

 */
class Nomstock_Com_Plugin extends Nomstock_Com_Base_v1c2_Plugin {

    /**
     * Add Hooks
     *
     * Adds WordPress Hooks.
     * @param none
     * @return void
     */
    public function addHooks() {

/*
	* required to get the catalyst header to display properly
*/
remove_action( 'template_redirect', 'catalyst_add_stylesheets' );
add_action( 'template_redirect', 'catalyst_add_stylesheets', 0 );


        /*
         * Save Activation Errors for later display
         */
        add_action( 'activated_plugin', array( $this, 'save_activation_error' ) );

        /*
         * Show Activation Error
         *
         */

        add_action( 'admin_notices', array( $this, 'show_activation_extra_characters' ) );





        /*
         * Enqueue the framework's namespace script so we can namespace our javascript
         * Add our local variables
         *
         */
        if ( is_admin() ) {
            /*
             * Enqueue Inline and External Scripts
             */
            add_action( 'admin_enqueue_scripts', array( $this, 'hookEnqueueScripts' ), 0 );
            /*
             * Print Header Inline Scripts
             */
            add_action( 'admin_print_scripts', array( $this, 'hookPrintInlineHeaderScripts' ) );
            /*
             * Print Local Vars ( always print to the footer)
             */
            add_action( 'admin_print_footer_scripts', array( $this, 'hookPrintLocalVars' ) );
            /*
             * Print Footer Inline Scripts
             */
            add_action( 'admin_print_footer_scripts', array( $this, 'hookPrintInlineFooterScripts' ) );
        } else {
            /*
             * Enqueue Inline and External Scripts
             */
            add_action( 'wp_enqueue_scripts', array( $this, 'hookEnqueueScripts' ) );
            /*
             * Print Header Inline Scripts
             */
            add_action( 'wp_print_scripts', array( $this, 'hookPrintInlineHeaderScripts' ) );
            /*
             * Print Local Vars ( always print to the footer)
             */
            add_action( 'wp_print_footer_scripts', array( $this, 'hookPrintLocalVars' ) );
            /*
             * Print Footer Inline Scripts
             */
            add_action( 'wp_print_footer_scripts', array( $this, 'hookPrintInlineFooterScripts' ) );

//            add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
//            add_action('wp_print_footer_scripts', array($this, 'printLocalVars'));
//            add_action('wp_print_footer_scripts', array($this, 'printInlineFooterScripts'));
        }










        $this->debug()->log( 'Plugin Directory: ' . $this->getDirectory() );
        $this->debug()->log( 'Module Directory: ' . $this->getModuleDirectory() );


        $this->debug()->log( 'Plugin URL: ' . $this->getUrl() );
    }

    /**
     * Configure
     *
     * Configures Plugin
     *
     * @param none
     * @return void
     */
    public function config() {


        $this->debug()->t();

        /*
         *  Load any libraries you need that may not be included with the default wordpress installation
         */

        if ( !class_exists( 'WP_Http' ) )
            include_once( ABSPATH . WPINC . '/class-http.php' );



        /*
         * Sedo Template
         *
         * Link Template
         */
        $this->setConfig(
                'SEDO_LINK_TEMPLATE'
                , '<a href="http://www.sedo.de/search/details.php4?domain={domain_name}&amp;language=us">Sedo</a>'
        );
        /*
         * Afternic Link Template
         *
         * Listing Link Template
         */
        $this->setConfig(
                'AFTERNIC_LINK_TEMPLATE'
                , '<a href="<a href="http://www.afternic.com/name.php?domain={domain_name}">Afternic</a>'
        );

        /*
         * Contact Owner Link Template
         *
         * Listing Link Template
         */
        $this->setConfig(
                'CONTACT_OWNER_LINK_TEMPLATE'
                , '<a href="/contact/us/?domain={domain_name}">Direct from Seller</a>'
        );


        /*
         * Contact Owner Link Template
         *
         * Listing Link Template
         */
        $this->setConfig(
                'LISTING_EXPIRATION_HOURS'
                , 99999
        );

  
        /*
         * EMAIL_FROM
         *
         * The default from email address that will be used to send emails
         */
        $this->setConfig(
        'EMAIL_FROM_DEFAULT'
        , 'siteadmin@nomstock.com'
        );    
        
        /*
         * EMAIL_HOST
         *
         * The host that will be used for sending emails.
         */
        $this->setConfig(
        'EMAIL_HOST'
        , 'ssl://in.mailjet.com'
        );
        
        
        /*
         * EMAIL_AUTH
         *
         * Whether Email Authentication is required when sending Emails. True or False
         */
        $this->setConfig(
        'EMAIL_AUTH'
        , true
        );

        
        /*
         * EMAIL_PORT
         *
         * The port to be used when sending emails.
         */
        $this->setConfig(
        'EMAIL_PORT'
        , 465
        );
        
        /*
         * EMAIL_USERNAME
         *
         * The username used for authentication when sending Emails.
         */
        $this->setConfig(
        'EMAIL_USERNAME'
        , MAILJET_API_KEY //this is MailJet's API Key as set in wp-config.php
        );

        /*
         * EMAIL_PASSWORD
         *
         * The password to be used when sending emails.
         */
        $this->setConfig(
        'EMAIL_PASSWORD'
        , MAILJET_SECRET_KEY //this is MailJet's Secret Key as set in wp-config.php
        );
        
 

        /*
         * DISABLED_MODULES
         *
         * Disable any modules that you don't want loaded
         * Usage Example:
          $this->setConfig(
          'DISABLED_MODULES'
          , array('QueryVars', 'Shortcodes')
          );
         */
        $this->setConfig(
                'DISABLED_MODULES'
                , array(
            'ExampleModule'
//            , 'PostUserOptions'
//            , 'Menu10DevGetStarted'
//            , 'Menu20DevManage'
//            , 'Menu30Advanced'
//            , 'PostUserOptions'
//            , 'Tools'
//            , 'Queryvars'
//            , 'Shortcodes'
                //   , 'Tools'
                )
        );



        /*
         * ALWAYS_ENABLED_REGEX_PATTERN
         *
         * sets the regex pattern that allows matching modules to remain loaded even after the user selects 'disabled' from the plugin options. This allows the user to continue to acccess the admin options to re-enable the plugin. Must be set prior to the call to loadModules or it will be igored.
         */
        $this->setConfig(
                'ALWAYS_ENABLED_REGEX_PATTERN'
                , '/menu|admin/s'
        );


        /*
         * COMPRESS
         *
         * Compress with Zlib
         */
        $this->setConfig(
                'COMPRESS'
                , false
        );

        /*
         * CSS Path
         *
         * Directory Path to the CSS File
         */
        $this->setConfig(
                'URL_CSS'
                , dirname(dirname($this->getURL())) . '/content/published/_jekyll-output/css'
        );
        
        /*
         * DISABLED_ADDONS
         *
         * Disable any Addons that you don't want loaded.
         */
        $this->setConfig(
                'DISABLED_ADDONS'
                , array()
        );

        $this->setConfig(
                'ALLOW_SHORTCODES'
                , true
        );
        $this->setConfig(
                'DEBUG'
                , true
        );

        
        
        

        /*
         *
         * Start Default User Options
         *
         * A user option must have a default
         * or it wont be recognized.
         * After adding a new default, you must run 'update all settings' from the
         * maintenance panel for it to be recognized in the save and load routines.
         *
         */






        /*
         *
         * Defaults for Example Settings in 'Example Settings Metabox'
         *
         */

        /*
         * Radio Button Setting Example
         * 'yes' or 'no'
         */
        //      $this->setUserOptionDefault(
        //           'radio_setting', 'yes'
        //    );





        /*
         *
         * End Default Plugin User Options
         *
         */
    }

    /**
     * Plugin Shutdown
     *
     * called by register_shutdown_function. Takes cleanup actions here. use this instead of __destruct because some objects
     * are not available within __destruct  ( e.g. get_transient will fail)
     *
     * @param none
     * @return void
     */
    public function shutdown() {

        /*
         * Update Persistent Actions
         *
         * Remove any persistent actions that have been taken
         * so they dont keep getting taken for subsequent requests.
         */


        $persistent_actions = get_transient( $this->getSlug() . '_persistent_actions' );




        foreach ( $persistent_actions as $action_name => $action ) {
            if ( $action[ 'action_taken' ] === true ) {

                unset( $persistent_actions[ $action_name ] );
            }
        }


        set_transient( $this->getSlug() . '_persistent_actions', $persistent_actions );
    }

    /**
     * Deactivate Plugin
     *
     * Add any actions that need to take place during plugin deactivation here.
     *
     * @param none
     * @return void
     */
    public function deactivatePlugin() {
        flush_rewrite_rules(true);
    }

    /**
     * Activate Plugin
     *
     * Installs the plugin when user activates it
     *
     * @param none
     * @return void
     */
    public function activatePlugin() {
        $this->debug()->t();
    

        /*
         * Execute all the Activate Actions
         * Due to the way that WordPress handles the activation events,its not possible to add a custom hook and use the add_action function
         * in the normal way to enable modules to hook into this event.
         * Instead, we implement the actions a different way, through the Plugin class's AddActivateAction method.
         * So now, we just need to cycle through the actions, calling each method as they are provided. Note its not possible at this time to
         * include arguments.
         *
         *
         * Instead of the variable methods used here, call_user_func(array($object, $method)) could be used instead.
         * variable method calls were chosen instead so that we had more informative output for debugging
         * ( call_user_function would hide the calling class and method).
         */


        $activate_actions = $this->getActivateActions();

        foreach ( $activate_actions as $action ) {

            $object = $action[ 0 ];
            $method = $action[ 1 ];
            $this->debug()->log( 'Calling Activate Action : ' . get_class( $object ) . '::' . $method );

            $object->$method();
            //
        }




        /*
         *
         * Add any installation routines that you need
         * Modify as necessary if single or multi-site
         *
         */

        /*
         * Extend a Hook to trigger other activation actions
         */
        do_action( $this->getSlug() . '_activated' );

        /*
         * Flush rewrite rules
         *
         * Add a 'doPersistentAction($this->getSlug() . '_flush_rewrite_rules') after any code that requires
         * rewrite rules flushing. the flush will occur immediately after the user activates the plugin, and at the point
         * in the code where the 'doPersistentAction' method is called.
         */

        $this->addPersistentAction( $this->getSlug() . '_flush_rewrite_rules', 'flush_rewrite_rules' );
    }

}
