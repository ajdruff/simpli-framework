<?php

/**
 * Post Module
 *
 * Adds options to the edit post screen.
 *
 * @author Andrew Druffner
 * @package SimpliFramework
 * @subpackage SimpliHello
 *
 */
class Simpli_Hello_Modules_PostUserOptions extends Simpli_Hello_Basev1c2_Plugin_Module {

    /**
     * Post option Defaults
     *
     * @var array
     */
    protected $_option_defaults = null;

    /**
     * Post options
     *
     * @var array
     */
    protected $_options = null;

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
         * Load Post options on front end
         */


        add_action('the_post', array($this, 'hookPost')); //archive pages will call multiple posts, and with each new post, the options have to be reloaded or you'll carry forward the topmost post's options to the ones below it

        /*
         * add hooks that are dependent on knowing which screen we are on.
         */

        add_action('current_screen', array($this, 'hookEditingScreen'));

        /*
         * Must add any wp_ajax actions to addHooks
         */
        add_action('wp_ajax_' . $this->plugin()->getSlug() . '_save_post', array($this, 'hookAjaxSavePost'));
    }

    /**
     * Hook - Post
     *
     * Hooks into the current post.
     * Add method calls that should
     * occur when the post is displayed to the front end user.
     *
     *
     * @param none
     * @return void
     */
    public function hookPost() {
        /*
         * Dont continue if we are in admin
         */
        if (is_admin()) {
            return;
        }
        /*
         * Load the User Options so they can be used for content filters
         */

        $this->loadUserOptions();
    }

    /**
     * Hook - Editing Screen
     *
     * Hooks into the Editing Screen
     * Add method calls that should occur when the Editing or 'add new' screen
     * is displayed to someone logged into admin.
     * This is a good place to call addMetaBoxes, and to add any scripts or styles that
     * should only appear on the editor.
     * Checks the current screen object, and then builds the layout of the screen , adding metaboxes, scripts etc
     *
     * @param none
     * @return void
     */
    public function hookEditingScreen() {
        $this->debug()->t();
        if (!$this->pageCheckEditor()) {
            $this->debug()->log('Exiting hookEditingScreen since it didnt pass pageCheckEditor test');
            return;
        }
        $this->debug()->logVar('current_screen = ', get_current_screen());
        global $post;
        $this->debug()->logVar('$post = ', $post);
        $this->debug()->log('Passed pageCheckEditor test');
        /*
         * Hook our save method into the post's save action
         */

        add_action('save_post', array($this, 'hookSavePost'));


        /*
         * Load Post options when in Admin
         */
        $this->loadUserOptions();
//      add_action('current_screen', array($this, 'loadUserOptions')); //wp hook is not reliable on edit post page. admin_init cannot be used since a call to get_current_screen will return null see usage restrictions: http://codex.wordpress.org/Function_Reference/get_current_screen


        /*
         * Add our metabox
         * Hook into 'current_screen' .
         * We dont use the 'add_meta_boxes' action since it will not work when used
         * with a custom post editor.
         */
        $this->metabox()->hookAddMetaBoxes();
//     add_action('current_screen', array($this, 'addMetaBoxes'));


        /* Ajax
         * Note: You cannot add wp_ajax actions here or they wont fire. You must add them to the addHooks method
         */




// Add scripts
        /*
         * Add javascript for Ajax form submission
         *
         */
        $handle = $this->plugin()->getSlug() . '_ajax-actions-post.js';
        $path = $this->plugin()->getDirectory() . '/admin/js/ajax-actions-post.js';
        $inline_deps = array();
        $external_deps = array('jquery');
        $this->plugin()->enqueueInlineScript($handle, $path, $inline_deps, $external_deps);

        /*
         * Add javascript for non-ajax form submission
         *
         */
        $handle = $this->plugin()->getSlug() . '_publish-post-actions.js';
        $path = $this->plugin()->getDirectory() . '/admin/js/publish-post-actions.js';
        $inline_deps = array();
        $external_deps = array('jquery');
        //  $this->plugin()->enqueueInlineScript($handle, $path, $inline_deps, $external_deps);
        //  add_action('admin_enqueue_scripts', array($this, 'hookEnqueueScripts'));
    }

    /**
     * Configure Module
     *
     * @param none
     * @return void
     */
    public function config() {
        $this->debug()->t();





        /*
         * Configure the metabox object
         * Pass the optional pageCheck callback method to
         * ensure no hooks fire on pages other than our Menu page
         */
        $this->metabox()->config(array($this, 'pageCheckEditor'));





        /*
         * dont show the Meta Box for the Snippet
         * post type,since that would cause recursion when
         * viewing the snippet post type.
         */

        /*
         * add the metaboxes
         */
        if (true)
            $this->metabox()->addMetaBox(
                    $this->getSlug() . '_' . 'metabox_options'  //Meta Box DOM ID
                    , __('Simpli Hello Options', $this->plugin()->getTextDomain()) //title of the metabox.
                    , array($this->metabox(), 'renderMetaBoxTemplate')//function that prints the html
                    , array('exclude' => $this->plugin()->getSlug() . '_snippet')//  string|object|array $screen Optional. The screen on which to show the box (post, page, link). Defaults to current screen. If you pass an array, the meta box will be added to the current screen, for the post types specified. To exclude the meta box from being added to post types, use this format: array('exclude'=>array('post','custom_post_type'). to limit to only certain post types, use this format array('include'=>array('post','custom_post_type'))
                    , 'normal' //normal advanced or side The part of the page where the metabox should show
                    , 'default' // 'high' , 'core','default', 'low' The priority within the context where the box should show
                    , null //$metabox['args'] in callback function
//,  array('path' => $this->plugin()->getDirectory() . '/admin/templates/metabox/post.php') //$metabox['args'] in callback function
            );
        $this->debug()->log('adding meta box with id =  ' . $this->getSlug() . '_' . 'metabox_options');


        /*
         * set the metabox initial open/closes states
         *
         * You can force the Meta Box's initial state to close using the following:
          $this->metabox()->setMetaboxOpenState($this->getSlug() . '_metabox_options', false, false);
         */

        /*
         * Set the Post Option defaults
         * About options: You must prefix each option with the plugin slug in the form 'mycompany_myplugin_<option_name>
         * When creating post option forms, the field name must exactly equal the associative index of each element below
         * You can access a option with or without the prefix. as in $text=getUserOption('text') or $text=getUserOption('simpli_hello_text')
         * Both will retrieve the same value
         */

        /*
         * Enabled
         * enabled or disabled. Whether you want the Hello World feature to be enabled for this post
         *
         */
        $this->setUserOptionDefault(
                'enabled', 'enabled'
        );


        /*
         * Post Text
         * The text that you want inserted after each post
         *
         */
        $this->setUserOptionDefault(
                'text', 'Hello World!'
        );
        /*
         * Use Global Text
         * 'custom_text','default', or 'snippet'.
         * 'custom_text' - will use the custom text from the 'text' post option
         * 'default' - will use the text provided in the plugin's admin settings
         * 'snippet' - will use the text provided by the snippet custom post type using the post_id of the snippet from the snippet dropdown.
         *
         */
        $this->setUserOptionDefault(
                'use_global_text', 'default' //'custom_text','default', or snippet.
        );



        /*
         * Placement
         * 'before' , 'after' or 'default'.
         *
         * Where you'd like to place the post text in relation to the post's content
         * 'default' will use the value provided by the global option
         *
         *
         */
        $this->setUserOptionDefault(
                'placement', 'default'
        );


        /*
         * Snippet
         * The post->ID of the snippet to be used
         *
         *
         */
        $this->setUserOptionDefault(
                'snippet', '0'
        );



        /*
         * Test Checkbox
         * 'before' , 'after' or 'default'.
         *
         * Where you'd like to place the post text in relation to the post's content
         * 'default' will use the value provided by the global option
         *
         *
         */
        $this->setUserOptionDefault(
                'my_checkbox', array('red' => 'yes')
        );

        /*
         * Messages to the User
         */
        $this->setConfigDefault('MESSAGE_SAVE_SUCCESS', __("Changes saved.", $this->plugin()->getTextDomain()));
        $this->setConfigDefault('MESSAGE_NONCE_FAILED', __("Failed to save changes, please log out and log back in and try again.", $this->plugin()->getTextDomain()));
        $this->setConfigDefault('MESSAGE_SAVE_FAILED', __("Failed to save changes, please log out and log back in and try again.", $this->plugin()->getTextDomain()));



        /*
         *
         * Nonces
         * The PostUserOptions module controls nonce creation of metaboxes that are added
         * to editor pages. Any NONCE_ properties created by other menu modules are ignored.
         */

        $this->setConfig('NONCE_ACTION', $this->plugin()->getSlug() . '_' . $this->plugin()->getSlug() . '_save_post');


        $this->setConfig('NONCE_DEFAULT_VALUE', null); //cant wp_create_nonce now, since function not available
        $this->setConfig('NONCE_FIELD_NAME', $this->plugin()->getSlug() . '_nonce');

        $this->setConfig('UNIQUE_ACTION_NONCES', true);
    }

    /**
     * Get Post Option Defaults
     *
     * @param none
     * @return string
     */
    public function getUserOptionDefaults() {
        $this->debug()->t();

        return $this->_option_defaults;
    }

//    /**
//     * Template Tag - Field
//     *
//     * Returns HTML for a Text Input Field
//     * @param string $name The field name of the input field
//     * @param string $value The value of the input
//     * @param string $label The field label
//     * @param string $hint Text that displays on the form to guide the user on how to fill in the field
//     * @param string $help More detailed text on what the field is and how it should be used.
//     * @return string The parsed output of the form body tag
//     */
//    function textOLD($option_id, $label = null, $hint = null, $help = null, $template_id = null) {
//        $this->debug()->t();
//
//
//        $field_name = $this->getUseroptionName($option_id);
//        $value = $this->getUserOption($option_id);
//
//        echo $this->plugin()->getModule('Form')->text($field_name, $value, $label, $hint, $help, $template_id);
//    }
//    /**
//     * Template Tag - Post Option
//     *
//     * Echos out the result of getUserOption()
//     * @param string $content The shortcode content
//     * @return string The parsed output of the form body tag
//     */
//    function postOptionOld($name) {
//        $this->debug()->t();
//
//        echo $this->getUserOption($name);
//    }
//    /**
//     * Get Post Option
//     *
//     * You can access a option with or without the prefix. as in $text=getUserOption('text') or $text=getUserOption('simpli_hello_text')
//     * Its a bit faster without the prefix :)
//     * @param string $option_name
//     * @return mixed
//     */
//    public function getUseroptionOld($name) {
//        $this->debug()->t();
//
//
//        $post_user_options = $this->getUserOptions();
//        $name_with_added_prefix = $this->plugin()->getSlug() . '_' . $name;
//
////echo '<br/> getUserOptions()=';
////        echo '<pre>';
////echo '</pre>';
////echo  '<br/> Name with added prefix=' .$name_with_added_prefix;
//        /* assume access is by short version, so tack on prefix */
//        if (isset($post_user_options[$name_with_added_prefix])) {
//
//            return($post_user_options[$name_with_added_prefix]);
//        } elseif (isset($post_user_options[$name])) { //then try it unmodified
//            return($post_user_options[$name]);
//        } else {
//            return null;
//        }
//    }

    public function getUserOption($name) {
        $this->debug()->t();


        $post_user_options = $this->getUserOptions();


        if (isset($post_user_options[$name])) {
            $result = ($post_user_options[$name]);
        } else {
            $result = null;
        }


        $this->debug()->logVar('$post_user_options[' . $name . '] = ', $result);
        return $result;
    }

//
//    /**
//     * Template Tag Field Name
//     *
//     * Helper function to echo out the field name of an option
//     * Convienant to use in form fields to avoid having to type 'echo'
//     * @param string $option_name The accessor name  of the option
//     * @return void
//     */
//    function fieldNameOLD($accessor_name) {
//        $this->debug()->t();
//
//
//        echo $this->getUseroptionName($accessor_name);
//    }
//
//    /**
//     * Get Field Name
//     *
//     * Helper function to return the field name of an option
//     * @param string $option_name The accessor name  of the option
//     * @return string
//     */
//    function getFieldNameOLD($accessor_name) {
//        $this->debug()->t();
//
//
//        return $this->getUseroptionName($accessor_name);
//    }
//    /**
//     * Template Tag Field Label
//     *
//     * Helper function to echo out the default field label of an option
//     * Conveniant to use in form fields
//     * @param string $option_name The accessor name  of the option
//     * @return void
//     */
//    function fieldLabelOld($accessor_name) {
//        $this->debug()->t();
//
//
//        echo '__NEW_LABEL__'; // replace with lookup of the option's label
//    }
//    /**
//     * Get Field Label
//     *
//     * Helper function to return the default field label of an option
//     * @param string $option_name The accessor name  of the option
//     * @return string
//     */
//    function getFieldLabelOld($accessor_name) {
//        $this->debug()->t();
//
//
//        return '__NEW_LABEL__'; // replace with lookup of the option's label
//    }
//    /**
//     * Template Tag Field Help
//     *
//     * Helper function to echo out the default field help text of an option
//     * Conveniant to use in form fields
//     * @param string $option_name The accessor name  of the option
//     * @return void
//     */
//    function fieldHelpOld($accessor_name) {
//        $this->debug()->t();
//
////stub
//        echo '__HELP_TEXT__'; // replace with lookup of the option's help
//        //echo getUseroptionsName($accessor_name);
//    }
//    /**
//     * Get Field Help
//     *
//     * Helper function to return the default field help text of an option
//     * @param string $option_name The accessor name  of the option
//     * @return string
//     */
//    function getFieldHelpOld($accessor_name) {
//        $this->debug()->t();
//
////stub
//        return '__HELP_TEXT__'; // replace with lookup of the option's help
//        //echo getUseroptionsName($accessor_name);
//    }
//    /**
//     * Get Post Option Name
//     *
//     * Helper function that saves time when creating field names for forms, and in allowing the getUserOption function to
//     * be used to access an option using only its shortname
//     * Simply returns the plugin slug prepended to the argument.
//     * @param string $option_name
//     * @return mixed
//     */
//    public function getUseroptionNameOld($name) {
//        $this->debug()->t();
//
//
//
//        /*
//         * If the $name already has the slug prepended, return it without further processing
//         */
//        if (stripos($name, $this->plugin()->getSlug() . '_') !== false) {
//            return $name;
//        } else
//        /*
//         * If the name does not have a prefix, give it one.
//         */ {
//
//            return $this->plugin()->getSlug() . '_' . $name;
//        }
//    }

    /**
     * Get User options
     *
     * Returns the current array of user options for the post
     * @param none
     * @return array
     */
    public function getUserOptions() {
        $this->debug()->t();

        $this->debug()->logVar('$this->_options = ', $this->_options);
        return $this->_options;
    }

    /**
     * Set User option
     *
     * Sets a user option
     *
     * @param string $option
     * @param mixed $value
     * @param int $blog_id
     * @return $this
     */
    public function setUserOption($option_name, $option_value) {
        $this->debug()->t();


        /*
         * Update options array with new value but only if the option
         * key already exists in the array
         * you set the allowed keys in your plugin's $_options declaration
         */
        if (in_array(trim($option_name), array_keys($this->getUserOptions()))) {
//if (in_array(trim($option_name), array_keys($this->getUserOptionDefaults()))) {
            if (is_string($option_value)) {
                $option_value = trim($option_value);
            }
            $this->_options[$option_name] = $option_value;
        }



        return $this;
    }

    /**
     * Save User Options for the Post object to the WordPress Database
     * Takes post_options array and saves it to wp_options table
     * @param int $post_id The id of the post object
     * @param boolean $true_on_success True means you will recieve true when the update succeeds, even if the existing data matches the data you are updating it with, and even if the option_name had to be created first. If you set this to False, you'll get the semi-asinine result that the update_post_meta gives you such that 'false' is returned if the values dont change and the meta_id is returned if a new option had to be created. See this :http://codex.wordpress.org/Function_Reference/update_post_meta  . The default for $true_on_success if false since we want to be consistent with the codex even if it is asinine, and because it means we take one less trip to the database.
     * @return mixed If $true_on_success is set to true, gives true if there is no failure on save. If $true_on_success is false,  the result behaves identically to the codex explanation of update_post_meta result: Returns meta_id if the meta doesn't exist, otherwise returns true on success and false on failure
     */
    public function saveUserOptions($post_id, $true_on_success = false) {
        $this->debug()->t();


        $this->debug()->log('Saving Post Options');

        $wp_option_name = $this->plugin()->getSlug() . '_options';
        $options = $this->getUserOptions();
        $this->debug()->logVar('$options = ', $options);
        $this->debug()->log('update_post_meta (' . $post_id . ',' . $wp_option_name . ',$options');

        /*
         * if the user wants the 'true' result, meaning , they want to see if the false is
         * really a failure, we need to compare with existing
         */
        if ($true_on_success) {
            $existing_options = get_post_meta($wp_option_name);
            $result = update_post_meta($post_id, $wp_option_name, $options);
            if ($existing_options === $options) {
                if ($result === false) {
                    $result = true;
                }
            } else {
                if ($result !== true) { //if $result is not false but is not true, then $result is the meta_id of the added option, so the $result is actually true. get it? per codex: update_post_meta Returns meta_id if the meta doesn't exist, otherwise returns true on success and false on failure
                    $result = true;
                }
            }
        } else {

            $this->debug()->log('Updating post meta using update_post_meta()');


            $this->debug()->logExtract(array('post_id' => $post_id, 'wp_option_name' => $wp_option_name, 'options' => $options,));


            $result = update_post_meta($post_id, $wp_option_name, $options);
        }
        /*
         * Note that false does not necessarily mean failure
         * false also means that the data has not changed.
         */
        $this->debug()->logVar('$result = ', $result);
        return $result;
    }

    /**
     * Load Post Options from database
     * @param int $post_id
     * @return $this
     */
    public function loadUserOptions() {
        $this->debug()->t();


        /*
         *
         */

        if (!$this->plugin()->tools()->isAjax()) {



            if (!$this->pageCheckEditor()) {

                /*
                 * even though its not
                 * and editing page, if its not admin, do *not* return,
                 * since we need to load options for the 'the_post' action
                 * which occurs on the frontend (the non-admin pages)
                 *
                 */
                if (is_admin()) {


                    $this->debug()->log('pageCheck failed, returning');

                    return;
                }
            }
        }
        $this->debug()->log('pageCheck passed');


        $default_options = $this->getUserOptionDefaults();
        $post_meta_options = array();

        $wp_option_name = $this->plugin()->getSlug() . '_options';

        global $post;

        $post = (isset($_GET['post'])) ? get_post($_GET['post']) : $post;
        $post = (empty($post) && !empty($_POST['post_ID'])) ? get_post($_POST['post_ID']) : $post;
// if (!empty($post)&& !empty($_POST['post_ID'])) {
        if (!empty($post)) {
// $this->loadUserOptions($post->ID);
            $post_id = $post->ID;
            $post_meta_options = get_post_meta($post_id, $wp_option_name, true);
        } else {
            $default_options = $this->getUserOptionDefaults();
// echo '<br> loading defaults' ;
        }


        /*
         * merge the two options
         * this means that new defaults will filter out old options
         * also that any option values in the database will
         * overwrite the default options
         */
        /*
         * Make sure post_meta_options is an array
         * If no post options have been saved yet, the get_post_meta function will return
         * an empty string, so we must make post_meta_options into an array so the merge wont fail.
         */
        if (!is_array($post_meta_options)) {
            $post_meta_options = array();
        }


        $options = array_merge($default_options, $post_meta_options);



        if (empty($options)) {
//              echo '<br> loading defaults' ;
            $options = $this->getUserOptionDefaults();
        }

        $this->_options = $options;

        $this->debug()->logVar('$this->_options = ', $this->_options);

        return $this->_options;
    }

    /**
     * Hook Enqueue Scripts
     *
     * Enqueue javascript and styles
     *
     * @param none
     * @return void
     */
    public function hookEnqueueScripts() {


//        /*
//         * Add javascript for form submission
//         *
//         */
//        $handle = $this->plugin()->getSlug() . '_ajax-actions-post.js';
//        $path = $this->plugin()->getDirectory() . '/admin/js/ajax-actions-post.js';
//        $inline_deps = array();
//        $external_deps = array('jquery');
//        $this->plugin()->enqueueInlineScript($handle, $path, $inline_deps, $external_deps);
//+ '&' + simpli_hello.plugin.slug + '_nonce= ' + simpli_hello.save_post_option_nonce
//        <?php wp_nonce_field('ajax_save_post_options', $this->plugin()->getSlug() . '_nonce');



        /* You could also load it as an external file
         * Example of loading using the wp_enqueue_script method

          $handle = $this->plugin()->getSlug() . '_save-menu-options-post.js';
          $src = $this->plugin()->getUrl() . '/admin/js/save-post-options.js';
          $deps = array('jquery');
          $ver = '1.0';
          $in_footer = true;
          wp_enqueue_script($handle, $src, $deps, $ver, $in_footer);
          wp_localize_script($handle, 'my_localize_script_object', array('save_post_option_nonce' => wp_create_nonce($this->plugin()->getSlug() . '_save_post')));//this would make my_localize_script_object.save_post_option_nonce available in javascript
         *   */
    }

    /**
     * Hook - Save Post (Wrapper)
     *
     * Saves the posted form to user options
     * @param int $post_id
     * @return int $post_id
     */
    public function hookSavePost() {


        /*
         * check we're on the editor page
         */
        if (!$this->pageCheckEditor()) {
            return;
        }


        /*
         * check that there is something to save
         */
        if (empty($_POST)) {
            return;
        }

        $post_id = $this->plugin()->tools()->getEditPostId();
        $this->_savePost($post_id);
    }

    /**
     * Save Post (Internal)
     *
     * Internal method to save post options to wordpress database
     * @param int $post_id
     * @return int $post_id
     */
    protected function _savePost($post_id = null) {
        $this->debug()->t();


        if (is_admin()) {

            /*
             * check if our query variable exists,
             * if it doesnt, exit since the post wont include any of our form values
             */
            if ($this->plugin()->tools()->getRequestVar($this->plugin()->getSlug()) === null) {
                $this->debug()->logVar('$_POST = ', $_POST);
                $this->debug()->log('Exiting Post Save since $_POST doesnt include our options');
                return false;
            }
            $this->debug()->logVar('$_POST = ', $_POST, false, true, false, false); //automatically show arrays without needing to click
//return if doing autosave
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                return true;
            }
//check permissions
            if (isset($_POST['post_type']) && $_POST['post_type'] == 'page') {
                if (!current_user_can('edit_pages', $post_id)) {
                    $this->debug()->log('Cant save options - user is not authorized to edit a page');
                    return false;
                }
            } else {
                if (!current_user_can('edit_posts', $post_id)) {
                    $this->debug()->log('Cant save options - user is not authorized to edit this post type');
                    return false;
                }
            }
        }

        /*
         * Get User Options
         *
         * On a non-ajax call, they will
         * already be loaded. If being called by Ajax
         * however, they are probably not yet loaded so check first, then load them.
         */

        $user_options = $this->getUserOptions();

        if (is_null($user_options)) {
            $this->loadUserOptions();
            $user_options = $this->getUserOptions();
        } elseif (!is_array($user_options)) {
            $user_options = array();
        }
        $this->debug()->logVar('$user_options ', $user_options, false, true, false, false);

        foreach ($user_options as $option_name => $option_value) {



            /**
             * Set new option value equal to the submitted value only if the option was actually submitted, otherwise, keep the option value the same.
             *  Add extra code to scrub the values for specific options if needed
             */
            $option_value = (isset($_POST[$this->plugin()->getSlug()][$option_name])) ? $_POST[$this->plugin()->getSlug()][$option_name] : $option_value;

            $this->setUserOption($option_name, $option_value);
        }
        /*
         * since saveUserOptions doesnt give us a good true/false result we always set it to true
         */
        /*
         * WordPress update_post_meta function does not give a true indication of
         * success or failure , so as a result, neither do we by default and by designso we choose here to always return a success message.
         * If you want to override this behavior, you'll need to set the parameter $true_on_success to true in (Simpli_Hello_Module_PostUserOptions::saveUserOptions)
         * If the user questions whether updating is occuring properly, they can turn on debugging.
         */
        $this->saveUserOptions($post_id);


// return $post_id;
        return true;
    }

    /**
     * Hook - Save Post Ajax
     *
     * Save the post options using ajax
     *
     * @param none
     * @return void
     */
    public function hookAjaxSavePost() {
        $this->debug()->t();
        /*
         * No pageCheck needed since this is ajax, so the method wouldnt even be called unless it was on the right page
         */

        /*
         * Check Nonces
         * This check *must* happen within the wrapper, not in the internal function, or
         * the __FUNCTION__ parameter wont be correct and the nonce will fail.
         */
        if (!$this->metabox()->wpVerifyNonce(__FUNCTION__)) {

            $message = $this->MESSAGE_NONCE_FAILED;
            $this->metabox()->displayAjaxMessage(
                    $this->plugin()->getDirectory() . '/admin/templates/ajax_message_post_options.php', //string $template The path to the template to be used
                    $message, // string $message The html or text message to be displayed to the user
                    array(), //$errors Any error messages to display
                    false, //boolean $logout Whether to force a logout after the message is displayed
                    false //boolean $reload Whether to force a page reload after the message is displayed
            );
        }


        /*
         * save post options
         */


        /*
         * Get the post id that is provided by the editor
         * getEditPostID() will try different places the id might
         * be located , depending on whether its a new or existeing post, and
         * whether you are using a custom post editor
         */
        $post = $this->plugin()->post()->getPost();

        $this->debug()->logVar('$post = ', $post);
        $post_id = $post->ID;

        /*
         * Now that we know the post id, we can save the options
         */
        $success = $this->_savePost($post_id);

        if ($success === true) {
            $message = $this->MESSAGE_SAVE_SUCCESS;
        } else {
            $message = $this->MESSAGE_SAVE_FAILED;
        }


        $this->metabox()->displayAjaxMessage(
                $this->plugin()->getDirectory() . '/admin/templates/ajax_message_post_options.php', //string $template The path to the template to be used
                $message, // string $message The html or text message to be displayed to the user
                array(), //$errors Any error messages to display
                false, //boolean $logout Whether to force a logout after the message is displayed
                false //boolean $reload Whether to force a page reload after the message is displayed
        );
    }

    /*
     * Page Check Editor
     *
     * Indicates whether the current page is
     * an editor of any post type
     *
     * @var $_page_check_editor boolean
     *
     */

    private $_page_check_editor = null;

    /**
     * Page Check Editor
     *
     * Use for hook functions. Checks to see if we are on an Edit page before we take any hook actions.
     * @param none
     * @return boolean
     */
    public function pageCheckEditor() {
        $this->debug()->t();



        if (is_null($this->_page_check_editor)) {

            if (!is_admin()) {
                $this->_page_check_editor = false;
            } else {

                $this->_page_check_editor = $this->plugin()->tools()->isScreen(array('edit', 'add'), null, false);
                if ($this->_page_check_editor) {
                    $this->debug()->logVar('Page is either an add or an edit page. Result = ', $this->_page_check_editor);
                } else {
                    /*
                     * if pageCheck failed, check to see if we are on a custom edit or add screen
                     */
                    $this->debug()->log('Not a standard edit or add page, checking to see if its a CustomEdit or CustomAdd screen');
                    $this->_page_check_editor = $this->plugin()->tools()->isScreen(array('custom_edit', 'custom_add'), null, false);
                    if (!$this->_page_check_editor) {
                        $this->debug()->log('Page is not an edit, custom edit, add, or custom add page, returning false.');
                    } else {
                        $this->debug()->logVar('Page a custom add or custom edit page', $this->_page_check_editor);
                    }
                }
            }
        } else {
            $this->debug()->logVar('Page check already done, so returning cached result of : ', $this->_page_check_editor);
        }



        /*
         * check to see if we are either on the edit or add screen
         *
         */





        return ($this->_page_check_editor);
    }

    /**
     * Set Default option
     *
     * Sets a default value for options that have not yet been saved to the database.
     * If you want a option to have a value before any configuration by the user occurs,
     * you must set it here.
     *
     * @param string $option_name The name of the option. Must be unique for the plugin
     * @param mixed $option_value The value of the option.
     * @return void
     */
    protected function setUserOptionDefault($option_name, $option_value) {

        $this->_option_defaults[$option_name] = $option_value;
    }

    protected $_meta_box_object = null;

    /**
     * Metabox
     *
     * Provides the Metabox States utility object that manages the metabox open/closed state.
     *
     * @param none
     * @return object
     */
    public function metabox() {

        if (is_null($this->_meta_box_object)) {
            $this->_meta_box_object = new Simpli_Hello_Basev1c2_Plugin_Module_Metabox($this);
        }
        return $this->_meta_box_object;
    }

}

