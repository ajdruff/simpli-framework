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
        if (!is_array($form_atts)) {
            $form_atts = array();
        }
        $this->_form = array(); //initialize, clearing the old form
        $this->_form['elements'] = array();
//        $this->_form['elements']['atts'] = array();
//        $this->_form['elements']['att_defaults'] = array();
//        $this->_form['elements']['tags'] = array();
        $this->_form['form']['atts'] = $form_atts;
    }

    /**
     * End Form
     *
     *  Parses the form that was added with startForm
     * @param none
     * @return void
     */
    public function endForm() {

        /*
         * Get the Form Elements that have been collecting in $this->_form;
         */
        $form = $this->_form;
        // extract($this->_form); // now you have $form_fields and $form_atts as variables
        // $fields=$form['fields'];
        // $form_atts=$form['form_atts'];
        /*
         * set the form filter
         */
        //    if (isset($elements['filter'])) {
        //        $this->setFormFilter($elements['filter']); //sets form filter
        //    }

        /*
         * We have to iterate through the form array 3 times (youch) so that we can allow our template tags to get
         * access to the final attributes of all the elements.
         * The first iteration is to evaluate the defaults
         * The second iteration is to filter the attributes . If you combine the first and second iteration, you wont allow each element to have access to all the other element's final attributes which they may need when filtering
         * Finally, by the third iteration we have all the attributes figured out and we can evaluate the tags
         */

        /*
         * First, rationalize the user supplied attributes
         * by applying the defaults attributes against them using shortcode_atts.
         *
         */
        echo '<pre>', print_r($this->_form, true), '</pre>';
        foreach ($this->_form['elements'] as $element_name => $element_properties) {
            $element_atts = $element_properties['atts'];

            //   echo '<pre>', print_r($element_atts, true), '</pre>';
//
//            echo '<pre>', print_r($this->_form['elements'], true), '</pre>';
            $element_type = $element_atts['type'];


            $default_atts = $this->getElementsModule()->getAttDefaults($element_atts);
  //          echo 'default atts=<pre>', print_r($default_atts, true), '</pre>';

            $element_atts = shortcode_atts($default_atts, $element_atts);


            /*
             * Update the form with the new attributes
             */
            $this->_form['elements'][$element_name]['atts'] = $element_atts;


        }

        /*
         * Next, filter the attributes
         *
         */
        foreach ($this->_form['elements'] as $element_name => $element_properties) {
            $element_atts = $element_properties['atts'];



            $element_type = $element_atts['type'];


            $filtered_atts = $this->getElementsModule()->getFilteredAtts($element_type, $element_atts);


            /*
             * Update the form with the new attributes
             */
            $this->_form['elements'][$element_name]['atts'] = $filtered_atts;
        }




        /*
         * Finally, evaluate the template tags
         */
           foreach ($this->_form['elements'] as $element_name => $element_properties) {
            $element_atts = $element_properties['atts'];


            $element_type = $element_atts['type'];

            $element_template_tags = $this->getElementsModule()->getTemplateTags($element_type, $element_atts);

            /*
             * Update the form with the new attributes
             */
            $this->_form['elements'][$element_name]['element_tags'] = $element_template_tags;
        }






        /*
         * parse each element by Echoing out the result of element methods within the elements module
         * The type of field matches the method name.
         */


        echo '<pre>', print_r($this->_form, true), '</pre>';

        die('exiting line ' . __LINE__);



        foreach ($form_fields as $field_atts) {

            $method = $field_atts['type'];
            echo $elements_module->$method($field_atts);
        }
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
            return false
            ;
        }

        $this->_form['form'][$property_name] = $value;
        return $this->_form;
    }

    /**
     * Set Element Property
     *
     * Updates the Form object with new property value for the element
     * e.g.: setElementProperty('my_name','atts',$atts)
     *
     * @param string $element_name The name of the element property, e.g.: 'my_name'
     * @param string $property_name The name of the property, e.g.: att_defaults
     * @param mixed $value The value of the property
     * @return mixed False if no such property name, form object if successful
     */
    public function setElementProperty($element_name, $property_name, $value) {
        if (!isset($this->_form['elements'][$element_name][$property_name])) {
            return false;
        }

        $this->_form['elements'][$element_name][$property_name] = $value;
        return $this->_form;
    }


    /**
     * Get Element Property
     *
     * Updates the Form object with new property value for the element
     * e.g.: setElementProperty('my_name','atts',$atts)
     *
     * @param string $element_name The name of the element property, e.g.: 'my_name'
     * @param string $property_name The name of the property, e.g.: att_defaults
     * @return mixed null if no such property name already exists, requested value otherwise
     */
    public function getElementProperty($element_name, $property_name) {
        if (!isset($this->_form['elements'][$element_name][$property_name])) {
            return null;
        }

        $this->_form['elements'][$element_name][$property_name] = $value;
        return $this->_form['elements'][$element_name][$property_name];
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

    private $_elements_module;

    /**
     * Get Elements Module
     *
     * Returns the module used for elements
     *
     * @param none
     * @return object
     */
    public function getElementsModule() {

        $this->_elements_module = $this->getTheme()->getFormElementsModule();
        return($this->_elements_module);
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

    private $_tag_set;

    /**
     * Get Tags Module
     *
     * Returns the Tags Module
     * @param none
     * @return object
     */
    public function getTagsModule() {


        if (is_null($this->_tag_set)) {

            $this->_tag_set = ''; //this results in the default filter Module name defined by $this->getAddon->MODULE_NAME_FILTERS
        }

        $module_name = $this->getAddon()->MODULE_NAME_TAGS . $this->_tag_set; //e.g.: TagsMytags

        return $this->getAddon()->getModule($module_name);
    }

    /**
     * Set Tag Set
     *
     * Sets the Tag Set
     * @param string $tag_set Tag Set
     * @return object $this
     */
    public function setTagSet($tag_set) {
        /*
         * set the form filter, capatilzing the first word.
         */

        $this->_tag_set = ucwords($tag_set);
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
         * If no names are provided, provide a '__undefined__' index with counter
         * This allows the fields to still be added, but you can later throw an
         * error in a Filter module when checking if $atts['name'] is null
         */
        static $fieldCounter;
        if (isset($atts['name'])) {


            $this->_form['elements'][$atts['name']]['atts'] = $atts;
        } else {
            $fieldCounter++;
            $this->_form['elements']['__name_undefined__' . $fieldCounter]['atts'] = $atts;
        }
    }

    public function renderElement($tag_id, $atts, $defaults) {

    }

    public function renderElementOld($tag_id, $atts, $defaults) {


        /*
         * Merge the defaults and the attributes provided by the user together
         */
        $atts = shortcode_atts($defaults, $atts);


        /*
         * Apply the filter set by the template.
         * If no filter was set, it will use the basic filter which
         * is the 'Filter' module.
         */


        $filter_module_name = $this->getAddon()->MODULE_NAME_FILTERS . $this->getFormFilter(); // e.g.: 'FilterOptions'
        $filter_hook_name = $this->getAddon()->getModule($filter_module_name)->getHookName(); //find the module corresponding to the filter and use its getHookName() method so we call its filters
        $atts = apply_filters($filter_hook_name, $atts, $tag_id);


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


//        /*
//         * Fill in with defaults for those that werent provided
//         * Scrub attributes so only those defined in defaults show
//         */
//        $args = shortcode_atts($defaults, $atts);


        /*
         * Parse Tags
         */

        $tags_module_name = $this->getAddon()->MODULE_NAME_TAGS . $this->getTagSet(); // e.g.: 'TagsSeattle'
        $tags_hook_name = $this->getAddon()->getModule($tags_module_name)->getHookName(); //find the module corresponding to the filter and use its getHookName() method so we call its filters
        $template_tags = apply_filters($tags_hook_name, $atts, $tag_id);


        $theme = $this->getTheme();
        $util = $this->getPlugin()->getModule('Tools');


        /*
         * apply the template
         */


        $template = $theme->getTemplate($atts['template_id']);


        /*
         * Make case insensitive replacement of attributes
         * Will replace {my_attribute} with its value
         * For case sensitive replacements, just use str_replace instead
         */
        $att_tags = $this->getTagPairs($atts);

        $tags = array_merge($template_tags, $att_tags); //merge all the tags together
        $tags = $this->getTagPairs($tags); //convert to tag pairs
        echo '<pre>', print_r($tags, true), '</pre>';
        $result = str_ireplace($tags['names'], $tags['values'], $template);

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

        if (!is_array($tags)) {
            return array('names' => null, 'values' => null);
        }
        $result['names'] = array_map(array($this, 'convertToTag'), array_keys($tags));
        $result['values'] = array_values($tags);

        return $result;
    }

    /**
     * Convert To Tag
     *
     * Wraps a word with brackets and uppercases it
     *
     * @param none
     * @return void
     */
    private function convertToTag($tag) {

        return '{' . strtoupper($tag) . '}';
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

