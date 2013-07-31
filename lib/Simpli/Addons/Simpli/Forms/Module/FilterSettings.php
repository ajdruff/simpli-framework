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
class Simpli_Addons_Simpli_Forms_Module_FilterSettings extends Simpli_Addons_Simpli_Forms_Module_Filter {

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

        //  echo '<pre>', print_r($atts, true), '</pre>';
        $atts['value'] = $this->getPlugin()->getModule('Post')->getPostOption($atts['name']);
        $atts['name'] = $this->getPlugin()->getSlug() . '_' . $atts['name'];





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
    protected function _filterText($atts) {

        $atts['value'] = 'filtered by Settings';

        return ($atts);
    }

}

