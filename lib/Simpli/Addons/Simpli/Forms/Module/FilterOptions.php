<?php

/**
 * Form Filter Module - Options
 *
 * Modifies Field Inputs from Form Templates
 *
 * @author Andrew Druffner
 * @package SimpliFramework
 * @subpackage SimpliAddonsForms
 *
 */
class Simpli_Addons_Simpli_Forms_Module_FilterOptions extends Simpli_Addons_Simpli_Forms_Module_Filter {

    /**
     * Common Filter
     *
     * All Fields are subject to this filter
     * @param string $content The shortcode content
     * @return string The parsed output of the form body tag
     */
    protected function _commonFilter($atts) {
        /*
         * Use parent filters so we dont need to duplicate
         *  the common filter here, but we can extend it
         */
        $atts = parent::_commonFilter($atts);


        $atts['value'] = $this->getPlugin()->getModule('Post')->getPostOption($atts['name']);







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

       // $atts['value'] = 'filtered by Options';

        return ($atts);
    }

}

