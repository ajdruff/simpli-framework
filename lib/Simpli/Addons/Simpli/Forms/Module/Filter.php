<?php

/**
 * Form Filter Module
 *
 * Modifies Field Inputs from Form Templates
 *
 * @author Andrew Druffner
 * @package SimpliFramework
 * @subpackage SimpliAddonsForms
 *
 */
class Simpli_Addons_Simpli_Forms_Module_Filter extends Simpli_Basev1c0_Plugin_Module {

    /**
     * Add Hooks
     *
     * Adds WordPress Hooks, triggered during module initialization
     * @param none
     * @return void
     */
    public function addHooks() {
        $this->debug()->t();

    }

//    /**
//     * Get Filter Tag - Read Only
//     *
//     * Provides a Unique Filter Name to be used for hooks
//     *
//     * @param none
//     * @return stringReadOnly
//     */
//    public function getHookName() {
//        $this->debug()->t();
//
//        $hook_name = $this->getAddon()->getSlug() . '_' . $this->getSlug(); //e.g.: simpli_addons_simpli_forms_filters
//        return $hook_name;
//    }

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
     * Filter Wrapper ( Acts as central proxy for other filters within this module)
     *
     * Acts as a wrapper around the various form filter methods.
     * @param string $tag_id The tag identifier. e.g.: 'text' for the text tag
     * @param string $atts The attributes of the tag
     * @return string $atts
     */
    public function filter($properties) {
        $this->debug()->t();
        $this->debug()->logVars(get_defined_vars());
        if (!isset($properties['scid'])) {
         $this->debug()->stop(true);
        }
        $method = 'filter' . ucwords($properties['scid']);
        // die('exiting' . __METHOD__);
        $properties = $this->_commonFilter($properties);
        $properties = $this->$method($properties);

        return ($properties);
    }

    /**
     * Common Filter
     *
     * All Fields are subject to this filter
     * @param string $content The shortcode content
     * @return string The parsed output of the form body tag
     */
    protected function _commonFilter($properties) {
        $this->debug()->t();

        extract($properties);
        /*
         * Return error if required arguments are not found
         */
        if ((!isset($atts['name'])) || (is_null($atts['name']))) {

            $atts ['_error'][] = 'Name attribute is required';
        }




        /*
         * Add a unique prefix to the name so we dont conflict with other plugins that might be on the same form
         */
        $atts['name'] = $this->getFieldPrefix() . $atts['name'];



        /*
         * Add a default label if one wasnt provided
         */

        if (is_null($atts['label'])) {
            $atts['label'] = $this->getDefaultFieldLabel($atts['name']);
        }



        return(compact('atts', 'tags'));
    }

    /**
     * Filter Text
     *
     * Filters the Text Tag Attributers
     * @param string $atts The attributes of the tag
     * @return string $atts
     */
    protected function filterText($properties) {
        $this->debug()->t();

        extract($properties);
        $atts['value'] = 'filtered by basic filters';
        $tags['test_text'] = 'This is the test tag for a text template';
        return (compact('atts', 'tags'));
    }

      /**
     * Filter Text
     *
     * Filters the Text Tag Attributers
     * @param string $atts The attributes of the tag
     * @return string $atts
     */
    protected function filterSelect($properties) {
        $this->debug()->t();

        extract($properties);

        $options_html = '';
        foreach ($atts['options'] as $value => $display_text) {
            $options_html.='<option ' . $value . '>' . $display_text . '</option>';
        }

        $atts['value'] = 'filtered by basic filters';
        $tags['options_html'] = $options_html;





        return (compact('atts', 'tags'));
    }

    /**
     * Get Default Field Label
     *
     * Uses name to derive a label
     * @param string $content The shortcode content
     * @return string The parsed output of the form body tag
     */
    function getDefaultFieldLabel($name) {
        $this->debug()->t();



        $label = str_replace($this->getFieldPrefix(), '', $name);
        $label = strtolower($label);
        $label = str_replace('_', ' ', $label);
        $label = ucwords($label);
        return $label;
    }

    /**
     * Get Field Prefix - Read Only
     *
     * @param none
     * @return string
     */
    public function getFieldPrefix() {
        $this->debug()->t();


        $this->getPlugin()->getSlug() . '_';
    }

}

