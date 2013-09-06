<?php

/**
 * Post Module
 *
 * Adds settings to the edit post screen.
 *
 * @author Andrew Druffner
 * @package SimpliFramework
 * @subpackage SimpliHello
 *
 */
class Simpli_Hello_Module_Post extends Simpli_Basev1c0_Plugin_Module {

    /**
     * Post Setting Defaults
     *
     * @var array
     */
    protected $_setting_defaults = null;

    /**
     * Post Settings
     *
     * @var array
     */
    protected $_settings = null;

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


        add_action('the_post', array($this, 'hookLoadUserSettings')); //archive pages will call multiple posts, and with each new post, the options have to be reloaded or you'll carry forward the topmost post's options to the ones below it

        /*
         * Admin Hooks Follow
         */

        if (!is_admin()) {
            return;
        }

        /*
         * Hook our save method into the post's save action
         */

        add_action('save_post', array($this, 'hookPostSave'));

        /*
         * Add our metabox
         */
        add_action('add_meta_boxes', array($this, 'hookAddMetaBoxToPost')); //use action add_meta_boxes

        //  add_action ('wp',array($this,'hookLoadUserSettings')); //wp is first reliable hook where $post object is available

        /*
         * Load Post options when in Admin
         */
        add_action('current_screen', array($this, 'hookLoadUserSettings')); //wp hook is not reliable on edit post page. admin_init cannot be used since a call to get_current_screen will return null see usage restrictions: http://codex.wordpress.org/Function_Reference/get_current_screen


        /*
         * Hook into the form class so we can provide the value of forms with an option lookup
         */
        add_action('simpli_hello_forms_pre_parse', array($this, 'forms_pre_parse'));
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
         * Set the Post Option defaults
         * About settings: You must prefix each setting with the plugin slug in the form 'mycompany_myplugin_<setting_name>
         * When creating post option forms, the field name must exactly equal the associative index of each element below
         * You can access a setting with or without the prefix. as in $text=getUserSetting('text') or $text=getUserSetting('simpli_hello_text')
         * Both will retrieve the same value
         */

        /*
         * Enabled
         * enabled or disabled. Whether you want the Hello World feature to be enabled for this post
         *
         */
        $this->setDefaultUserSetting(
                'enabled', 'enabled'
        );


        /*
         * Post Text
         * The text that you want inserted after each post
         *
         */
        $this->setDefaultUserSetting(
                'text', 'Hello World!'
        );
        /*
         * Use Global Text
         * true or false. Use global text instead of this post's text
         *
         */
        $this->setDefaultUserSetting(
                'use_global_text', 'false'
        );



        /*
         * Placement
         * 'before' , 'after' or 'default'.
         *
         * Where you'd like to place the post text in relation to the post's content
         * 'default' will use the value provided by the global setting
         *
         *
         */
        $this->setDefaultUserSetting(
                'placement', 'default'
        );

        /*
         * Test Checkbox
         * 'before' , 'after' or 'default'.
         *
         * Where you'd like to place the post text in relation to the post's content
         * 'default' will use the value provided by the global setting
         *
         *
         */
        $this->setDefaultUserSetting(
                'my_checkbox', array('red' => 'yes')
        );
    }

    /**
     * Get Post Option Defaults
     *
     * @param none
     * @return string
     */
    public function getUserSettingDefaults() {
        $this->debug()->t();

        return $this->_setting_defaults;
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
//        $field_name = $this->getUserSettingName($option_id);
//        $value = $this->getUserSetting($option_id);
//
//        echo $this->getPlugin()->getModule('Form')->text($field_name, $value, $label, $hint, $help, $template_id);
//    }

//    /**
//     * Template Tag - Post Option
//     *
//     * Echos out the result of getUserSetting()
//     * @param string $content The shortcode content
//     * @return string The parsed output of the form body tag
//     */
//    function postOptionOld($name) {
//        $this->debug()->t();
//
//        echo $this->getUserSetting($name);
//    }

//    /**
//     * Get Post Option
//     *
//     * You can access a setting with or without the prefix. as in $text=getUserSetting('text') or $text=getUserSetting('simpli_hello_text')
//     * Its a bit faster without the prefix :)
//     * @param string $option_name
//     * @return mixed
//     */
//    public function getUserSettingOld($name) {
//        $this->debug()->t();
//
//
//        $post_user_settings = $this->getUserSettings();
//        $name_with_added_prefix = $this->getPlugin()->getSlug() . '_' . $name;
//
////echo '<br/> getUserSettings()=';
////        echo '<pre>';
////echo '</pre>';
////echo  '<br/> Name with added prefix=' .$name_with_added_prefix;
//        /* assume access is by short version, so tack on prefix */
//        if (isset($post_user_settings[$name_with_added_prefix])) {
//
//            return($post_user_settings[$name_with_added_prefix]);
//        } elseif (isset($post_user_settings[$name])) { //then try it unmodified
//            return($post_user_settings[$name]);
//        } else {
//            return null;
//        }
//    }

    public function getUserSetting($name) {
        $this->debug()->t();


        $post_user_settings = $this->getUserSettings();

        if (isset($post_user_settings[$name])) {
            return($post_user_settings[$name]);
        } else {
            return null;
        }
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
//        echo $this->getUserSettingName($accessor_name);
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
//        return $this->getUserSettingName($accessor_name);
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
//        //echo getUserSettingsName($accessor_name);
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
//        //echo getUserSettingsName($accessor_name);
//    }

//    /**
//     * Get Post Option Name
//     *
//     * Helper function that saves time when creating field names for forms, and in allowing the getUserSetting function to
//     * be used to access an option using only its shortname
//     * Simply returns the plugin slug prepended to the argument.
//     * @param string $option_name
//     * @return mixed
//     */
//    public function getUserSettingNameOld($name) {
//        $this->debug()->t();
//
//
//
//        /*
//         * If the $name already has the slug prepended, return it without further processing
//         */
//        if (stripos($name, $this->getPlugin()->getSlug() . '_') !== false) {
//            return $name;
//        } else
//        /*
//         * If the name does not have a prefix, give it one.
//         */ {
//
//            return $this->getPlugin()->getSlug() . '_' . $name;
//        }
//    }

    /**
     * Get User Settings
     *
     * Returns the current array of user settings for the post
     * @param none
     * @return array
     */
    public function getUserSettings() {
        $this->debug()->t();


        return $this->_settings;
    }

    /**
     * Set User Setting
     *
     * Sets a user setting
     *
     * @param string $setting
     * @param mixed $value
     * @param int $blog_id
     * @return $this
     */
    public function setUserSetting($option_name, $option_value) {
        $this->debug()->t();


        /*
         * Update settings array with new value but only if the setting
         * key already exists in the array
         * you set the allowed keys in your plugin's $_settings declaration
         */
        if (in_array(trim($option_name), array_keys($this->getUserSettings()))) {
            //if (in_array(trim($option_name), array_keys($this->getUserSettingDefaults()))) {
            if (is_string($option_value)) {
                $option_value = trim($option_value);
            }
            $this->_settings[$option_name] = $option_value;
        }



        return $this;
    }

    /**
     * Save Post Options to WordPress Database
     * Takes post_options array and saves it to wp_options table
     * @param int $blog_id
     * @return $this
     */
    public function saveUserSettings($post_id) {
        $this->debug()->t();


        $this->debug()->log('Saving Post Options');

        $wp_option_name = $this->getPlugin()->getSlug() . '_options';
        $options = $this->getUserSettings();
        $this->debug()->logVar('$options = ', $options);
        update_post_meta($post_id, $wp_option_name, $options);




        return $this;
    }

    /**
     * Load Post Options from database
     * @param int $post_id
     * @return $this
     */
    public function hookLoadUserSettings() {
        $this->debug()->t();




        if (!$this->pageCheck()) {
            return;
        }



        $default_options = $this->getUserSettingDefaults();
        $post_meta_options = array();

        $wp_option_name = $this->getPlugin()->getSlug() . '_options';

        global $post;

        $post = (isset($_GET['post'])) ? get_post($_GET['post']) : $post;
        $post = (empty($post) && !empty($_POST['post_ID'])) ? get_post($_POST['post_ID']) : $post;
        // if (!empty($post)&& !empty($_POST['post_ID'])) {
        if (!empty($post)) {
            // $this->hookLoadUserSettings($post->ID);
            $post_id = $post->ID;
            $post_meta_options = get_post_meta($post_id, $wp_option_name, true);
        } else {
            $default_options = $this->getUserSettingDefaults();
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
            $options = $this->getUserSettingDefaults();
        }

        $this->_settings = $options;



        return $this->_settings;
    }

    /**
     * Adds  meta box to post edit screen.
     * WordPress Hook - add_meta_boxes
     *
     * @param none
     * @return void
     */
    public function hookAddMetaBoxToPost() {
        $this->debug()->t();


//        if (!$this->pageCheck()) {
//            return;
//        }



        $args = array(
            'public' => true,
        );
        $post_types = get_post_types($args);
        foreach ($post_types as $post_type) {


/*
 * note if you want to reposition the metaboxes, chang ethe context from 'side' to 'normal' or vice versa
 * if the change didn't work, then you need to delete the 'meta-box-order_post' meta data in the wp_usermeta table
 * and try again.
 * if you just need to change the location temporarily to make more room for troubleshooting messages, you can just select 'Number of columns' to 1 from the screen options on the post editor page.
 */


            add_meta_box(
                    $this->getSlug() . '_' . 'metabox_settings'  //Meta Box DOM ID
                    , __($this->getPlugin()->getName(), $this->getPlugin()->getTextDomain()) //title of the metabox.
                    , array($this, 'renderMetaBoxTemplate')//function that prints the html
                    , $post_type// post_type when you embed meta boxes into post edit pages
                    , 'advanced' //normal advanced or side The part of the page where the metabox should show
                    , 'high' // 'high' , 'core','default', 'low' The priority within the context where the box should show
                    , null //$metabox['args'] in callback function
                    //,  array('path' => $this->getPlugin()->getDirectory() . '/admin/templates/metabox/post.php') //$metabox['args'] in callback function
            );


        }


    }





    /**
     * Save  option to post or page
     *
     * @param int $post_id
     * @return int $post_id
     */
    public function hookPostSave($post_id) {
        $this->debug()->t();


        if (!$this->pageCheck()) {
            return;
        }

        //if the post variable doesnt include our plugin, than exit.
        if (is_admin()) {
            if (!array_key_exists($this->getPlugin()->getSlug(), $_POST)) {
                $this->debug()->logVar('$_POST = ', $_POST);
                $this->debug()->log('Exiting Post Save since $_POST doesnt include our settings');
                return $post_id;
            }
            //   $this->debug()->log('Exiting save ajax call');
            $this->debug()->logVar('$_POST = ', $_POST, false, true, false, false); //automatically show arrays without needing to click
            // if nonce fails , return
            if (!wp_verify_nonce($_POST[$this->getPlugin()->getSlug() . '_nonce'], 'save_post')) {
                $this->debug()->log('Nonce Failed while trying to save settings');
                return $post_id;
            }

//return if doing autosave
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                return $post_id;
            }
//check permissions
            if (@$_POST['post_type'] == 'page') {
                if (!current_user_can('edit_page', $post_id)) {
                    $this->debug()->log('Cant save settings - user is not authorized');
                    return $post_id;
                }
            } else {
                if (!current_user_can('edit_post', $post_id)) {
                    $this->debug()->log('Cant save settings - user is not authorized');
                    return $post_id;
                }
            }
        }


        $this->debug()->logVar('$this->getUserSettings() ', $this->getUserSettings(), false, true, false, false);



        foreach ($this->getUserSettings() as $option_name => $option_value) {



            /**
             * Set new option value equal to the submitted value only if the setting was actually submitted, otherwise, keep the setting value the same.
             *  Add extra code to scrub the values for specific settings if needed
             */
            $option_value = (isset($_POST[$this->getPlugin()->getSlug()][$option_name])) ? $_POST[$this->getPlugin()->getSlug()][$option_name] : $option_value;

            $this->setUserSetting($option_name, $option_value);
        }
        $this->saveUserSettings($post_id);


        return $post_id;
    }

    /**
     * Renders a meta box
     *
     * @param string $module
     * @param array $metabox
     * @return void
     */
    public function renderMetaBoxTemplate($module, $metabox) {
        $this->debug()->t();


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
        include($template_path);
    }

    /**
     * Renders a meta box using an Ajax Request
     *
     * @param string $module
     * @param array $metabox
     * @return void
     */
    public function renderMetaBoxAjax($module, $metabox) {
        $this->debug()->t();



        include($this->getPlugin()->getDirectory() . '/admin/templates/metabox/ajax.php');
    }

    /**
     * Page Check
     *
     * Use for hook functions. Checks to see if we are on the right page before we add any hook actions.
     * @param none
     * @return boolean
     */
    private function pageCheck() {
        $this->debug()->t();


        if (!is_admin()) {
            return;
        } //no page check necessary if not in admin since there is only one hook is used in front end, and it will only be fired when we need it ('the_post') .

        /*
         * use static variable so we dont need to call the method each time.
         */
        static $pageCheck;

        if (is_null($pageCheck)) {
            $pageCheck = $this->getPlugin()->getModule('Tools')->isScreen('edit-add', null, false);
        }




        /*
         * check to see if we are either on the edit or add screen
         */
        return ($pageCheck);
    }

    /**
     * Set Default Setting
     *
     * Sets a default value for settings that have not yet been saved to the database.
     * If you want a setting to have a value before any configuration by the user occurs,
     * you must set it here.
     *
     * @param string $setting_name The name of the setting. Must be unique for the plugin
     * @param mixed $setting_value The value of the setting.
     * @return void
     */
    protected function setDefaultUserSetting($setting_name, $setting_value) {

        $this->_setting_defaults[$setting_name] = $setting_value;
    }

}

