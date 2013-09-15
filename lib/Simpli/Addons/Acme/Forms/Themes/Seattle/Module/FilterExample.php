<?php

/**
 * Form Filter Module - Example
 *
 * Modifies Field Inputs from Form Templates
 * Usage: To use this Example template, copy and rename it to 'FiltersMyfiltername' and replace 'FiltersExample' in the class name with 'FiltersMyfiltername'
 * thats it! Now in your form template, simply use 'filter='myfiltername' to access its filters. Remember that it is an extension of the Filters class, which will
 * use its own filters if you dont define replacements.
 *
 * @author Andrew Druffner
 * @package SimpliFramework
 * @subpackage SimpliAddonsForms
 *
 */
class Simpli_Addons_Acme_Forms_Themes_Seattle_Module_FilterExample extends Simpli_Addons_Acme_Forms_Module_Filter {

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


        $atts['value'] = 'filtered by Seattle Example';


        return (compact('atts', 'tags'));
    }




}

