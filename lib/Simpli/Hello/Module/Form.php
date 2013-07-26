<?php

/**
 * Form Module
 *
 * Provides Form Helper Methods
 *
 * @author Andrew Druffner
 * @package SimpliFramework
 * @subpackage SimpliHello
 *
 */
class Simpli_Hello_Module_Form extends Simpli_Basev1c0_Plugin_Module {

    private $_form_theme;
    private $_field_prefix;

    /**
     * Initialize Module when in Admin environment
     *
     * @param none
     * @return object $this
     */
    public function initModuleAdmin() {

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

    private $_form_filter_suffix;

    /**
     * Get Form Filter
     *
     * @param none
     * @return string
     */
    public function getFormFilterTag() {

        $filter_suffix=$this->_form_filter_suffix;

        $filter_tag=$this->getPlugin()->getSlug() . '_form_filters_' . $this->_form_filter_suffix;


        return $filter_tag;
    }

    /**
     * Set Form Filter
     *
     * Sets the Form Filter Suffix
     * @param string getFormFilterTag
     * @return object $this
     */
    public function setFormFilter($form_filter_tag) {
        $this->_form_filter_suffix = $form_filter_tag;
        return $this;
    }



    /**
     * Text Field
     *
     * Returns HTML for a Text Input Field
     * @param string $name The field name of the input field
     * @param string $value The value of the input
     * @param string $label The field label
     * @param string $hint Text that displays on the form to guide the user on how to fill in the field
     * @param string $help More detailed text on what the field is and how it should be used.
     * @return string The parsed output of the form body tag
     */
    function text($atts) {
        $tag_id = __FUNCTION__;


        $atts = apply_filters($this->getFormFilterTag(), $atts, $tag_id);

                $defaults = array(
            'name' => null  //the name of the form field.
            , 'value' => null
            , 'label' => $this->getDefaultFieldLabel($atts['name']) //take short form , remote prefix, remove underscores and capitalize it
            , 'hint' => null
            , 'help' => null
            , 'template_id' => 'text'
        );


        echo ($this->_field($tag_id, $atts, $defaults));
    }

    private function _field($tag_id, $atts, $defaults) {


        /*
         * Raise Error
         */

        if ((isset($atts['_error'])) && (!is_null($atts['_error']))) {

            foreach ($atts['_error'] as $error_message) {
                echo $this->getTagErrorMessage($tag_id, $error_message);
            }
            /*
             * Dont process any further
             */
            return;
        }


        /*
         * Fill in with defaults for those that werent provided
         * Scrub attributes so only those defined in defaults show
         */
        $args = shortcode_atts($defaults, $atts);







        extract($args);


        $theme = $this->getTheme();
        $util = $this->getPlugin()->getModule('Tools');


        /*
         * apply the template
         */


        $template = $theme->getTemplate($template_id);


        $result = sprintf($template, $name, $value, $label, $hint, $help);

        return $result;
    }

    /**
     * Get Theme
     *
     * @param none
     * @return object
     */
    public function getTheme() {

        if ($this->getPlugin()->getModule('FormTheme')) {

            return ($this->getPlugin()->getModule('FormTheme'));
        }


        return null;
    }

    /**
     * Get Default Field Label
     *
     * Uses name to derive a label
     * @param string $content The shortcode content
     * @return string The parsed output of the form body tag
     */
    function getDefaultFieldLabel($name) {


        $label = str_replace($this->getFieldPrefix(), '', $name);
                $label = strtolower($label);
        $label = str_replace('_', ' ', $label);
        $label = ucwords($label);
        return $label;
    }

    /**
     * Get Field Prefix
     *
     * @param none
     * @return string
     */
    public function getFieldPrefix() {

        if (is_null($this->_field_prefix)) {
            $this->setFieldPrefix($this->getPlugin()->getSlug() . '_');
        }

        return $this->_field_prefix;
    }

    /**
     * Set Field Prefix
     *
     * @param string $prefix
     * @return object $this
     */
    public function setFieldPrefix($field_prefix) {
        $this->_field_prefix = $field_prefix;
        return $this;
    }

    /**
     * Get Error Message
     *
     * Returns the formatted error message
     * @param string $error_message An error string
     * @return string The formatted error message
     */
    public function getTagErrorMessage($tag, $error_message) {

        $result = '<p> <strong>Tag Error (' . $tag . ') : </strong><em style="color:red">' . $error_message . '</em></p>';
        return $result;
    }

}