<?php

/**
 * Options Filter
 *
 * Modifies Field Inputs from Form Templates
 *
 * @author Andrew Druffner
 * @package SimpliFramework
 * @subpackage SimpliAddonsForms
 *
 */
class Simpli_Addons_Simpli_Forms_Themes_Saratoga_Module_FilterOptions extends Simpli_Addons_Simpli_Forms_Module_Filter {

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


        $atts['value'] = $this->plugin()->getModule('PostUserOptions')->getUserOption($atts['name']);

        return (compact('scid','atts', 'tags'));
    }

    /**
     * Filter Text
     *
     * Filters the Text Tag Attribute
     * @param string $atts The attributes of the tag
     * @return string $atts
     */
    protected function filterText($properties) {
        $this->debug()->t();

        extract($properties);
       // $atts['value'] = 'filtered by options filter';
        $tags['test_text'] = 'This is the test tag for a text template';
        return (compact('atts', 'tags'));
    }




}

