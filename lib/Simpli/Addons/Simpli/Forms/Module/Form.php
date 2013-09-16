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

        /*
         * Add Shortcodes
         */
        add_shortcode($this->plugin()->getSlug() . '_form', array($this, 'hookShortcodeElementWithoutContent'), 10);
        add_shortcode($this->plugin()->getSlug() . '_form_options', array($this, 'hookShortcodeElementWithContent'), 10);
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
            'el' => 'formStart'
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

        $this->form['elements'][$element_name] = $properties;
        return $this->form;
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
     * Hook Function - Shortcode Element (shortcode , without closing tag)
     *
     * The form element shortcode callback function for a self closing shortcode
     * tag (one that does not require a  trailing [/] tag.
     * Mapped using the add_shortcode hook.
     * It is a wrapper around _el(), which is shared code used by both the public el() and the shortcodes. The internal element method _el() handles the rendering of each of the form elements.
     * @param $atts The attributes of the shortcode
     * @return string The output of the shortcode
     */
    public function hookShortcodeElementWithoutContent($atts, $content, $tag) {
        /*
         * the add_shortcode will always pass $content and $tag to us.
         * in the case of a non-enclosed shortcode, such as this, $content should be null,
         * but for some reason , when used within a class method, $content will not evaluate to null
         * unless we explicitly set it to null within the method, so we do that here:
         */

        $content = null;


        /*
         * if el_id is not specified as an attribute, then make the 0th attribute the el_id
         * that allows you to do something like this [simpli_hello text] , note the space after
         * the shortcode tag. or [simpli_hello el='text'
         */
        if (!isset($atts['el']) || is_null($atts['el'])) {
            $atts['el'] = $atts[0];
        }

        return($this->_el($atts));
    }

    /**
     * Hook Function - Shortcode Element Options (shortcode , with closing tag)
     *
     * The callback function for an element shortcode with closing tag
     * Mapped using the add_shortcode hook.
     * It is a wrapper around _el(), which is shared code used by both this function and the shortcodes. The internal element method _el() handles the rendering of each of the form elements.
     *
     * @param $properties The attributes of the shortcode
     * @param $content The content contained between the opening and closing tag
     * @param $tag The shortcode string that triggered the callback
     * @return string The output of the shortcode
     */
    public function hookShortcodeElementWithContent($atts, $content, $tag) {
        $this->debug()->t();


        if (!isset($atts['el']) || is_null($atts['el'])) {
            $atts['el'] = $atts[0];
        }

        /*
         * Find the options string and change it to an array , assigning it to $atts['options']
         * $options may be passed as an attribute in the format of a query string, or they may be passed
         * within the content
         *
         * e.g.:
         * [simpli_forms_options options="enable=Enabled&disable=Click for Disabled"][/simpli_forms_options]
         *
          [simpli_forms_options]
          enable|Click for Enabled
          disable|Click for Enabled
          [/simpli_forms_options]
         * if options arent provided in the 'options' attribute, use the contents as the options
         */
        if (!isset($atts['options']) || is_null($atts['options'])) {
            //  $atts['options'] = $content;

            $atts['options'] = $this->plugin()->tools()->lines2array($content);
        } else {

            $atts['options'] = $this->plugin()->tools()->parse_str($atts['options']);
        }


        /*
         * Parse the 'selected' attribute into an array
         * if the selected attribute is provided, turn it into an array with the values as keys, and
         * the values as 'yes' .
         */
        if (isset($atts['selected'])) {

            $atts_selected = explode(',', $atts['selected']); //makes an array of the values
            $atts_selected = array_filter($atts_selected); //removese empty elements

            $atts_selected = array_flip($atts_selected); //values are now keys

            $atts['selected'] = array_combine(array_keys($atts_selected), array_fill(0, count($atts_selected), 'yes')); //assigns 'yes' to all elements
        }


        /*
         * Convert any shortcode attributes to arrays where required
         *
         * Since shortcodes handle all attributes as strings,
         * we convert those attributes we know should be arrays to arrays.
         * the !is_array check is required since if you use more than one filter, the
         * second filter will throw an array to string conversion error if !is_array check is not included. this will happen since the string
         * has already been converted to a string
         */



        $this->debug()->logVars(get_defined_vars());
        /*
         * call the internal _el method which is the proxy method that branches to the target method for handling the element that was identified by el_id.
         */
        return($this->_el($atts));
    }

    /**
     * Element Wrapper
     *
     * The purpose of this method is to provide a public interface to the user as an alternative to using a shortcode for rendering an element. It is a wrapper around _el(), which is shared code used by both this function and the shortcodes. The internal element method _el() handles the rendering of each of the form elements.
     *
     * The difference between using this function and using the shortcode callback functions directly, is that this function echos its output. A previous implementation was less reliable as it attempted to distringish whether a shortcode was calling it, before deciding whether to echo out its contents. As a consequence, frequently, the output failed, since something would inevitably interfere with the paramaters that indiciated whether it was being called by a shortcode. Using a separate function is much more stable and does not require conditionals.
     *
     * @param string $properties The properties provided by the user
     * @return string The output of the element method
     */
    public function el($properties) {
        $this->debug()->t();

        echo($this->_el($properties));
    }

    /**
     * Element (Internal)
     *
     * Calls the appropriate element method to render the element
     *
     * @param string $properties The properties provided by the user
     * @return string The output of the element method
     */
    private function _el($properties) {
        $this->debug()->t();

        /*
         * Use the name of the element id as the method
         *
         */


        $method = $properties['el'];

        unset($properties['el']); //remove the element id since we dont want it part of atts, and it served its only purpose

        $this->debug()->logVars(get_defined_vars());
        if (method_exists($this->getElementsModule(), $method)) {
            return($this->getElementsModule()->$method($properties));
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
        return $this->form;
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

            $filter_module_name = $this->addon()->MODULE_NAME_FILTERS . $filter; // e.g.: 'FilterOptions'
            if (method_exists($this->addon()->getModule($filter_module_name), 'filter')) {
                $this->debug()->log('filtering using ' . $filter_module_name);
                $this->debug()->logVar('unfiltered properties are  = ', $properties);
                $this->debug()->log('Filtering with module ' . $filter_module_name);
                $properties = $this->addon()->getModule($filter_module_name)->filter($properties);
                $this->debug()->logVar('filtered properties are  = ', $properties);
            }
        }




        /*
         * Unpack Properties
         */
        extract($properties); //unpacks to: $scid , $atts, $tags



                /*
         * Return content from content_override if set
         */
        if (isset($atts['content_override']) &&
                !is_null($atts['content_override'])
        ) {

            return $atts['content_override'];
        }



        /*
         * Define convienance variables
         */

        $element_name = $atts['name'];


        /*
         * Raise Error
         */

        if ((isset($atts['_error'])) && (!is_null($atts['_error']))) {


            return ($this->getElementErrorMessages($scid, $atts['_error']));
        }





        $theme = $this->getTheme();
        $this->debug()->logVar('$theme = ', $theme->getThemeName());

        /*
         *
         * Parse Template - Replace the Attribute Template Tags with their values
         *
         */



        $template = $theme->getTemplate($atts['template']);

        $this->debug()->logVar('$template for $template_id ' . $atts['template'] . '=<br>', $template);

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



        $result = $this->addon()->getModule('Theme');
        $this->debug()->log('theme name in getTheme is : ' . $result->getThemeName());
        $this->debug()->logVar('$this->addon()->getModule(\'Theme\') ', $result);

        $this->debug()->log('Theme object =<pre>', print_r($this->addon()->getModule('Theme'), true), '</pre>');
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
            $this->setFieldPrefix($this->plugin()->getSlug() . '_');
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
            'theme' => 'Admin',
            'action' => $_SERVER['REQUEST_URI'],
            'method' => 'post',
            'template' => __FUNCTION__,
            'filter' => null
            );


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
        $this->debug()->logVars(get_defined_vars());
        $this->el(array(
            'el' => 'formStart',
            'name' => $properties['name'],
            'action' => $properties['action'],
            'method' => $properties['method'],
            'template' => $properties['template'],
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
    public function formEnd($properties = array()) {
        $this->debug()->t();
        $defaults = array(
            'name' => null,
            'template' => __FUNCTION__,
        );


        /*
         * Apply Defaults
         * Use the shortcode_atts function which will also remove
         * any attributes not specified in the element defaults
         */
        $properties = shortcode_atts($defaults, $properties);




        $this->el(array(
            'el' => 'formEnd',
            'name' => 'formEnd',
            'template' => $properties['template'],
                )
        );
    }

}

