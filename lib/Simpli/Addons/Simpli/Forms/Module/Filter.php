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

/*
 * Add a hook for our filters
 */

        add_filter($this->getFilterTag(), array($this, 'filter'), 10, 2);


        return $this;
    }


    /**
     * Get Filter Tag - Read Only
     *
     * Provides a Unique Filter Name to be used for hooks
     *
     * @param none
     * @return stringReadOnly
     */
    public function getFilterTag() {
           $filter_tag=$this->getAddon()->getSlug() . '_' . $this->getSlug(); //e.g.: simpli_addons_simpli_forms_filters
return $filter_tag;
    }

    /**
     * Configure Module
     *
     * @param none
     * @return void
     */
    public function config() {

    }

    /**
     * Filter Wrapper ( Acts as central proxy for other filters within this module)
     *
     * Acts as a wrapper around the various form filter methods.
     * @param string $tag_id The tag identifier. e.g.: 'text' for the text tag
     * @param string $atts The attributes of the tag
     * @return string $atts
     */
    public function filter($atts, $tag_id) {
        $method = '_filter' . ucwords($tag_id);

        $atts = $this->_commonFilter($atts);
        $atts = $this->$method($atts);

        return ($atts);
    }

    /**
     * Common Filter
     *
     * All Fields are subject to this filter
     * @param string $content The shortcode content
     * @return string The parsed output of the form body tag
     */
    protected function _commonFilter($atts) {

        /*
         * Return error if required arguments are not found
         */
        if ((!isset($atts['name'])) || (is_null($atts['name']))) {

            $atts ['_error'][] = 'Name attribute is required';

            return ($atts);
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



        return($atts);
    }

    /**
     * Filter Text
     *
     * Filters the Text Tag Attributers
     * @param string $atts The attributes of the tag
     * @return string $atts
     */
    protected function _filterText($atts) {

        $atts['value']='filtered by basic filters';

        return ($atts);
    }

    /**
     * Get Default Field Label
     *
     * Uses name to derive a label
     * @param string $content The shortcode content
     * @return string The parsed output of the form body tag
     */
    function getDefaultFieldLabel($name) {


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

        $this->getPlugin()->getSlug() . '_';
    }

}

