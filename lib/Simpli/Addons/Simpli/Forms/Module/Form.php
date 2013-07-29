<?php

/**
 * Form Module
 *
 * Provides Form Helper Methods
 *
 * @author Andrew Druffner
 * @package SimpliFramework
 * @subpackage SimpliAddonsForms
 *
 */
class Simpli_Addons_Forms_Module_Form extends Simpli_Basev1c0_Plugin_Module {

    private $_form_theme;
    private $_field_prefix;
    private $_form_filter_suffix;
    private $_form;
    private $_forms;

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

    /**
     * Start Form
     *
     * Creates a temporary form array to hold the fields until endForm which parses it
     * @param none
     * @return void
     */
    public function startForm($form_atts) {

        $this->_form = array();
        $this->_form['form_fields'] = array();
        $this->_form['form_atts'] = $form_atts;
    }

    /**
     * End Form
     *
     *  Parses the form that was added with startForm
     * @param none
     * @return void
     */
    public function endForm() {

        $form = $this->_form;
        extract($this->_form);
        // $fields=$form['fields'];
        // $form_atts=$form['form_atts'];
        if (isset($form_atts['filter'])) {
            $this->setFormFilter($form_atts['filter']); //sets default form filter
        }
        $elements_module=$this->getTheme()->getFormElementsModule();
        foreach ($form_fields as $field_atts) {

            $method = $field_atts['type'];
            $elements_module->$method($field_atts);
        }
    }

    /**
     * Get Form Filter
     *
     * @param none
     * @return string
     */
    public function getFormFilterTag() {

        $filter_suffix = $this->_form_filter_suffix;

        $filter_tag = $this->getPlugin()->getSlug() . '_form_filters_' . $this->_form_filter_suffix;


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
     * Add Form Field
     *
     * Adds the field to the form array to be processed at form end
     * @param string $atts Field Attributes
     * @return void
     */
    public function addField($atts) {
        $this->_form['form_fields'][$atts['name']] = $atts;
    }



    public function renderElement($tag_id, $atts, $defaults) {

    $atts = apply_filters($this->getFormFilterTag(), $atts, $tag_id);

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