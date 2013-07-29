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
class Simpli_Addons_Forms_Module_Elements extends Simpli_Basev1c0_Plugin_Module {

    protected $_form_module;



    /**
     * Initialize Module when in Admin environment
     *
     * @param none
     * @return object $this
     */
    public function initModuleAdmin() {
        $this->initModule();
    }

    /**
     * Initialize Module
     *
     * @param none
     * @return object $this
     */
    public function initModule() {



        return $this;
    }

    /**
     * Configure Module
     *
     * @param none
     * @return void
     */
    public function config() {

    }



/**
     * Get Form Module
     *
 *  Read Only
     * @param none
     * @return object Form Module
     */
    public function getFormModule() {
        return $this->getPlugin()->getModule('Form');
    }


    /**
     * Text Field
     *
     * Returns HTML for a Text Input Field
     * @param array $atts Shortcode attributes
     * @return void
     */
 public   function text($atts) {
        $tag_id = __FUNCTION__;


        $defaults = array(
            'name' => null  //the name of the form field.
            , 'value' => null //value of the field
            , 'label' => null
            , 'hint' => null
            , 'help' => null
            , 'template_id' =>  null
        );


        return($this->getPlugin()->getModule('Form')->renderElement($tag_id, $atts, $defaults));
    }

        /**
     * Select Field
     *
     * Returns HTML for a Select Input Field
     * @param string $name The field name of the input field
     * @param string $value The value of the input
     * @param string $label The field label
     * @param string $hint Text that displays on the form to guide the user on how to fill in the field
     * @param string $help More detailed text on what the field is and how it should be used.
     * @return string The parsed output of the form body tag
     */
    function select($atts) {
        $tag_id = __FUNCTION__;


        $atts = apply_filters($this->getFormFilterTag(), $atts, $tag_id);

        $defaults = array(
            'name' => null  //the name of the form field.
            , 'value' => null //value of options that is selected
            , 'label' => $this->getFormModule()->getDefaultFieldLabel($atts['name']) //take short form , remote prefix, remove underscores and capitalize it
            , 'hint' => null
            , 'help' => null
            , 'options'=>null //array in the form 'value'=>'display_text'
            , 'default_option'=>null //string indiciating the value that should be selected on default
            , 'template_id' => $tag_id
        );


        echo ($this->_field($tag_id, $atts, $defaults));
    }



}