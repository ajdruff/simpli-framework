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
class Simpli_Hello_Module_PostUserOptions extends Simpli_Basev1c0_Plugin_Module {

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
            return;
        }


        /*
         * Hook our save method into the post's save action
         */

        add_action('save_post', array($this, 'hookPostSave'));


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


        /* save using ajax */
        //  add_action('wp_ajax_' . $this->plugin()->getSlug() . '_settings_save', array($this, 'hookAjaxSave'));


        /* DEPRECATED
         * Hook into the form class so we can provide the value of forms with an option lookup

          add_action('simpli_hello_forms_pre_parse', array($this, 'forms_pre_parse'));
         */


        // Add scripts
        add_action('admin_enqueue_scripts', array($this, 'hookEnqueueScripts'));
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
 * add the metaboxes
 */

        $this->metabox()->addMetaBox(
                $this->getSlug() . '_' . 'metabox_options'  //Meta Box DOM ID
                , __('Box 1 - Metabox added from within ' . basename(__FILE__), $this->plugin()->getTextDomain()) //title of the metabox.
                , array($this->metabox(), 'renderMetaBoxTemplate')//function that prints the html
                , $screen_id = null// post_type when you embed meta boxes into post edit pages
                , 'normal' //normal advanced or side The part of the page where the metabox should show
                , 'default' // 'high' , 'core','default', 'low' The priority within the context where the box should show
                , null //$metabox['args'] in callback function
                //,  array('path' => $this->plugin()->getDirectory() . '/admin/templates/metabox/post.php') //$metabox['args'] in callback function
        );
//
       $this->metabox()->addMetaBox(
                $this->getSlug() . '_' . 'metabox_test'  //Meta Box DOM ID
                , __('Box 2 - Metabox added from within ' . basename(__FILE__), $this->plugin()->getTextDomain()) //title of the metabox.
                , array($this->metabox(), 'renderMetaBoxTemplate') //function that prints the html
                , $screen_id = null// must be null so WordPress uses current screen id as default. mistakenly called $post_type in the codex. See Source Code.
                , 'normal' //normal advanced or side The part of the page where the metabox should show
                , 'default' // 'high' , 'core','default', 'low' The priority within the context where the box should show
                , null //$metabox['args'] in callback function
        );


        /*
         * set the metabox initial open/closes states
         */
        $this->metabox()->setMetaboxOpenState($this->getSlug() . '_metabox_ajax_options', true, false);


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
         * true or false. Use global text instead of this post's text
         *
         */
        $this->setUserOptionDefault(
                'use_global_text', false
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


        $this->debug()->logVar('$post->getUserOption(' . $name . ') = ', $result);
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
     * @param int $blog_id
     * @return $this
     */
    public function saveUserOptions($post_id) {
        $this->debug()->t();


        $this->debug()->log('Saving Post Options');

        $wp_option_name = $this->plugin()->getSlug() . '_options';
        $options = $this->getUserOptions();
        $this->debug()->logVar('$options = ', $options);
        update_post_meta($post_id, $wp_option_name, $options);




        return $this;
    }

    /**
     * Load Post Options from database
     * @param int $post_id
     * @return $this
     */
    public function loadUserOptions() {
        $this->debug()->t();




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
     * Add Meta Boxes
     *
     * Add any calls to add_meta_boxes here. This method will be called from within hookEditingScreen to add metaboxes for the editing screen. The hookEditingScreen does a pageCheckEditor() to ensure that its the correct editor before calling this method.
     * @param none
     * @return void
     */
    public function addMetaBoxesOLD() {
        $this->debug()->t();

//        /*
//         * On top of the normal pageCheck, check to make sure that we arent on a custom post editor page. If we are, then add the metaboxes.
//         */
//        if (!$this->pageCheck()) {
//
//            $custom_edit_page = ((isset($_GET[$this->plugin()->QUERY_VAR]) && ($_GET[$this->plugin()->QUERY_VAR] === $this->plugin()->QV_EDIT_POST)) ? true : false);
//            $custom_add_page = ((isset($_GET[$this->plugin()->QUERY_VAR]) && ($_GET[$this->plugin()->QUERY_VAR] === $this->plugin()->QV_ADD_POST)) ? true : false);
//            /*
//             * Check if on Custom Editor
//             * If not on either the custom edit page or the custom add page, return
//             */
//            if (!$custom_edit_page && !$custom_add_page) {
//                return;
//            }
//        }



        $args = array(
            'public' => true,
        );
        $post_types = get_post_types($args);
        global $post;
        $this->debug()->logVar('$post = ', $post);
        //    foreach ($post_types as $post_type) {

        /*
         * Add the options metabox, but only if the post type is not
         * our custom post type This avoids possible recursion.
         */
        add_meta_box(
                $this->getSlug() . '_' . 'metabox_options'  //Meta Box DOM ID
                , __('Box 1 - Metabox added from within ' . basename(__FILE__), $this->plugin()->getTextDomain()) //title of the metabox.
                , array($this->metabox(), 'renderMetaBoxTemplate')//function that prints the html
                , $screen_id = null// post_type when you embed meta boxes into post edit pages
                , 'normal' //normal advanced or side The part of the page where the metabox should show
                , 'default' // 'high' , 'core','default', 'low' The priority within the context where the box should show
                , null //$metabox['args'] in callback function
                //,  array('path' => $this->plugin()->getDirectory() . '/admin/templates/metabox/post.php') //$metabox['args'] in callback function
        );

        add_meta_box(
                $this->getSlug() . '_' . 'metabox_test'  //Meta Box DOM ID
                , __('Box 2 - Metabox added from within ' . basename(__FILE__), $this->plugin()->getTextDomain()) //title of the metabox.
                , array($this->metabox(), 'renderMetaBoxTemplate') //function that prints the html
                , $screen_id = null// must be null so WordPress uses current screen id as default. mistakenly called $post_type in the codex. See Source Code.
                , 'normal' //normal advanced or side The part of the page where the metabox should show
                , 'default' // 'high' , 'core','default', 'low' The priority within the context where the box should show
                , null //$metabox['args'] in callback function
        );


        //  if ($post->post_type!=='simpli_hello_snippet') {
        if (true) {


            /*
             * note if you want to reposition the metaboxes, chang ethe context from 'side' to 'normal' or vice versa
             * if the change didn't work, then you need to delete the 'meta-box-order_post' meta data in the wp_usermeta table
             * and try again.
             * if you just need to change the location temporarily to make more room for troubleshooting messages, you can just select 'Number of columns' to 1 from the screen options on the post editor page.
             */


            add_meta_box(
                    $this->getSlug() . '_' . 'metabox_options2'  //Meta Box DOM ID
                    , __('Box 3  - Metabox added from within ' . basename(__FILE__), $this->plugin()->getTextDomain()) //title of the metabox.
                    , array($this->metabox(), 'renderMetaBoxTemplate')//function that prints the html
                    , $screen_id = null// must be null so WordPress uses current screen id as default. mistakenly called $post_type in the codex. See Source Code.
                    , 'normal' //normal advanced or side The part of the page where the metabox should show
                    , 'high' // 'high' , 'core','default', 'low' The priority within the context where the box should show
                    , null //$metabox['args'] in callback function
                    //,  array('path' => $this->plugin()->getDirectory() . '/admin/templates/metabox/post.php') //$metabox['args'] in callback function
            );


            add_meta_box(
                    $this->getSlug() . '_' . 'metabox_ajax_options'  //Meta Box DOM ID
                    , __('Box 4  - Metabox added from within ' . basename(__FILE__), $this->plugin()->getTextDomain()) //title of the metabox.
                    , array($this->metabox(), 'renderMetaBoxTemplate')//function that prints the html
                    , $screen_id = null// must be null so WordPress uses current screen id as default. mistakenly called $post_type in the codex. See Source Code.
                    , 'side' //normal advanced or side The part of the page where the metabox should show
                    , 'high' // 'high' , 'core','default', 'low' The priority within the context where the box should show
                    , null //$metabox['args'] in callback function
                    //,  array('path' => $this->plugin()->getDirectory() . '/admin/templates/metabox/post.php') //$metabox['args'] in callback function
            );
        }




        //  }
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


        /*
         * Add javascript for form submission
         *
         */
        $handle = $this->plugin()->getSlug() . '_metabox-form-post.js';
        $path = $this->plugin()->getDirectory() . '/admin/js/metabox-form-post.js';
        $inline_deps = array();
        $external_deps = array('jquery');
        $this->plugin()->enqueueInlineScript($handle, $path, $inline_deps, $external_deps);


        /* You could also load it as an external file
         * Example of loading using the wp_enqueue_script method
         */
        //                  $handle = $this->plugin()->getSlug() . '_metabox-form-post.js';
        //        $src = $this->plugin()->getUrl() . '/admin/js/metabox-form-post.js';
        //        $deps = array('jquery');
        //        $ver = '1.0';
        //        $in_footer = false;
        //        wp_enqueue_script($handle, $src, $deps, $ver, $in_footer);
    }

    /**
     * Save  option to post or page
     *
     * @param int $post_id
     * @return int $post_id
     */
    public function hookPostSave($post_id) {
        $this->debug()->t();


        if (!$this->pageCheckEditor()) {
            return;
        }

        //if the post variable doesnt include our plugin, than exit.
        if (is_admin()) {
            if (!array_key_exists($this->plugin()->getSlug(), $_POST)) {
                $this->debug()->logVar('$_POST = ', $_POST);
                $this->debug()->log('Exiting Post Save since $_POST doesnt include our options');
                return $post_id;
            }
            //   $this->debug()->log('Exiting save ajax call');
            $this->debug()->logVar('$_POST = ', $_POST, false, true, false, false); //automatically show arrays without needing to click
            // if nonce fails , return
            if (!wp_verify_nonce($_POST[$this->plugin()->getSlug() . '_nonce'], 'save_post')) {
                $this->debug()->log('Nonce Failed while trying to save options');
                return $post_id;
            }

//return if doing autosave
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                return $post_id;
            }
//check permissions
            if (@$_POST['post_type'] == 'page') {
                if (!current_user_can('edit_page', $post_id)) {
                    $this->debug()->log('Cant save options - user is not authorized');
                    return $post_id;
                }
            } else {
                if (!current_user_can('edit_post', $post_id)) {
                    $this->debug()->log('Cant save options - user is not authorized');
                    return $post_id;
                }
            }
        }


        $this->debug()->logVar('$this->getUserOptions() ', $this->getUserOptions(), false, true, false, false);



        foreach ($this->getUserOptions() as $option_name => $option_value) {



            /**
             * Set new option value equal to the submitted value only if the option was actually submitted, otherwise, keep the option value the same.
             *  Add extra code to scrub the values for specific options if needed
             */
            $option_value = (isset($_POST[$this->plugin()->getSlug()][$option_name])) ? $_POST[$this->plugin()->getSlug()][$option_name] : $option_value;

            $this->setUserOption($option_name, $option_value);
        }
        $this->saveUserOptions($post_id);


        return $post_id;
    }

    /**
     * Hook - Ajax Save
     *
     * Save the post options using ajax
     *
     * @param none
     * @return void
     */
    public function hookAjaxSave() {

//        if (!wp_verify_nonce($_POST['_wpnonce'], $this->plugin()->getSlug())) {
//            return false;
//        }
        //do something here.

        $message = __("Post Options Saved.", $this->plugin()->getTextDomain());
        $errors = array(); // initialize the error array , add any validation errors when you scrub the form_field values
        //return a success message on submission
        require_once($this->plugin()->getDirectory() . '/admin/templates/ajax_message.php');

        die(); //required after require to ensure ajax request exits cleanly; otherwise it hangs and browser request is garbled.
    }

    /**
     * Renders a meta box
     *
     * @param string $module
     * @param array $metabox
     * @return void
     */
    public function renderMetaBoxTemplateOLD($module, $metabox) {
        $this->debug()->t();


        /*
         * If no template path provided, use the metabox id as the template name and /admin/templates/metabox as the path
         */
        $template_path = $this->plugin()->getDirectory() . '/admin/templates/metabox/' . $metabox['id'] . '.php';
        if (isset($metabox['args']['path'])) {
            $template_path = $metabox['args']['path'];
        }
        if (!file_exists($template_path)) {
            _e('Not available at this time.', $this->plugin()->getTextDomain());
            $this->plugin()->debug()->logcError($this->plugin()->getSlug() . ' : Meta Box ' . $metabox['id'] . ' error - template path does not exist ' . $template_path);
            return;
        }
        include($template_path);
    }

    /**
     * Renders a meta box using an Ajax Request
     *
     * @param string $module
     * @param array $metabox
     * @return void
     */
    public function renderMetaBoxAjaxOLD($module, $metabox) {
        $this->debug()->t();



        include($this->plugin()->getDirectory() . '/admin/templates/metabox/ajax.php');
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
                if (!$this->_page_check_editor) {
                    /*
                     * if pageCheck failed, check to see if we are on a custom edit or add screen
                     */
                    $this->debug()->log('Not a standard edit or add page, checking to see if its a CustomEdit or CustomAdd screen');
                    $this->_page_check_editor = $this->plugin()->tools()->isScreen(array('custom_edit', 'custom_add'), null, false);
                }
            }
        }



        /*
         * check to see if we are either on the edit or add screen
         *
         */



        $this->debug()->logVar('$this->_page_check_editor  = ', $this->_page_check_editor);

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

    /**
     * Get Metabox States
     *
     * @param none
     * @return array $this->$_meta_box_open_states;
     */
    public function getMetaboxOpenStatesOLD() {
        return $this->_meta_box_open_states;
    }

    /**
     *
     * @var array Meta Box Initial Open Closed States
     */
    protected $_meta_box_open_statesOLD = null;

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
     * @return void
     */
    public function setMetaboxOpenStateOLD($id, $open = true, $persist = false) {

        /*
         * Apply defaults to array if not all the settings were provided
         * This also ensures that if an element wasnt provided, it wont
         * break while the array is accessed
         */


        $this->_meta_box_open_states[$id] = array('open' => $open, 'persist' => $persist);
    }

    protected $_meta_box_object=null;

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
            $this->_meta_box_object = new Simpli_Basev1c0_Plugin_Module_Metabox($this);
        }
        return $this->_meta_box_object;
    }

}

