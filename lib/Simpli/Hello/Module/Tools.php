<?php

/**
 * Utility Module
 *
 * General Utility Functions
 *
 * @author Andrew Druffner
 * @package SimpliFramework
 * @subpackage SimpliHello
 *
 */
class Simpli_Hello_Module_Tools extends Simpli_Basev1c0_Plugin_Module {

    /**
     * Configure Module
     *
     * @param none
     * @return void
     */
    public function config() {
        $this->debug()->t();
    }

    /**
     * Utility Function - Scrub Args
     *
     * Given an array of defaults and the function args passed by the user, will return the argument array
     * Note that this differs from shorcode_atts in that shortcode_atts relies on the upstream shortcode core functions to provide a
     * complete array to the shortcode function. Here, we dont have that luxury, so we have to build the array ourselves.
     * @param array $args_passed An array of arguments passed by the user
     * @return string The parsed output of the form body tag
     */
    function scrubArgs($args_passed, $defaults) {
        $this->debug()->t();

        $pad_length = count($defaults);
        $atts = array_pad($args_passed, $pad_length, NULL); //pad the array so we can use it with array_combine which requires the same number of elements
        $atts = array_combine(array_keys($defaults), array_values($atts)); //create an assoc array using array_combine
        $atts = array_filter($atts, 'strlen'); //remove any null elements so merge wont overwrite defaults with null
//                                echo '<pre>';
//        echo '</pre>';
        $args = array_merge($defaults, $atts); //merge it with defaults
//                               echo '<pre>';
//        echo '</pre>';
        return $args;
    }

    /**
     * Is Screen
     *
     * Checks whether the current admin screen is the one you want
     * Requires that this be invoked at any time after the 'current_screen' action. one way to do this on an init function is to check of 'get_current_screen' function exists or is null, and if not, to add an action that calls the current function where your check appears. See the Post module's initModuleAdmin method for an example. below is another example for function hookLoadOptions
      function hookLoadOptions(){
      if ((!function_exists('get_current_screen') || (get_current_screen()===null))){
      $this->debug()->t();

      add_action('current_screen',array($this,'hookLoadPostOptions'));
      return;

      }

      }


Note that it will return false if the post type passed does not match the post type of the screen you are looking for. If you dont care which post type is matched, then pass null, and it will return true regardless of post type as long as the screen matches.
     *
     * @param string $screen_id Identifies which screen we want. Does not necessarily match current_screen->id. See Switch statement for current list.
     * @param string $post_type Post type that you want to have matched e.g.: 'post','page' or null (for all post types)
     * @param boolean $debug Will print our the entire screen object
     * @return boolean
     */
    function isScreen($screen_id, $post_type = null) {
        $this->debug()->t();



        $result = false;

        /*
         * Each screen has certain paramaters ( base,id,action) that change depending on the admin page. check them against
         * known values and return result. If no post type is provided, then set the post type check to always be true, and remove the post_type check for isEdit and isAdd
         */


        $current_screen = get_current_screen();



        /*
         * if post type paramater is null, just set it to the same as the screen.
         * that way, our checks will still work for all post types
         */
        if (is_null($post_type)) {
            $post_type = $current_screen->post_type; //need to define so subsequent isList check works.
            $isPostType=true; //if post type is null, then thats really saying this check is for all post types.
             $debug_message_post_types=' any post type ';
        }else{

            $isPostType=$current_screen->post_type === $post_type;
            $debug_message_post_types=' the ' . $post_type . ' post type ';
        }

        $isList = (($current_screen->base === 'edit') && ($current_screen->id === 'edit-' . $post_type) && ($current_screen->action === ''));


        $isEdit = ($current_screen->base === 'post' && $current_screen->action === ''); //base will always be post regardless of post type. action will always be an empty string.
        $isAdd = ($current_screen->base === 'post' && $current_screen->action === 'add');






        switch ($screen_id) {
            case 'list': // the listing page for the post type provided
                $result = ($isList && $isPostType) ? true : false;
               $debug_message='Listing Screen';
                break;

            case 'add': // the 'add new' page for the post type provided

                $result = ($isAdd && $isPostType) ? true : false;

                $debug_message='Add Screen';
                break;
            case 'edit-add': //will return true if on either the 'edit' or 'add' page for the post type provided

                $result = (($isEdit && $isPostType) || ($isAdd && $isPostType)) ? true : false;
                $debug_message='Edit or Add Screen';

                break;

            case 'edit': // the post editor page for the post type provided

                $result = ($isEdit && $isPostType) ? true : false;
                $debug_message='Edit Screen';
                break;
            case 'plugins-list': //the plugins listing page
                $result = ($current_screen->base === 'plugins');
                $debug_message='Plugin Listing Screen';
                break;

            //todo: add more here (media,comments,etc)
        }

       $debug_result=($result) ? ', and it is ' : ', and it is NOT ';
        $this->debug()->log('Checked to see if this was the ' . $debug_message . ' for ' . $debug_message_post_types  . $debug_result);

        $this->debug()->logVars(get_defined_vars());

        return($result);
    }

}