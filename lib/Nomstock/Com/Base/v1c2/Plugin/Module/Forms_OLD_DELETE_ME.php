<?php

/**
 * Forms Utility Class
 *
 * Loads the necessary javascript to manage non-admin forms. Admin forms are handled using the metabox class.
 *
 *
 * @author Andrew Druffner
 * @package SimpliFramework
 * @subpackage SimpliBasev1c2
 */
class Nomstock_Com_Base_v1c2_Plugin_Module_Forms extends Nomstock_Com_Base_v1c2_Plugin_Module_Helper {

    /**
     * Config
     *
     * Configures the current object
     *
     * @param array $page_check_cb Callback method in the form $object,'method'
     * @return void
     */
    public function config() {

        /*
         * Give a chance for the module to configure
         */
        $this->_config();

        /*
         * add any additional hooks
         */
        $this->_addHooks();
    }

    /**
     * Config (internal)
     *
     * Add any method calls that need to be called when the object is first configured.
     *
     * @param none
     * @return void
     */
    protected function _config() {
        $this->hookEnqueueScripts();
    }

    /**
     * Add Hooks (internal)
     *
     * Adds any actions needed for the class
     *
     * @param none
     * @return void
     */
    protected function _addHooks() {
        $this->debug()->t();






        /*
         * Add a hook for showing a form response message after a redirect.
         */

        add_action( $this->plugin()->QUERY_VAR . '_action' . '_form_response', array( $this, 'hookShowResponseMessageAfterRedirect' ) ); // ?$this->plugin()->QUERY_VAR . '_action'=form_response will execute this action

        /*
         * Add Scripts
         */
        add_action( 'wp_enqueue_scripts', array( $this, 'hookEnqueueScripts' ) );


    }

    /**
     * Hook - Enqueue Scripts
     *
     * Enqueue Scripts and Styles
     *
     * @param none
     * @return void
     */
    public function hookEnqueueScripts() {



        /*
         * Add javascript for form submission
         *
         *
         *
         */


        $handle = $this->plugin()->getSlug() . '_form-menu-events.js';

        $path = $this->plugin()->getDirectory() . '/admin/js/form-menu-events.js';
        $inline_deps = array();
        $external_deps = array( 'jquery' );
        $this->plugin()->enqueueInlineScript( $handle, $path, $inline_deps, $external_deps );
        $this->debug()->log( 'loaded script ' . $handle );


        $handle = $this->plugin()->getSlug() . '_form-menu-hooks.js';

        $path = $this->plugin()->getDirectory() . '/admin/js/form-menu-hooks.js';
        $inline_deps = array();
        $external_deps = array( 'jquery' );
        $this->plugin()->enqueueInlineScript( $handle, $path, $inline_deps, $external_deps );
        $this->debug()->log( 'loaded script ' . $handle );


        $handle = $this->plugin()->getSlug() . '_form-response.js';

        $path = $this->plugin()->getDirectory() . '/admin/js/form-form-response.js';
        $inline_deps = array();
        $external_deps = array( 'jquery' );
        $this->plugin()->enqueueInlineScript( $handle, $path, $inline_deps, $external_deps );
        $this->debug()->log( 'loaded script ' . $handle );




        $handle = $this->plugin()->getSlug() . '_form-submit.js';



        $path = $this->plugin()->getDirectory() . '/admin/js/form-submit.js';
        $inline_deps = array();
        $external_deps = array( 'jquery' );
        $this->plugin()->enqueueInlineScript( $handle, $path, $inline_deps, $external_deps );
        $this->debug()->log( 'loaded script ' . $handle );

        /*
         * Add some additional variables we'll need
         */

        $vars[ 'forms' ][ 'referer_url_field_name' ] = '_simpli_forms_referer_url'; //note the underscore
        $vars[ 'forms' ][ 'ajaxurl' ] = admin_url( 'admin-ajax.php' ); //provide ajax url to forms using ajax on the front end. wordpress doesnt define it outside of admin




        /*
         * Localize the variables we added above
         */

        $this->debug()->logVar( '$vars = ', $vars );
        $this->plugin()->setLocalVars( $vars );




        /*
         * create the nonces
         */


        $this->_createNonces();
    }

    /**
     * Create Nonces
     *
     * Create the WordPress Nonces that will be needed for our forms.
     * Will create a default nonce, unique for the menu, that will be used
     * for any form action. If unique nonces are enabled, a nonce will
     * be created for each method that starts with hookFormAction.
     * All nonces will be made available to the javaqscript at
     * <namespace>.forms.nonce_values[$action_slug] where $action_slug is
     * a shortened version of the hookFormAction method that is called when
     * the ajax form is submitted.
     *
     * @param none
     * @return void
     */
    protected function _createNonces() {



#init
        $vars = array();



        /*
         * Create the default Nonce Value for Form Submission
         * This will be added to the ajax request when submitting the form.
         */
        $this->module()->setConfig( 'NONCE_DEFAULT_VALUE', wp_create_nonce( $this->module()->NONCE_DEFAULT_ACTION ) );
        $this->debug()->log( 'Created Default Nonce using NONCE_DEFAULT_ACTION = ' . $this->module()->NONCE_DEFAULT_ACTION );

        /*
         * Generate Unique Nonces
         *
         * Generate Unique Nonces for each hookFormAction and each hookFormAction method contained in this class.             *
         * If the user wants to generate unique nonces,
         * then create them using reflection and then pass them to
         * javascript
         *
         */


        if ( $this->module()->NONCE_UNIQUE_ENABLED ) {
            /*
             * use reflection to get all the public method names of the module's current class
             */
            $all_methods = $this->plugin()->tools()->getMethodsNames( get_class( $this->module() ), ReflectionMethod::IS_PUBLIC );


            /*
             * now filter for those methods that
             * contain hookFormAction
             * indicating they are a valid ajax action
             */
            $ajax_action_methods = $this->plugin()->tools()->getStringsWithSubstring( array( 'hookFormAction' ), $all_methods );

            $non_ajax_methods = $this->plugin()->tools()->getStringsWithSubstring( array( 'hookFormAction' ), $all_methods );

            $action_methods = array_merge(
                    ($ajax_action_methods ), ($non_ajax_methods ) //cast to array to make sure merges work.
            );


            $this->debug()->logVar( '$actions = ', $action_methods );

            /*
             * Create a WordPress nonce action string for each method that starts with hookFormAction, which are the
             * action handlers for ajax actions.
             *
             * Loop through each of the hookFormAction methods found within this class,
             * and parse the names of the methods to turn them into nonce action names
             *
             *
             * $action The name of the action method , e.g.:hookFormActionSettingsSave
             *                *
             * $action_slug  e.g.:  'settings_save' . The 'action slug', which is the short name for the action (without the module slug prefix)
             *
             * $action_long  e.g.: nomstock_com_Menu010_general_settings_save The 'long name' of the action
             *
             *
             */
            foreach ( $action_methods as $action_method ) {
                $action_slug = $this->plugin()->tools()->getSlugFromWord( str_replace( array( 'hookFormActionAjax', 'hookFormAction' ), '', $action_method ) );


                $action = $this->plugin()->getSlug() . '_' . $this->module()->getSlug() . '_' . $action_slug;



//   $vars[$action_short_name . '_nonce_value'] = wp_create_nonce($action_long);
                $vars[ 'forms' ][ 'nonce_values' ][ $action_slug ] = wp_create_nonce( $action );

                $this->debug()->log( ' Method <em>' . $action_method . '</em> automatically created Nonce value ' . $vars[ 'forms' ][ 'nonce_values' ][ $action_slug ] . ' using Nonce Action <strong>' . $action . '</strong>' );
            }
        }

        /*
         * tell javascript whether we are using unique nonces
         * A unique nonce is a nonce that is unique for each
         * ajax action. This is different from the default, where
         * we have a nonce that is
         * unique to the menu, not the action.
         */
        $vars[ 'forms' ][ 'unique_action_nonces' ] = $this->module()->NONCE_UNIQUE_ENABLED;

        $vars[ 'forms' ][ 'nonce_field_name' ] = $this->module()->NONCE_FIELD_NAME;
        $vars[ 'forms' ][ 'nonce_values' ][ 'default' ] = $this->module()->NONCE_DEFAULT_VALUE;

        $this->debug()->logVar( 'Added to javascript: $vars = ', $vars );

        $this->plugin()->setLocalVars( $vars );
    }

    /**
     * Verify WordPress Nonce
     *
     * Verifies the WordPress Nonce , using either a unique action name (derived from the $function_name parameter) or from the default $this->NONCE_DEFAULT_ACTION action.
     *
     * The simpli framework automatically handles WordPress Nonces for you for any settings saved by this module. The default configuration is to use a 'one nonce' for each menu, regardless of how many ajax actions are created. This is the easiest to implement, and the least performance heavy, and one that does not require any adherence to method naming conventions for it to work.
     * Alternately, If you wish to use a unique nonce for each action, this is also easily done but is a bit more performance heavy and requires additional understanding if you are to create your own ajax actions.
     * The basic steps are :
     * 1) be sure to stick to naming conventions , where the function for the action hook must be named 'hookFormAction<MyAction>'
     * 2) that $this->setConfig('NONCE_UNIQUE_ENABLED',true) in the config() method for your Menu module
     * 3) within your ajax script use nomstock_com.my_action_nonce_value
     * @param $function_name The name of the wp_ajax hook function. Must be in the form 'hookFormAction' , otherwise, the nonce will be rejected.
     * @return void
     */
    public function wpVerifyNonce( $function_name = null ) {
        $this->debug()->t();
        /*
         * Get the nonce value that was submitted by checking
         * the $_REQUEST header ( which includes $_GET and $_POST vars)
         */
        $nonce_value = $this->plugin()->tools()->getRequestVar( $this->module()->NONCE_FIELD_NAME );

        $this->debug()->logVar( '$nonce_value = ', $nonce_value );
        /*
         * Check whether unique nonces are enabled.
         *
         */
        if ( $this->module()->NONCE_UNIQUE_ENABLED && !is_null( $function_name ) ) {
            /*
             * if unique nonces for each action are enabled, then get their action name from the function name
             */
            $nonce_action = $this->plugin()->getSlug() . '_' . $this->module()->getSlug() . '_' . $this->plugin()->tools()->getSlugFromWord( str_replace( array( 'hookFormActionAjax', 'hookFormAction' ), '', $function_name ) );
            $this->debug()->logVar( '$nonce_action for unique action = ', $nonce_action );
        } else {
            /*
             * otherwise, just use the default action name
             */
            $nonce_action = $this->module()->NONCE_DEFAULT_ACTION;
            $this->debug()->log( 'Not unique nonces, so using action = ' . $nonce_action . ' taken from module ' . $this->module()->getName() );
        }
        if ( !wp_verify_nonce( $nonce_value, $nonce_action ) ) {

            $this->debug()->logError( 'Failed Nonce for ' . $nonce_action );
            // $this->debug()->stop(true);
            return false;
        } else {
            $this->debug()->log( 'Nonce PASSED for ' . $nonce_action );
            return true;
        }
    }

    /**
     * Show Response Message (Protected)
     *
     * Displays a message returned by an ajax request to a user
     *
     * @param string $template The path to the template to be used
     * @param string $message The html or text message to be displayed to the user
     * @param array $errors Any error messages to display
     * @param boolean $logout Whether to force a logout after the message is displayed
     * @param boolean $reload Whether to force a page reload after the message is displayed
     * @return void
     */
    protected function _showResponseMessage( $template_path, $message, $errors = null, $logout = false, $reload = false ) {
        $this->debug()->t();

        if ( $this->plugin()->tools()->isAjax() ) {
            if ( $logout ) {
                $this->debug()->log( 'Logout is set to true, logging out' );
                wp_logout();
            }
        }






        /*
         * Check for errors

          $errors[]='That was definitly the wrong answer';
         * $errors[]='Nope, try again';
          $errors[]='Can\'t you get anything right?';

         */

        if ( !is_null( $errors ) && !empty( $errors ) ) { //if there are error messages, display them

            /*
             * Build an Error Template and Process it
             */
            $error_html = '';
            foreach ( $errors as $error ) {
                $error_html.= '<li><p>' . $error . '</p></li>';
            }
            $error_html.= '</ul></div>';
            $tags[ 'ERROR_HTML' ] = $error_html;

            $template = '
    <div class="error below-h2 fade" id="message">

    <ul>
    {ERROR_HTML}
    </ul>
    </div>';
        } else { // but if there are no errors...
            $this->debug()->log( 'Show the message by including template ' . $template_path );

            /*
             * Build a Message Template and Process it
             */
            $tags[ 'MESSAGE' ] = $message;
            $tags[ 'RELOAD_SCRIPT' ] = ( $logout || $reload ) ? '<script type="text/javascript">var d = new Date();window.location = window.location.href+\'&\' + d.getTime();</script>' : '';

            ob_start();
            include ($template_path);
            $template = ob_get_clean();
        }


        /*
         * Clean Buffers
         * Get rid of all the output buffers and end output buffering
         * ao as to ensure nothing is output except what follows after the cleaning
         * Do not clean if we are using compression since compression makes use of the buffers,
         * so we would lose our content otherwise.
         * Do not clean if not using ajax or you will get not output.
         */
        if ( $this->plugin()->tools()->isAjax() ) {
            if ( !$this->plugin()->COMPRESS ) {
                while ( @ob_end_clean() );
            }
        }



        /*
         * Finally output the template
         */
        $message_html = $this->plugin()->tools()->crunchTpl( $tags, $template );
        $this->debug()->logVar( 'Showing template = ', $template );
        $this->debug()->logVar( 'Showing message = ', $message_html );

        if ( $this->plugin()->tools()->isAjax() ) {
            echo $message_html;

        } else {

            /*
             * Localize Success Message
             */
            $vars = array( 'forms' => array( 'response' => $message ) );
            $vars = array( 'forms' => array(
                    'submitted_form_id' => $this->plugin()->tools()->getRequestVar( 'simpli_forms_id' )
                    , 'response' => $message_html
                )
            );


            $this->plugin()->setLocalVars( $vars );



            $handle = $this->plugin()->getSlug() . '_form-response.js';

            $path = $this->plugin()->getDirectory() . '/admin/js/form-response.js';
            $inline_deps = array();
            $external_deps = array( 'jquery' );
            $this->plugin()->enqueueInlineScript( $handle, $path, $inline_deps, $external_deps );
            $this->debug()->log( 'loaded script ' . $handle );




            if ( !$this->plugin()->tools()->isAjax() ) {
                if ( $logout ) {

                    /*
                     * Before logging out, we have to redirect to the current url, but remove
                     * the action query paramater first. This is done because WordPress automatically
                     * redirects back to the previous url if the user decides to log back in. This
                     * would result in an endless loop if we didn't remove the _action param and the
                     * condition still existed.
                     *
                     * This is not done if this is an ajax request, since QUERY_VAR wouldnt apply in that case.
                     */
                    unset( $_GET[ $this->plugin()->QUERY_VAR . '_action' ] );
                    $redirect_url = $this->plugin()->tools()->rebuildUrl( $_GET, null, true );
                    $this->debug()->logVar( '$redirect_url = ', $redirect_url, true );
                    //$this->debug()->stop(true);
                    /*
                     * before logging out, redirect to a url without the action
                     * parameter. this will enable subsequent logins from
                     * avoiding an endless loop in case the referral url
                     * was causing the problem.
                     */
                    wp_redirect( $redirect_url );

                    wp_logout();
                    exit();
                }
            }
        }
        if ( $this->plugin()->tools()->isAjax() ) {
            exit(); //required to ensure ajax request exits cleanly; otherwise it hangs and browser request is garbled.
        }
    }

    /**
     * Add Form Action (Non-Ajax)
     *
     * Wrapper around add_action to help make the interface more user friendly and consistent with adding an ajax action.
      Instead of this :
     * add_action($this->plugin()->QUERY_VAR . '_action' . '_say_hello', array($this, 'hookFormActionSayHello'));
     *
     * you can do this:
     * $this->metabox()->addFormAction('say_hello');
     * or this :
     * $this->metabox()->addFormAction('say_hello', array($this, 'hookFormActionSayHello'));
     *
     * @param none
     * @return void
     */
    public function addFormAction( $action_slug, $callback = null ) {
        /*
         * will also assume $this, hookFormAction
         */
        $methodName = '';
        if ( is_null( $callback ) ) {
            $methodName = $this->plugin()->tools()->getWordFromSlug( $action_slug );
            $callback = array( $this->module(), 'hookFormAction' . $methodName );
        }





        add_action( $this->plugin()->QUERY_VAR . '_action_' . $action_slug, $callback );
        $this->debug()->logVar( 'Added Action for = ', 'hookFormAction' . $methodName );
    }

    /**
     * Add Form Action (Ajax)
     *
     * Wrapper around add_action to help make the interface more user friendly and consistent with adding a non-ajax action.
     * It also takes care of adding the action for a front end request where the user is not logged in.
      Instead of this :
     * add_action('wp_ajax_' . $this->plugin()->getSlug() . '_say_hello', array($this, 'hookFormActionSayHello'));
     *
     * you can do this:
     * $this->metabox()->addFormActionAjax('say_hello');
     * or this :
     * $this->metabox()->addFormActionAjax('say_hello', array($this, 'hookFormActionSayHello'));

     *
     * @param none
     * @return void
     */
    public function addFormActionAjax( $action_slug, $callback = null ) {


        /*
         * will also assume $this, hookFormAction
         */
        $methodName = '';
        if ( is_null( $callback ) ) {
            $methodName = $this->plugin()->tools()->getWordFromSlug( $action_slug );
            if ( is_admin() ) {
                
} else{
                $callback = array( $this, 'hookFormActionAjax' . $methodName );
}

        }



        $this->debug()->logVar( 'Added Action , Action = \'wp_ajax_' . $this->plugin()->getSlug() . '_' . $action_slug . ', callback = ', $callback );

        add_action( 'wp_ajax_' . $this->plugin()->getSlug() . '_' . $action_slug, $callback );
        add_action( 'wp_ajax_nopriv_' . $this->plugin()->getSlug() . '_' . $action_slug, $callback );








    }

    /**
     * Show Response Message (Wrapper around _showResponseMessage)
     *
     * Saves the Response Message Arguments to a transient for later
     * retrieval by hookShowResponseMessageAfterRedirect
     *
     * @param none
     * @return void
     */
    public function showResponseMessage( $template_path, $message, $errors = null, $logout = false, $reload = false ) {

        if ( $this->plugin()->tools()->isAjax() ) {
            /*
             * if its ajax, just call the internal method directly.
             *
             */
            $this->debug()->log( 'ajax call, so calling the internal method directly' );
            $this->_showResponseMessage( $template_path, $message, $errors, $logout, $reload );
            return;
        }

        /*
         * If its not ajax, we need to store the arguments first, since
         * we are going to redirect to a new page ( so a refresh wont cause
         * the form to submit again)
         */

        $form_id = $this->plugin()->tools()->getRequestVar( 'simpli_forms_id' );
        $form_response_args = (compact(
                        'template_path'
                        , 'message'
                        , 'errors'
                        , 'logout'
                        , 'reload'
                        , 'form_id'
        ));

        /*
         * save the arguments to a transient
         */
        set_transient( $this->plugin()->getSlug() . '_form_response_args', $form_response_args );

        /*
         * redirect to a url with 'form_response' in the url which fires
         * the 'hookShowResponseMessage' which shows the message there.
         */
        $redirect_url = $this->plugin()->tools()->rebuildUrl( array( $this->plugin()->QUERY_VAR . '_action' => 'form_response', 'simpli_forms_id' => $form_id ), $this->plugin()->tools()->getRequestVar( '_simpli_forms_referer_url' ) );
///wp-admin/edit.php?post_type=sf_snippet&page=nomstock_com_menu20_my_menu&nomstock_com_action=form_response&simpli_forms_id=simpli_forms_1
        //   $this->debug()->logVar('$redirect_url = ', $redirect_url);

        wp_redirect( $redirect_url );
        exit(); //redirect should always be followed by exit;
    }

    /**
     * Hook Show Response Message After A Redirect
     *
     * Shows Response Message after a Redirect, taking its arguments from
     * a transient
     *
     * @param none
     * @return void
     */
    public function hookShowResponseMessageAfterRedirect() {
        static $already_fired = false;

        /*
         * Prevent Multiple Firings of this method.
         *
         * Prevent this method from firing every time the metabox() class is invoked.
         * we only need it to fire once.
         */
        if ( !$already_fired ) {
            $already_fired = true;
        } else {
            $this->debug()->log( 'already took action, returning' );
            return;
        }
        /* why no pageCheck?
         *
         * We don't need to do a pageCheck since this action wont be fired
         * unless the proper query variable was added ( 'form_response')
         * Also, a pageCheck wouldn't work since the action fires before the
         * current_screen object is available.
         */

        $this->debug()->t();

        $form_response_args = get_transient( $this->plugin()->getSlug() . '_form_response_args' );
        if ( $form_response_args === false ) {

            /*
             * prevent us from showing the success message again on a page refresh
             */
            return;
        }
        /*
         * delete the transient holding the message arguments
         * We have no use for it once its retrieved once and need to make sure
         * any refreshes of the page will not show the message again or it will
         * make the user think the form was submitted again ( which it wouldn't be, since
         * we are redirecting to a landing page after the form submission, but there would
         * be a perception we were)
         *
         */
        delete_transient( $this->plugin()->getSlug() . '_form_response_args' );

        $this->debug()->logVar( '$form_response_args = ', $form_response_args );
        /*
         * show the message
         */
        $this->_showResponseMessage(
                $form_response_args[ 'template_path' ], $form_response_args[ 'message' ], $form_response_args[ 'errors' ], $form_response_args[ 'logout' ], $form_response_args[ 'reload' ]
        );
    }

}

?>