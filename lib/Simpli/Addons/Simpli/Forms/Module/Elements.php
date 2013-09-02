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
        $this->debug()->t();







        /**
         *
         *
         *  add scripts
         * example: add_action('wp_enqueue_scripts', array(&$this, 'enqueue_scripts'));
         *
         */
        /**
         *
         * Add custom ajax handlers
         *  Map Ajax Handlers to Ajax Actions passed to php by the ajax request
         * example: add_action('wp_ajax_' . $this->getPlugin()->getSlug() . '_my_action', array(&$this, 'my_function'));
         * see http://codex.wordpress.org/Plugin_API/Action_Reference/wp_ajax_%28action%29
         */

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
     * Get Form Module
     *
     *  Read Only
     * @param none
     * @return object Form Module
     */
    public function getFormModule() {
        $this->debug()->t();

        return $this->getPlugin()->getModule('Form');
    }

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
            ,'is_shortcode'=>false
            , 'value' => null //value of the field
            , 'label' => null
            , 'hint' => null
            , 'help' => null
            , 'template_id' => __FUNCTION__
        );


return($this->getAddon()->getModule('Form')->renderElement(__FUNCTION__, $atts, $defaults));
    }

    /**
     * Select Element
     *
     * Returns HTML for a Text Input Field
     * @param array $atts Shortcode attributes
     * @return void
     */
    public function select($atts) {
         $this->debug()->t();



        $defaults = array(
            'name' => null  //the name of the form field.
            , 'value' => null //value of options that is selected
            , 'label' => null
            , 'hint' => null
            , 'help' => null
            , 'options' => null //array in the form 'value'=>'display_text'
            , 'default_option' => null //string indiciating the value that should be selected on default
            , 'template_id' => __FUNCTION__
        );


        return($this->getAddon()->getModule('Form')->renderElement(__FUNCTION__, $atts, $defaults));
    }

      /**
     * Checkbox Element
     *
     * Returns HTML for a Checkbox
     * @param array $atts Shortcode attributes
     * @return void
     */
    public function checkbox($atts) {
         $this->debug()->t();



        $defaults = array(
            'name' => null,  //the name of the form field.
             'heading' => null  //the name of the form field.
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
     * Radio Element
     *
     * Returns HTML for a Text Input Field
     * @param array $atts Shortcode attributes
     * @return void
     */
    public function radio($atts) {
         $this->debug()->t();



        $defaults = array(
            'name' => null  //the name of the form field.
                        ,'is_shortcode'=>false
            , 'heading' => null  //the name of the form field.
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
     * Form Start
     *
     * Adds the <form> tag
     * @param array $atts Shortcode attributes
     * @return void
     */
    public function formStart($atts) {
        $this->debug()->t();


        $defaults = array(
            'name' => 'simpli_forms', //the name of the form field.
            'action' => null, //the action of the form
            'method' => null, //the method of the form , 'post' or 'get'
            'is_shortcode' => false,
            'template_id' => __FUNCTION__
        );




        return($this->getAddon()->getModule('Form')->renderElement(__FUNCTION__, $atts, $defaults));

    }

    /**
     * Form End
     *
     * Adds the end tag and buttons to a form
     * @param array $atts Shortcode attributes
     * @return void
     */
    public function formEnd($atts) {
        $this->debug()->t();


        $defaults = array(
            'name' => 'form_end', //unique id of the form
            'is_shortcode' => false,
            'template_id' => __FUNCTION__
        );


        return($this->getAddon()->getModule('Form')->renderElement(__FUNCTION__, $atts, $defaults));
    }

}

