<?php

/**
 * Metabox States Utility Class
 *
 * Keeps track of meta box open/closed states. Originally integrated into the menu class, but pulled out so we could manage states in modules for things like the PostUserOptions module which manages metabox states within a post editor.
 * The base Menu class and the _PostUserOptions class provides access
 * to this Utility class through the metabox() method. You can easily
 * integrate it into any other module by adding a similar metabox() method
 * to it.
 *
 * Usage:
  1) Add the following line to your config() method :
  $this->metabox()->config(array($this,'pageCheck'));
 * pageCheck should be a method that returns true or false indicating whether
 * you are on the page on which you want the metaboxes to be managed
 *
 * 2). Then to configure an individual metabox, add the following to the
 * module's config() method:
  $this->metabox()->setOpenState($id,$open,$persist);
 *
 *
 * @author Andrew Druffner
 * @package SimpliFramework
 * @subpackage SimpliBasev1c2
 */
class Simpli_Frames_Base_v1c2_Plugin_Module_Metabox extends Simpli_Frames_Base_v1c2_Plugin_Module_Helper {

    /**
     *
     * @var array Page Check Callback - a callable function that should return
     * a boolean to indicate whether we are on the correct page.
     * The class will check this before executing any hooks.
     */
    protected $_page_check_callback = null;

    /**
     * Config
     *
     * Configures the current object
     *
     * @param array $page_check_cb Callback method in the form $object,'method'
     * @return void
     */
    public function config( $page_check_callback = null ) {
       
        $this->debug()->t();
        /*
         * Save the callback for later use before executing hooks
         */
        
 $this->_page_check_callback = $page_check_callback;




        /*
         * Configure the form helper which loads the form javascript
         */

        $this->form_helper()-> config();

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

        add_action( 'current_screen', array( $this, 'hookCurrentScreen' ) );

// add ajax action
        add_action( 'wp_ajax_' . $this->plugin()->getSlug() . '_ajax_metabox', array( $this, 'hookAjaxMetabox' ) );
        add_action( 'wp_ajax_' . $this->plugin()->getSlug() . '_ajax_metabox_cache', array( $this, 'hookAjaxMetaboxCache' ) );

        /*
         * Show Admin notices added by the forms
         *
         */
        add_action( 'admin_notices', array( $this, 'showAdminNotices' ) );



    }

    /**
     *
     * @var array An array of admin notices that have been added by form submissions
     */
    protected $_admin_notices = null;

    /**
     * Add Admin Notice
     *
     * Adds an Admin Notice to $_admin_notices array. This array will later be displayed when the add_admin_notice hook is fired.
     *
     * @param string $message The text or html to be displayed.
     * @return void
     */
    public function addAdminNotice( $message, $class = 'updated' ) {
        $this->debug()->t();
        $this->_admin_notices[] = '<div class="' . $class . '" >' . $message . '</div>';
    }

    /**
     * Get Admin Notices
     *
     * Returns the admin_notices array
     *
     * @param none
     * @return array
     */
    public function getAdminNotices() {

        if ( is_null( $this->_admin_notices ) ) {
            return array();
        } else {
            return $this->_admin_notices;
        }
    }

    /**
     * Hook Current Screen
     *
     * Hooks into 'current_screen' so has access to the screen object.
     * Add aby actions or method calls here that need to fire when the screen
     * object is accessible
     *
     * @param none
     * @return void
     */
    public function hookCurrentScreen() {

        /*
         * dont take any current_screen actions if we havent added any metaboxes.
         */
        if ( is_null( $this->_meta_boxes_args ) ) {
            return;
        }

        $this->debug()->t();

        $this->debug()->log( 'My module is ' . $this->module()->getName() );

        if ( !$this->pageCheck() ) {

            return;
       
        }


        $screen = get_current_screen();

        
            /*
     * Note that the pageCheck() is now referring to the pageCheck method within this 
     * class, which in turn uses the $page_check_callback method passed as an argument
     * This is too early to call to access Screen though.
     */    
        if ( !$this->pageCheck()){
            return;
}
        
        /*
         * Add Scripts
         */
        add_action( 'admin_enqueue_scripts', array( $this, 'hookEnqueueScripts' ) );
        /*
         * Add the filter for closed meta boxes.
         * This allows us to modify the array of metaboxes passed to it
         * Note that the hook is page specific, so no need for a page check
         * (no matter how many add_filters are made, only the one that has the current screenid will fire.)
         */

        add_filter( 'get_user_option_closedpostboxes_' . $screen->id, array( $this, 'hookCloseMetaboxes' ), 10, 3 );
        add_filter( 'get_user_option_closedpostboxes_post', array( $this, 'hookCloseMetaboxes' ), 10, 3 ); //no underscore
    }

    /**
     * Page Check
     *
     * Returns the result of the pageCheck callback method passed by the caller when creating the metabox.
     * Used for indicating whether we are on the correct page before executing hooks
     *
     * @param none
     * @return boolean
     */
    public function pageCheck() {

        if ( is_null( $this->_page_check_callback ) ) {
            $this->debug()->log( '$this->_page_check_callback was null, so page check passes automatically' );
            return true; //if no pageCheck callback was passed , then it always passes
        }

        if ( is_array( $this->_page_check_callback ) && isset( $this->_page_check_callback[ 1 ] ) ) {
      
            $this->plugin()->debug()->logVar( '$this->_page_check_callback[0] = ', $this->_page_check_callback[ 0 ] );
            $this->plugin()->debug()->logVar( '$this->_page_check_callback[1] = ', $this->_page_check_callback[ 1 ] );

            return (call_user_func( array( $this->_page_check_callback[ 0 ], $this->_page_check_callback[ 1 ] ) ));
        } else {
            /*
             * if pageCheck is not null, and if it is just a string, just call it.
             */
            return (call_user_func( $this->_page_check_callback ));
        }
    }

    /**
     * Filter Hook - Close Metaboxes
     * WordPress Hook Filter Function for 'get_user_option_closedpostboxes_{screen_id}'
     *
     * Returns an array of the meta box ids that are closed for use in setting the default positions
     * @param array $closed_metaboxes
     * @return array $closed_metaboxes
     *
     */
    public function hookCloseMetaboxes( $closed_metaboxes, $option_name, $user ) {

//        $closed_metaboxes[] = 'simpli_frames_post_user_options_metabox_options';
//        $this->debug()->logVar('$closed_metaboxes = ', $closed_metaboxes);
//        return $closed_metaboxes;



        if ( !is_array( $closed_metaboxes ) ) {
            $closed_metaboxes = array();
        }













        /*
         * Doesnt need a page check because the hook is page specific
         */


        /* Check whether any metabox positions have been saved and if not, consider
         * this a 'first visit'  and ensure the initial argument type is an array
         * ensure that data type is array to avoid errors when empty

          if (!is_array($closed_metaboxes)) {
          $first_visit = true;
          $closed_metaboxes = array();
          } else {
          $first_visit = false;
          }
         */

        //   $first_visit = true;


        $metaboxDefaultStates = $this->_getMetaboxOpenStates();

        $this->debug()->logVar( '$metaboxDefaultStates = ', $metaboxDefaultStates );

        $this->debug()->logVar( '$closed_metaboxes before filtering = ', $closed_metaboxes );


        /*
         * exit the filter if no default states have been set
         */
        if ( !is_array( $metaboxDefaultStates ) ) {
            $this->debug()->log( 'Exiting hook because no default states' );
            return $closed_metaboxes;
        }


        /*
         * iterate through each of the default states and add the metabox
         * id to the filter if the metabox is to be closed
         */

        foreach ( $metaboxDefaultStates as $metabox_id => $preferences ) {
            $this->debug()->logVar( '$metabox_id = ', $metabox_id );
            $this->debug()->logVar( '$preferences = ', $preferences );


            /* works in settings
             *
             * if open and not persist, then do nothing since this is normal behavior
             */
            if ( ($preferences[ 'open' ] === true) && ($preferences[ 'persist' ] === false) ) {
                continue;
            }

            /* works in settings
             *
             * if open and persist, then unset and return
             */

            if ( ($preferences[ 'open' ] === true) && ($preferences[ 'persist' ] === true) ) {
                $key = array_search( $metabox_id, $closed_metaboxes );
                if ( $key !== false ) {

                    unset( $closed_metaboxes[ $key ] );
                    //$closed_metaboxes[$key] = '';
                }
                continue;
            }

            /*
             * works for settings
             *
             * if closed and persist then add to array and return
             */
            if ( ($preferences[ 'open' ] === false) && ($preferences[ 'persist' ] === true) ) {


                $key = array_search( $metabox_id, $closed_metaboxes );
                if ( $key === false ) {
                    $closed_metaboxes[] = $metabox_id;
                }
                continue;
            }

            /*
             *
             *
             * if closed and not persist (the trickiest)
             * we check first for a dummy metabox id, and if it exists,
             * we return unchanged.
             *
             * if it doesnt exist, we add both the metabox id and the dummy metabox id
             */



            if ( ($preferences[ 'open' ] === false) && ($preferences[ 'persist' ] === false) ) {

                /*
                 * if not in $closed_metaboxes
                 * AND
                 * not in $clicked_metaboxes
                 * then add to $closed_metaboxes AND add to clicked_metaboxes
                 */


                $closed_once_metaboxes = get_user_option( $option_name . '_close_once', $user->ID );

                if ( !is_array( $closed_once_metaboxes ) ) {
                    $closed_once_metaboxes = array();
                }
                $key_metabox_id_closed_once = array_search( $metabox_id, $closed_once_metaboxes );


                $this->debug()->logVar( '$key_metabox_id_closed_once = ', $key_metabox_id_closed_once );

                /*
                 * if neither exist then add a 'not clicked'
                 */
                if (
                        $key_metabox_id_closed_once === false //the not clicked tracker wasnt added yet
                ) {
                    $close_once_metaboxes[] = $metabox_id; //add the closed status.
                    $closed_metaboxes[] = $metabox_id; //add the tracker flag
                    update_user_option( //http://codex.wordpress.org/Function_Reference/update_user_option
                            $user->ID, //User ID
                            $option_name . '_close_once', //User option name.
                            $close_once_metaboxes, //User option value.
                            true//Whether option name is blog specific or not.
                    );
                    update_user_option( //http://codex.wordpress.org/Function_Reference/update_user_option
                            $user->ID, //User ID
                            $option_name, //User option name.
                            $closed_metaboxes, //User option value.
                            true//Whether option name is blog specific or not.
                    );
                    continue;
                } else {

                    continue;
                }
            }

        }
        $this->debug()->logVar( '$closed_metaboxes = ', $closed_metaboxes );


        return $closed_metaboxes;
    }

    /**
     * Get Metabox Open States (Internal)
     *
     * Returns an array that indicates the open and persist preferences for
     * any configured Meta Boxes
     * For the structure of this array, see the setOpenState() method
     *
     * @param none
     * @return array $this->$_meta_box_open_states;
     */
    protected function _getMetaboxOpenStates() {
        return $this->_meta_box_open_states;
    }

    /**
     *
     * @var array An array that indicates the open and persist preferences for
     * any configured Meta Boxes. Used by setOpenState

     */
    protected $_meta_box_open_states = null;

    /**
     * Set Meta Box Open State
     *
     * Sets the intial open or closed state of a meta box. If persistance is set to 'true' ,
     * the meta box will retain that state regardless of whether the user changes it.
     * With this method you can :
     * initially set the metabox to closed on first visit:
     * force metabox to always be closed when the page is visited:
     * force metabox to always be open when the page is visited:
     *
     * @param string $id The id of the meta box used in the add_meta_box method. Must be unique to the meta box.
     * @param boolean $open  True for open, False for closed
     * @param boolean $persist True will cause the meta box to keep the state indicated by the $open parameter value
     * at next visit to the page, even if the user changed it (i.e.: it ignores saved changes)
     * @uses setOpenState()
     * @return void
     */
    public function setOpenState( $id, $open = true, $persist = false ) {

        /*
         * allow for use of 'open' and 'closed'
         */
        if ( !is_bool( $open ) ) {
            $open = trim( $open );
            $open = ($open === 'opened' || $open === 'open' ) ? true : $open;
            $open = ($open === 'closed' || $open === 'close' ) ? false : $open;
        }

        /*
         * Prepend the plugin and module slug
         */

        $full_id = $this->plugin()->getSlug() . '_' . $this->module()->getSlug() . '_' . $id;

        /*
         * Add the metabox state to a tracking array
         */


        $this->_meta_box_open_states[ $full_id ] = array( 'open' => $open, 'persist' => $persist );
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
        $this->debug()->t();
        if ( !$this->pageCheck() ) {
            $this->debug()->log( 'Failed Page Check for ' . $this->module()->getName() );
            return;
        }

        $this->debug()->log( 'Passed Page Check for ' . $this->module()->getName() );


        wp_enqueue_script( 'post' );

        /* DEPRECATED save-metabox-state.js
         *
         * save-metabox-state.js doesnt appear relevant anymore - deprecating until confirmed.
         * the wordpress 'post' javascript should provide everything needed to handle metabox clicks, position placement, position persistance, and click state  persistance.
         */


        $handle = $this->plugin()->getSlug() . '_save-metabox-state.js';
        $path = $this->plugin()->getDirectory() . '/admin/js/save-metabox-state.js';
        $inline_deps = null;
        $external_deps = array( 'post' );
        /*        not required any longer  - $this->plugin()->enqueueInlineScript($handle, $path, $inline_deps, $external_deps);
         *      $this->debug()->log('loaded script ' . $handle);
         */
        //  $this->plugin()->enqueueInlineScript($handle, $path, $inline_deps, $external_deps);



    }

    /**
     * Hook Add Meta Boxes
     *
     * Adds all the metaboxes that have been configured using the public addMetaBox method.
     *
     * Usage:
     * add_action('current_screen',array($my_obj,'hookAddMetaBoxes')) or called directly from within a wrapper method that is itself called by the current_screen hook.
     *
     * @param none
     * @return void
     */
    public function hookAddMetaBoxes() {
        $this->debug()->t();


        if ( is_null( $this->_meta_boxes_args ) ) {
            $this->plugin()->debug()->log( 'Exiting ' . __FUNCTION__ . ' since $_meta_boxes_args is null' );
            return;
        } else {

            $this->debug()->logVar( 'Adding Meta Box , Meta Box arguments are: = ', $this->_meta_boxes_args );
        }
        $post_type = $this->plugin()->post()->getPostType();

        foreach ( $this->_meta_boxes_args as $meta_box_args ) {


            /*
             * extract the array of arguments
             * provided by the public addMenuPage() method
             */


            /*
             * Now call the internal method that actually does the work within the hook
             */
            /*
             * Do not add meta box if post type is not included or is excluded
             */
            $skip = false;
            $screen_id = $meta_box_args[ 'screen_id' ];
            $meta_box_args[ 'callback_args' ][ 'template_file_name' ] = $meta_box_args[ 'id' ]; //make the template name equal to the metabox id.
            if ( is_array( $meta_box_args[ 'screen_id' ] ) ) {

                $post_types = $meta_box_args[ 'screen_id' ];
                $screen_id = null;
                if ( isset( $post_types[ 'exclude' ] ) ) {
                    if ( in_array( $post_type, $post_types ) ) {
                        $skip = true;
                    }
                }
                if ( isset( $post_types[ 'include' ] ) ) {
                    if ( !in_array( $post_type, $post_types ) ) {
                        $skip = true;
                    }
                }
            }

            if ( !$skip ) {


                add_meta_box(
                        $this->plugin()->getSlug() . '_' . $meta_box_args[ 'id' ]  // Meta Box DOM ID , the HTML 'id' attribute of the edit screen section
                        , $meta_box_args[ 'title' ]  // Title of the edit screen section, visible to user
                        , $meta_box_args[ 'callback' ]  //Function that prints out the HTML for the edit screen section. The function name as a string, or, within a class, an array to call one of the class's methods. The callback can accept up to two arguments, see Callback args.
                        , $screen_id // string|object The screen on which to show the box (post, page, link). Null defaults to current screen.
                        , $meta_box_args[ 'context' ]  //normal advanced or side The part of the page where the metabox should show This just allows you to separate out the metaboxes into groups , so that when you call do_metaboxes on a page template, it knows which group to process.
                        , $meta_box_args[ 'priority' ]  // 'high' , 'core','default', 'low' The priority within the context where the box should show.
                        , $meta_box_args[ 'callback_args' ] // Arguments to pass into your callback function. The callback will receive the $post object and whatever parameters are passed through this variable. Get to these args in your callback function by using $metabox['args']
                );
            }
        }
    }

    protected $_meta_boxes_args;

    /**
     * Add Meta Box (Wrapper)
     *
     * Stores the parameter values to an array for later retrieval by a hook that will make a call to the WordPress method add_meta_box.
     *
     * @param string $id Meta Box DOM ID , the HTML 'id' attribute of the edit screen section
     * @param string $title Title of the edit screen section, visible to user
     * @param mixed $callback Function that prints out the HTML for the edit screen section. The function name as a string, or, within a class, an array to call one of the class's methods. The callback can accept up to two arguments, see Callback args.
     * @param string $screen_id Current Screen ID . This is mistakenly called $post_type in the codex. See WordPress source code for details.
     * @param string $context ('normal', 'advanced', or 'side') The part of the page where the metabox should show This just allows you to separate out the metaboxes into groups , so that when you call do_metaboxes on a page template, it knows which group to process.
     * @param string $priority ('high' , 'core','default', 'low' ) The priority within the context where the box should show.
     * @param array $callback_args Arguments to pass into your callback function. The callback will receive the $post object and whatever parameters are passed through this variable. Get to these args in your callback function by using $metabox['args']
     * @return void
     */
    public function addMetaBox( $id, $title, $callback, $screen_id, $context, $priority, $callback_args ) {
        $this->debug()->t();
        /*
         * Add to the Meta Box Args array.
         * This will allow us to retrieve them later when the hook for add_meta_boxes is fired.
         */
        /*
         * Prepend the module slug to make the metabox id unique for all metaboxes
         * Template file names are combinations of module slug and metabox id, and since they
         * all reside in the same directory, their names must be unique.
         *
         */
        $id = $this->module()->getSlug() . '_' . $id;
        $this->_meta_boxes_args[] = compact( 'id', 'title', 'callback', 'screen_id', 'context', 'priority', 'callback_args' );
    }

    /**
     * Dispatch request for ajax metabox
     *
     * @param int $cache_timeout Minutes before the cache refreshes
     * @return void
     */
    protected function _AjaxMetabox( $cache_timeout = 0 ) {
        $this->debug()->t();

//skip the pageCheck check since this is an ajax request and wont contain the $_GET page variable
// Disable errors
        error_reporting( 0 );
        /*
         * Compress Output
         */
        if ( $this->plugin()->COMPRESS ) {
            $this->debug()->log( 'Started zlib buffering' );
            $this->plugin()->tools()->startGzipBuffering();
        }

// Set headers
        header( "Status: 200" );
        header( "HTTP/1.1 200 OK" );
        header( 'Content-Type: text/html' );
        header( "Vary: Accept-Encoding" );

        /*
         * set cache
         */
        if ( $cache_timeout === 0 ) {
            header( 'Cache-Control: no-store, no-cache, must-revalidate' );
            header( 'Cache-Control: post-check=0, pre-check=0', FALSE );
            header( 'Pragma: no-cache' );
        } else {

            $expires = 60 * $cache_timeout;        // convert to  minutes expressed in seconds

            header( 'Pragma: public' );
            header( 'Cache-Control: maxage=' . $expires );
            header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
            header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', time() + $expires ) . ' GMT' );
        }


        if ( !wp_verify_nonce( $_GET[ '_nonce' ], $this->plugin()->getSlug() ) ) {

            $this->debug()->log( 'Nonce check failed, exiting method' );
            exit;
        }


        $request = new WP_Http;
        $request_result = $request->request( $_GET[ 'url' ] );
        $result[ 'html' ] = $request_result[ 'body' ];
        $result[ 'metabox_id' ] = $_GET[ 'id' ];

        $this->debug()->logVar( '$result = ', $result );



        echo json_encode( $result );


        exit();
    }

    /**
     * Ajax Action - Get Metabox with Cache
     *
     * @param none
     * @return void
     */
    public function hookAjaxMetaboxCache() {

//skip the pageCheck check since this is an ajax request and wont contain the $_GET page variable
        $this->_AjaxMetabox( 30 );
    }

    /**
     * Ajax Action - Get Metabox without Cache
     *
     * @param none
     * @return void
     */
    public function hookAjaxMetabox() {

//skip the pageCheck check since this is an ajax request and wont contain the $_GET page variable
        $this->_AjaxMetabox( 0 );
    }

    /**
     * Renders a meta box using an Ajax Request
     *
     * @param string $module
     * @param array $metabox
     * @return void
     */
    public function renderMetaBoxAjax( $module, $metabox ) {


        include($this->plugin()->getDirectory() . '/admin/templates/metabox/ajax.php');
    }

    /**
     * Renders a meta box
     *
     * @param string $module
     * @param array $metabox
     * @return void
     */
    public function renderMetaBoxTemplate( $module, $metabox ) {



        $this->debug()->logVar( '$metabox[\'id\'] = ', $metabox[ 'id' ] );
        $this->debug()->logVar( '$metabox[\'args\'][\'template_file_name\'] = ', $metabox[ 'args' ][ 'template_file_name' ] );



        /*
         * If no template path provided, use the metabox id as the template name and /admin/templates/metabox as the path
         */
        $template_path = $this->plugin()->getDirectory() . '/admin/templates/metabox/' . $metabox[ 'args' ][ 'template_file_name' ] . '.php';
        if ( isset( $metabox[ 'args' ][ 'path' ] ) ) {
            $template_path = $metabox[ 'args' ][ 'path' ];
        }
        if ( !file_exists( $template_path ) ) {
            _e( 'Not available at this time.', $this->plugin()->getTextDomain() );
            $this->plugin()->debug()->logcError( $this->plugin()->getSlug() . ' : Meta Box ' . $metabox[ 'id' ] . ' error - template path does not exist ' . $template_path );
            return;
        }
        $this->debug()->logVar( '$this->plugin()->ALLOW_SHORTCODES = ', $this->plugin()->ALLOW_SHORTCODES );


        if ( $this->plugin()->ALLOW_SHORTCODES ) {
            $this->debug()->log( 'Executing shortcodes and including path' );

            ob_start();

            include($template_path);


            $template = do_shortcode( ob_get_clean() );
//  $this->debug()->log('$template = ' . $template);//note that if $template has debug statements in it, its better to use 'log' not logVar , since logVar will turn everything into htmlspecialchars, which will obscure the output
            echo $template;
        } else {
            $this->debug()->log( 'Including the template path without execurting the shortcodes since ALLOW_SHORTCODES is false' );

            include($template_path);

            return;
        }



//      echo do_shortcode($template); //using buffer and do_shortcode is required to allow shortcodes to work within an included file, otherwise they dont render.
    }

    /**
     * Show Admin Messages
     *
     * Long Description
     *
     * @param none
     * @return void
     */
    public function showAdminNotices() {
        $this->debug()->t();
        if ( !$this->pageCheck() ) {
            $this->debug()->log( 'Failed Page Check for ' . $this->module()->getName() );
            return;
        }


        if ( is_null( $this->_admin_notices ) || empty( $this->_admin_notices ) ) {
            $this->debug()->log( 'Returning because admin notices are empty' );
            return;
        } else {
            $admin_notices = $this->getAdminNotices();


            echo implode( '', $admin_notices );
        }
    }

    /**
     *
     * @var FORM Form Helper
     */
    protected $_form_helper = null;

    /**
     * Form Helper
     *
     * Returns a form helper object to manage nonces
     *
     * @param none
     * @return void
     */
    public function form_helper() {
//        if ( !$this->pageCheck() ) {
//            return new Simpli_Frames_Base_v1c2_Phantom();
//   
//        }
        if ( is_null( $this->_form_helper ) ) {
            $this->_form_helper = new Simpli_Frames_Base_v1c2_Plugin_Module_Form($this->module());


        }
        return $this->_form_helper;
    }
}

?>