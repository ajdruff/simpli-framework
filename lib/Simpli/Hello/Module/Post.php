<?php

/**
 * Post Module
 *
 * Adds settings to the edit post screen.
 *
 * @author Mike Ems
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

            public $_post_option_defaults=array(
        'simpli-hello-posttext'=>'Hello World! This is my post'
        ,'simpli-hello-postenabled'=>'true'
                ,'simpli_placement'=>'after'
    );


    /**
     * Post Options
     *
     * @var array
     */
    protected $_post_options = array();

    /**
     * Initialize Module
     *
     * @param none
     * @return void
     */
    public function init() {


        // Save custom post data
        add_action('save_post', array(&$this, 'post_save'));
        // Add Force SSL checkbox to edit post screen
        add_action('add_meta_boxes', array(&$this, 'add_meta_box_post'));
        //  add_action ('wp',array(&$this,'loadPostOptions')); //wp is first reliable hook where $post object is available
        add_action('the_post', array(&$this, 'loadPostOptions')); //archive pages will call multiple posts, and with each new post, the options have to be reloaded or you'll carry forward the topmost post's options to the ones below it
        if (is_admin()) {
            add_action('admin_init', array(&$this, 'loadPostOptions')); //wp hook is not reliable on edit post page.
        }


        // global $post;
    }

    /**
     * Get Post Option
     *
     * @param string $option_name
     * @return mixed
     */
    public function getPostOption($option_name) {

        if (isset($this->_post_options[$option_name])) {
            return($this->_post_options[$option_name]);
        } else {
            return null;
        }
    }

    /**
     * Get Post Option Defaults
     *
     * @param none
     * @return array
     */
    public function getPostOptionDefaults() {
        return $this->_option_defaults;
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
    public function loadPostOptions() {

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
            // $this->loadPostOptions($post->ID);
            $post_id = $post->ID;
            $options = get_post_meta($post_id, $wp_option_name, true);
            // print_r($this->getPostOptions());die('stopping');
        } else {
            $options = $this->_post_option_defaults;
            // echo '<br> loading defaults' ;
        }
//                echo '<br/>post=';
//                echo '<pre>';
//                print_r($post);
//                echo '</pre>';





        if (empty($options)) {
            //   echo '<br> loading defaults' ;
            $options = $this->_post_option_defaults;
        }

        $this->_post_options = $options;

//                echo '<br/>Loading Options : post=';
//                echo '<pre>';
//                print_r($this->_post_options);
//                echo '</pre>';
//		return $this->_post_options;
    }

    /**
     * Adds  meta box to post edit screen.
     * WordPress Hook - add_meta_boxes
     *
     * @param none
     * @return void
     */
    public function add_meta_box_post() {
        $args = array(
            'public' => true,
        );
        $post_types = get_post_types($args);
        foreach ($post_types as $post_type) {
            add_meta_box(
                    $this->getPlugin()->getSlug(), __($this->getPlugin()->getName(), $this->getPlugin()->getSlug()), array($this->getPlugin()->getModule('Admin'), 'meta_box_render'), $post_type, 'side', 'core', array('metabox' => 'post')
            );
        };
    }

    /**
     * Save  option to post or page
     *
     * @param int $post_id
     * @return int $post_id
     */
    public function post_save($post_id) {


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


            $post_option_defaults = $this->getPlugin()->getModule('Post')->_post_option_defaults;
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

}
