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
  $this->metabox()->setMetaboxOpenState($id,$open,$persist);
 *
 *
 * @author Andrew Druffner
 * @package SimpliFramework
 * @subpackage SimpliBase
 */
class Simpli_Hello_Basev1c0_Plugin_Module_Metabox extends Simpli_Hello_Basev1c0_Plugin_Module_Helper {

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
    public function config($page_check_callback = null) {
        /*
         * Save the callback for later use before executing hooks
         */
        $this->_page_check_callback = $page_check_callback;

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

        add_action('current_screen', array($this, 'hookCurrentScreen'));

        // add ajax action
        add_action('wp_ajax_' . $this->plugin()->getSlug() . '_ajax_metabox', array($this, 'hookAjaxMetabox'));
        add_action('wp_ajax_' . $this->plugin()->getSlug() . '_ajax_metabox_cache', array($this, 'hookAjaxMetaboxCache'));
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
        if (is_null($this->_meta_boxes_args)) {
            return;
        }

        $this->debug()->t();

        $this->debug()->log('My module is ' . $this->module()->getName());

        if (!$this->pageCheck()) {

            return;
            ;
        }


        $screen = get_current_screen();

        /*
         * Add Scripts
         */
        add_action('admin_enqueue_scripts', array($this, 'hookEnqueueScripts'));
        /*
         * Add the filter for closed meta boxes.
         * This allows us to modify the array of metaboxes passed to it
         */
        add_filter('get_user_option_closedpostboxes_' . $screen->id, array($this, 'hookCloseMetaboxes'));
        // add_filter('get_user_option_meta-box-order_' . $screen->id, array($this, 'hookMetaboxOrder'));
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

        if (is_null($this->_page_check_callback)) {
            $this->debug()->log('$this->_page_check_callback was null, so page check passes automatically');
            return true; //if no pageCheck callback was passed , then it always passes
        }

        if (is_array($this->_page_check_callback) && isset($this->_page_check_callback[1])) {
            $this->plugin()->debug()->logVar('$this->_page_check_callback = ', $this->_page_check_callback);
            $this->plugin()->debug()->logVar('$this->_page_check_callback[0] = ', $this->_page_check_callback[0]);
            $this->plugin()->debug()->logVar('$this->_page_check_callback[1] = ', $this->_page_check_callback[1]);

            return (call_user_func(array($this->_page_check_callback[0], $this->_page_check_callback[1])));
        } else {
            /*
             * if pageCheck is not null, and if it is just a string, just call it.
             */
            return (call_user_func($this->_page_check_callback));
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
    public function hookCloseMetaboxes($closed_metaboxes) {

        /*
         * if pageCheck doesnt pass,
         * then return the $closed_metaboxes array untouched.
         */
        if (!$this->pageCheck()) {

            return($closed_metaboxes);
        }


        /* Check whether any metabox positions have been saved and if not, consider
         * this a 'first visit'  and ensure the initial argument type is an array
         * ensure that data type is array to avoid errors when empty
         */
        if (!is_array($closed_metaboxes)) {
            $first_visit = true;
            $closed_metaboxes = array();
        } else {
            $first_visit = false;
        }




        $metaboxDefaultStates = $this->_getMetaboxOpenStates();


        /*
         * exit the filter if no default states have been set
         */
        if (!is_array($metaboxDefaultStates)) {
            return $closed_metaboxes;
        }


        /*
         * iterate through each of the default states and add the metabox
         * id to the filter if the metabox is to be closed
         */

        foreach ($metaboxDefaultStates as $metabox_id => $preferences) {

            if ($preferences['open'] === false) {
                /*
                 * if this is the first visit, and user wanted to apply defaults only to first visit
                 * or if this is not the first visit, and the user wanted to apply them always
                 * then apply the preference
                 */
                if (($first_visit) || (!$first_visit && $preferences['persist'])) {
                    if (array_search($metabox_id, $closed_metaboxes) === false) { //if the closed array didnt contain the metabox
                        $closed_metaboxes[] = $metabox_id;
                    }
                } else {

                }
            } else {

                //   if (($first_visit && $preferences['persist']) || (!$first_visit && $preferences['persist'])) {
                if (($first_visit) || (!$first_visit && $preferences['persist'])) {
                    $key = array_search($metabox_id, $closed_metaboxes);
                    if ($key !== false) {
                        $closed_metaboxes[$key] = '';
                    }
                }
            }
        }



        return $closed_metaboxes;
    }

//        /**
//     * Filter Hook - Metabox Order
//     * WordPress Hook Filter Function for 'get_user_option_meta-box-order_{screen_id}'
//     *
//     * Returns an array of the meta box ids that are closed for use in setting the default positions
//     * @param array $closed_metaboxes
//     * @return array $closed_metaboxes
//     *
//     */
//    public function hookMetaboxOrder($metabox_order) {
//
//        /*
//         * if pageCheck doesnt pass,
//         * then return the $closed_metaboxes array untouched.
//         */
//        if (!$this->pageCheck()) {
//
//            return($closed_metaboxes);
//        }
//
//
//        /* Check whether any metabox positions have been saved and if not, consider
//         * this a 'first visit'  and ensure the initial argument type is an array
//         * ensure that data type is array to avoid errors when empty
//         */
//        if (!is_array($closed_metaboxes)) {
//            $first_visit = true;
//            $closed_metaboxes = array();
//        } else {
//            $first_visit = false;
//        }
//
//
//
//
//        $metaboxDefaultStates = $this->_getMetaboxOpenStates();
//
//
//        /*
//         * exit the filter if no default states have been set
//         */
//        if (!is_array($metaboxDefaultStates)) {
//            return $closed_metaboxes;
//        }
//
//
//        /*
//         * iterate through each of the default states and add the metabox
//         * id to the filter if the metabox is to be closed
//         */
//
//        foreach ($metaboxDefaultStates as $metabox_id => $preferences) {
//
//            if ($preferences['open'] === false) {
//                /*
//                 * if this is the first visit, and user wanted to apply defaults only to first visit
//                 * or if this is not the first visit, and the user wanted to apply them always
//                 * then apply the preference
//                 */
//                if (($first_visit) || (!$first_visit && $preferences['persist'])) {
//                    if (array_search($metabox_id, $closed_metaboxes) === false) { //if the closed array didnt contain the metabox
//                        $closed_metaboxes[] = $metabox_id;
//                    }
//                } else {
//
//                }
//            } else {
//
//                //   if (($first_visit && $preferences['persist']) || (!$first_visit && $preferences['persist'])) {
//                if (($first_visit) || (!$first_visit && $preferences['persist'])) {
//                    $key = array_search($metabox_id, $closed_metaboxes);
//                    if ($key !== false) {
//                        $closed_metaboxes[$key] = '';
//                    }
//                }
//            }
//        }
//
//
//
//        return $closed_metaboxes;
//    }

    /**
     * Get Metabox Open States (Internal)
     *
     * Returns an array that indicates the open and persist preferences for
     * any configured Meta Boxes
     * For the structure of this array, see the setMetaboxOpenState() method
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
     * any configured Meta Boxes. Used by setMetaboxOpenState

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
     * @uses setMetaboxOpenState()
     * @return void
     */
    public function setMetaboxOpenState($id, $open = true, $persist = false) {

        /*
         * Add the metabox state to a tracking array
         */


        $this->_meta_box_open_states[$id] = array('open' => $open, 'persist' => $persist);
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
        if (!$this->pageCheck()) {
            $this->debug()->log('Failed Page Check for ' . $this->module->getName());
            return;
        }

        $this->debug()->log('Passed Page Check for ' . $this->module()->getName());

        $handle = $this->plugin()->getSlug() . '_save-metabox-state.js';
        $path = $this->plugin()->getDirectory() . '/admin/js/save-metabox-state.js';
        $inline_deps = null;
        $external_deps = array('post');
        $this->plugin()->enqueueInlineScript($handle, $path, $inline_deps, $external_deps);


        $this->debug()->log('loaded script ' . $handle);


        /*
         * Must pass onto the script the menu slug and current screen id so the metabox placement and expand/collapse will work properly
         * Because the PostUserOptions module is not a menu, it doesnt have a getMenuSlug() method, so we cant call it.
         */
        $screen_id = get_current_screen()->id;
        $isEditScreen = $this->plugin()->tools()->isScreen(array('edit', 'add', 'custom_edit', 'custom_add'), null, false);
        if ($isEditScreen) {

            $vars['menu_slug'] = '';
            //$this->module()->getSlug();
        } else {
            $vars['menu_slug'] = $this->module()->getMenuSlug();
        }
        $vars['screen_id'] = get_current_screen()->id; //required so we can make sure metabox positions are remembered. used in save-metabox-state.js

        $this->debug()->logVar('$vars = ', $vars);
        /*
         * Add javascript for form submission
         *
         *
         *
         */


        $handle = $this->plugin()->getSlug() . '_ajax-submit-form.js';

        $path = $this->plugin()->getDirectory() . '/admin/js/ajax-submit-form.js';
        $inline_deps = array();
        $external_deps = array('jquery');
        $this->plugin()->enqueueInlineScript($handle, $path, $inline_deps, $external_deps);
        $this->debug()->log('loaded script ' . $handle);


        $this->plugin()->setLocalVars($vars);







        $this->_createNonces();
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


        if (is_null($this->_meta_boxes_args)) {
            $this->plugin()->debug()->log('Exiting ' . __FUNCTION__ . ' since $_meta_boxes_args is null');
            return;
        } else {

            $this->debug()->logVar('Adding Meta Box , Meta Box arguments are: = ', $this->_meta_boxes_args);
        }
        $post_type = $this->plugin()->post()->getPostType();

        foreach ($this->_meta_boxes_args as $meta_box_args) {


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
            $screen_id = $meta_box_args['screen_id'];
            $meta_box_args['callback_args']['template_file_name'] = $meta_box_args['id']; //make the template name equal to the metabox id.
            if (is_array($meta_box_args['screen_id'])) {

                $post_types = $meta_box_args['screen_id'];
                $screen_id = null;
                if (isset($post_types['exclude'])) {
                    if (in_array($post_type, $post_types)) {
                        $skip = true;
                    }
                }
                if (isset($post_types['include'])) {
                    if (!in_array($post_type, $post_types)) {
                        $skip = true;
                    }
                }
            }

            if (!$skip) {


                add_meta_box(
                        $this->plugin()->getSlug() . '_' . $meta_box_args['id']  // Meta Box DOM ID , the HTML 'id' attribute of the edit screen section
                        , $meta_box_args['title']  // Title of the edit screen section, visible to user
                        , $meta_box_args['callback']  //Function that prints out the HTML for the edit screen section. The function name as a string, or, within a class, an array to call one of the class's methods. The callback can accept up to two arguments, see Callback args.
                        , $screen_id // string|object The screen on which to show the box (post, page, link). Null defaults to current screen.
                        , $meta_box_args['context']  //normal advanced or side The part of the page where the metabox should show This just allows you to separate out the metaboxes into groups , so that when you call do_metaboxes on a page template, it knows which group to process.
                        , $meta_box_args['priority']  // 'high' , 'core','default', 'low' The priority within the context where the box should show.
                        , $meta_box_args['callback_args'] // Arguments to pass into your callback function. The callback will receive the $post object and whatever parameters are passed through this variable. Get to these args in your callback function by using $metabox['args']
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
    public function addMetaBox($id, $title, $callback, $screen_id, $context, $priority, $callback_args) {
        $this->debug()->t();
        /*
         * Add to the Meta Box Args array.
         * This will allow us to retrieve them later when the hook for add_meta_boxes is fired.
         */
        $this->_meta_boxes_args[] = compact('id', 'title', 'callback', 'screen_id', 'context', 'priority', 'callback_args');
    }

    /**
     * Dispatch request for ajax metabox
     *
     * @param int $cache_timeout Minutes before the cache refreshes
     * @return void
     */
    protected function _AjaxMetabox($cache_timeout = 0) {
        $this->debug()->t();

        //skip the pageCheck check since this is an ajax request and wont contain the $_GET page variable
        // Disable errors
        error_reporting(0);
        /*
         * Compress Output
         */
        if ($this->plugin()->COMPRESS) {
            $this->debug()->log('Started zlib buffering');
            $this->plugin()->tools()->startGzipBuffering();
        }

        // Set headers
        header("Status: 200");
        header("HTTP/1.1 200 OK");
        header('Content-Type: text/html');
        header("Vary: Accept-Encoding");

        /*
         * set cache
         */
        if ($cache_timeout === 0) {
            header('Cache-Control: no-store, no-cache, must-revalidate');
            header('Cache-Control: post-check=0, pre-check=0', FALSE);
            header('Pragma: no-cache');
        } else {

            $expires = 60 * $cache_timeout;        // convert to  minutes expressed in seconds

            header('Pragma: public');
            header('Cache-Control: maxage=' . $expires);
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
            header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $expires) . ' GMT');
        }


        if (!wp_verify_nonce($_GET['_nonce'], $this->plugin()->getSlug())) {

            $this->debug()->log('Nonce check failed, exiting method');
            exit;
        }


        $request = new WP_Http;
        $request_result = $request->request($_GET['url']);
        $result['html'] = $request_result['body'];
        $result['metabox_id'] = $_GET['id'];

        $this->debug()->logVar('$result = ', $result);



        echo json_encode($result);


        die();
    }

    /**
     * Ajax Action - Get Metabox with Cache
     *
     * @param none
     * @return void
     */
    public function hookAjaxMetaboxCache() {

        //skip the pageCheck check since this is an ajax request and wont contain the $_GET page variable
        $this->_AjaxMetabox(30);
    }

    /**
     * Ajax Action - Get Metabox without Cache
     *
     * @param none
     * @return void
     */
    public function hookAjaxMetabox() {

        //skip the pageCheck check since this is an ajax request and wont contain the $_GET page variable
        $this->_AjaxMetabox(0);
    }

    /**
     * Renders a meta box using an Ajax Request
     *
     * @param string $module
     * @param array $metabox
     * @return void
     */
    public function renderMetaBoxAjax($module, $metabox) {


        include($this->plugin()->getDirectory() . '/admin/templates/metabox/ajax.php');
    }

    /**
     * Renders a meta box
     *
     * @param string $module
     * @param array $metabox
     * @return void
     */
    public function renderMetaBoxTemplate($module, $metabox) {



        $this->debug()->logVar('$metabox[\'id\'] = ', $metabox['id']);
        $this->debug()->logVar('$metabox[\'args\'][\'template_file_name\'] = ', $metabox['args']['template_file_name']);



        /*
         * If no template path provided, use the metabox id as the template name and /admin/templates/metabox as the path
         */
        $template_path = $this->plugin()->getDirectory() . '/admin/templates/metabox/' . $metabox['args']['template_file_name'] . '.php';
        if (isset($metabox['args']['path'])) {
            $template_path = $metabox['args']['path'];
        }
        if (!file_exists($template_path)) {
            _e('Not available at this time.', $this->plugin()->getTextDomain());
            $this->plugin()->debug()->logcError($this->plugin()->getSlug() . ' : Meta Box ' . $metabox['id'] . ' error - template path does not exist ' . $template_path);
            return;
        }
        $this->debug()->logVar('$this->plugin()->ALLOW_SHORTCODES = ', $this->plugin()->ALLOW_SHORTCODES);
        if ($this->plugin()->ALLOW_SHORTCODES) {
            $this->debug()->log('Executing shortcodes and including path');

            ob_start();
            include($template_path);

            $template = do_shortcode(ob_get_clean());
            //  $this->debug()->log('$template = ' . $template);//note that if $template has debug statements in it, its better to use 'log' not logVar , since logVar will turn everything into htmlspecialchars, which will obscure the output
            echo $template;
        } else {
            $this->debug()->log('Including the template path without execurting the shortcodes since ALLOW_SHORTCODES is false');

            include($template_path);

            return;
        }



        //      echo do_shortcode($template); //using buffer and do_shortcode is required to allow shortcodes to work within an included file, otherwise they dont render.
    }

    /**
     * Create Nonces
     *
     * Create the WordPress Nonces that will be needed for our forms.
     * Will create a default nonce, unique for the menu, that will be used
     * for any form action. If unique nonces are enabled, a nonce will
     * be created for each method that starts with hookAjax.
     * All nonces will be made available to the javaqscript at
     * <namespace>.forms.nonce_values[$action_slug] where $action_slug is
     * a shortened version of the hookAjax method that is called when
     * the ajax form is submitted.
     *
     * @param none
     * @return void
     */
    protected function _createNonces() {

        /*
         * Check if Editor Page
         * If it is, only use the nonce created by PostUserOptions
         *
         * If this is an editor page, don't
         * create the nonces except if the module creating them is the PostUserOptions
         * This ensures that only PostUserOptions controls nonce creation
         */
        if ($this->plugin()->tools()->isScreen(array('edit', 'add', 'custom_edit', 'custom_add'))) {
            if ($this->plugin()->isModuleLoaded('PostUserOptions')) {


                if ($this->module()->getName() !== 'PostUserOptions') {
                    return;
                }
            }
        }

//        $page = $this->plugin()->tools()->getRequestVar('page');
//        if (is_null($page)) {
//            $page = $this->plugin()->tools()->getRequestVar('action');
//        }
        #init
        $vars = array();



        /*
         * Create the default Nonce Value for Form Submission
         * This will be added to the ajax request when submitting the form.
         */
        $this->module()->setConfig('NONCE_DEFAULT_VALUE', wp_create_nonce($this->module()->NONCE_ACTION));
        $this->debug()->log('Created Nonce using NONCE_ACTION = ' . $this->module()->NONCE_ACTION);

        /*
         * Generate Unique Nonces
         *
         * Generate Unique Nonces for each hookAjax method contained in this class.             *
         * If the user wants to generate unique nonces,
         * then create them using reflection and then pass them to
         * javascript
         *
         */


        if ($this->module()->UNIQUE_ACTION_NONCES) {
            /*
             * use reflection to get all the public method names of the module's current class
             */
            $all_methods = $this->plugin()->tools()->getMethodsNames(get_class($this->module()), ReflectionMethod::IS_PUBLIC);


            /*
             * now filter for those methods that
             * contain hookAjax
             * indicating they are a valid ajax action
             */
            $action_methods = $this->plugin()->tools()->getStringsWithSubstring(array('hookAjax'), $all_methods);

            $this->debug()->logVar('$actions = ', $action_methods);

            /*
             * Create a WordPress nonce action string for each method that starts with hookAjax, which are the
             * action handlers for ajax actions.
             *
             * Loop through each of the hookAjax methods found within this class,
             * and parse the names of the methods to turn them into nonce action names
             *
             *
             * $action The name of the action method , e.g.:hookAjaxSettingsSave
             *                *
             * $action_slug  e.g.:  'settings_save' . The 'action slug', which is the short name for the action (without the module slug prefix)
             *
             * $action_long  e.g.: simpli_hello_Menu010_general_settings_save The 'long name' of the action
             *
             *
             */
            foreach ($action_methods as $action_method) {
                $action_slug = $this->plugin()->tools()->createSlug(str_replace('hookAjax', '', $action_method));
                $this->debug()->logVar('$action_short_name = ', $action_slug);
                $action = $this->plugin()->getSlug() . '_' . $this->module()->getSlug() . '_' . $action_slug;


                //   $vars[$action_short_name . '_nonce_value'] = wp_create_nonce($action_long);
                $vars['forms']['nonce_values'][$action_slug] = wp_create_nonce($action);
            }
        }

        /*
         * tell javascript whether we are using unique nonces
         * A unique nonce is a nonce that is unique for each
         * ajax action. This is different from the default, where
         * we have a nonce that is
         * unique to the menu, not the action.
         */
        $vars['forms']['unique_action_nonces'] = $this->module()->UNIQUE_ACTION_NONCES;

        $vars['forms']['nonce_field_name'] = $this->module()->NONCE_FIELD_NAME;
        $vars['forms']['nonce_values']['default'] = $this->module()->NONCE_DEFAULT_VALUE;

        $this->debug()->logVar('Added to javascript: $vars = ', $vars);

        $this->plugin()->setLocalVars($vars);
    }

    /**
     * Verify WordPress Nonce
     *
     * Verifies the WordPress Nonce , using either a unique action name (derived from the $function_name parameter) or from the default $this->NONCE_ACTION action.
     *
     * The simpli framework automatically handles WordPress Nonces for you for any settings saved by this module. The default configuration is to use a 'one nonce' for each menu, regardless of how many ajax actions are created. This is the easiest to implement, and the least performance heavy, and one that does not require any adherence to method naming conventions for it to work.
     * Alternately, If you wish to use a unique nonce for each action, this is also easily done but is a bit more performance heavy and requires additional understanding if you are to create your own ajax actions.
     * The basic steps are :
     * 1) be sure to stick to naming conventions , where the function for the action hook must be named 'hookAjax<MyAction>'
     * 2) that $this->setConfig('UNIQUE_ACTION_NONCES',true) in the config() method for your Menu module
     * 3) within your ajax script use simpli_hello.my_action_nonce_value
     * @param $function_name The name of the wp_ajax hook function. Must be in the form 'hookAjax' , otherwise, the nonce will be rejected.
     * @return void
     */
    public function wpVerifyNonce($function_name = null) {
        $this->debug()->t();
        /*
         * Get the nonce value that was submitted by checking
         * the $_REQUEST header ( which includes $_GET and $_POST vars)
         */
        $nonce_value = $this->plugin()->tools()->getRequestVar($this->module()->NONCE_FIELD_NAME);

        $this->debug()->logVar('$nonce_value = ', $nonce_value);
        /*
         * Check whether unique nonces are enabled.
         *
         */
        if ($this->module()->UNIQUE_ACTION_NONCES && !is_null($function_name)) {
            /*
             * if unique nonces for each action are enabled, then get their action name from the function name
             */
            $nonce_action = $this->plugin()->getSlug() . '_' . $this->module()->getSlug() . '_' . $this->plugin()->tools()->createSlug(str_replace('hookAjax', '', $function_name));
            $this->debug()->logVar('$nonce_action for unique action = ', $nonce_action);
        } else {
            /*
             * otherwise, just use the default action name
             */
            $nonce_action = $this->module()->NONCE_ACTION;
            $this->debug()->log('Not unique nonces, so using action = ' . $nonce_action . ' taken from module ' . $this->module()->getName());
        }
        if (!wp_verify_nonce($nonce_value, $nonce_action)) {

            $this->debug()->log('Failed Nonce for ' . $nonce_action);
            return false;
        } else {
            $this->debug()->log('Nonce PASSED for ' . $nonce_action);
            return true;
        }
    }

    /**
     * Display Ajax Message
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
    public function displayAjaxMessage($template_path, $message, $errors = null, $logout = false, $reload = false) {




        if ($logout) {
            wp_logout();
        }

        /*
         *
         * Check to see if the current request is an ajax call
         * If it is not, return
         * Disable the check if we are debugging, since we still want to see the result if we make a non-ajax call to this method
         */
        if (!$this->plugin()->DEBUG) //Disable the ajax check if we are debugging, since we still want to see the result if we make a non-ajax call to this method directly
            if (!$this->plugin()->tools()->isAjax()) {
                return '';
            }



        /*
         * Ajax Message
         *
         * Handles the output of ajax messages, formatted for WordPress Admin
         *
         *
         */


        /*
         * Check for errors

          $errors[]='That was definitly the wrong answer';
         * $errors[]='Nope, try again';
          $errors[]='Can\'t you get anything right?';

         */

        if (!is_null($errors) && !empty($errors)) { //if there are error messages, display them

            /*
             * Build an Error Template and Process it
             */
            $error_html = '';
            foreach ($errors as $error) {
                $error_html.= '<li><p>' . $error . '</p></li>';
            }
            $error_html.= '</ul></div>';
            $tags['ERROR_HTML'] = $error_html;

            $template = '
    <div class="error below-h2 fade" id="message">

    <ul>
    {ERROR_HTML}
    </ul>
    </div>';
        } else { // but if there are no errors...
            /*
             * Build a Message Template and Process it
             */
            $tags['MESSAGE'] = $message;
            $tags['RELOAD_SCRIPT'] = ( $logout || $reload ) ? '<script type="text/javascript">var d = new Date();window.location = window.location.href+\'&\' + d.getTime();</script>' : '';

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
         */
        if (!$this->plugin()->COMPRESS) {
            while (@ob_end_clean());
        }

        /*
         * Finally output the template
         */
        echo $this->plugin()->tools()->crunchTpl($tags, $template);

        die(); //required to ensure ajax request exits cleanly; otherwise it hangs and browser request is garbled.
    }

}

?>