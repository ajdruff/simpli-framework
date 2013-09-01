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
    public $form;
    private $_forms;
    public $form_counter = 0; // keeps track of how many forms were added to the page. used to make each form and id unique

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
     * Start Form
     *
     * Creates a  form array for reference by each of the shortcodes
     * @param none
     * @return void
     */
    public function startFormOLD($properties) {
        $this->debug()->t();


        /*
         * initialize the properties
         */
        $defaults = array(
            'filter' => null,
            'name' => 'simpli_forms',
            'template' => 'formStart', // the template to use for the form start
            'theme' => null,
            'is_shortcode' => false
        );


                /*
         * Apply Defaults
         * Use the shortcode_atts function which will also remove
         * any attributes not specified in the element defaults
         */
        $properties = shortcode_atts($defaults, $properties);



        /*
         * increase the form counter
         */
        $this->_form_counter++;

        if (!is_array($properties)) {
            $properties = array();
        }
        $this->_form = array(); //initialize, clearing any previous form on the same page






        /*
         * if a theme was provided, set it.
         */
        if (!is_null($properties['theme'])) {
            $this->getTheme()->setTheme($properties['theme']);
        }

        /*
         * if a filter was provided, set it.
         */

        if (!is_null($properties['filter'])) {


            $this->setFilter($properties['filter']);
        }

        /*
         * if a filter was provided, set it.
         */

        if (!is_null($properties['filter'])) {


            $this->setFilter($properties['filter']);
        }

        /*
         * reset the elements array for this new form
         */
        $this->_form['elements'] = array();


        /*
         * reset the form properties
         */
        $this->_form['form'] = $properties;


        /*
         * finally, output the form's starting element
         */


        $this->el(array(
            'el_id' => 'formStart'
                )
        );



        $this->debug()->logVars(get_defined_vars());
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

            $this->_form_filter = ''; //this results in the default filter Module name defined by $this->getAddon->MODULE_NAME_FILTERS , e.g.: 'Filter'
        }


        return $this->_form_filter;
    }

    /**
     * Set  Filter
     *
     * Sets the Form Filter Suffix
     * @param mixed filter_name or an array of filter names
     * @returnvoid
     */
    public function setFilter($filters) {
        $this->debug()->t();


        /*
         * set the form filter, capatilzing the first word.
         */
        if (!is_array($filters)) {
            $filters = array($filters);
        }
        foreach ($filters as $key => $filter) {
            $filters[$key] = ucwords(trim($filter));
        }
        $this->_form_filter = $filters;
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

        $this->debug()->logVars(get_defined_vars());
        if (method_exists($this->getElementsModule(), $method)) {
            $this->getElementsModule()->$method($properties);
        } else {
            /*
             * if no element defined, then display an error message
             */
            echo $this->getElementErrorMessages($el_id, array('Element ' . $el_id . ' is not defined in ' . get_class($this->getElementsModule())));
        }
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


$this->debug()->logVars(get_defined_vars());

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
        $this->debug()->logVar('$scid = ', $scid);
        $properties = array(
            'scid' => $scid, //shortcode , represents the
            'atts' => $atts,
            'tags' => $tags
        );

        $this->debug()->logVar('$properties = ', $properties);
        /*
         * Filter
         * Apply the filter set by the template.
         * If no filter was set, it will use the basic filter which
         * is the 'Filter' module.
         */

        $this->debug()->logVars(get_defined_vars());
        /*
         * get filter module name by combining the constant MODULE_NAME_FILTERs with the user set filter.
         * If the user set 'filter' => 'Example' when creating the form, then the module name would be 'FilterExample'
         */

        /*
         * apply the filters by calling the filter method from the filter module that was set  when the user
         * set 'filter'=>'Example'
         */



        $filters = $this->getFilter();
        if (!is_array($filters)) {
            /*
             * if not already an array, make it an array so we can iterate
             */
            $filters = array($filters);
        }

        foreach ($filters as $filter) {

            $filter_module_name = $this->getAddon()->MODULE_NAME_FILTERS . $filter; // e.g.: 'FilterOptions'
            if (method_exists($this->getAddon()->getModule($filter_module_name), 'filter')) {
                $this->debug()->log('filtering using ' . $filter_module_name);
                 $this->debug()->logVar('unfiltered properties are  = ', $properties);
                 $this->debug()->log('Filtering with module ' . $filter_module_name);
                $properties = $this->getAddon()->getModule($filter_module_name)->filter($properties);
                $this->debug()->logVar('filtered properties are  = ', $properties);

            }
        }




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
            if ($this->form['form']['is_shortcode'] === false) {
                echo $this->getElementErrorMessages($scid, $atts['_error']);
                return;
            } else {
                return ($this->getElementErrorMessages($scid, $atts['_error']));
            }
        }





        $theme = $this->getTheme();
        $this->debug()->logVar('$theme = ', $theme->getThemeName());

        /*
         *
         * Parse Template - Replace the Attribute Template Tags with their values
         *
         */



        $template = $theme->getTemplate($atts['template_id']);

        $this->debug()->logVar('$template for $template_id ' . $atts['template_id'] . '=<br>',  $template);

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
        if ($this->form['form']['is_shortcode'] === false) {
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



        $result = $this->getAddon()->getModule('Theme');
        $this->debug()->log('theme name in getTheme is : ' . $result->getThemeName());
        $this->debug()->logVar('$this->getAddon()->getModule(\'Theme\') ', $result);

        $this->debug()->log('Theme object =<pre>', print_r($this->getAddon()->getModule('Theme'), true), '</pre>');
        return ($result);
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

    /**
     * Form Start
     *
     * Creates a  form array for reference by each of the shortcodes
     * @param none
     * @return void
     */
    public function formStart($properties) {
        $this->debug()->t();
        $defaults = array(

            'name' => 'simpli_forms',
            'is_shortcode'=>false,
            'theme' => 'Admin',
            'action' => $_SERVER['REQUEST_URI'],
            'method' => 'post',
            'template_id' => __FUNCTION__,
            'filter' => 'Settings',);


                /*
         * Apply Defaults
         * Use the shortcode_atts function which will also remove
         * any attributes not specified in the element defaults
         */
        $properties = shortcode_atts($defaults, $properties);



        $this->debug()->logVar('$properties = ', $properties);

        /*
         * increase the form counter
         */
        $this->form_counter++;

        if (!is_array($properties)) {
            $properties = array();
        }
        $this->form = array(); //initialize, clearing any previous form on the same page









        /*
         * if a theme was provided, set it.
         */
        if (!is_null($properties['theme'])) {
            $this->getTheme()->setTheme($properties['theme']);
        }

        /*
         * if a filter was provided, set it.
         */

        if (!is_null($properties['filter'])) {


            $this->setFilter($properties['filter']);
        }



        /*
         * reset the elements array for this new form
         */
        $this->form['elements'] = array();


        /*
         * reset the form properties
         */
        $this->form['form'] = $properties;


        /*
         * output the html
         */

        $this->el(array(
            'el_id' => 'formStart',
            'is_shortcode' => $properties['is_shortcode'],
            'name' => $properties['name'],
            'action' => $properties['action'],
            'method' => $properties['method'],
            'template_id' => $properties['template_id'],
                )
        );
    }
    /**
     * Form End
     *
     * Adds the form's end tag and any other controls required ( buttons,etc)
     * @param none
     * @return void
     */
    public function formEnd($properties=array()) {
        $this->debug()->t();
        $defaults = array(

            'name' => null,
            'is_shortcode'=>false,
            'template_id' => __FUNCTION__,
           );


                /*
         * Apply Defaults
         * Use the shortcode_atts function which will also remove
         * any attributes not specified in the element defaults
         */
        $properties = shortcode_atts($defaults, $properties);




        $this->el(array(
            'el_id' => 'formEnd',
            'is_shortcode' => $properties['is_shortcode'],
            'name' => 'formEnd',
            'template_id' => $properties['template_id'],
                )
        );
    }

}

