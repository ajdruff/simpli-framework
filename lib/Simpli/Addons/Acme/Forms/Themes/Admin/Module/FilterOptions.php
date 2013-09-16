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
class Simpli_Addons_Acme_Forms_Themes_Admin_Module_FilterOptions extends Simpli_Addons_Acme_Forms_Module_Filter {

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

$setting_name=$atts['name'];//capture the name before it gets converted to an array element. do this so when we retrieve the settings, we can use this format since the settings are saved this way.
        /* Convert Name to an Array Element
         *
         * Because the settings will be saved in an array that will serve to namespace the names from the other names
         * on the form, we will convert the name to an array element
         */
        $atts['name']=$this->plugin()->getSlug() . '[' . $atts['name'] .']';

            $atts['selected'] =  $this->plugin()->getModule('PostUserOptions')->getUserOption($setting_name);


            $atts['value'] =  $this->plugin()->getModule('PostUserOptions')->getUserOption($setting_name);




            if ($atts['name']==='simpli_hello[text]') {
                   $this->debug()->logVar('$setting_name = ', $setting_name);
                $this->debug()->logVar('$atts = ', $atts);
            }
        return (compact('scid','atts', 'tags'));
    }





}

