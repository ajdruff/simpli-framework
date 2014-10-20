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
class Simpli_Frames_Addons_Simpli_Forms_Themes_Admin_Modules_FilterSettings extends Simpli_Frames_Addons_Simpli_Forms_Modules_Filter {

    /**
     * Common Filter
     *
     * All Fields are subject to this filter
     ** @param none
     * @return void
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

