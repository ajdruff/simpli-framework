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
class Nomstock_Com_Addons_Simpli_Forms_Themes_Admin_Modules_FilterOptions extends Nomstock_Com_Addons_Simpli_Forms_Modules_Filter {

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

        if (array_key_exists('name', $atts) && is_null($atts['name'])) {

            $atts ['_error'][] = 'Name attribute is required';

            return ($atts);
        }

        $setting_name = $atts['name']; //capture the name before it gets converted to an array element. do this so when we retrieve the settings, we can use this format since the settings are saved this way.
        /* Convert Name to an Array Element
         *
         * Because the settings will be saved in an array that will serve to namespace the names from the other names
         * on the form, we will convert the name to an array element
         */
        $atts['name'] = $this->plugin()->getSlug() . '[' . $atts['name'] . ']';


        // look up selected if its an attribute of the element
        if (array_key_exists('selected', $atts)) {
            $atts['selected'] = $this->plugin()->getModule('PostUserOptions')->getUserOption($setting_name);
        }


        // look up 'value' if its an attribute of the element
        if (array_key_exists('value', $atts)) {
            $atts['value'] = $this->plugin()->getModule('PostUserOptions')->getUserOption($setting_name);
        }




        return (compact('scid', 'atts', 'tags'));
    }

    /**
     * Form Start
     *
     * Filters the Text Tag Attribute
     * @param string $atts The attributes of the tag
     * @return string $atts
     */
    protected function filterFormStart($properties) {
        $this->debug()->t();

        extract($properties);
        $this->debug()->logVar('Form Start $properties = ', $properties);

        if (array_key_exists('name', $atts) && is_null($atts['name'])) {
            $this->debug()->log('Setting name to simpli_forms', true);
            $atts['name'] = 'simpli_forms';
        }
        if (array_key_exists('action', $atts) && is_null($atts['action'])) {
            $atts['action'] = $_SERVER['REQUEST_URI'];
        }

        if (array_key_exists('method', $atts) && is_null($atts['method'])) {
            $atts['method'] = 'post';
        }
        /*
         * normally, we dont use ajax since the forms publish button will be used.
         */
        if (array_key_exists('ajax', $atts) && is_null($atts['ajax'])) {
            $atts['ajax'] = false;
        }
        /*
         * if not using ajax, no need for a form tag
         * since the post option will take care of it.
         */
        if ($atts['ajax'] === true) {


            if ($atts['template'] === 'formStart') {
                $atts['template'] === 'formStartPostAjax'; //if there is ajax, we need to use the correct template
            };
        } else {
            $atts['content_override'] = ''; //if there is no ajax, no need for an enclosing <form> tag
        }



        return (compact('scid', 'atts', 'tags'));
    }

    protected function filterFormEnd($properties) {
        $this->debug()->t();

        extract($properties);

        if (!isset($atts['name']) || is_null($atts['name'])) {
            $atts['name'] = 'simpli_forms';
        }
        if (!isset($atts['action']) || is_null($atts['action'])) {
            $atts['action'] = $_SERVER['REQUEST_URI'];
        }

        if (!isset($atts['method']) || is_null($atts['method'])) {
            $atts['method'] = 'post';
        }

        /*
         * if not using ajax, no need for a form tag
         * since the post option will take care of it.
         */
        $form = $this->addon()->getModule('Form')->getForm();
        $this->debug()->logVar('Form $fields = ', $form);

        if (!$form['form']['ajax'] === true) {
            $atts['content_override'] = '';
        } else {
            //make sure you are using the ajax template
            $atts['template'] = 'FormEndPostAjax';
        }



        return (compact('scid', 'atts', 'tags'));
    }

}

