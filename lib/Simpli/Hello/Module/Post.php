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
     * Post Option Defaults
     *
     * @var array
     */
    protected $_post_option_defaults = array();

    /**
     * Post Options
     *
     * @var array
     */
    protected $_post_options = array();



    /**
     * Add Hooks
     *
     * Adds WordPress Hooks, triggered during module initialization
     * @param none
     * @return void
     */
    public function addHooks() {

        /*
         * Load Post options on front end
         */


        add_action('the_post', array($this, 'hookLoadPostOptions')); //archive pages will call multiple posts, and with each new post, the options have to be reloaded or you'll carry forward the topmost post's options to the ones below it

        /*
         * Admin Hooks Follow
         */

        if (!is_admin()){return;}

        /*
         * Hook our save method into the post's save action
         */

        add_action('save_post', array($this, 'hookPostSave'));

        /*
         * Add our metabox
         */
        add_action('add_meta_boxes', array($this, 'hookAddMetaBoxToPost')); //use action add_meta_boxes
        //  add_action ('wp',array(&$this,'hookLoadPostOptions')); //wp is first reliable hook where $post object is available

        /*
         * Load Post options when in Admin
         */
        add_action('current_screen', array($this, 'hookLoadPostOptions')); //wp hook is not reliable on edit post page. admin_init cannot be used since a call to get_current_screen will return null see usage restrictions: http://codex.wordpress.org/Function_Reference/get_current_screen


        /*
         * Hook into the form class so we can provide the value of forms with an option lookup
         */
        add_action('simpli_hello_forms_pre_parse',array($this,'forms_pre_parse'));

    }

    /**
     * Configure Module
     *
     * @param none
     * @return void
     */
    public function config() {

        /*
         * Set the Post Option defaults
         * About settings: You must prefix each setting with the plugin slug in the form 'mycompany_myplugin_<setting_name>
         * When creating post option forms, the field name must exactly equal the associative index of each element below
         * You can access a setting with or without the prefix. as in $text=getPostOption('text') or $text=GetPostOption('simpli_hello_text')
         * Both will retrieve the same value
         */


        //todo: replace with setOptionDefault('text','Hello World!','Enter Text Here','The you want to enter');
        $this->setPostOptionDefaults(
                array(
 $this->getPlugin()->getSlug() . '_text' => 'Default text set within Post.php' // any text
                    , $this->getPlugin()->getSlug() . '_new' => 'im new man' // any text
                    , $this->getPlugin()->getSlug() . '_use_global_text' => 'true' // true/false
                    , $this->getPlugin()->getSlug() . '_enabled' => 'enabled' // true/false
                    , $this->getPlugin()->getSlug() . '_placement' => 'default'  // before,after,default
        ));
    }

    /**
     * Get Post Option Defaults
     *
     * @param none
     * @return string
     */
    public function getPostOptionDefaults() {
        return $this->_post_option_defaults;
    }

    /**
     * Set Post Option Defaults
     *
     * @param string $default_settings
     * @return object $this
     */
    public function setPostOptionDefaults($post_option_defaults) {
        $this->_post_option_defaults = $post_option_defaults;
        return $this;
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
    function text($option_id, $label=null, $hint=null, $help=null,$template_id=null) {

        $field_name = $this->getPostOptionName($option_id);
        $value = $this->getPostOption($option_id);

        echo $this->getPlugin()->getModule('Form')->text($field_name, $value, $label, $hint, $help,$template_id);
    }

    /**
     * Template Tag - Post Option
     *
     * Echos out the result of getPostOption()
     * @param string $content The shortcode content
     * @return string The parsed output of the form body tag
     */
    function postOption($name) {
        echo $this->getPostOption($name);
    }

    /**
     * Get Post Option
     *
     * You can access a setting with or without the prefix. as in $text=getPostOption('text') or $text=GetPostOption('simpli_hello_text')
     * Its a bit faster without the prefix :)
     * @param string $option_name
     * @return mixed
     */
    public function getPostOption($name) {

        $post_options = $this->getPostOptions();
        $name_with_added_prefix = $this->getPlugin()->getSlug() . '_' . $name;

//echo '<br/> getPostOptions()=';
//        echo '<pre>';
//print_r($post_options);
//echo '</pre>';
//echo  '<br/> Name with added prefix=' .$name_with_added_prefix;
        /* assume access is by short version, so tack on prefix */
        if (isset($post_options[$name_with_added_prefix])) {

            return($post_options[$name_with_added_prefix]);
        } elseif (isset($post_options[$name])) { //then try it unmodified
            return($post_options[$name]);
        } else {
            return null;
        }
    }

    /**
     * Template Tag Field Name
     *
     * Helper function to echo out the field name of an option
     * Convienant to use in form fields to avoid having to type 'echo'
     * @param string $option_name The accessor name  of the option
     * @return void
     */
    function fieldName($accessor_name) {

        echo $this->getPostOptionName($accessor_name);
    }

    /**
     * Get Field Name
     *
     * Helper function to return the field name of an option
     * @param string $option_name The accessor name  of the option
     * @return string
     */
    function getFieldName($accessor_name) {

        return $this->getPostOptionName($accessor_name);
    }

    /**
     * Template Tag Field Label
     *
     * Helper function to echo out the default field label of an option
     * Conveniant to use in form fields
     * @param string $option_name The accessor name  of the option
     * @return void
     */
    function fieldLabel($accessor_name) {

        echo '__NEW_LABEL__'; // replace with lookup of the option's label
    }

    /**
     * Get Field Label
     *
     * Helper function to return the default field label of an option
     * @param string $option_name The accessor name  of the option
     * @return string
     */
    function getFieldLabel($accessor_name) {

        return '__NEW_LABEL__'; // replace with lookup of the option's label
    }

    /**
     * Template Tag Field Help
     *
     * Helper function to echo out the default field help text of an option
     * Conveniant to use in form fields
     * @param string $option_name The accessor name  of the option
     * @return void
     */
    function fieldHelp($accessor_name) {
//stub
        echo '__HELP_TEXT__'; // replace with lookup of the option's help
        //echo getPostOptionsName($accessor_name);
    }

    /**
     * Get Field Help
     *
     * Helper function to return the default field help text of an option
     * @param string $option_name The accessor name  of the option
     * @return string
     */
    function getFieldHelp($accessor_name) {
//stub
        return '__HELP_TEXT__'; // replace with lookup of the option's help
        //echo getPostOptionsName($accessor_name);
    }

    /**
     * Get Post Option Name
     *
     * Helper function that saves time when creating field names for forms, and in allowing the getPostOption function to
     * be used to access an option using only its shortname
     * Simply returns the plugin slug prepended to the argument.
     * @param string $option_name
     * @return mixed
     */
    public function getPostOptionName($name) {


        /*
         * If the $name already has the slug prepended, return it without further processing
         */
        if (stripos($name, $this->getPlugin()->getSlug() . '_') !== false) {
            return $name;
        } else
        /*
         * If the name does not have a prefix, give it one.
         */ {

            return $this->getPlugin()->getSlug() . '_' . $name;
        }
    }

    /**
     * Get Plugin Options
     *
     * @param none
     * @return array
     */
    public function getPostOptions() {

        return $this->_post_options;
    }

    /**
     * Set Post Option
     *
     * @param string $setting
     * @param mixed $value
     * @param int $blog_id
     * @return $this
     */
    public function setPostOption($option_name, $option_value) {

        /*
         * Update settings array with new value but only if the setting
         * key already exists in the array
         * you set the allowed keys in your plugin's $_settings declaration
         */
        if (in_array(trim($option_name), array_keys($this->getPostOptions()))) {
            //if (in_array(trim($option_name), array_keys($this->getPostOptionDefaults()))) {
            if (is_string($option_value)) {
                $option_value = trim($option_value);
            }
            $this->_post_options[$option_name] = $option_value;
        }



        return $this;
    }

    /**
     * Save Post Options to WordPress Database
     * Takes post_options array and saves it to wp_options table
     * @param int $blog_id
     * @return $this
     */
    public function savePostOptions($post_id) {



        $wp_option_name = $this->getPlugin()->getSlug() . '_options';
        $options = $this->getPostOptions();

        update_post_meta($post_id, $wp_option_name, $options);




        return $this;
    }

    /**
     * Load Post Options from database
     * @param int $post_id
     * @return $this
     */
    public function hookLoadPostOptions() {



        if (!$this->pageCheck()) {
            return;
        }



        $default_options = $this->getPostOptionDefaults();
        $post_meta_options = array();

        $wp_option_name = $this->getPlugin()->getSlug() . '_options';

        global $post;
//                 echo '<br/>post=';
//                echo '<pre>';
//                print_r($post);
//                echo '</pre>';
        $post = (isset($_GET['post'])) ? get_post($_GET['post']) : $post;
        $post = (empty($post) && !empty($_POST['post_ID'])) ? get_post($_POST['post_ID']) : $post;
        // if (!empty($post)&& !empty($_POST['post_ID'])) {
        if (!empty($post)) {
            // $this->hookLoadPostOptions($post->ID);
            $post_id = $post->ID;
            $post_meta_options = get_post_meta($post_id, $wp_option_name, true);
            // print_r($this->getPostOptions());die('stopping');
        } else {
            $default_options = $this->getPostOptionDefaults();
            // echo '<br> loading defaults' ;
        }
//                echo '<br/>post=';
//                echo '<pre>';
//                print_r($post);
//                echo '</pre>';

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
            $options = $this->getPostOptionDefaults();
        }

        $this->_post_options = $options;


//        echo '<br/>$this->_post_options=';
//        echo '<pre>';
//        print_r($this->_post_options);
//        echo '</pre>';
//                echo '<br/>Loading Options : post=';
//                echo '<pre>';
//                print_r($this->_post_options);
//                echo '</pre>';
        return $this->_post_options;
    }

    /**
     * Adds  meta box to post edit screen.
     * WordPress Hook - add_meta_boxes
     *
     * @param none
     * @return void
     */
    public function hookAddMetaBoxToPost() {

//        if (!$this->pageCheck()) {
//            return;
//        }



        $args = array(
            'public' => true,
        );
        $post_types = get_post_types($args);
        foreach ($post_types as $post_type) {
//            add_meta_box(
//                    $this->getPlugin()->getSlug(), __($this->getPlugin()->getName(), $this->getPlugin()->getTextDomain()), array($this->getPlugin()->getModule('Admin'), 'meta_box_render'), $post_type, 'side', 'core', array('metabox' => 'post')
//            );





            add_meta_box(
                    $this->getSlug() . '_' . 'metabox_settings'  //Meta Box DOM ID
                    , __($this->getPlugin()->getName(), $this->getPlugin()->getTextDomain()) //title of the metabox.
                    , array($this, 'renderMetaBoxTemplate')//function that prints the html
                    , $post_type// post_type when you embed meta boxes into post edit pages
                    , 'side' //normal advanced or side The part of the page where the metabox should show
                    , 'core' // 'high' , 'core','default', 'low' The priority within the context where the box should show
                    , null //$metabox['args'] in callback function
                    //,  array('path' => $this->getPlugin()->getDirectory() . '/admin/templates/metabox/post.php') //$metabox['args'] in callback function
            );

//            add_meta_box(
//                    $this->getSlug() . '_' . 'metabox_about'  //Meta Box DOM ID
//                    , __('About Simpli Hello and the Simpli Framework', $this->getPlugin()->getTextDomain()) //title of the metabox.
//                    , array($this, 'renderMetaBoxTemplate') //function that prints the html
//                    , $screen_id = null// must be null so WordPress uses current screen id as default. mistakenly called $post_type in the codex. See Source Code.
//                    , 'normal' //normal advanced or side The part of the page where the metabox should show
//                    , 'default' // 'high' , 'core','default', 'low' The priority within the context where the box should show
//                    , null //$metabox['args'] in callback function
//            );
        };
    }

    /**
     * Save  option to post or page
     *
     * @param int $post_id
     * @return int $post_id
     */
    public function hookPostSave($post_id) {

        if (!$this->pageCheck()) {
            return;
        }

//echo '<pre>';
//        print_r($_POST);
//echo '</pre>';
//die('exiting' .__FILE__);
//        print_r($this->getPostOptions());die('exiting' .__FILE__);
        //if the post variable doesnt include our plugin, than exit.
        if (is_admin()) {
            if (!array_key_exists($this->getPlugin()->getSlug(), $_POST)) {
                return $post_id;
            }



            // if nonce fails , return
            if (!wp_verify_nonce($_POST[$this->getPlugin()->getSlug()], $this->getPlugin()->getSlug())) {
                return $post_id;
            }

//return if doing autosave
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                return $post_id;
            }
//check permissions
            if (@$_POST['post_type'] == 'page') {
                if (!current_user_can('edit_page', $post_id)) {
                    return $post_id;
                }
            } else {
                if (!current_user_can('edit_post', $post_id)) {
                    return $post_id;
                }
            }
        }
        foreach ($this->getPostOptions() as $option_name => $option_value) {
            /**
             * Set new option value equal to the submitted value only if the setting was actually submitted, otherwise, keep the setting value the same.
             *  Add extra code to scrub the values for specific settings if needed
             */
            $option_value = ((isset($_POST[$option_name]) === true) ? $_POST[$option_name] : $option_value);

            $this->setPostOption($option_name, $option_value);
        }
        $this->savePostOptions($post_id);


        return;






        if (array_key_exists($this->getPlugin()->getSlug(), $_POST)) {
            if (!wp_verify_nonce($_POST[$this->getPlugin()->getSlug()], $this->getPlugin()->getSlug())) {
                return $post_id;
            }










            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                return $post_id;
            }

            if (@$_POST['post_type'] == 'page') {
                if (!current_user_can('edit_page', $post_id)) {
                    return $post_id;
                }
            } else {
                if (!current_user_can('edit_post', $post_id)) {
                    return $post_id;
                }
            }


            $post_option_defaults = $this->getPlugin()->getModule('Post')->getPostOptionDefaults();
            $post_options = get_post_meta($post->ID, $this->getPlugin()->getSlug() . '_post_options', true);
            $post_options = (empty($post_options)) ? $post_option_defaults : $post_options;





            $force_ssl = ( @$_POST['force_ssl'] == 1 ? true : false);
            if ($force_ssl) {
                update_post_meta($post_id, 'force_ssl', 1);
            } else {
                delete_post_meta($post_id, 'force_ssl');
            }

            $force_ssl_children = ( @$_POST['force_ssl_children'] == 1 ? true : false);
            if ($force_ssl_children) {
                update_post_meta($post_id, 'force_ssl_children', 1);
            } else {
                delete_post_meta($post_id, 'force_ssl_children');
            }
        }

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

        /*
         * If no template path provided, use the metabox id as the template name and /admin/templates/metabox as the path
         */
        $template_path = $this->getPlugin()->getDirectory() . '/admin/templates/metabox/' . $metabox['id'] . '.php';
        if (isset($metabox['args']['path'])) {
            $template_path = $metabox['args']['path'];
        }
        if (!file_exists($template_path)) {
            _e('Not available at this time.', $this->getPlugin()->getTextDomain());
            $this->getPlugin()->getLogger()->logError($this->getPlugin()->getSlug() . ' : Meta Box ' . $metabox['id'] . ' error - template path does not exist ' . $template_path);
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



        //  $screen=get_current_screen();print_r($screen);
        /*
         * check to see if we are either on the edit or add screen
         */
        return ($pageCheck);
    }
    /**
     * Alters a fields properties prior to parsing
     *
     * Long Description
     * @param string $content The shortcode content
     * @return string The parsed output of the form body tag
 */
    function forms_pre_parse($template_args) {

        $template_args['value']='snowy';

}
}

