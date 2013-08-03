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
class Simpli_Addons_Simpli_Forms_Module_Elements extends Simpli_Basev1c0_Plugin_Module {

    protected $_form_module;

    /**
     * Add Hooks
     *
     * Adds WordPress Hooks, triggered during module initialization
     * @param none
     * @return void
     */
    public function addHooks() {

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
     * Get Template Tags
     *
     * Adds template tags for the element
     *
     * @param none
     * @return void
     */
    public function getTemplateTags($element_type, $atts) {
        $this->_template_tags = array();
        $method = $element_type;
        $this->$method($atts);
        return ($this->_template_tags);
    }
   private $_filtered_atts;

    /**
     * Filter Attributes
     *
     * Filters an elements attributes and returns the attributes as an array
     *
     * @param none
     * @return void
     */
    public function getFilteredAtts($element_type, $atts) {
        $method = $element_type;
        $this->$method($atts);
        $element_name = $atts['name'];

        return ($this->_filtered_atts);
    }
    private $_att_defaults;

    /**
     * Set Att Defaults
     *
     * Updates the Form Module's Element array with the attribute defaults
     *
     * @param none
     * @return void
     */
    public function setAttDefaults($att_defaults, $atts) {


 $this->_att_defaults = $att_defaults;
    }

    /**
     * Get Att Defaults
     *
     * Get Att Defaults
     *
     * @param none
     * @return void
     */
    public function getAttDefaults($atts) {
        $method = $atts['type'];
        $this->$method($atts);

        $element_defaults = $this->_att_defaults;
        return ($this->_att_defaults);
    }

    /**
     * Text Field
     *
     * Returns HTML for a Text Input Field
     * @param array $form Form Array contains all the fields and properties of the form
     * @param string $flag Must be one of several flags that gover
     * @return void
     */
    public function text($atts) {

        $this->setAttDefaults(
                array(
            'name' => null  //the name of the form field.
            , 'value' => null //value of the field
            , 'label' => null
            , 'hint' => null
            , 'help' => null
            , 'template_id' => __FUNCTION__
            , 'type' => __FUNCTION__
                )
                , $atts);

        /*
         * Filter Attributes
         */

        $this->filterAtt('value', 25, $atts); //this means this will override the provided input 'name' with the filtered

        /*
         * Set template Tags
         */
        $this->addTemplateTag('My_Name', 'John Smith', $atts);
    }


    private $_template_tags;

    /**
     * Add Template Tag
     *
     * Adds a Template Tag to the Form
     *
     * @param none
     * @return void
     */
    public function addTemplateTag($tag_name, $tag_value, $atts) {
        $element_name = $atts['name'];

        $tags = $this->getAddon()->getModule('Form')->getElementProperty($element_name, 'tags');
        $tags[$tag_name] = $tag_value;
        $this->_template_tags = $tags;
    }

    /**
     * Filter Att
     *
     * Modify the attribute in $forms with the filtered attribute
     *
     * @param none
     * @return void
     */
    public function filterAtt($att_name, $new_att_value, $atts) {
        $element_name = $atts['name'];
        $atts[$att_name] = $new_att_value;
        $this->_filtered_atts = $atts;
    }

    /**
     * Text Field
     *
     * Returns HTML for a Text Input Field
     * @param array $atts Shortcode attributes
     * @return void
     */
    public function textold($atts) {
        $tag_id = __FUNCTION__;


        $defaults = array(
            'name' => null  //the name of the form field.
            , 'value' => null //value of the field
            , 'label' => null
            , 'hint' => null
            , 'help' => null
            , 'template_id' => $tag_id
        );


        return($this->getAddon()->getModule('Form')->renderElement($tag_id, $atts, $defaults));
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
            , 'options' => null //array in the form 'value'=>'display_text'
            , 'default_option' => null //string indiciating the value that should be selected on default
            , 'template_id' => $tag_id
        );


        echo ($this->_field($tag_id, $atts, $defaults));
    }

}

