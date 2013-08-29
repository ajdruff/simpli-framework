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
class Simpli_Addons_Simpli_Forms_Themes_Seattle_Module_FilterSettings extends Simpli_Addons_Simpli_Forms_Module_Filter {

    /**
     * Common Filter
     *
     * All Fields are subject to this filter
     * @param string $content The shortcode content
     * @return string The parsed output of the form body tag
     */
    protected function _commonFilter($atts) {
        $this->debug()->t();


        /*
         * Return error if required arguments are not found
         */
        if ((!isset($atts['name'])) || (is_null($atts['name']))) {

            $atts ['_error'][] = 'Name attribute is required';

            return ($atts);
        }


        $atts['value'] = $this->getPlugin()->getModule('Post')->getPostOption($atts['name']);
        $atts['name'] = $this->getPlugin()->getSlug() . '_' . $atts['name'];






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
        $this->debug()->t();


        $atts['value'] = 'filtered by Settings';

        return ($atts);
    }

}

