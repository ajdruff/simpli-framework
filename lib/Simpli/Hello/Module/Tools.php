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

  
}