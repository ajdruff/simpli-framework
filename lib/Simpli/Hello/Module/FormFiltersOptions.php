<?php

/**
 * Form Filters Module
 *
 * Modifies Field Inputs from Form Templates
 *
 * @author Andrew Druffner
 * @package SimpliFramework
 * @subpackage SimpliHello
 *
 */
class Simpli_Hello_Module_FormFiltersOptions extends Simpli_Basev1c0_Plugin_Module {

    /**
     * Initialize Module when in Admin environment
     *
     * @param none
     * @return object $this
     */
    public function initModuleAdmin() {
        $this->initModule();
    }

    /**
     * Initialize Module
     *
     * @param none
     * @return object $this
     */
    public function initModule() {

        add_filter($this->getPlugin()->getSlug() . '_form_filters_' . 'options', array($this, 'filter'), 10, 2);


        return $this;
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
     * Filter Form Input
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
    private function _commonFilter($atts) {

        /*
         * Return error if required arguments are not found
         */
        if ((!isset($atts['option_name'])) || (is_null($atts['option_name']))) {

            $atts ['_error'][] = 'Option Name attribute is required';

            return ($atts);
        }

      //  echo '<pre>', print_r($atts, true), '</pre>';
        $atts['value'] = $this->getPlugin()->getModule('Post')->getPostOption($atts['option_name']);
        $atts['name'] = $this->getPlugin()->getSlug() . '_' . $atts['option_name'];





      //  echo '<pre>', print_r($atts, true), '</pre>';
        return($atts);
    }

    /**
     * Filter Text
     *
     * Filters the Text Tag Attributers
     * @param string $atts The attributes of the tag
     * @return string $atts
     */
    private function _filterText($atts) {



        return ($atts);
    }

}