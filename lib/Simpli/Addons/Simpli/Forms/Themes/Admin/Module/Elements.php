<?php

/**
 * Form Elements Module
 *
 * Provides Basic Elements
 *
 * @author Andrew Druffner
 * @package SimpliFramework
 * @subpackage SimpliAddonsForms
 *
 */
class Simpli_Addons_Simpli_Forms_Themes_Admin_Module_Elements extends Simpli_Addons_Simpli_Forms_Module_Elements {

    /**
     * Text Field
     *
     * Returns HTML for a Text Input Field
     * @param array $atts Shortcode attributes
     * @return void
     */
    public function text($atts) {
        $this->debug()->t();



        $defaults = array(
            'name' => null  //the name of the form field.
            , 'value' => null //value of the field
            , 'heading' => null
            , 'label' => null
            , 'hint' => null
            , 'help' => null
            , 'template_id' => __FUNCTION__
        );



        return($this->getAddon()->getModule('Form')->renderElement(__FUNCTION__, $atts, $defaults));
    }

    /**
     * Radio Element
     *
     * Returns HTML for a Radio Input Field
     * @param array $atts Shortcode attributes
     * @return void
     */
    public function radio($atts) {
        $this->debug()->t();



        $defaults = array(
            'name' => null, //the name of the form field.
            'heading' => null  //the name of the form field.
            , 'value' => null //value of options that is selected
            , 'label' => null
            , 'hint' => null
            , 'help' => null
            , 'options' => null //array in the form 'value'=>'display_text'
            , 'selected' => null //string indiciating the value that should be selected on default
            , 'template_id' => __FUNCTION__
        );


        return($this->getAddon()->getModule('Form')->renderElement(__FUNCTION__, $atts, $defaults));
    }

        /**
     * Select Element
     *
     * Returns HTML for a Select Input Field
     * @param array $atts Shortcode attributes
     * @return void
     */
    public function select($atts) {
        $this->debug()->t();



        $defaults = array(
            'name' => null, //the name of the form field.
            'heading' => null  //the name of the form field.
            , 'value' => null //value of options that is selected
            , 'label' => null
            , 'hint' => null
            , 'help' => null
            , 'options' => null //array in the form 'value'=>'display_text'
            , 'selected' => null //string indiciating the value that should be selected on default
            , 'template_id' => __FUNCTION__
        );


        return($this->getAddon()->getModule('Form')->renderElement(__FUNCTION__, $atts, $defaults));
    }

}

