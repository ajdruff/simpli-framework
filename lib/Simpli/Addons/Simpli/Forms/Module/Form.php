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
class Simpli_Addons_Simpli_Forms_Module_Form extends Simpli_Basev1c0_Plugin_Module {

    private $_form_theme;
    private $_form_filter;
    private $_form;
    private $_forms;

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
        extract($this->_form); // now you have $form_fields and $form_atts as variables
        // $fields=$form['fields'];
        // $form_atts=$form['form_atts'];
        /*
         * set the form filter
         */
        if (isset($form_atts['filter'])) {
            $this->setFormFilter($form_atts['filter']); //sets default form filter
        }
        /*
         * parse each element by Echoing out the result of element methods within the elements module which contains the element methods
         * The type of field *is* the method since it should be exactly the same as the method to be used for parsing.
         */
        $elements_module = $this->getTheme()->getFormElementsModule();

        foreach ($form_fields as $field_atts) {

            $method = $field_atts['type'];
            echo $elements_module->$method($field_atts);
        }
    }

    /**
     * Get Form Filter
     *
     * @param none
     * @return string
     */
    public function getFormFilter() {


        if (is_null($this->_form_filter)) {

            $this->_form_filter = ''; //this results in the default filter Module name defined by $this->getAddon->MODULE_NAME_FILTERS
        }


        return $this->_form_filter;
    }

    /**
     * Set Form Filter
     *
     * Sets the Form Filter Suffix
     * @param string getFormFilterTag
     * @return object $this
     */
    public function setFormFilter($form_filter) {
        /*
         * set the form filter, capatilzing the first word.
         */

        $this->_form_filter = ucwords($form_filter);
    }

    /**
     * Add Form Field
     *
     * Adds the field to the form array to be processed at form end
     * @param string $atts Field Attributes
     * @return void
     */
    public function addField($atts) {
        /*
         * Index fields by their names
         * If no names are provided, provide a '__undefined-_' index with counter
         * This allows the fields to still be added, but you can later throw an
         * error in a Filter module when checking if $atts['name'] is null
         */
        static $fieldCounter;
        if (isset($atts['name'])) {


            $this->_form['form_fields'][$atts['name']] = $atts;
        } else {
            $fieldCounter++;
            $this->_form['form_fields']['__name_undefined__' . $fieldCounter] = $atts;
        }
    }

    public function renderElement($tag_id, $atts, $defaults) {


        /*
         * Merge the defaults and the attributes provided by the user together
         */
        $atts = shortcode_atts($defaults, $atts);


        /*
         * Apply the filter set by the template.
         * If no filter was set, it will use the basic filter which
         * is the 'Filter' module.
         */


        $filterModuleName = $this->getAddon()->MODULE_NAME_FILTERS . $this->getFormFilter(); // e.g.: 'FilterOptions'
        $filterTag = $this->getAddon()->getModule($filterModuleName)->getFilterTag(); //find the module corresponding to the filter and use its getFilterTag() method so we call its filters
        $atts = apply_filters($filterTag, $atts, $tag_id);


     //   echo '<pre>', print_r($this->_form, true), '</pre>';
        /*
         * Print Our Any Errors that are returned by the filters
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
        //echo 'Args after extraction<pre>', print_r($args, true), '</pre>';

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


        return ($this->getAddon()->getModule('Theme'));
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

