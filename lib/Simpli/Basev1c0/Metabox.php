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
 $this->metabox()->config(array($this,'pageCheckMenu'));
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
class Simpli_Basev1c0_Metabox{

    /**
     *
     * @var object The object that creates this class's object
     * (usually the menu screen that will be adding the metabox).
     * Could be a Menu object or a PostUserOptions object
     */
    protected $_caller;

    /**
     * Get caller
     *
     * Returns the caller object (the calling object)
     *
     * @param none
     * @return object Calling Object
     */
    private function _getCaller() {
        return $this->_caller;
    }
    /**
     * Constructor
     *
     * Use a constructor so we can capture the caller's reference
     *
     * @param object $caller The object that uses a method to instantiate this
     * class's object
     * @return void
     */

    public function __construct($caller) {
        $this->_caller = $caller;
    }

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
        add_action('wp_ajax_' . $this->getPlugin()->getSlug() . '_ajax_metabox', array($this, 'hookAjaxMetabox'));
        add_action('wp_ajax_' . $this->getPlugin()->getSlug() . '_ajax_metabox_cache', array($this, 'hookAjaxMetaboxCache'));



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
        $this->debug()->t();
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
    }

    /**
     * Page Check
     *
     * Returns the result of the pageCheck callback method passed by the caller.
     *Used for indicating whether we are on the correct page before executing hooks
     *
     * @param none
     * @return boolean
     */
    public function pageCheck() {

        if (is_null($this->_page_check_callback)) {
            return true; //if no pageCheck callback was passed , then it always passes
        }

        if (is_array($this->_page_check_callback) && isset($this->_page_check_callback[1])) {
            $this->getPlugin()->debug()->logVar('$this->_page_check_callback = ', $this->_page_check_callback);
            $this->getPlugin()->debug()->logVar('$this->_page_check_callback[0] = ',$this->_page_check_callback[0]);
            $this->getPlugin()->debug()->logVar('$this->_page_check_callback[1] = ', $this->_page_check_callback[1]);

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
     * @param boolean $persist True will cause the meta box to keep the state indicated by the $open paramater value
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

        if (!$this->pageCheck()) {

            return;
        }
        $handle = $this->getPlugin()->getSlug() . '_save-metabox-state.js';
        $path = $this->getPlugin()->getDirectory() . '/admin/js/save-metabox-state.js';
        $inline_deps = null;
        $external_deps = array('post');
        $this->getPlugin()->enqueueInlineScript($handle, $path, $inline_deps, $external_deps);

        $this->getPlugin()->debug()->logVar('get_current_screen() = ', get_current_screen());
        /*
         * Must pass onto the script the menu slug and current screen id so the metabox placement and expand/collapse will work properly
         * If on an edit screen, the screen_id and menu_slug should be empty strings
         */
        $screen_id=get_current_screen()->id;
        $isEditScreen=$this->getPlugin()->tools()->isScreen(array('edit','add','custom_edit','custom_add'),null,false);
        if ($isEditScreen) {

                        $vars = array(
                'menu_slug' => ''
                , 'screen_id' => ''
            );

        }else{
                  $vars = array(
            'menu_slug' => $this->_getCaller()->getMenuSlug()
            , 'screen_id' => get_current_screen()->id
        );

        }

        $this->getPlugin()->setLocalVars($vars);
    }
    /**
     * debug
     *
     * Returns the plugin's debug object
     *
     * @param none
     * @return void
     */
    public function debug() {

        return $this->getPlugin()->debug();
    }
    /**
     * Get Plugin
     *
     * Returns the calling caller's plugin object
     *
     * @param none
     * @return void
     */
    public function getPlugin() {

        return $this->_getCaller()->getPlugin();
    }
    /**
     * Hook Add Meta Boxes
     *
     * Fired when metaboxes are added, and adds all the metaboxes that have been configured.
     *
     * @param none
     * @return void
     */
    public function addMetaBoxes() {

        $this->getPlugin()->debug()->t();
        if (is_null($this->_meta_boxes_args)) {
            $this->getPlugin()->debug()->log('Exiting ' . __FUNCTION__ . ' since $_meta_boxes_args is null');
            return;
        }

        foreach ($this->_meta_boxes_args as $meta_box_args) {


            /*
             * extract the array of arguments
             * provided by the public addMenuPage() method
             */
            extract($meta_box_args);

            /*
             * Now call the internal method that actually does the work within the hook
             */




           add_meta_box(
                    $id  // Meta Box DOM ID , the HTML 'id' attribute of the edit screen section
                    , $title  // Title of the edit screen section, visible to user
                    , $callback //Function that prints out the HTML for the edit screen section. The function name as a string, or, within a class, an array to call one of the class's methods. The callback can accept up to two arguments, see Callback args.
                    , $screen_id// Current Screen ID . This is mistakenly called $post_type in the codex. See source.
                    , $context //normal advanced or side The part of the page where the metabox should show This just allows you to separate out the metaboxes into groups , so that when you call do_metaboxes on a page template, it knows which group to process.
                    , $priority // 'high' , 'core','default', 'low' The priority within the context where the box should show.
                    , $callback_args// Arguments to pass into your callback function. The callback will receive the $post object and whatever parameters are passed through this variable. Get to these args in your callback function by using $metabox['args']
            );
        }
    }
    protected $_meta_boxes_args;
    /**
     * Add Meta Box (Wrapper)
     *
     * Stores the paramater values to an array for later retrieval by a hook that will make a call to the WordPress method add_meta_box.
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
    public function addMetaBox($id,$title,$callback,$screen_id,$context,$priority,$callback_args) {
        $this->debug()->t();
         /*
          * Add to the Meta Box Args array.
          * This will allow us to retrieve them later when the hook for add_meta_boxes is fired.
          */
        $this->_meta_boxes_args[]=compact('id','title','callback','screen_id','context','priority','callback_args');

    }

    /**
     * Dispatch request for ajax metabox
     *
     * @param int $cache_timeout Minutes before the cache refreshes
     * @return void
     */
    public function _AjaxMetabox($cache_timeout = 0) {
        $this->debug()->t();

        //skip the pageCheck check since this is an ajax request and wont contain the $_GET page variable
        // Disable errors
        error_reporting(0);
        /*
         * Compress Output
         */
        if ($this->getPlugin()->COMPRESS) {
               $this->debug()->log('Started zlib buffering');
           $this->getPlugin()->tools()->startGzipBuffering();


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


        if (!wp_verify_nonce($_GET['_nonce'], $this->getPlugin()->getSlug())) {

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


        include($this->getPlugin()->getDirectory() . '/admin/templates/metabox/ajax.php');
    }
    /**
     * Renders a meta box
     *
     * @param string $module
     * @param array $metabox
     * @return void
     */
    public function renderMetaBoxTemplate($module, $metabox) {




        /*
         * If no template path provided, use the metabox id as the template name and /admin/templates/metabox as the path
         */
        $template_path = $this->getPlugin()->getDirectory() . '/admin/templates/metabox/' . $metabox['id'] . '.php';
        if (isset($metabox['args']['path'])) {
            $template_path = $metabox['args']['path'];
        }
        if (!file_exists($template_path)) {
            _e('Not available at this time.', $this->getPlugin()->getTextDomain());
            $this->getPlugin()->debug()->logcError($this->getPlugin()->getSlug() . ' : Meta Box ' . $metabox['id'] . ' error - template path does not exist ' . $template_path);
            return;
        }

        if ($this->getPlugin()->ALLOW_SHORTCODES) {
            ob_start();
            include($template_path);
            $template = ob_get_clean();
            echo $template;
        } else {
            include($template_path);

            return;
        }



        //      echo do_shortcode($template); //using buffer and do_shortcode is required to allow shortcodes to work within an included file, otherwise they dont render.
    }

}

?>