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
    private $_field_prefix;
    private $_form_filter_suffix;
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
        $this->debug()->t();
    }

    /**
     * Configure Module
     *
     * @param none
     * @return void
     */
    public function config() {
        $this->debug()->t();
    }

    /**
     * Create Form
     *
     * Creates a  form array for reference by each of the shortcodes
     * @param none
     * @return void
     */
    public function createForm($properties) {
        //$this->debug()->t(true,1);


        $defaults = array(
            'filter' => null,
            'theme' => null,
            'is_shortcode' => false
        );
        $properties = shortcode_atts($defaults, $properties);

        $this->setFilter($properties['filter']);

        $this->getAddon()->getModule('Theme')->setTheme($properties['theme']);


        if (!is_array($properties)) {
            $properties = array();
        }
        $this->_form = array(); //initialize, clearing the old form
        $this->_form['elements'] = array();
//        $this->_form['elements']['atts'] = array();
//        $this->_form['elements']['att_defaults'] = array();
//        $this->_form['elements']['tags'] = array();
        $this->_form['form'] = $properties;
    }

    /**
     * Set Form Property
     *
     * Updates the Form object with new property value
     * e.g.: setFormProperty('atts',array('id'=>2);)
     *
     * @param none
     * @return mixed False if no such property name, form object if successful
     */
    public function setFormProperty($property_name, $value) {
        if (!isset($this->_form['form'][$property_name])) {
            $this->debug()->t();

            return false
            ;
        }

        $this->_form['form'][$property_name] = $value;
        return $this->_form;
    }

    /**
     * Set Element
     *
     *  Adds an element to the Form array for reference by other elements
     *
     * @param string $element_name The name of the element property, e.g.: 'my_name'
     * @param string $property_name The name of the property, e.g.: att_defaults
     * @return array $this->_form
     */
    public function setElement($element_name, $properties) {
        $this->debug()->t();

        /*
         * Index fields by their names
         * If no names are provided, provide a '__undefined__' index with counter
         * This allows the fields to still be added, but you can later throw an
         * error in a Filter module when checking if $atts['name'] is null
         */


        static $fieldCounter;
        if (is_null($element_name)) {
            // clear the fieldCounter if we are starting a new form with no elements in it
            if (count($this->_form['elements']) == 0) {
                $fieldCounter = 0;
            }
            $fieldCounter++;
            $element_name = '__name_undefined__' . $fieldCounter;
        }

        $this->_form['elements'][$element_name] = $properties;
        return $this->_form;
    }

    /**
     * Get Element Property
     *
     * Gets a property of an element that has been added to the Form.
     * e.g.: getElementProperty('my_name','atts')
     *
     * @param string $element_name The name of the element property, e.g.: 'my_name'
     * @param string $property_name The name of the property, e.g.: att_defaults
     * @return mixed null if no such property name already exists, requested value otherwise
     */
    public function getElementProperty($element_name, $property_name) {
        if (!isset($this->_form['elements'][$element_name][$property_name])) {
            $this->debug()->t();

            return null;
        }

        $this->_form['elements'][$element_name][$property_name] = $value;
        return $this->_form['elements'][$element_name][$property_name];
    }

    private $_form_filter;

    /**
     * Get Form Filter
     *
     * @param none
     * @return string
     */
    public function getFilter() {
        $this->debug()->t();



        if (is_null($this->_form_filter)) {

            $this->_form_filter = ''; //this results in the default filter Module name defined by $this->getAddon->MODULE_NAME_FILTERS
        }


        return $this->_form_filter;
    }

    /**
     * Set  Filter
     *
     * Sets the Form Filter Suffix
     * @param string getFormFilterTag
     * @return object $this
     */
    public function setFilter($form_filter) {
        $this->debug()->t();


        /*
         * set the form filter, capatilzing the first word.
         */

        $this->_form_filter = ucwords($form_filter);
    }

    /**
     * Element Wrapper
     *
     * Calls the appropriate element method to render the element
     *
     * @param string $properties The properties provided by the user
     * @return void
     */
    public function el($properties) {
        $this->debug()->t();

        /*
         * Use the name of the element id as the method
         *
         */
        $el_id = $properties['el_id'];
        $method = $el_id;
        unset($properties['el_id']); //remove the element id since we dont want it part of atts, and it served its only purpose
        $this->getElementsModule()->$method($properties);
    }

    /**
     * Get Form
     *
     * @param none
     * @return string
     */
    public function getForm() {
        return $this->_form;
    }

    /**
     * Render Element
     *
     * Renders the element into HTML using its template and user supplied attributes
     *
     * @param $scid
     * @param $atts
     * @param $defaults
     * @return string (if used in a shortcode) or void if used directly as it echos its output
     */
    public function renderElement($scid, $atts, $defaults) {
        $this->debug()->t();




        /*
         * Apply Defaults
         * Use the shortcode_atts function which will also remove
         * any attributes not specified in the element defaults
         */
        $atts = shortcode_atts($defaults, $atts);

        /*
         * Package Properties so we can hand off to filters
         */
        $tags = array();
        $properties = array(
            'scid' => $scid, //shortcode , represents the
            'atts' => $atts,
            'tags' => $tags
        );

        /*
         * Filter
         * Apply the filter set by the template.
         * If no filter was set, it will use the basic filter which
         * is the 'Filter' module.
         */


        $filter_module_name = $this->getAddon()->MODULE_NAME_FILTERS . $this->getFilter(); // e.g.: 'FilterOptions'
        $filter_hook_name = $this->getAddon()->getModule($filter_module_name)->getHookName(); //find the module corresponding to the filter and use its getHookName() method so we call its filters
        $properties = apply_filters($filter_hook_name, $properties);

        /*
         * Unpack Properties
         */
        extract($properties); //unpacks to: $scid , $atts, $tags

        /*
         * Define convienance variables
         */

        $element_name = $atts['name'];


        /*
         * Raise Error
         */

        if ((isset($atts['_error'])) && (!is_null($atts['_error']))) {
            if ($this->_form['form']['is_shortcode'] === false) {
                echo $this->getElementErrorMessages($scid, $atts['_error']);
                return;
            } else {
                return ($this->getElementErrorMessages($scid, $atts['_error']));
            }
        }





        $theme = $this->getTheme();



        /*
         *
         * Parse Template - Replace the Attribute Template Tags with their values
         *
         */



        $template = $theme->getTemplate($atts['template_id']);



        $att_template_tags = $this->getTagPairs($atts); //convert to tag pairs
        $template_with_atts_replaced = str_ireplace($att_template_tags['names'], $att_template_tags['values'], $template);

        /*
         *
         * Parse Template - Replace the Element Template Tags with their values
         *
         */
        $element_template_tags = $this->getTagPairs($tags); //convert to tag pairs
        $processed_template = str_ireplace($element_template_tags['names'], $element_template_tags['values'], $template_with_atts_replaced);

        /*
         *
         * Add the element to the Form array
         * This is so other element filters can have access to their properties if needed
         */
        $this->setElement($element_name, $properties);
        /*
         * Only echo the result if function is being used outside of the shortcode
         * otherwise, let the shortcode handle output (best practice)
         */
        if ($this->_form['form']['is_shortcode'] === false) {
            echo $processed_template;
        }


        $this->debug()->logVars(get_defined_vars());

        return $processed_template;
    }

    protected $_elements_module;

    /**
     * Get Elements Module
     *
     * Returns the module used for elements
     *
     * @param none
     * @return object
     */
    public function getElementsModule() {
        $this->debug()->t();


        $this->_elements_module = $this->getTheme()->getFormElementsModule();
        return($this->_elements_module);
    }

    /**
     * Get Theme
     *
     * @param none
     * @return object
     */
    public function getTheme() {
        $this->debug()->t();



        return ($this->getAddon()->getModule('Theme'));
    }

    /**
     * Get Default Field Label
     *
     * Uses name to derive a label
     * @param string $content The shortcode content
     * @return string The parsed output of the form body tag
     */
    function getDefaultFieldLabel($name) {
        $this->debug()->t();



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
        $this->debug()->t();


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
        $this->debug()->t();

        $this->_field_prefix = $field_prefix;
        return $this;
    }

    /**
     * Get Element Error Messages
     *
     * Returns the formatted error message
     * @param string $error_message An error string
     * @return string The formatted error message
     */
    public function getElementErrorMessages($tag, $error_messages) {
        $this->debug()->t();

        $error_messages = implode(',', $error_messages);

        $result = '<p> <strong>Unable to Display (' . $tag . ') form element: </strong><em style="color:red">' . $error_messages . '</em></p>';
        return $result;
    }

    /**
     * Get Tag Pairs
     *
     * Given an associative array of name/value pairs, it returns an array with indexes surrounded by brackets
     * so you can use them in a str_replace
     * give ['mytag']='my_value' , will return ['{mytag}']='my_value'
     *
     * @param none
     * @return void
     */
    public function getTagPairs($tags) {
        $this->debug()->t();


        if (!is_array($tags)) {
            return array('names' => null, 'values' => null);
        }
        $result['names'] = array_map(array($this, 'convertTagName'), array_keys($tags));
        $result['values'] = array_map(array($this, 'convertTagValue'), array_values($tags));


        return $result;
    }

    /**
     * Convert Tag Name
     *
     * Wraps a word with brackets and uppercases it
     *
     * @param none
     * @return void
     */
    private function convertTagName($tag_name) {
        $this->debug()->t();


        return '{' . strtoupper($tag_name) . '}';
    }

    /**
     * Convert Tag Value
     *
     * Converts a Template Tag value into a string if not one already
     *
     * @param none
     * @return void
     */
    private function convertTagValue($tag_value) {
        $this->debug()->t();


        if (is_array($tag_value) || is_object($tag_value)) {
            return var_export($tag_value, true);
        }
        return $tag_value;
    }

}

