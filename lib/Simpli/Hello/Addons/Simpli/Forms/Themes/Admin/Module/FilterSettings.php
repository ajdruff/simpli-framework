<?php

/**
 * Form Filter Module - Settings
 *
 * Modifies Field Inputs from Form Templates
 *
 * @author Andrew Druffner
 * @package SimpliFramework
 * @subpackage SimpliAddonsForms
 *
 */
class Simpli_Hello_Addons_Simpli_Forms_Themes_Admin_Module_FilterSettings extends Simpli_Hello_Addons_Simpli_Forms_Module_Filter {

    /**
     * Common Filter
     *
     * All Fields are subject to this filter
     * @param string $content The shortcode content
     * @return string The parsed output of the form body tag
     */
    protected function _commonFilter($properties) {
        $this->debug()->t();
        $properties = parent::_commonFilter($properties);
        extract($properties);

        /*
         * Return error if required arguments are not found
         */
        if ((!isset($atts['name'])) || (is_null($atts['name']))) {

            $atts ['_error'][] = 'Name attribute is required';

            return ($atts);
        }

            $atts['selected'] = $this->plugin()->getUserOption($atts['name']);


            $atts['value'] = $this->plugin()->getUserOption($atts['name']);



        return (compact('scid','atts', 'tags'));
    }

}

